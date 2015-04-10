<?php
class ModelShippingUsps extends Model {
	public function getQuote($address) {
		/**
		* Modified: Custom Shipping Methods
		*
		*/
		$this->language->load('shipping/usps');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('usps_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('usps_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$this->load->model('localisation/country');

			$quote_data = array();

			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('usps_weight_class_id'));

			$weight = ($weight < 0.1 ? 0.1 : $weight);
			$pounds = floor($weight);
			$ounces = round(16 * ($weight - $pounds), 2); // max 5 digits

			$postcode = str_replace(' ', '', $address['postcode']);

			if ($address['iso_code_2'] == 'US') {
				$xml  = '<RateV4Request USERID="' . $this->config->get('usps_user_id') . '">';
				$xml .= '	<Package ID="1">';
				$xml .=	'		<Service>ALL</Service>';
				$xml .=	'		<ZipOrigination>' . substr($this->config->get('usps_postcode'), 0, 5) . '</ZipOrigination>';
				$xml .=	'		<ZipDestination>' . substr($postcode, 0, 5) . '</ZipDestination>';
				$xml .=	'		<Pounds>' . $pounds . '</Pounds>';
				$xml .=	'		<Ounces>' . $ounces . '</Ounces>';

				// Prevent common size mismatch error from USPS (Size cannot be Regular if Container is Rectangular for some reason)
				if ($this->config->get('usps_container') == 'RECTANGULAR' && $this->config->get('usps_size') == 'REGULAR') {
					$this->config->set('usps_container', 'VARIABLE');
				}

				$xml .=	'		<Container>' . $this->config->get('usps_container') . '</Container>';
				$xml .=	'		<Size>' . $this->config->get('usps_size') . '</Size>';
				$xml .= '		<Width>' . $this->config->get('usps_width') . '</Width>';
				$xml .= '		<Length>' . $this->config->get('usps_length') . '</Length>';
				$xml .= '		<Height>' . $this->config->get('usps_height') . '</Height>';

				// Calculate girth based on usps calculation
				$xml .= '		<Girth>' . (round(((float)$this->config->get('usps_length') + (float)$this->config->get('usps_width') * 2 + (float)$this->config->get('usps_height') * 2), 1)) . '</Girth>';


				$xml .=	'		<Machinable>' . ($this->config->get('usps_machinable') ? 'true' : 'false') . '</Machinable>';
				$xml .=	'	</Package>';
				$xml .= '</RateV4Request>';

				$request = 'API=RateV4&XML=' . urlencode($xml);
			} else {
				$country = array(
					'AF' => 'Afghanistan',
					'AL' => 'Albania',
					'DZ' => 'Algeria',
					'AD' => 'Andorra',
					'AO' => 'Angola',
					'AI' => 'Anguilla',
					'AG' => 'Antigua and Barbuda',
					'AR' => 'Argentina',
					'AM' => 'Armenia',
					'AW' => 'Aruba',
					'AU' => 'Australia',
					'AT' => 'Austria',
					'AZ' => 'Azerbaijan',
					'BS' => 'Bahamas',
					'BH' => 'Bahrain',
					'BD' => 'Bangladesh',
					'BB' => 'Barbados',
					'BY' => 'Belarus',
					'BE' => 'Belgium',
					'BZ' => 'Belize',
					'BJ' => 'Benin',
					'BM' => 'Bermuda',
					'BT' => 'Bhutan',
					'BO' => 'Bolivia',
					'BA' => 'Bosnia-Herzegovina',
					'BW' => 'Botswana',
					'BR' => 'Brazil',
					'VG' => 'British Virgin Islands',
					'BN' => 'Brunei Darussalam',
					'BG' => 'Bulgaria',
					'BF' => 'Burkina Faso',
					'MM' => 'Burma',
					'BI' => 'Burundi',
					'KH' => 'Cambodia',
					'CM' => 'Cameroon',
					'CA' => 'Canada',
					'CV' => 'Cape Verde',
					'KY' => 'Cayman Islands',
					'CF' => 'Central African Republic',
					'TD' => 'Chad',
					'CL' => 'Chile',
					'CN' => 'China',
					'CX' => 'Christmas Island (Australia)',
					'CC' => 'Cocos Island (Australia)',
					'CO' => 'Colombia',
					'KM' => 'Comoros',
					'CG' => 'Congo (Brazzaville),Republic of the',
					'ZR' => 'Congo, Democratic Republic of the',
					'CK' => 'Cook Islands (New Zealand)',
					'CR' => 'Costa Rica',
					'CI' => 'Cote d\'Ivoire (Ivory Coast)',
					'HR' => 'Croatia',
					'CU' => 'Cuba',
					'CY' => 'Cyprus',
					'CZ' => 'Czech Republic',
					'DK' => 'Denmark',
					'DJ' => 'Djibouti',
					'DM' => 'Dominica',
					'DO' => 'Dominican Republic',
					'TP' => 'East Timor (Indonesia)',
					'EC' => 'Ecuador',
					'EG' => 'Egypt',
					'SV' => 'El Salvador',
					'GQ' => 'Equatorial Guinea',
					'ER' => 'Eritrea',
					'EE' => 'Estonia',
					'ET' => 'Ethiopia',
					'FK' => 'Falkland Islands',
					'FO' => 'Faroe Islands',
					'FJ' => 'Fiji',
					'FI' => 'Finland',
					'FR' => 'France',
					'GF' => 'French Guiana',
					'PF' => 'French Polynesia',
					'GA' => 'Gabon',
					'GM' => 'Gambia',
					'GE' => 'Georgia, Republic of',
					'DE' => 'Germany',
					'GH' => 'Ghana',
					'GI' => 'Gibraltar',
					'GB' => 'Great Britain and Northern Ireland',
					'GR' => 'Greece',
					'GL' => 'Greenland',
					'GD' => 'Grenada',
					'GP' => 'Guadeloupe',
					'GT' => 'Guatemala',
					'GN' => 'Guinea',
					'GW' => 'Guinea-Bissau',
					'GY' => 'Guyana',
					'HT' => 'Haiti',
					'HN' => 'Honduras',
					'HK' => 'Hong Kong',
					'HU' => 'Hungary',
					'IS' => 'Iceland',
					'IN' => 'India',
					'ID' => 'Indonesia',
					'IR' => 'Iran',
					'IQ' => 'Iraq',
					'IE' => 'Ireland',
					'IL' => 'Israel',
					'IT' => 'Italy',
					'JM' => 'Jamaica',
					'JP' => 'Japan',
					'JO' => 'Jordan',
					'KZ' => 'Kazakhstan',
					'KE' => 'Kenya',
					'KI' => 'Kiribati',
					'KW' => 'Kuwait',
					'KG' => 'Kyrgyzstan',
					'LA' => 'Laos',
					'LV' => 'Latvia',
					'LB' => 'Lebanon',
					'LS' => 'Lesotho',
					'LR' => 'Liberia',
					'LY' => 'Libya',
					'LI' => 'Liechtenstein',
					'LT' => 'Lithuania',
					'LU' => 'Luxembourg',
					'MO' => 'Macao',
					'MK' => 'Macedonia, Republic of',
					'MG' => 'Madagascar',
					'MW' => 'Malawi',
					'MY' => 'Malaysia',
					'MV' => 'Maldives',
					'ML' => 'Mali',
					'MT' => 'Malta',
					'MQ' => 'Martinique',
					'MR' => 'Mauritania',
					'MU' => 'Mauritius',
					'YT' => 'Mayotte (France)',
					'MX' => 'Mexico',
					'MD' => 'Moldova',
					'MC' => 'Monaco (France)',
					'MN' => 'Mongolia',
					'MS' => 'Montserrat',
					'MA' => 'Morocco',
					'MZ' => 'Mozambique',
					'NA' => 'Namibia',
					'NR' => 'Nauru',
					'NP' => 'Nepal',
					'NL' => 'Netherlands',
					'AN' => 'Netherlands Antilles',
					'NC' => 'New Caledonia',
					'NZ' => 'New Zealand',
					'NI' => 'Nicaragua',
					'NE' => 'Niger',
					'NG' => 'Nigeria',
					'KP' => 'North Korea (Korea, Democratic People\'s Republic of)',
					'NO' => 'Norway',
					'OM' => 'Oman',
					'PK' => 'Pakistan',
					'PA' => 'Panama',
					'PG' => 'Papua New Guinea',
					'PY' => 'Paraguay',
					'PE' => 'Peru',
					'PH' => 'Philippines',
					'PN' => 'Pitcairn Island',
					'PL' => 'Poland',
					'PT' => 'Portugal',
					'QA' => 'Qatar',
					'RE' => 'Reunion',
					'RO' => 'Romania',
					'RU' => 'Russia',
					'RW' => 'Rwanda',
					'SH' => 'Saint Helena',
					'KN' => 'Saint Kitts (St. Christopher and Nevis)',
					'LC' => 'Saint Lucia',
					'PM' => 'Saint Pierre and Miquelon',
					'VC' => 'Saint Vincent and the Grenadines',
					'SM' => 'San Marino',
					'ST' => 'Sao Tome and Principe',
					'SA' => 'Saudi Arabia',
					'SN' => 'Senegal',
					'YU' => 'Serbia-Montenegro',
					'SC' => 'Seychelles',
					'SL' => 'Sierra Leone',
					'SG' => 'Singapore',
					'SK' => 'Slovak Republic',
					'SI' => 'Slovenia',
					'SB' => 'Solomon Islands',
					'SO' => 'Somalia',
					'ZA' => 'South Africa',
					'GS' => 'South Georgia (Falkland Islands)',
					'KR' => 'South Korea (Korea, Republic of)',
					'ES' => 'Spain',
					'LK' => 'Sri Lanka',
					'SD' => 'Sudan',
					'SR' => 'Suriname',
					'SZ' => 'Swaziland',
					'SE' => 'Sweden',
					'CH' => 'Switzerland',
					'SY' => 'Syrian Arab Republic',
					'TW' => 'Taiwan',
					'TJ' => 'Tajikistan',
					'TZ' => 'Tanzania',
					'TH' => 'Thailand',
					'TG' => 'Togo',
					'TK' => 'Tokelau (Union) Group (Western Samoa)',
					'TO' => 'Tonga',
					'TT' => 'Trinidad and Tobago',
					'TN' => 'Tunisia',
					'TR' => 'Turkey',
					'TM' => 'Turkmenistan',
					'TC' => 'Turks and Caicos Islands',
					'TV' => 'Tuvalu',
					'UG' => 'Uganda',
					'UA' => 'Ukraine',
					'AE' => 'United Arab Emirates',
					'UY' => 'Uruguay',
					'UZ' => 'Uzbekistan',
					'VU' => 'Vanuatu',
					'VA' => 'Vatican City',
					'VE' => 'Venezuela',
					'VN' => 'Vietnam',
					'WF' => 'Wallis and Futuna Islands',
					'WS' => 'Western Samoa',
					'YE' => 'Yemen',
					'ZM' => 'Zambia',
					'ZW' => 'Zimbabwe'
				);

				if (isset($country[$address['iso_code_2']])) {
					$xml  = '<IntlRateV2Request USERID="' . $this->config->get('usps_user_id') . '">';
					$xml .=	'	<Package ID="1">';
					$xml .=	'		<Pounds>' . $pounds . '</Pounds>';
					$xml .=	'		<Ounces>' . $ounces . '</Ounces>';
					$xml .=	'		<MailType>All</MailType>';
					$xml .=	'		<GXG>';
					$xml .=	'		  <POBoxFlag>N</POBoxFlag>';
					$xml .=	'		  <GiftFlag>N</GiftFlag>';
					$xml .=	'		</GXG>';
					$xml .=	'		<ValueOfContents>' . $this->cart->getSubTotal() . '</ValueOfContents>';
					$xml .=	'		<Country>' . $country[$address['iso_code_2']] . '</Country>';

					// Intl only supports RECT and NONRECT
					if ($this->config->get('usps_container') == 'VARIABLE') {
						$this->config->set('usps_container', 'NONRECTANGULAR');
					}

					$xml .=	'		<Container>' . $this->config->get('usps_container') . '</Container>';
					$xml .=	'		<Size>' . $this->config->get('usps_size') . '</Size>';
					$xml .= '		<Width>' . $this->config->get('usps_width') . '</Width>';
					$xml .= '		<Length>' . $this->config->get('usps_length') . '</Length>';
					$xml .= '		<Height>' . $this->config->get('usps_height') . '</Height>';
					$xml .= '		<Girth>' . $this->config->get('usps_girth') . '</Girth>';
					$xml .= '		<CommercialFlag>N</CommercialFlag>';
					$xml .=	'	</Package>';
					$xml .=	'</IntlRateV2Request>';

					$request = 'API=IntlRateV2&XML=' . urlencode($xml);
				} else {
					$status = false;
				}
			}

			if ($status) {
				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, 'production.shippingapis.com/ShippingAPI.dll?' . $request);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

				$result = curl_exec($curl);

				curl_close($curl);

				// strip reg, trade and ** out 01-02-2011
				$result = str_replace('&amp;lt;sup&amp;gt;&amp;#174;&amp;lt;/sup&amp;gt;', '', $result);
        		$result = str_replace('&amp;lt;sup&amp;gt;&amp;#8482;&amp;lt;/sup&amp;gt;', '', $result);
        		$result = str_replace('1-Day', '', $result);
				$result = str_replace('**', '', $result);
				$result = str_replace("\r\n", '', $result);
				$result = str_replace('\"', '"', $result);

				if ($result) {
					if ($this->config->get('usps_debug')) {
						$this->log->write("USPS DATA SENT: " . urldecode($request));
						$this->log->write("USPS DATA RECV: " . $result);
					}

					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($result);

					$rate_response = $dom->getElementsByTagName('RateV4Response')->item(0);
					$intl_rate_response = $dom->getElementsByTagName('IntlRateV2Response')->item(0);
					$error = $dom->getElementsByTagName('Error')->item(0);

					$firstclasses = array (
						'First-Class Mail Parcel',
						'First-Class Mail Large Envelope',
						'First-Class Mail Letter',
						'First-Class Mail Postcards'
					);

					$this->load->model('extras/product_shipping');
					$allowed = array();
					foreach($this->cart->getProducts() as $result) {
						if ($result['affiliate_id'] == 0) {
							$allowed[] = $this->model_extras_product_shipping->getProductShippingMethod($result['product_id']);
						}
					}

					$allowed = $this->array_flatten($allowed);

					if ($rate_response || $intl_rate_response) {
						if ($address['iso_code_2'] == 'US') { /* Domestic Mail Service */

							if (in_array(4,$allowed)) { 		// 4 = Parcel Post
								$allowed = array(4);
							}elseif (in_array(1,$allowed)) {	// 1 = Priority Mail
								$allowed = array(1);
							}elseif (in_array(22,$allowed)) {	// 22 = Large Flat Rate Box
								$allowed = array(22);
							}elseif (in_array(17,$allowed)) {	// 17 = Regular Flat Rate Box
								$allowed = array(17);
							}elseif (in_array(16,$allowed)) {	// 16 = Flat Rate Envelope
								$allowed = array(16);
							}elseif (in_array(0,$allowed)) {	// 0 = First Class Mail
								$allowed = array(0);
							}else{
								$allowed = $this->config->get('domestic_shipping_default_id');
							}

							$package = $rate_response->getElementsByTagName('Package')->item(0);

							$postages = $package->getElementsByTagName('Postage');

							if ($postages->length) {
								foreach ($postages as $postage) {
									$classid = $postage->getAttribute('CLASSID');

									if (in_array($classid, $allowed)) {
										if ($classid == '0') {
											$mailservice = $postage->getElementsByTagName('MailService')->item(0)->nodeValue;

											foreach ($firstclasses as $k => $firstclass)  {
												if ($firstclass == $mailservice) {
													$classid = $classid . $k;
													break;
												}
											}
										}

										if (($this->config->get('usps_domestic_' . $classid))) {
											$cost = $postage->getElementsByTagName('Rate')->item(0)->nodeValue;

											$quote_data[$classid] = array(
												'code'         => 'usps.' . $classid,
												'title'        => $postage->getElementsByTagName('MailService')->item(0)->nodeValue,
												'cost'         => $this->currency->convert($cost, 'USD', $this->config->get('config_currency')),
												'tax_class_id' => $this->config->get('usps_tax_class_id'),
												'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($cost, 'USD', $this->currency->getCode()), $this->config->get('usps_tax_class_id'), $this->config->get('config_tax')), $this->currency->getCode(), 1.0000000)
											);
										}
									}
								}
							} else {
								$error = $package->getElementsByTagName('Error')->item(0);

								$method_data = array(
									'code'       => 'usps',
									'title'      => $this->language->get('text_title'),
									'quote'      => $quote_data,
									'sort_order' => $this->config->get('usps_sort_order'),
									'error'      => $error->getElementsByTagName('Description')->item(0)->nodeValue
								);
							}
						} else { /* International Mail Service */

							if (in_array(2,$allowed)) {				// priority mail
								$allowed = array(2);
							} elseif (in_array(11,$allowed)) {		// flat rate box large
								$allowed = array(11);
							} elseif (in_array(9,$allowed)) {		// flat rate box
								$allowed = array(9);
							} elseif (in_array(8,$allowed)) {		// flat rate envelope
								$allowed = array(8);
							} elseif (in_array(15,$allowed)) {		// first class
								$allowed = array(15);
							} else {
								$allowed = $this->config->get('international_shipping_default_id');
							}

							$package = $intl_rate_response->getElementsByTagName('Package')->item(0);

							$services = $package->getElementsByTagName('Service');

							foreach ($services as $service) {
								$id = $service->getAttribute('ID');

								if (in_array($id, $allowed) && $this->config->get('usps_international_' . $id)) {
									$title = $service->getElementsByTagName('SvcDescription')->item(0)->nodeValue;

									if ($this->config->get('usps_display_time')) {
										$title .= ' (' . $this->language->get('text_eta') . ' ' . $service->getElementsByTagName('SvcCommitments')->item(0)->nodeValue . ')';
									}

									$cost = $service->getElementsByTagName('Postage')->item(0)->nodeValue;

									$quote_data[$id] = array(
										'code'         => 'usps.' . $id,
										'title'        => $title,
										'cost'         => $this->currency->convert($cost, 'USD', $this->config->get('config_currency')),
										'tax_class_id' => $this->config->get('usps_tax_class_id'),
										'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($cost, 'USD', $this->currency->getCode()), $this->config->get('usps_tax_class_id'), $this->config->get('config_tax')), $this->currency->getCode(), 1.0000000)
									);
								}
							}
						}
					} elseif ($error) {
						$method_data = array(
							'code'       => 'usps',
							'title'      => $this->language->get('text_title'),
							'quote'      => $quote_data,
							'sort_order' => $this->config->get('usps_sort_order'),
							'error'      => $error->getElementsByTagName('Description')->item(0)->nodeValue
						);
					}
				}
			}

			if ($quote_data) {
				$title_data = array();

				$title_data[] = $this->getAdminShippingInfo();

				$method_data = array(
					'code'       => 'usps',
					'title'      => $title_data,
					'quote'      => $quote_data,
					'sort_order' => $this->config->get('usps_sort_order'),
					'error'      => false
				);
			}
		}

		return $method_data;
	}

	public function getAffiliateQuote($address, $affiliate_id) {
		$this->language->load('shipping/usps');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('usps_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('usps_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$this->load->model('localisation/country');

			$quote_data = array();

			$weight = $this->weight->convert($this->cart->getWeightByAffiliateId($affiliate_id), $this->config->get('config_weight_class_id'), $this->config->get('usps_weight_class_id'));

			$weight = ($weight < 0.1 ? 0.1 : $weight);
			$pounds = floor($weight);
			$ounces = round(16 * ($weight - $pounds), 2); // max 5 digits

			$postcode = str_replace(' ', '', $address['postcode']);

			if ($address['iso_code_2'] == 'US') {
				$xml  = '<RateV4Request USERID="' . $this->config->get($affiliate_id . '_usps_user_id') . '">';
				$xml .= '	<Package ID="1">';
				$xml .=	'		<Service>ALL</Service>';
				$xml .=	'		<ZipOrigination>' . substr($this->config->get($affiliate_id . '_usps_postcode'), 0, 5) . '</ZipOrigination>';
				$xml .=	'		<ZipDestination>' . substr($postcode, 0, 5) . '</ZipDestination>';
				$xml .=	'		<Pounds>' . $pounds . '</Pounds>';
				$xml .=	'		<Ounces>' . $ounces . '</Ounces>';

				// Prevent common size mismatch error from USPS (Size cannot be Regular if Container is Rectangular for some reason)
				if ($this->config->get('usps_container') == 'RECTANGULAR' && $this->config->get('usps_size') == 'REGULAR') {
					$this->config->set('usps_container', 'VARIABLE');
				}

				$xml .=	'		<Container>' . $this->config->get('usps_container') . '</Container>';
				$xml .=	'		<Size>' . $this->config->get('usps_size') . '</Size>';
				$xml .= '		<Width>' . $this->config->get($affiliate_id . '_usps_width') . '</Width>';
				$xml .= '		<Length>' . $this->config->get($affiliate_id . '_usps_length') . '</Length>';
				$xml .= '		<Height>' . $this->config->get($affiliate_id . '_usps_height') . '</Height>';

				// Calculate girth based on usps calculation
				$xml .= '		<Girth>' . (round(((float)$this->config->get($affiliate_id . '_usps_length') + (float)$this->config->get($affiliate_id . '_usps_width') * 2 + (float)$this->config->get($affiliate_id . '_usps_height') * 2), 1)) . '</Girth>';


				$xml .=	'		<Machinable>' . ($this->config->get('usps_machinable') ? 'true' : 'false') . '</Machinable>';
				$xml .=	'	</Package>';
				$xml .= '</RateV4Request>';

				$request = 'API=RateV4&XML=' . urlencode($xml);
			} else {
				$country = array(
					'AF' => 'Afghanistan',
					'AL' => 'Albania',
					'DZ' => 'Algeria',
					'AD' => 'Andorra',
					'AO' => 'Angola',
					'AI' => 'Anguilla',
					'AG' => 'Antigua and Barbuda',
					'AR' => 'Argentina',
					'AM' => 'Armenia',
					'AW' => 'Aruba',
					'AU' => 'Australia',
					'AT' => 'Austria',
					'AZ' => 'Azerbaijan',
					'BS' => 'Bahamas',
					'BH' => 'Bahrain',
					'BD' => 'Bangladesh',
					'BB' => 'Barbados',
					'BY' => 'Belarus',
					'BE' => 'Belgium',
					'BZ' => 'Belize',
					'BJ' => 'Benin',
					'BM' => 'Bermuda',
					'BT' => 'Bhutan',
					'BO' => 'Bolivia',
					'BA' => 'Bosnia-Herzegovina',
					'BW' => 'Botswana',
					'BR' => 'Brazil',
					'VG' => 'British Virgin Islands',
					'BN' => 'Brunei Darussalam',
					'BG' => 'Bulgaria',
					'BF' => 'Burkina Faso',
					'MM' => 'Burma',
					'BI' => 'Burundi',
					'KH' => 'Cambodia',
					'CM' => 'Cameroon',
					'CA' => 'Canada',
					'CV' => 'Cape Verde',
					'KY' => 'Cayman Islands',
					'CF' => 'Central African Republic',
					'TD' => 'Chad',
					'CL' => 'Chile',
					'CN' => 'China',
					'CX' => 'Christmas Island (Australia)',
					'CC' => 'Cocos Island (Australia)',
					'CO' => 'Colombia',
					'KM' => 'Comoros',
					'CG' => 'Congo (Brazzaville),Republic of the',
					'ZR' => 'Congo, Democratic Republic of the',
					'CK' => 'Cook Islands (New Zealand)',
					'CR' => 'Costa Rica',
					'CI' => 'Cote d\'Ivoire (Ivory Coast)',
					'HR' => 'Croatia',
					'CU' => 'Cuba',
					'CY' => 'Cyprus',
					'CZ' => 'Czech Republic',
					'DK' => 'Denmark',
					'DJ' => 'Djibouti',
					'DM' => 'Dominica',
					'DO' => 'Dominican Republic',
					'TP' => 'East Timor (Indonesia)',
					'EC' => 'Ecuador',
					'EG' => 'Egypt',
					'SV' => 'El Salvador',
					'GQ' => 'Equatorial Guinea',
					'ER' => 'Eritrea',
					'EE' => 'Estonia',
					'ET' => 'Ethiopia',
					'FK' => 'Falkland Islands',
					'FO' => 'Faroe Islands',
					'FJ' => 'Fiji',
					'FI' => 'Finland',
					'FR' => 'France',
					'GF' => 'French Guiana',
					'PF' => 'French Polynesia',
					'GA' => 'Gabon',
					'GM' => 'Gambia',
					'GE' => 'Georgia, Republic of',
					'DE' => 'Germany',
					'GH' => 'Ghana',
					'GI' => 'Gibraltar',
					'GB' => 'Great Britain and Northern Ireland',
					'GR' => 'Greece',
					'GL' => 'Greenland',
					'GD' => 'Grenada',
					'GP' => 'Guadeloupe',
					'GT' => 'Guatemala',
					'GN' => 'Guinea',
					'GW' => 'Guinea-Bissau',
					'GY' => 'Guyana',
					'HT' => 'Haiti',
					'HN' => 'Honduras',
					'HK' => 'Hong Kong',
					'HU' => 'Hungary',
					'IS' => 'Iceland',
					'IN' => 'India',
					'ID' => 'Indonesia',
					'IR' => 'Iran',
					'IQ' => 'Iraq',
					'IE' => 'Ireland',
					'IL' => 'Israel',
					'IT' => 'Italy',
					'JM' => 'Jamaica',
					'JP' => 'Japan',
					'JO' => 'Jordan',
					'KZ' => 'Kazakhstan',
					'KE' => 'Kenya',
					'KI' => 'Kiribati',
					'KW' => 'Kuwait',
					'KG' => 'Kyrgyzstan',
					'LA' => 'Laos',
					'LV' => 'Latvia',
					'LB' => 'Lebanon',
					'LS' => 'Lesotho',
					'LR' => 'Liberia',
					'LY' => 'Libya',
					'LI' => 'Liechtenstein',
					'LT' => 'Lithuania',
					'LU' => 'Luxembourg',
					'MO' => 'Macao',
					'MK' => 'Macedonia, Republic of',
					'MG' => 'Madagascar',
					'MW' => 'Malawi',
					'MY' => 'Malaysia',
					'MV' => 'Maldives',
					'ML' => 'Mali',
					'MT' => 'Malta',
					'MQ' => 'Martinique',
					'MR' => 'Mauritania',
					'MU' => 'Mauritius',
					'YT' => 'Mayotte (France)',
					'MX' => 'Mexico',
					'MD' => 'Moldova',
					'MC' => 'Monaco (France)',
					'MN' => 'Mongolia',
					'MS' => 'Montserrat',
					'MA' => 'Morocco',
					'MZ' => 'Mozambique',
					'NA' => 'Namibia',
					'NR' => 'Nauru',
					'NP' => 'Nepal',
					'NL' => 'Netherlands',
					'AN' => 'Netherlands Antilles',
					'NC' => 'New Caledonia',
					'NZ' => 'New Zealand',
					'NI' => 'Nicaragua',
					'NE' => 'Niger',
					'NG' => 'Nigeria',
					'KP' => 'North Korea (Korea, Democratic People\'s Republic of)',
					'NO' => 'Norway',
					'OM' => 'Oman',
					'PK' => 'Pakistan',
					'PA' => 'Panama',
					'PG' => 'Papua New Guinea',
					'PY' => 'Paraguay',
					'PE' => 'Peru',
					'PH' => 'Philippines',
					'PN' => 'Pitcairn Island',
					'PL' => 'Poland',
					'PT' => 'Portugal',
					'QA' => 'Qatar',
					'RE' => 'Reunion',
					'RO' => 'Romania',
					'RU' => 'Russia',
					'RW' => 'Rwanda',
					'SH' => 'Saint Helena',
					'KN' => 'Saint Kitts (St. Christopher and Nevis)',
					'LC' => 'Saint Lucia',
					'PM' => 'Saint Pierre and Miquelon',
					'VC' => 'Saint Vincent and the Grenadines',
					'SM' => 'San Marino',
					'ST' => 'Sao Tome and Principe',
					'SA' => 'Saudi Arabia',
					'SN' => 'Senegal',
					'YU' => 'Serbia-Montenegro',
					'SC' => 'Seychelles',
					'SL' => 'Sierra Leone',
					'SG' => 'Singapore',
					'SK' => 'Slovak Republic',
					'SI' => 'Slovenia',
					'SB' => 'Solomon Islands',
					'SO' => 'Somalia',
					'ZA' => 'South Africa',
					'GS' => 'South Georgia (Falkland Islands)',
					'KR' => 'South Korea (Korea, Republic of)',
					'ES' => 'Spain',
					'LK' => 'Sri Lanka',
					'SD' => 'Sudan',
					'SR' => 'Suriname',
					'SZ' => 'Swaziland',
					'SE' => 'Sweden',
					'CH' => 'Switzerland',
					'SY' => 'Syrian Arab Republic',
					'TW' => 'Taiwan',
					'TJ' => 'Tajikistan',
					'TZ' => 'Tanzania',
					'TH' => 'Thailand',
					'TG' => 'Togo',
					'TK' => 'Tokelau (Union) Group (Western Samoa)',
					'TO' => 'Tonga',
					'TT' => 'Trinidad and Tobago',
					'TN' => 'Tunisia',
					'TR' => 'Turkey',
					'TM' => 'Turkmenistan',
					'TC' => 'Turks and Caicos Islands',
					'TV' => 'Tuvalu',
					'UG' => 'Uganda',
					'UA' => 'Ukraine',
					'AE' => 'United Arab Emirates',
					'UY' => 'Uruguay',
					'UZ' => 'Uzbekistan',
					'VU' => 'Vanuatu',
					'VA' => 'Vatican City',
					'VE' => 'Venezuela',
					'VN' => 'Vietnam',
					'WF' => 'Wallis and Futuna Islands',
					'WS' => 'Western Samoa',
					'YE' => 'Yemen',
					'ZM' => 'Zambia',
					'ZW' => 'Zimbabwe'
				);

				if (isset($country[$address['iso_code_2']])) {
					$xml  = '<IntlRateV2Request USERID="' . $this->config->get($affiliate_id . '_usps_user_id') . '">';
					$xml .=	'	<Package ID="1">';
					$xml .=	'		<Pounds>' . $pounds . '</Pounds>';
					$xml .=	'		<Ounces>' . $ounces . '</Ounces>';
					$xml .=	'		<MailType>All</MailType>';
					$xml .=	'		<GXG>';
					$xml .=	'		  <POBoxFlag>N</POBoxFlag>';
					$xml .=	'		  <GiftFlag>N</GiftFlag>';
					$xml .=	'		</GXG>';
					$xml .=	'		<ValueOfContents>' . $this->cart->getSubTotal() . '</ValueOfContents>';
					$xml .=	'		<Country>' . $country[$address['iso_code_2']] . '</Country>';

					// Intl only supports RECT and NONRECT
					if ($this->config->get('usps_container') == 'VARIABLE') {
						$this->config->set('usps_container', 'NONRECTANGULAR');
					}

					$xml .=	'		<Container>' . $this->config->get('usps_container') . '</Container>';
					$xml .=	'		<Size>' . $this->config->get('usps_size') . '</Size>';
					$xml .= '		<Width>' . $this->config->get($affiliate_id . '_usps_width') . '</Width>';
					$xml .= '		<Length>' . $this->config->get($affiliate_id . '_usps_length') . '</Length>';
					$xml .= '		<Height>' . $this->config->get($affiliate_id . '_usps_height') . '</Height>';
					$xml .= '		<Girth>' . $this->config->get('usps_girth') . '</Girth>';
					$xml .= '		<CommercialFlag>N</CommercialFlag>';
					$xml .=	'	</Package>';
					$xml .=	'</IntlRateV2Request>';

					$request = 'API=IntlRateV2&XML=' . urlencode($xml);
				} else {
					$status = false;
				}
			}

			if ($status) {
				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, 'production.shippingapis.com/ShippingAPI.dll?' . $request);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

				$result = curl_exec($curl);

				curl_close($curl);

				// strip reg, trade and ** out 01-02-2011
				$result = str_replace('&amp;lt;sup&amp;gt;&amp;#174;&amp;lt;/sup&amp;gt;', '', $result);
        		$result = str_replace('&amp;lt;sup&amp;gt;&amp;#8482;&amp;lt;/sup&amp;gt;', '', $result);
        		$result = str_replace('1-Day', '', $result);
				$result = str_replace('**', '', $result);
				$result = str_replace("\r\n", '', $result);
				$result = str_replace('\"', '"', $result);

				if ($result) {
					if ($this->config->get('usps_debug')) {
						$this->log->write("USPS DATA SENT: " . urldecode($request));
						$this->log->write("USPS DATA RECV: " . $result);
					}

					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($result);

					$rate_response = $dom->getElementsByTagName('RateV4Response')->item(0);
					$intl_rate_response = $dom->getElementsByTagName('IntlRateV2Response')->item(0);
					$error = $dom->getElementsByTagName('Error')->item(0);

					$firstclasses = array (
						'First-Class Mail Parcel',
						'First-Class Mail Large Envelope',
						'First-Class Mail Letter',
						'First-Class Mail Postcards'
					);

					$this->load->model('extras/product_shipping');
					$allowed = array();
					foreach($this->cart->getProducts() as $product) {
						if ($product['affiliate_id'] == $affiliate_id) {
							$allowed[] = $this->model_extras_product_shipping->getProductShippingMethod($product['product_id']);
						}
					}

					$allowed = $this->array_flatten($allowed);
					// $allowed = array('4');
					if ($rate_response || $intl_rate_response) {
						if ($address['iso_code_2'] == 'US') {


							/*if (in_array(4,$allowed)) { 		// 4 = Parcel Post // doesnt work???? -- CLASSID 4 is Standard Post from usps api call example
								$allowed = array(4);
							}else*/if (in_array(1,$allowed)) {	// 1 = Priority Mail
								$allowed = array(1);
							}elseif (in_array(22,$allowed)) {	// 22 = Large Flat Rate Box
								$allowed = array(22);
							}elseif (in_array(17,$allowed)) {	// 17 = Regular Flat Rate Box
								$allowed = array(17);
							}elseif (in_array(16,$allowed)) {	// 16 = Flat Rate Envelope
								$allowed = array(16);
							}elseif (in_array(0,$allowed)) {	// 0 = First Class Mail // doesnt work????
								$allowed = array(0);
							}else{
								$allowed = array(22);
							}


							$package = $rate_response->getElementsByTagName('Package')->item(0);

							$postages = $package->getElementsByTagName('Postage');

							if ($postages->length) {
								foreach ($postages as $postage) {
									$classid = $postage->getAttribute('CLASSID');

									if (in_array($classid, $allowed)) {
										if ($classid == '0') {
											$mailservice = $postage->getElementsByTagName('MailService')->item(0)->nodeValue;

											foreach ($firstclasses as $k => $firstclass)  { // CLASSID 01 = 'First-Class Mail Parcel' CLASSID 02 = 'First-Class Mail Large Envelope'
												if ($firstclass == $mailservice) {			// CLASSID 03 = 'First-Class Mail Letter' CLASSID 04 = 'First-Class Mail Postcards'
													$classid = $classid . $k;
													break;
												}
											}
										}

										if (($this->config->get('usps_domestic_' . $classid))) {
											$cost = $postage->getElementsByTagName('Rate')->item(0)->nodeValue;

											$quote_data[] = array(
												'code'         => 'usps.' . $classid,
												'title'        => $postage->getElementsByTagName('MailService')->item(0)->nodeValue,
												'cost'         => $this->currency->convert($cost, 'USD', $this->config->get('config_currency')),
												'tax_class_id' => $this->config->get('usps_tax_class_id'),
												'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($cost, 'USD', $this->currency->getCode()), $this->config->get('usps_tax_class_id'), $this->config->get('config_tax')), $this->currency->getCode(), 1.0000000)
											);
										}
									}
								}
							} else {
								$error = $package->getElementsByTagName('Error')->item(0);

								$method_data = array(
									'code'       => 'usps',
									'title'      => $this->language->get('text_title'),
									'quote'      => $quote_data,
									'sort_order' => $this->config->get('usps_sort_order'),
									'error'      => $error->getElementsByTagName('Description')->item(0)->nodeValue
								);
							}
						} else { /* International Mail Service */

							if (in_array(2,$allowed)) {				// priority mail
								$allowed = array(2);
							} elseif (in_array(11,$allowed)) {		// flat rate box large
								$allowed = array(11);
							} elseif (in_array(9,$allowed)) {		// flat rate box
								$allowed = array(9);
							} elseif (in_array(8,$allowed)) {		// flat rate envelope
								$allowed = array(8);
							} elseif (in_array(15,$allowed)) {		// first class
								$allowed = array(15);
							} else {
								$allowed = $this->config->get('international_shipping_default_id');
							}

							$package = $intl_rate_response->getElementsByTagName('Package')->item(0);

							$services = $package->getElementsByTagName('Service');

							foreach ($services as $service) {
								$id = $service->getAttribute('ID');

								if (in_array($id, $allowed) && $this->config->get('usps_international_' . $id)) {
									$title = $service->getElementsByTagName('SvcDescription')->item(0)->nodeValue;

									if ($this->config->get('usps_display_time')) {
										$title .= ' (' . $this->language->get('text_eta') . ' ' . $service->getElementsByTagName('SvcCommitments')->item(0)->nodeValue . ')';
									}

									$cost = $service->getElementsByTagName('Postage')->item(0)->nodeValue;

									$quote_data[] = array(
										'code'         => 'usps.' . $id,
										'title'        => $title,
										'cost'         => $this->currency->convert($cost, 'USD', $this->config->get('config_currency')),
										'tax_class_id' => $this->config->get('usps_tax_class_id'),
										'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($cost, 'USD', $this->currency->getCode()), $this->config->get('usps_tax_class_id'), $this->config->get('config_tax')), $this->currency->getCode(), 1.0000000)
									);
								}
							}
						}
					} elseif ($error) {
						$method_data = array(
							'code'       => 'usps',
							'title'      => $this->language->get('text_title'),
							'quote'      => $quote_data,
							'sort_order' => $this->config->get('usps_sort_order'),
							'error'      => $error->getElementsByTagName('Description')->item(0)->nodeValue
						);
					}
				}
			}

			if ($quote_data) {


				$seller_info = array();
				if ($affiliate_id > 0) {
					$seller_info[] = $this->getAffiliateShippingInfo($affiliate_id);
				} else {
					$seller_info[] = $this->getAdminShippingInfo();
				}

				$method_data = array(
					'code'       => 'usps',
					'info'       => $seller_info,
					'quote'      => $quote_data,
					'sort_order' => $this->config->get('usps_sort_order'),
					'error'      => false
				);
			}
		}

		return $method_data;
	}

	private function getAffiliateShippingInfo($affiliate_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "affiliate a WHERE a.affiliate_id = '" . (int)$affiliate_id . "'");

		$zone = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone z WHERE z.zone_id = '" . (int)$query->row['zone_id'] . "'");

		$weight = $this->weight->convert($this->cart->getWeightByAffiliateId($affiliate_id), $this->config->get('config_weight_class_id'), $this->config->get('usps_weight_class_id'));

		$address = array(
			'firstname'  => $query->row['firstname'],
			'lastname'   => $query->row['lastname'],
			'state_name' => $zone->row['name'],
			'state_code' => $zone->row['code'],
			'city'       => $query->row['city'],
			'postcode'   => $query->row['postcode'],
			'address_1'  => $query->row['address_1'],
			'address_2'  => $query->row['address_2'],
			'weight'     => $this->weight->format($weight, $this->config->get('usps_weight_class_id'))
		);

		return $address;
	}

	private function getAdminShippingInfo() {
		$weight = $this->weight->convert($this->cart->getWeightByAffiliateId(0), $this->config->get('config_weight_class_id'), $this->config->get('usps_weight_class_id'));

		$info = array(
			'name'    => $this->config->get('config_owner'),
			'address' => $this->config->get('config_address'),
			'weight'  => $this->weight->format($weight, $this->config->get('usps_weight_class_id'))
		);

		return $info;
	}

	private function array_flatten($array) {
		$i = 0;

		while ($i < count($array)) {
		    if (is_array($array[$i]))
		        array_splice($array,$i,1,$array[$i]);
		    else
		        ++$i;
		}
		return $array;
	}
}
?>
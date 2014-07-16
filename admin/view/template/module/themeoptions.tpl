<?php echo $header; ?>

<?php // Normal and Google fonts array
	$fonts = array(
		'Arial'                 => 'Arial',
		'Verdana'               => 'Verdana',
		'Helvetica'             => 'Helvetica',
        'Helvetica+Neue'        => 'Helvetica Neue',
		'Lucida+Grande'         => 'Lucida Grande',
		'Trebuchet+MS'          => 'Trebuchet MS',
		'Times+New+Roman'       => 'Times New Roman',
		'Tahoma'                => 'Tahoma',
		'Georgia'               => 'Georgia',
        ''                      => '-- GOOGLE FONTS --',
		'Abel'                  => 'Abel',
		'Abril+Fatface'         => 'Abril Fatface',
		'Acme'                  => 'Acme',
		'Adamina'               => 'Adamina',
		'Advent+Pro'            => 'Advent Pro',
		'Alfa+Slab+One'         => 'Alfa Slab One',
		'Alice'                 => 'Alice',
		'Allan'                 => 'Allan',
		'Amaranth'              => 'Amaranth',
		'Amatic+SC'             => 'Amatic SC',
		'Andika'                => 'Andika',
		'Anonymous+Pro'         => 'Anonymous Pro',
		'Anton'                 => 'Anton',
		'Arimo'                 => 'Arimo',
		'Bangers'               => 'Bangers',
		'Basic'                 => 'Basic',
		'Baumans'               => 'Baumans',
		'Belgrano'              => 'Belgrano',
		'Berkshire+Swash'       => 'Berkshire Swash',
		'Bitter'                => 'Bitter',
		'Boogaloo'              => 'Boogaloo',
		'Brawler'               => 'Brawler',
		'Bree+Serif'            => 'Bree Serif',
		'Bubblegum+Sans'        => 'Bubblegum Sans',
		'Buda'                  => 'Buda',
		'Cabin+Condensed'       => 'Cabin Condensed',
		'Cabin+Sketch'          => 'Cabin Sketch',
		'Caudex'                => 'Caudex',
		'Contrail+One'          => 'Contrail One',
		'Courgette'             => 'Courgette',
		'Coustard'              => 'Coustard',
		'Crushed'               => 'Crushed',
		'Cuprum'                => 'Cuprum',
		'Damion'                => 'Damion',
		'Days+One'              => 'Days One',
		'Dorsa'                 => 'Dorsa',
		'Droid+Sans'            => 'Droid Sans',
		'Droid+Serif'           => 'Droid Serif',
		'Duru+Sans'             => 'Duru Sans',
		'Enriqueta'             => 'Enriqueta',
		'Federo'                => 'Federo',
		'Francois+One'          => 'Francois One',
		'Fredericka+the+Great'  => 'Fredericka the Great',
		'Fredoka+One'           => 'Fredoka One',
		'Goudy+Bookletter+1911' => 'Goudy Bookletter 1911',
		'Gruppo'                => 'Gruppo',
		'Homenaje'              => 'Homenaje',
		'Imprima'               => 'Imprima',
		'Inder'                 => 'Inder',
		'Istok+Web'             => 'Istok Web',
		'Jockey+One'            => 'Jockey One',
		'Josefin+Slab'          => 'Josefin Slab',
		'Just+Another+Hand'     => 'Just Another Hand',
		'Kaushan+Script'        => 'Kaushan Script',
		'Kotta+One'             => 'Kotta One',
		'Lemon'                 => 'Lemon',
		'Lobster+Two'           => 'Lobster Two',
		'Lobster'               => 'Lobster',
		'Maiden+Orange'         => 'Maiden Orange',
		'Marvel'                => 'Marvel',
		'Merienda+One'          => 'Merienda One',
		'Molengo'               => 'Molengo',
		'Montserrat'            => 'Montserrat',
		'News+Cycle'            => 'News Cycle',
		'Niconne'               => 'Niconne',
		'Nixie+One'             => 'Nixie One',
		'Nobile'                => 'Nobile',
		'Oleo+Script'           => 'Oleo Script',
		'Open+Sans'             => 'Open Sans',
		'Overlock'              => 'Overlock',
		'Ovo'                   => 'Ovo',
		'PT+Sans'               => 'PT Sans',
		'Philosopher'           => 'Philosopher',
		'Playball'              => 'Playball',
		'Poiret+One'            => 'Poiret One',
		'Quando'                => 'Quando',
		'Quattrocento+Sans'     => 'Quattrocento Sans',
		'Quicksand'             => 'Quicksand',
		'Raleway'               => 'Raleway',
		'Righteous'             => 'Righteous',
		'Rokkitt'               => 'Rokkitt',
		'Ropa+Sans'             => 'Ropa Sans',
		'Sansita+One'           => 'Sansita One',
		'Sofia'                 => 'Sofia',
		'Source+Sans+Pro'       => 'Source Sans Pro',
		'Stoke'                 => 'Stoke',
		'Ubuntu'                => 'Ubuntu',
		'Wire+One'              => 'Wire One',
		'Yanone+Kaffeesatz'     => 'Yanone Kaffeesatz',
		'Yellowtail'            => 'Yellowtail'
		); 
	

// Default values
if(empty($themeoptions_title_font)) $themeoptions_title_font                         ="Helvetica Neue";
if(empty($themeoptions_body_font)) $themeoptions_body_font                           ="Helvetica Neue";
if(empty($themeoptions_small_font)) $themeoptions_small_font                         ="Helvetica Neue";
if(empty($themeoptions_title_font_size)) $themeoptions_title_font_size               ="32";
if(empty($themeoptions_body_font_size)) $themeoptions_body_font_size                 ="14";
if(empty($themeoptions_small_font_size)) $themeoptions_small_font_size               ="10";
if(empty($themeoptions_pattern_overlay)) $themeoptions_pattern_overlay               ="default";
if(empty($themeoptions_container_bg)) $themeoptions_container_bg           		     ="";
if(empty($themeoptions_footer_bg)) $themeoptions_footer_bg           			     ="";
if(empty($themeoptions_module_bg)) $themeoptions_module_bg           		     	 ="";

// Header
if(empty($themeoptions_menu_colour)) $themeoptions_menu_colour                    ="";
if(empty($themeoptions_menu_hover_background)) $themeoptions_menu_hover_background  ="";
if(empty($themeoptions_menu_hover)) $themeoptions_menu_hover                      ="";
if(empty($themeoptions_menu_background)) $themeoptions_menu_background            ="";
if(empty($themeoptions_dropdown_colour)) $themeoptions_dropdown_colour            ="";
if(empty($themeoptions_dropdown_hover)) $themeoptions_dropdown_hover              ="";
if(empty($themeoptions_dropdown_hover_bg)) $themeoptions_dropdown_hover_bg        ="";
if(empty($themeoptions_dropdown_background)) $themeoptions_dropdown_background    ="";
if(empty($themeoptions_topmenu_colour)) $themeoptions_topmenu_colour              ="";
if(empty($themeoptions_topmenu_hover_colour)) $themeoptions_topmenu_hover_colour  ="";
if(empty($themeoptions_topmenu_background)) $themeoptions_topmenu_background      ="";
if(empty($themeoptions_currency_colour)) $themeoptions_currency_colour              ="";
if(empty($themeoptions_currency_hover_colour)) $themeoptions_currency_hover_colour  ="";
if(empty($themeoptions_currency_background)) $themeoptions_currency_background      ="";
if(empty($themeoptions_currency_hover_background)) $themeoptions_currency_hover_background      ="";
if(empty($themeoptions_checkout_colour)) $themeoptions_checkout_colour     		  ="";
if(empty($themeoptions_checkout_hover)) $themeoptions_checkout_hover     		  ="";
if(empty($themeoptions_checkout_link)) $themeoptions_checkout_link    		  	  ="";
if(empty($themeoptions_checkoutlink_hover)) $themeoptions_checkoutlink_hover      ="";
if(empty($themeoptions_cart_border)) $themeoptions_cart_border    			      ="";
if(empty($themeoptions_menu_border)) $themeoptions_menu_border    			      ="";

// Body
if(empty($themeoptions_background_colour)) $themeoptions_background_colour                         ="";
if(empty($themeoptions_h1_title_colour)) $themeoptions_h1_title_colour                             ="";
if(empty($themeoptions_h2_title_colour)) $themeoptions_h2_title_colour                             ="";
if(empty($themeoptions_h3_title_colour)) $themeoptions_h3_title_colour                             ="";
if(empty($themeoptions_h4_title_colour)) $themeoptions_h4_title_colour                             ="";
if(empty($themeoptions_h5_title_colour)) $themeoptions_h5_title_colour                             ="";
if(empty($themeoptions_h6_title_colour)) $themeoptions_h6_title_colour                             ="";
if(empty($themeoptions_bodytext_colour)) $themeoptions_bodytext_colour                             ="";
if(empty($themeoptions_lighttext_colour)) $themeoptions_lighttext_colour                           ="";
if(empty($themeoptions_content_links_colour)) $themeoptions_content_links_colour                   ="";
if(empty($themeoptions_content_links_hover_colour)) $themeoptions_content_links_hover_colour       ="";
if(empty($themeoptions_breadcrumb_links_colour)) $themeoptions_breadcrumb_links_colour             ="";
if(empty($themeoptions_breadcrumb_links_hover_colour)) $themeoptions_breadcrumb_links_hover_colour ="";

// Footer
if(empty($themeoptions_footer_header_colour)) $themeoptions_footer_header_colour           ="";
if(empty($themeoptions_footer_text_colour)) $themeoptions_footer_text_colour               ="";
if(empty($themeoptions_footer_links_colour)) $themeoptions_footer_links_colour             ="";
if(empty($themeoptions_footer_links_hover_colour)) $themeoptions_footer_links_hover_colour ="";

// Add to cart buttons
if(empty($themeoptions_button_background_colour)) $themeoptions_button_background_colour ="";
if(empty($themeoptions_button_text_colour)) $themeoptions_button_text_colour             ="";
if(empty($themeoptions_button_border)) $themeoptions_button_border             ="";

// Products
if(empty($themeoptions_product_name_colour)) $themeoptions_product_name_colour             ="";
if(empty($themeoptions_product_name_hover_colour)) $themeoptions_product_name_hover_colour ="";
if(empty($themeoptions_normal_price_colour)) $themeoptions_normal_price_colour             ="";
if(empty($themeoptions_old_price_colour)) $themeoptions_old_price_colour                   ="";
if(empty($themeoptions_new_price_colour)) $themeoptions_new_price_colour                   ="";

// Other
if(empty($themeoptions_categories_menu_colour)) $themeoptions_categories_menu_colour             ="";
if(empty($themeoptions_categories_menu_hover_colour)) $themeoptions_categories_menu_hover_colour ="";
if(empty($themeoptions_categories_sub_colour)) $themeoptions_categories_sub_colour               ="";
if(empty($themeoptions_categories_sub_hover_colour)) $themeoptions_categories_sub_hover_colour   ="";
if(empty($themeoptions_categories_active_colour)) $themeoptions_categories_active_colour         ="";

if(empty($themeoptions_account_menu_colour)) $themeoptions_account_menu_colour             ="";
if(empty($themeoptions_account_menu_hover_colour)) $themeoptions_account_menu_hover_colour ="";
if(empty($themeoptions_account_sub_colour)) $themeoptions_account_sub_colour               ="";
if(empty($themeoptions_account_sub_hover_colour)) $themeoptions_account_sub_hover_colour   ="";
if(empty($themeoptions_account_active_colour)) $themeoptions_account_active_colour         ="";
?>

<style type="text/css">
	.customhelp { colour: #666; font-size:0.9em; }
	.color { <?php echo $entry_border_caption; ?>1px solid #AAA; }
	.pttrn {width:32px; display: inline-block; text-align: center;}
	#title_font_preview {
		font-size: <?php echo $themeoptions_title_font_size; ?>px; 
		font-family: "<?php echo str_replace("+", " ", $themeoptions_title_font); ?>";
	}
	#body_font_preview {
		font-size: <?php echo $themeoptions_body_font_size; ?>px; 
		font-family: "<?php echo str_replace("+", " ", $themeoptions_body_font); ?>";
	}
	#small_font_preview {
		font-size: <?php echo $themeoptions_small_font_size; ?>px; 
		font-family: "<?php echo str_replace("+", " ", $themeoptions_small_font); ?>";
	}
	#title_font_preview,
	#body_font_preview,
	#small_font_preview {
		padding: 6px 12px;
		background: #fefddf;
	}
</style>

<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
    <?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php }
	if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>

<div class="box">

	<div class="heading">
		<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
		<div class="buttons">
            <a onclick="$('#form').attr('action', '<?php echo $action; ?>&continue=1');$('#form').submit();" class="button"><?php echo $button_apply; ?></a> 
        	<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
        </div>
	</div>

	<div class="content">

	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

		<div style="margin-<?php echo $entry_bottom_caption; ?> 10px">
			<label><?php echo $entry_status; ?></label>
			<select name="themeoptions_status">
				<?php if ($themeoptions_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			</select>

			<span class="customhelp"><?php echo $theme_version; ?></span>

		</div>

		<div id="settings_tabs" class="htabs clearfix">
        	<a href="#display_settings"><?php echo $entry_tab_display; ?></a>
			<a href="#backgrounds_settings"><?php echo $entry_tab_backgrounds; ?></a>
			<a href="#colours_settings"><?php echo $entry_tab_colours; ?></a>
			<a href="#fonts_settings"><?php echo $entry_tab_fonts; ?></a>
			<a href="#footer_settings"><?php echo $entry_tab_footer; ?></a>
            <a href="#social_icons"><?php echo $entry_social_icons; ?></a>
			<a href="#custom_code_settings"><?php echo $entry_tab_custom_code; ?></a>
		</div>
        
        <div id="display_settings" class="divtab">
			<table class="form">
				<tr>
					<td colspan="2">
						<h3><?php echo $entry_display_sub; ?></h3>
					</td>
				</tr>
                
                <tr>
					<td><?php echo $entry_topbar; ?></td>
                    <script>
						$(document).ready(function() {
							if($('#themeoptions_topbarscroll').val() == "1"){
								$('#themeoptions_topbar').hide();
							}
							$('#themeoptions_topbarscroll').on('change',function(){
							   if($(this).val() == '1'){
								   $('#themeoptions_topbar').hide();
								   return true;
							   }
							   $('#themeoptions_topbar').show();
							});
						});
					</script>
					<td>
						<select name="themeoptions_topbarscroll" id="themeoptions_topbarscroll">
                            <?php if ($themeoptions_topbarscroll) { ?>
                                <option value="0">Fixed</option>
                                <option value="1" selected="selected">Scrollable</option>
                            <?php } else { ?>
                                <option value="0" selected="selected">Fixed</option>
                                <option value="1">Scrollable</option>
                            <?php } ?>
                        </select>
                        <span class="customhelp"><?php echo $entry_topbarscroll_sub_help; ?></span>
					</td>
                </tr>
                <tr id="themeoptions_topbar">
                    <td><?php echo $entry_topbar_position; ?></td>
                    <td>
						<select name="themeoptions_topbar">
                            <?php if ($themeoptions_topbar) { ?>
                                <option value="0">Top</option>
                                <option value="1" selected="selected">Bottom</option>
                            <?php } else { ?>
                                <option value="0" selected="selected">Top</option>
                                <option value="1">Bottom</option>
                            <?php } ?>
                        </select>
                        <span class="customhelp"><?php echo $entry_topbar_sub_help; ?></span>
					</td>
				</tr>
                <tr>
                    <td><?php echo $entry_currency; ?></td>
                    <td>
						<select name="themeoptions_currency">
                            <?php if ($themeoptions_currency) { ?>
                                <option value="0"><?php echo $text_enabled; ?></option>
                                <option value="1" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="0" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="1"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
					</td>
				</tr>
                <tr>
                    <td><?php echo $entry_main_nav; ?></td>
                    <td>
						<select name="themeoptions_main_nav">
                            <?php if ($themeoptions_main_nav) { ?>
                                <option value="0"><?php echo $text_enabled; ?></option>
                                <option value="1" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="0" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="1"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
					</td>
				</tr>
                <tr>
                    <td><?php echo $entry_breadcrumb; ?></td>
                    <td>
						<select name="themeoptions_breadcrumb">
                            <?php if ($themeoptions_breadcrumb) { ?>
                                <option value="0"><?php echo $text_enabled; ?></option>
                                <option value="1" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="0" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="1"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
					</td>
				</tr>
                <tr>
                    <td><?php echo $entry_center_logo; ?></td>
                    <script>
						$(document).ready(function() {
							if($('#themeoptions_center_logo').val() == "1"){
								$('#themeoptions_search').hide();
								$('#themeoptions_cart').hide();
							}
							$('#themeoptions_center_logo').on('change',function(){
							   if($(this).val() == '1'){
								   $('#themeoptions_search').hide();
								   $('#themeoptions_cart').hide();
								   return true;
							   }
							   $('#themeoptions_search').show();
							   $('#themeoptions_cart').show();
							});
						});
					</script>
                    <td>
						<select name="themeoptions_center_logo" id="themeoptions_center_logo">
                            <?php if ($themeoptions_center_logo) { ?>
                                <option value="0"><?php echo $text_disabled; ?>/Default</option>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?>/Centred</option>
                            <?php } else { ?>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?>/Default</option>
                                <option value="1"><?php echo $text_enabled; ?>/Centred</option>
                            <?php } ?>
                        </select>
					</td>
				</tr>
                <tr id="themeoptions_search">
                    <td><?php echo $entry_search; ?></td>
                    <td>
						<select name="themeoptions_search">
                            <?php if ($themeoptions_search) { ?>
                                <option value="0"><?php echo $text_enabled; ?></option>
                                <option value="1" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="0" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="1"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
					</td>
				</tr>
                <tr id="themeoptions_cart">
                    <td><?php echo $entry_cart; ?></td>
                    <td>
						<select name="themeoptions_cart">
                            <?php if ($themeoptions_cart) { ?>
                                <option value="0"><?php echo $text_enabled; ?></option>
                                <option value="1" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="0" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="1"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
					</td>
				</tr>
                
                <tr>
					<td colspan="2">
						<h3><?php echo $entry_cc_sub; ?></h3>
					</td>
				</tr>
                
                <tr>
                	<td><?php echo $entry_cc_select; ?></td>
					<td>
                    	<span class="customhelp"><?php echo $entry_cc_2co; ?></span>
                        <input type="checkbox" name="themeoptions_cc_2co"<?php if ($themeoptions_cc_2co) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_alipay; ?></span>
                        <input type="checkbox" name="themeoptions_cc_alipay"<?php if ($themeoptions_cc_alipay) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_amazon; ?></span>
                        <input type="checkbox" name="themeoptions_cc_amazon"<?php if ($themeoptions_cc_amazon) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_americanexpress; ?></span>
                        <input type="checkbox" name="themeoptions_cc_americanexpress"<?php if ($themeoptions_cc_americanexpress) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_asiapay; ?></span>
                        <input type="checkbox" name="themeoptions_cc_asiapay"<?php if ($themeoptions_cc_asiapay) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_cashu; ?></span>
                        <input type="checkbox" name="themeoptions_cc_cashu"<?php if ($themeoptions_cc_cashu) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_cirrus; ?></span>
                        <input type="checkbox" name="themeoptions_cc_cirrus"<?php if ($themeoptions_cc_cirrus) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_dinersclub; ?></span>
                        <input type="checkbox" name="themeoptions_cc_dinersclub"<?php if ($themeoptions_cc_dinersclub) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_discover; ?></span>
                        <input type="checkbox" name="themeoptions_cc_discover"<?php if ($themeoptions_cc_discover) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_easycash; ?></span>
                        <input type="checkbox" name="themeoptions_cc_easycash"<?php if ($themeoptions_cc_easycash) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_echeck; ?></span>
                        <input type="checkbox" name="themeoptions_cc_echeck"<?php if ($themeoptions_cc_echeck) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_egold; ?></span>
                        <input type="checkbox" name="themeoptions_cc_egold"<?php if ($themeoptions_cc_egold) echo 'checked="checked" value="1"';?>>
                        <br />
                        <span class="customhelp"><?php echo $entry_cc_eps; ?></span>
                        <input type="checkbox" name="themeoptions_cc_eps"<?php if ($themeoptions_cc_eps) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_giropay; ?></span>
                        <input type="checkbox" name="themeoptions_cc_giropay"<?php if ($themeoptions_cc_giropay) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_googlecheckout; ?></span>
                        <input type="checkbox" name="themeoptions_cc_googlecheckout"<?php if ($themeoptions_cc_googlecheckout) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_jcb; ?></span>
                        <input type="checkbox" name="themeoptions_cc_jcb"<?php if ($themeoptions_cc_jcb) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_laser; ?></span>
                        <input type="checkbox" name="themeoptions_cc_laser"<?php if ($themeoptions_cc_laser) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_maestro; ?></span>
                        <input type="checkbox" name="themeoptions_cc_maestro"<?php if ($themeoptions_cc_maestro) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_mastercard; ?></span>
                        <input type="checkbox" name="themeoptions_cc_mastercard"<?php if ($themeoptions_cc_mastercard) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_moneybookers; ?></span>
                        <input type="checkbox" name="themeoptions_cc_moneybookers"<?php if ($themeoptions_cc_moneybookers) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_obopay; ?></span>
                        <input type="checkbox" name="themeoptions_cc_obopay"<?php if ($themeoptions_cc_obopay) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_paypal; ?></span>
                        <input type="checkbox" name="themeoptions_cc_paypal"<?php if ($themeoptions_cc_paypal) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_pppay; ?></span>
                        <input type="checkbox" name="themeoptions_cc_pppay"<?php if ($themeoptions_cc_pppay) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_sagepay; ?></span>
                        <input type="checkbox" name="themeoptions_cc_sagepay"<?php if ($themeoptions_cc_sagepay) echo 'checked="checked" value="1"';?>>
                        <br />
                        <span class="customhelp"><?php echo $entry_cc_solo; ?></span>
                        <input type="checkbox" name="themeoptions_cc_solo"<?php if ($themeoptions_cc_solo) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_switch; ?></span>
                        <input type="checkbox" name="themeoptions_cc_switch"<?php if ($themeoptions_cc_switch) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_ukash; ?></span>
                        <input type="checkbox" name="themeoptions_cc_ukash"<?php if ($themeoptions_cc_ukash) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_unionpay; ?></span>
                        <input type="checkbox" name="themeoptions_cc_unionpay"<?php if ($themeoptions_cc_unionpay) echo 'checked="checked" value="1"';?>>
                    	<span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_visadebit; ?></span>
                		<input type="checkbox" name="themeoptions_cc_visadebit"<?php if ($themeoptions_cc_visadebit) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_visacredit; ?></span>
                        <input type="checkbox" name="themeoptions_cc_visacredit"<?php if ($themeoptions_cc_visacredit) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_visaelectron; ?></span>
                        <input type="checkbox" name="themeoptions_cc_visaelectron"<?php if ($themeoptions_cc_visaelectron) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_westernunion; ?></span>
                        <input type="checkbox" name="themeoptions_cc_westernunion"<?php if ($themeoptions_cc_westernunion) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_worldpay; ?></span>
                        <input type="checkbox" name="themeoptions_cc_worldpay"<?php if ($themeoptions_cc_worldpay) echo 'checked="checked" value="1"';?>>
                        <span style="margin-left: 10px;" class="customhelp"><?php echo $entry_cc_xoom; ?></span>
                        <input type="checkbox" name="themeoptions_cc_xoom"<?php if ($themeoptions_cc_xoom) echo 'checked="checked" value="1"';?>>
                    </td>
				</tr>
            </table>
        </div>

		<div id="backgrounds_settings" class="divtab">
			<table class="form">
				<tr>
					<td colspan="2">
						<h3><?php echo $entry_pattern_sub; ?></h3>
					</td>
				</tr>
                
                <tr>
					<td><?php echo $entry_no_bg; ?></td>
					<td>
						<select name="themeoptions_no_bg">
                            <?php if ($themeoptions_no_bg) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select> <?php echo $entry_no_bg_help; ?> <br /><br />
						<span class="customhelp"><?php echo $entry_no_bg_sub_help; ?></span>
					</td>
				</tr>

				<tr>
					<td><?php echo $entry_pattern_overlay; ?></td>
					<td>
						<div>
							<?php for ($i = 1; $i <= 42; $i++) { ?>
								<div class="pttrn"><span class="customhelp"><?php echo $i; ?></span><img src="view/image/patterns/<?php echo $i; ?>.png" alt="pattern <?php echo $i; ?>"></div>
								<?php if(!($i%14)): ?>
									<br />
								<?php endif ?>
							<?php } ?>
						</div> <br />
						<select name="themeoptions_pattern_overlay">
                            <option value="default" selected="selected" >default</option>
							<?php for ($i = 1; $i <= 42; $i++) { 
                                ($themeoptions_pattern_overlay == $i) ? $currentpat = 'selected' : $currentpat = '';
                            ?>
                                <option value="<?php echo $i; ?>" <?php echo $currentpat; ?>><?php echo $i; ?></option>'; 
							<?php } ?>
						</select>
						<span class="customhelp"><?php echo $entry_pattern_overlay_help; ?></span>
					</td>
				</tr>
				
				<tr>
					<td><?php echo $entry_custom_pattern; ?></td>
					<td>
						<input type="hidden" name="themeoptions_custom_pattern" value="<?php echo $themeoptions_custom_pattern; ?>" id="themeoptions_custom_pattern" />
						<img src="<?php echo $themeoptions_pattern_preview; ?>" id="themeoptions_pattern_preview" />
						<br /><a onclick="image_upload('themeoptions_custom_pattern', 'themeoptions_pattern_preview');"><?php echo $text_select; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#themeoptions_pattern_preview').attr('src', '<?php echo $no_image; ?>'); $('#themeoptions_custom_pattern').attr('value', '');"><?php echo $text_clear; ?></a>
					</td>
				</tr>

				<tr>
					<td>
						<?php echo $entry_custom_image; ?> <br />
						<span class="customhelp"><?php echo $entry_custom_image_help; ?></span>
					</td>
					<td>
						<input type="hidden" name="themeoptions_custom_image" value="<?php echo $themeoptions_custom_image; ?>" id="themeoptions_custom_image" />
						<img src="<?php echo $themeoptions_image_preview; ?>" alt="" id="themeoptions_image_preview" />
						<br /><a onclick="image_upload('themeoptions_custom_image', 'themeoptions_image_preview');"><?php echo $text_select; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#themeoptions_image_preview').attr('src', '<?php echo $no_image; ?>'); $('#themeoptions_custom_image').attr('value', '');"><?php echo $text_clear; ?></a>
					</td>
				</tr>
			</table>
		</div>

		<div id="colours_settings" class="divtab">
			<table class="form">

				<tr>
					<td colspan="2">
						<h3><?php echo $entry_colours_sub; ?></h3>
						
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<select name="themeoptions_custom_colours">
                            <?php if ($themeoptions_custom_colours) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select> <?php echo $entry_custom_colours_help; ?> <br /><br />
						<span class="customhelp"><?php echo $entry_colours_sub_help; ?></span>
					</td>
				</tr>

				<tr>
					<td colspan="2"><b><?php echo $entry_header_bold; ?></b></td>
				</tr>
                
                <tr>
					<td><?php echo $entry_topmenu_colour; ?></td>
					<td>
                        <span class="customhelp"><?php echo $entry_links_caption; ?></span>
                        <input type="text" name="themeoptions_topmenu_colour" value="<?php echo $themeoptions_topmenu_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_hover_caption; ?></span> 
                        <input type="text" name="themeoptions_topmenu_hover_colour" value="<?php echo $themeoptions_topmenu_hover_colour; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_background_caption; ?></span> 
                        <input type="text" name="themeoptions_topmenu_background" value="<?php echo $themeoptions_topmenu_background; ?>" size="6" class="color {required:false,hash:true}" />
					</td>
				</tr>
                
                <tr>
					<td><?php echo $entry_currency_colour; ?></td>
					<td>
                        <span class="customhelp"><?php echo $entry_links_caption; ?></span>
                        <input type="text" name="themeoptions_currency_colour" value="<?php echo $themeoptions_currency_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_hover_caption; ?></span> 
                        <input type="text" name="themeoptions_currency_hover_colour" value="<?php echo $themeoptions_currency_hover_colour; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_background_caption; ?></span> 
                        <input type="text" name="themeoptions_currency_background" value="<?php echo $themeoptions_currency_background; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_hover_bg_caption; ?></span> 
                        <input type="text" name="themeoptions_currency_hover_background" value="<?php echo $themeoptions_currency_hover_background; ?>" size="6" class="color {required:false,hash:true}" />
					</td>
				</tr>
                
                <tr>
					<td><?php echo $entry_checkout_colour; ?></td>
					<td>
                    	<span class="customhelp"><?php echo $entry_links_caption; ?></span>
						<input type="text" name="themeoptions_checkout_link" value="<?php echo $themeoptions_checkout_link; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_hover_caption; ?></span> 
                        <input type="text" name="themeoptions_checkoutlink_hover" value="<?php echo $themeoptions_checkoutlink_hover; ?>" size="6" class="color {required:false,hash:true}" />
						<span class="customhelp"><?php echo $entry_background_caption; ?></span>
						<input type="text" name="themeoptions_checkout_colour" value="<?php echo $themeoptions_checkout_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_hover_bg_caption; ?></span> 
                        <input type="text" name="themeoptions_checkout_hover" value="<?php echo $themeoptions_checkout_hover; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_border_caption; ?></span> 
                        <input type="text" name="themeoptions_cart_border" value="<?php echo $themeoptions_cart_border; ?>" size="6" class="color {required:false,hash:true}" />
                    </td>
				</tr>

				<tr>
					<td><?php echo $entry_menu_colour; ?></td>
					<td>
						<span class="customhelp"><?php echo $entry_links_caption; ?></span>
						<input type="text" name="themeoptions_menu_colour" value="<?php echo $themeoptions_menu_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_hover_caption; ?></span> 
                        <input type="text" name="themeoptions_menu_hover" value="<?php echo $themeoptions_menu_hover; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_background_caption; ?></span> 
                        <input type="text" name="themeoptions_menu_background" value="<?php echo $themeoptions_menu_background; ?>" size="6" class="color {required:false,hash:true}" />
						<span class="customhelp"><?php echo $entry_hover_bg_caption; ?></span>
						<input type="text" name="themeoptions_menu_hover_background" value="<?php echo $themeoptions_menu_hover_background; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_border_caption; ?></span>
						<input type="text" name="themeoptions_menu_border" value="<?php echo $themeoptions_menu_border; ?>" size="6" class="color {required:false,hash:true}"  />
                    </td>
				</tr>

				<tr>
					<td><?php echo $entry_dropdown_colour; ?></td>
					<td>
						<span class="customhelp"><?php echo $entry_links_caption; ?></span>
						<input type="text" name="themeoptions_dropdown_colour" value="<?php echo $themeoptions_dropdown_colour; ?>" size="6" class="color {required:false,hash:true}"  />
						<span class="customhelp"><?php echo $entry_hover_caption; ?></span> 
                        <input type="text" name="themeoptions_dropdown_hover" value="<?php echo $themeoptions_dropdown_hover; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_hover_bg_caption; ?></span> 
                        <input type="text" name="themeoptions_dropdown_hover_bg" value="<?php echo $themeoptions_dropdown_hover_bg; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_background_caption; ?></span> 
                        <input type="text" name="themeoptions_dropdown_background" value="<?php echo $themeoptions_dropdown_background; ?>" size="6" class="color {required:false,hash:true}" />
					</td>
				</tr>

				<tr>
					<td colspan="2"><b><?php echo $entry_body_bold; ?></b></td>
				</tr>

				<tr>
					<td><?php echo $entry_background_colour; ?></td>
					<td><input type="text" name="themeoptions_background_colour" value="<?php echo $themeoptions_background_colour; ?>" size="6" class="color {required:false,hash:true}"  /></td>
				</tr>

				<tr>
					<td><?php echo $entry_title_colour; ?></td>
					<td>
                    	<span class="customhelp">Main Page Title &lt;h1&gt;:</span>
						<input type="text" name="themeoptions_h1_title_colour" value="<?php echo $themeoptions_h1_title_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp">Sub Page Title &lt;h2&gt;:</span>
                        <input type="text" name="themeoptions_h2_title_colour" value="<?php echo $themeoptions_h2_title_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp">Custom Title &lt;h3&gt;:</span>
                        <input type="text" name="themeoptions_h3_title_colour" value="<?php echo $themeoptions_h3_title_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp">Custom Title &lt;h4&gt;:</span>
                        <input type="text" name="themeoptions_h4_title_colour" value="<?php echo $themeoptions_h4_title_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp">Custom Title &lt;h5&gt;:</span>
                        <input type="text" name="themeoptions_h5_title_colour" value="<?php echo $themeoptions_h5_title_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp">Custom Title &lt;h6&gt;:</span>
                        <input type="text" name="themeoptions_h6_title_colour" value="<?php echo $themeoptions_h6_title_colour; ?>" size="6" class="color {required:false,hash:true}"  />
						<span class="customhelp"><?php echo $entry_title_colour_help; ?></span>
                    </td>
				</tr>

				<tr>
					<td><?php echo $entry_textcolour_caption; ?></td>
					<td><input type="text" name="themeoptions_bodytext_colour" value="<?php echo $themeoptions_bodytext_colour; ?>" size="6" class="color {required:false,hash:true}"  />
						<span class="customhelp"><?php echo $entry_body_colour_help; ?></span></td>
				</tr>

				<tr>
					<td><?php echo $entry_content_links_colour_help; ?></td>
					<td>
                    	<span class="customhelp"><?php echo $entry_links_caption; ?></span>
                        <input type="text" name="themeoptions_content_links_colour" value="<?php echo $themeoptions_content_links_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_hover_caption; ?></span> 
                        <input type="text" name="themeoptions_content_links_hover_colour" value="<?php echo $themeoptions_content_links_hover_colour; ?>" size="6" class="color {required:false,hash:true}" />
                    </td>
				</tr>
                
				<tr>
					<td><?php echo $entry_breadcrumbs_caption; ?></td>
					<td>
                        <span class="customhelp"><?php echo $entry_links_caption; ?></span>
                        <input type="text" name="themeoptions_breadcrumb_links_colour" value="<?php echo $themeoptions_breadcrumb_links_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_hover_caption; ?></span>
                        <input type="text" name="themeoptions_breadcrumb_links_hover_colour" value="<?php echo $themeoptions_breadcrumb_links_hover_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_background_caption; ?></span> 
                        <input type="text" name="themeoptions_breadcrumb_background" value="<?php echo $themeoptions_breadcrumb_background; ?>" size="6" class="color {required:false,hash:true}" />
                    </td>
				</tr>
                
				<tr>
					<td><?php echo $entry_light_colour; ?></td>
					<td><input type="text" name="themeoptions_lighttext_colour" value="<?php echo $themeoptions_lighttext_colour; ?>" size="6" class="color {required:false,hash:true}"  />
						<span class="customhelp"><?php echo $entry_light_colour_help; ?></span></td>
				</tr>

				<tr>
					<td colspan="2"><b><?php echo $entry_footer_bold; ?></b></td>
				</tr>
                
                <tr>
					<td><?php echo $entry_footer_header; ?></td>
					<td><input type="text" name="themeoptions_footer_header_colour" value="<?php echo $themeoptions_footer_header_colour; ?>" size="6" class="color {required:false,hash:true}"  /></td>
				</tr>

				<tr>
					<td><?php echo $entry_textcolour_caption; ?></td>
					<td><input type="text" name="themeoptions_footer_text_colour" value="<?php echo $themeoptions_footer_text_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                    <span class="customhelp">Powered by Opencart text</span></td>
				</tr>

				<tr>
					<td><?php echo $entry_links_caption; ?></td>
					<td>
                        <span class="customhelp"><?php echo $entry_links_caption; ?></span>
                        <input type="text" name="themeoptions_footer_links_colour" value="<?php echo $themeoptions_footer_links_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_hover_caption; ?></span>
                        <input type="text" name="themeoptions_footer_links_hover_colour" value="<?php echo $themeoptions_footer_links_hover_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp">The links in the four columns</span>
                    </td>
				</tr>

				<tr>
					<td colspan="2"><b><?php echo $entry_buttons_bold; ?></b></td>
				</tr>

				<tr>
					<td><?php echo $entry_button_colour; ?></td>
					<td>
                        <span class="customhelp"><?php echo $entry_background_caption; ?></span> 
                        <input type="text" name="themeoptions_button_background_colour" value="<?php echo $themeoptions_button_background_colour; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_textcolour_caption; ?></span> 
                        <input type="text" name="themeoptions_button_text_colour" value="<?php echo $themeoptions_button_text_colour; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_border_caption; ?></span>
						<input type="text" name="themeoptions_button_border" value="<?php echo $themeoptions_button_border; ?>" size="6" class="color {required:false,hash:true}"  />
					</td>
				</tr>

				<tr>
					<td colspan="2"><b><?php echo $entry_products_bold; ?></b></td>
				</tr>

				<tr>
					<td>
						<?php echo $entry_product_name; ?>
					</td>
					<td>
                    	<span class="customhelp"><?php echo $entry_links_caption; ?></span>
                        <input type="text" name="themeoptions_product_name_colour" value="<?php echo $themeoptions_product_name_colour; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_hover_caption; ?></span>
                        <input type="text" name="themeoptions_product_name_hover_colour" value="<?php echo $themeoptions_product_name_hover_colour; ?>" size="6" class="color {required:false,hash:true}"  />
                        <span class="customhelp"><?php echo $entry_product_name_help; ?></span>
					</td>
				</tr>

				<tr>
					<td>
						<?php echo $entry_product_price; ?>
					</td>
					<td>
                        <span class="customhelp"><?php echo $entry_normal_price; ?></span> 
                        <input type="text" name="themeoptions_normal_price_colour" value="<?php echo $themeoptions_normal_price_colour; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_old_price; ?></span> 
                        <input type="text" name="themeoptions_old_price_colour" value="<?php echo $themeoptions_old_price_colour; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_promotion_price; ?></span> 
                        <input type="text" name="themeoptions_new_price_colour" value="<?php echo $themeoptions_new_price_colour; ?>" size="6" class="color {required:false,hash:true}" />
					</td>
				</tr>

				<tr>
					<td colspan="2"><b><?php echo $entry_content_bold; ?></b></td>
				</tr>
                
                <tr>
					<td>
						<?php echo $entry_container_bg; ?>
					</td>
					<td>
						<span class="customhelp"><?php echo $entry_background_caption; ?></span>
                        <input type="text" name="themeoptions_container_bg" value="<?php echo $themeoptions_container_bg; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp">Background colour for the main container.</span>
					</td>
				</tr>
                
                <tr>
					<td>
						<?php echo $entry_footer_bg; ?>
					</td>
					<td>
						<span class="customhelp"><?php echo $entry_background_caption; ?></span>
                        <input type="text" name="themeoptions_footer_bg" value="<?php echo $themeoptions_footer_bg; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp">Background colour of footer.</span>
					</td>
				</tr>
                
                <tr>
					<td colspan="2"><b><?php echo $entry_other_bold; ?></b></td>
				</tr>
                
                <tr>
					<td>
						<?php echo $entry_module_bg; ?>
					</td>
					<td>
						<span class="customhelp"><?php echo $entry_background_caption; ?></span>
                        <input type="text" name="themeoptions_module_bg" value="<?php echo $themeoptions_module_bg; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp">Background colour of side menus.</span>
					</td>
				</tr>

				<tr>
					<td>
						<?php echo $entry_category_menu; ?>
					</td>
					<td>
						<span class="customhelp"><?php echo $entry_category_top; ?></span>
                        <input type="text" name="themeoptions_categories_menu_colour" value="<?php echo $themeoptions_categories_menu_colour; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_hover_caption; ?></span> 
                        <input type="text" name="themeoptions_categories_menu_hover_colour" value="<?php echo $themeoptions_categories_menu_hover_colour; ?>" size="6" class="color {required:false,hash:true}" />
						<span class="customhelp"><?php echo $entry_category_sub; ?></span> 
                        <input type="text" name="themeoptions_categories_sub_colour" value="<?php echo $themeoptions_categories_sub_colour; ?>" size="6" class="color {required:false,hash:true}" />
                        <span class="customhelp"><?php echo $entry_hover_caption; ?></span> 
                        <input type="text" name="themeoptions_categories_sub_hover_colour" value="<?php echo $themeoptions_categories_sub_hover_colour; ?>" size="6" class="color {required:false,hash:true}" />
						<span class="customhelp"><?php echo $entry_active_caption; ?></span> 
                        <input type="text" name="themeoptions_categories_active_colour" value="<?php echo $themeoptions_categories_active_colour; ?>" size="6" class="color {required:false,hash:true}" />
					
					</td>
				</tr>

			</table>
		</div>

		<div id="fonts_settings" class="divtab">
		
			<table class="form">

				<tr>
					<td colspan="2">
						<h3><?php echo $entry_fonts_sub; ?></h3>
						<span class="customhelp"><?php echo $entry_fonts_sub_help; ?></span>
					</td>
				</tr>
                
                <tr>
                    <td><?php echo $entry_center_heading; ?></td>
                    <td>
						<select name="themeoptions_center_heading">
                            <?php if ($themeoptions_center_heading) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
					</td>
				</tr>
				
				<tr>
					<td><?php echo $entry_title_font; ?></td>
					<td>
						<select name="themeoptions_title_font" id="themeoptions_title_font" class="font_select">
							<?php foreach ($fonts as $fv => $fc) { ?>
								<?php ($fv ==  $themeoptions_title_font) ? $currentfont = 'selected' : $currentfont=''; ?>
								<option value="<?php echo $fv; ?>" <?php echo $currentfont; ?> ><?php echo $fc; ?></option>	
							<?php } ?>
						</select>

						<span class="customhelp"><?php echo $entry_size_caption; ?></span>

						<select name="themeoptions_title_font_size" id="themeoptions_title_font_size" class="size_select">
							<?php for ($i = 20; $i <= 48; $i++) { 
									($themeoptions_title_font_size == $i) ? $currentpat = 'selected' : $currentpat = '';
								?>
								<option value="<?php echo $i; ?>" <?php echo $currentpat; ?>><?php echo $i; ?></option>'; 
								<?php } ?>
						</select>

						<span class="customhelp"><?php echo $entry_title_font_help; ?></span>
					</td>
				</tr>

				<tr>
					<td><span class="customhelp"><?php echo $entry_preview_font; ?></span></td>
					<td>
						<span id="title_font_preview">The quick brown fox jumps over a lazy dog.</span>
					</td>
				</tr>

				<tr>
					<td><?php echo $entry_body_font ?></td>
					<td>
						<select name="themeoptions_body_font" id="themeoptions_body_font" class="font_select">
							<?php foreach ($fonts as $fv => $fc) { ?>
								<?php ($fv ==  $themeoptions_body_font) ? $currentfont = 'selected' : $currentfont=''; ?>
								<option value="<?php echo $fv; ?>" <?php echo $currentfont; ?> ><?php echo $fc; ?></option>	
							<?php } ?>
						</select>

						<span class="customhelp"><?php echo $entry_size_caption; ?></span>

						<select name="themeoptions_body_font_size" id="themeoptions_body_font_size" class="size_select">
							<?php for ($i = 10; $i <= 18; $i++) { 
								($themeoptions_body_font_size == $i) ? $currentpat = 'selected' : $currentpat = '';
							?>
								<option value="<?php echo $i; ?>" <?php echo $currentpat; ?>><?php echo $i; ?></option>'; 
							<?php } ?>
						</select>

						<span class="customhelp"><?php echo $entry_body_font_help; ?></span>

					</td>
				</tr>

				<tr>
					<td><span class="customhelp"><?php echo $entry_preview_font; ?></span></td>
					<td>
						<span id="body_font_preview">The quick brown fox jumps over a lazy dog.</span>
					</td>
				</tr>

			 	<tr>
					<td><?php echo $entry_small_font; ?></td>
					<td>
						<select name="themeoptions_small_font" id="themeoptions_small_font" class="font_select">
							<?php foreach ($fonts as $fv => $fc) { ?>
								<?php ($fv == $themeoptions_small_font) ? $currentfont = 'selected' : $currentfont=''; ?>
								<option value="<?php echo $fv; ?>" <?php echo $currentfont; ?> ><?php echo $fc; ?></option>	
							<?php } ?>
						</select>

						<span class="customhelp"><?php echo $entry_size_caption; ?></span>

						<select name="themeoptions_small_font_size" id="themeoptions_small_font_size" class="size_select"> 
							<?php for ($i = 7; $i <= 14; $i++) { 
									($themeoptions_small_font_size == $i) ? $currentpat = 'selected' : $currentpat = '';
								?>
								<option value="<?php echo $i; ?>" <?php echo $currentpat; ?>><?php echo $i; ?></option>'; 
								<?php } ?>
						</select>

						<span class="customhelp"><?php echo $entry_small_font_help; ?></span>
					</td>
				</tr>

				<tr>
					<td><span class="customhelp"><?php echo $entry_preview_font; ?></span></td>
					<td>
						<span id="small_font_preview">The quick brown fox jumps over a lazy dog.</span>
					</td>
				</tr>

			</table>

		</div>

		<div id="footer_settings" class="divtab">
		
			<table class="form">
				
				<tr>
					<td>
						<?php echo $entry_copyright_text; ?> <br />
						<span class="customhelp"><?php echo $entry_copyright_text_help; ?></span>
					</td>
					<td><textarea name="themeoptions_copyright" cols="52" rows="2"><?php echo $themeoptions_copyright; ?></textarea>
					</td>
				</tr>
                
                <tr>
					<td>
						<?php echo $entry_cred_text; ?> <br />
						<span class="customhelp"><?php echo $entry_cred_text_help; ?></span>
					</td>
					<td><textarea name="themeoptions_creds" cols="52" rows="2"><?php echo $themeoptions_creds; ?></textarea>
					</td>
				</tr>
                
			</table>

		</div>
        
        <div id="social_icons" class="divtab">
        
        	<table class="form">

				<tr>
					<td colspan="2">
						<h3><?php echo $entry_social_icons; ?></h3>
					</td>
				</tr>
				
				<tr>
					<td><?php echo $entry_social_fb; ?></td>
					<td><input type="text" name="themeoptions_social_fb" value="<?php echo $themeoptions_social_fb; ?>" />
                    <span class="customhelp"><?php echo $entry_social_fb_help; ?></span></td>
				</tr>
                
                <tr>
					<td><?php echo $entry_social_fb_icon; ?></td>
					<td><input type="text" name="themeoptions_social_fb_icon" value="<?php echo $themeoptions_social_fb_icon; ?>" />
                    <span class="customhelp"><?php echo $entry_social_fb_icon_help; ?></span></td>
				</tr>
                
                <tr>
					<td><?php echo $entry_social_twit; ?></td>
					<td><input type="text" name="themeoptions_social_twit" value="<?php echo $themeoptions_social_twit; ?>" /></td>
				</tr>
                
                <tr>
					<td><?php echo $entry_social_gplus; ?></td>
					<td><input type="text" name="themeoptions_social_gplus" value="<?php echo $themeoptions_social_gplus; ?>" /></td>
				</tr>
                
                <tr>
					<td><?php echo $entry_social_utube; ?></td>
					<td><input type="text" name="themeoptions_social_utube" value="<?php echo $themeoptions_social_utube; ?>" /></td>
				</tr>
                
                <tr>
					<td><?php echo $entry_social_tumblr; ?></td>
					<td><input type="text" name="themeoptions_social_tumblr" value="<?php echo $themeoptions_social_tumblr; ?>" /></td>
				</tr>
                
                <tr>
					<td><?php echo $entry_social_skype; ?></td>
					<td><input type="text" name="themeoptions_social_skype" value="<?php echo $themeoptions_social_skype; ?>" /></td>
				</tr>
                
                <tr>
					<td><?php echo $entry_social_pinterest; ?></td>
					<td><input type="text" name="themeoptions_social_pinterest" value="<?php echo $themeoptions_social_pinterest; ?>" /></td>
				</tr>
                
                <tr>
					<td><?php echo $entry_social_instagram; ?></td>
					<td><input type="text" name="themeoptions_social_instagram" value="<?php echo $themeoptions_social_instagram; ?>" /></td>
				</tr>

			</table>
        
        </div>

		<div id="custom_code_settings" class="divtab">
		
			<table class="form">

				<tr>
					<td colspan="2">
						<h3><?php echo $entry_custom_css_sub; ?></h3>
						<span class="customhelp"><?php echo $entry_custom_css_help; ?></span>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_custom_css; ?></td>
					<td><textarea name="themeoptions_custom_css" cols="52" rows="20" style="width:80%;"><?php echo $themeoptions_custom_css; ?></textarea>
						</td>
				</tr>

				<tr>
					<td colspan="2">
						<h3><?php echo $entry_custom_js_sub; ?></h3>
						<span class="customhelp"><?php echo $entry_custom_js_help; ?></span>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_custom_js; ?></td>
					<td><textarea name="themeoptions_custom_js" cols="52" rows="20" style="width:80%;"><?php echo $themeoptions_custom_js; ?></textarea>
						</td>
				</tr>

			</table>

		</div>

		</form>

	</div>

</div>

<?php echo $footer; ?>

<script type="text/javascript">

	$('#settings_tabs a').tabs();

</script>
<script type="text/javascript">

	$(document).ready(function() {

		var link = "<link href='http://fonts.googleapis.com/css?family=<?php echo $themeoptions_title_font; ?>' id='title_googlefont' rel='stylesheet' type='text/css'>";
				$('head').append(link);
		var link = "<link href='http://fonts.googleapis.com/css?family=<?php echo $themeoptions_body_font; ?>' id='body_googlefont' rel='stylesheet' type='text/css'>";
				$('head').append(link);
		var link = "<link href='http://fonts.googleapis.com/css?family=<?php echo $themeoptions_small_font; ?>' id='small_googlefont' rel='stylesheet' type='text/css'>";
				$('head').append(link);

		$('#themeoptions_title_font').change(function(){
				$('head #title_googlefont').remove();
				var link = "<link href='http://fonts.googleapis.com/css?family="+$(this).val()+"' id='title_googlefont' rel='stylesheet' type='text/css'>";
				$('head').append(link);
				
				var fontname = 	$(this).val().replace(/\+/g," ");
				
				$('#title_font_preview').css("font-family",'"'+fontname+'"');
			
		});

		$('#themeoptions_body_font').change(function(){
				$('head #body_googlefont').remove();
				var link = "<link href='http://fonts.googleapis.com/css?family="+$(this).val()+"' id='body_googlefont' rel='stylesheet' type='text/css'>";
				$('head').append(link);
				
				var fontname = 	$(this).val().replace(/\+/g," ");
				
				$('#body_font_preview').css("font-family",'"'+fontname+'"');
			
		});

		$('#themeoptions_small_font').change(function(){
				$('head #small_googlefont').remove();
				var link = "<link href='http://fonts.googleapis.com/css?family="+$(this).val()+"' id='small_googlefont' rel='stylesheet' type='text/css'>";
				$('head').append(link);
				
				var fontname = 	$(this).val().replace(/\+/g," ");
				
				$('#small_font_preview').css("font-family",'"'+fontname+'"');
			
		});
		
		$('.size_select').change(function(){

			var id = $(this).attr('id');

			if (id == 'themeoptions_title_font_size' ) { $('#title_font_preview').css("font-size",$(this).val()+'px'); }
			if (id == 'themeoptions_body_font_size' ) { $('#body_font_preview').css("font-size",$(this).val()+'px'); }
			if (id == 'themeoptions_small_font_size' ) { $('#small_font_preview').css("font-size",$(this).val()+'px'); }
			
		});
	});
</script>

<script type="text/javascript" src="view/javascript/jscolor/jscolor.js"></script> 

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>

<script type="text/javascript">
	CKEDITOR.replace('themeoptions_copyright', {
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	}); 
	CKEDITOR.replace('themeoptions_creds', {
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	}); 
</script>

<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
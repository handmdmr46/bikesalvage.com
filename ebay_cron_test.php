<?php
/** Modifications: cron job, designed to be on one hour intervals...
*
*				 Ebay Call:    getOrders()
*                Parameters:   timeFrom: now()-1hour  |  timeTo: now()
*
*
*/
$path = dirname( __FILE__ );
require_once($path . '/ebay_cron_config.php');
require_once(DIR_LIBRARY . '/ebaycall.php');

$log_message = '';

// mysql connect
$connection = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
if (!$connection) {
    $log_message .= 'Connection failed: ' . mysql_error();
}
// mysql db select
$db_selected = mysql_select_db(DB_DATABASE, $connection);
if (!$db_selected) {
    $log_message .= 'Can\'t select database: ' . mysql_error();
}

// get ebay settings
$sql    = "SELECT * FROM oc_ebay_settings WHERE affiliate_id = '0'";
$result = mysql_query($sql, $connection);
$row = mysql_fetch_assoc($result);

$compatability = $row['compat'];
$user_token    = $row['user_token'];
$app_id        = $row['application_id'];
$dev_id        = $row['developer_id'];
$cert_id       = $row['certification_id'];
$site_id       = $row['site_id'];
$affiliate_id  = $row['affiliate_id'];

if (!$result) {
    $log_message .= "DB Error, could not query the database\n";
    $log_message .= 'MySQL Error: ' . mysql_error();
    exit;
}
mysql_free_result($result);

// eBay Call GetOrders
$call_name = 'GetItem';
$ebay_call = new EbayCall($dev_id, $app_id, $cert_id, $compatability, $site_id, $call_name);

// format output example: 2014-07-29T01:20:07.000Z
date_default_timezone_set('America/Los_Angeles');
$timeFrom = date('Y-m-d\TH:i:s\.', time() - 3800). '000Z'; // 3600 = 1 hour (added 200ms to prevent any chance of overlap times)
$timeTo   = date('Y-m-d\TH:i:s\.') . '000Z';

$log_message .= '###################################################' . "\n";
$log_message .= 'Ebay Order Cron Log | Inventory Controller Message' . "\n";
$log_message .= 'Timestamp From: ' . $timeFrom . "\n";
$log_message .= 'Timestamp To: ' . $timeFrom . "\n";

$ebay_item_id = 110146219307;

$xml = '<?xml version="1.0" encoding="utf-8"?>';
$xml .= '<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
$xml .= '<RequesterCredentials>';
$xml .= '<eBayAuthToken>' . $user_token . '</eBayAuthToken>';
$xml .= '</RequesterCredentials>';
$xml .= '<ItemID>' . $ebay_item_id . '</ItemID>';
$xml .= '<WarningLevel>Low</WarningLevel>';
$xml .= '<OutputSelector>Title</OutputSelector>';
$xml .= '<OutputSelector>ItemID</OutputSelector>';
$xml .= '<OutputSelector>Quantity</OutputSelector>';
$xml .= '</GetItemRequest>';

$xml_response = $ebay_call->sendHttpRequest($xml);

if(stristr($xml_response, 'HTTP 404') || $xml_response == '') {
	$this->language->load('affiliate/stock_control');
    $response = $this->language->get('error_ebay_api_call');
    return $response;
}

$doc_response = new DomDocument();
$doc_response->loadXML($xml_response);
$message = $doc_response->getElementsByTagName('Ack')->item(0)->nodeValue;

if($message == 'Failure') {
	$severity_code = $doc_response->getElementsByTagName('SeverityCode')->item(0)->nodeValue;
	$error_code = $doc_response->getElementsByTagName('ErrorCode')->item(0)->nodeValue;
	$short_message = $doc_response->getElementsByTagName('ShortMessage')->item(0)->nodeValue;
	$long_message = $doc_response->getElementsByTagName('LongMessage')->item(0)->nodeValue;
    $response = strtoupper($severity_code) . ': ' . $long_message . ' Error Code: ' . $error_code;
	return $response;
}

$quantity = $doc_response->getElementsByTagName('Quantity')->item(0)->nodeValue;
$title = $doc_response->getElementsByTagName('Title')->item(0)->nodeValue;
$item_id = $doc_response->getElementsByTagName('ItemID')->item(0)->nodeValue;

$log_message .= 'Item Info: ' . $title . ' - ' . $item_id . ' - ' . $quantity . "\n";


$log_message .= '###################################################' . "\n";

// Write To Log File
$file = DIR_LOGS . 'ebay_cron_log.txt';
file_put_contents($file, $log_message, FILE_APPEND | LOCK_EX);
mysql_close($connection);


?>
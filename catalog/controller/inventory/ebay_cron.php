<?php
class ControllerInventoryEbayCron extends Controller {
	public function logCron() {
		$this->load->model('inventory/ebay_cron');

		date_default_timezone_set('America/Los_Angeles');

		$time_from = date('Y-m-d\TH:i:s\.', time() - 3600); // 3600 = 1 hour (added 200ms to prevent any chance of overlap times)
		$time_to   = date('Y-m-d\TH:i:s\.');
		// $time_from = '2014-10-05T20:08:37.000Z';
		// $time_to = '2014-10-06T20:08:37.000Z';

		// 2014-10-06T20:08:37.000Z

		$import_data = $this->model_inventory_ebay_cron->getOrdersRequest($time_from . '000Z', $time_to . '000Z');

		$log_message = 'NO ORDERS PRESENT AT THIS TIME - TimeStamp: ' . $time_to;

		if (is_array($import_data)) {
			$log_message = 'GetOrders - TimeStamp: ' . $time_to . '<br>';
			foreach(array_combine($import_data['id'], $import_data['qty_purchased']) as $item_id => $qty) {

				$log_message .= 'ItemID:' . $item_id . ' -- ' . $this->model_inventory_ebay_cron->getProductIdFromEbayListing($item_id) . '<br>';


				if ($this->model_inventory_ebay_cron->getProductIdFromEbayListing($item_id) > 0) {
					$log_message .= 'ItemID: ' . $item_id . '  ';
   					$log_message .= ' -- QuantityPurchased: ' . $qty . '  ';
					$product_id = $this->model_inventory_ebay_cron->getProductIdFromEbayListing($item_id);

					$product_quantity = $this->model_inventory_ebay_cron->getProductQuantity($product_id);

					$product_quantity = (int)$product_quantity;
					$qty = (int)$qty;

					$new_quantity = $product_quantity - $qty;

					$this->model_inventory_ebay_cron->setProductQuantity($new_quantity, $product_id);

					$log_message .= ' ActionTaken: ';

					if ($new_quantity < 1) {
						$this->model_inventory_ebay_cron->setProductStatus(0, $product_id);
						$log_message .= ' ProductID = ' . $product_id . ' NewQuantity = ' . $new_quantity . ' SET Status = Not Active(0)' . '<br>';
					} else {
						$log_message .= ' ProductID = ' . $product_id . ' SET NewQuantity = ' . $new_quantity . '<br>';
					}
				} else {
					$log_message .= 'ItemID: ' . $item_id . ' --  NO MATCH FOUND' . '<br>';
				}
			}
		} else if (is_string($import_data)) { // failed response, write failure message to db
			$this->model_inventory_ebay_cron->setEbayLogMessage($import_data);
		}

		$this->model_inventory_ebay_cron->setEbayLogMessage($log_message);
	}

	public function logTest() {
		// http://localhost:8888/open-cart1.6/index.php?route=inventory/ebay_cron/logTest

		$this->load->model('inventory/ebay_cron');

		date_default_timezone_set('America/Los_Angeles');


		$log_message = 'TESTING EBAY CRON JOBS: timestamp - ' . date('Y-m-d\TH:i:s\.');

		$this->model_inventory_ebay_cron->setEbayLogMessage($log_message);
	}

}// end class
?>
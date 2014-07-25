This is the bikesalvage main project area, all sofware additions will be broken into branches accordingly:

master   - main branch, all changes merged to here when complete
frontend - convert to oc-bootstrap theme, update OpenCart version 1.5.5.1 to 1.5.6.4

Active Branches: ** = not used


	affiliates
	  - admin/controller/affiliate/approval.php
	  - admin/controller/affiliate/import.php
	  - admin/controller/affiliate/profile.php
	  - admin/controller/affiliate/setting.php
	  - admin/model/affiliate/affiliate.php
	  - admin/language/english/affiliate.php
	  - admin/view/template/affiliate/approval_list.tpl
	  - admin/view/template/affiliate/import_list.tpl
	  - admin/view/template/affiliate/profile_list.tpl
	  - admin/view/template/affiliate/sale_list.tpl
	  - admin/view/template/affiliate/setting_list.tpl
	  - catalog/controller/affiliate/common/header.php
	  - catalog/controller/affiliate/common/footer.php
	  - catalog/controller/affiliate/dashboard.php
	  - catalog/controller/affiliate/dashboard_*
	  - catalog/controller/affiliate/filemanager.php
	  - catalog/controller/affiliate/login.php
	  - catalog/controller/affiliate/logout.php
	  - catalog/controller/affiliate/password.php
	  - catalog/controller/affiliate/payment.php
	  - catalog/controller/affiliate/register.php
	  - catalog/controller/affiliate/success.php
	  **catalog/controller/affiliate/tracking.php 
	  - catalog/controller/affiliate/transaction.php


	inventory
	  - admin/controller/inventory/ebay_cron_log.php
	  - admin/controller/inventory/linked_products.php
	  - admin/controller/inventory/unlinked_products.php
	  - admin/controller/inventory/stock_control.php
	  - admin/model/inventory/stock_control.php
	  - admin/language/english/inventory/stock_control.php
	  - admin/view/template/inventory/ebay_cron_log.tpl
	  - admin/view/template/inventory/linked_products.tpl
	  - admin/view/template/inventory/unlinked_products.tpl
	  - admin/view/template/inventory/stock_control.tpl
	  - system/startup.php
	  - system/library/ebaycall.php


	import
	  - admin/controller/import/csv_import.php
	  - admin/controller/import/ebayid_import.php
	  - admin/language/english/import/csv_import.php
	  - admin/model/import/csv_imnport.php
	  - admin/view/template/import/csv_import.tpl
	  - admin/view/template/import/ebayid_import.tpl
	  - system/startup.php
	  - system/library/ebaycall.php
	  - system/library/parsecsv.php

	
	categorytomanufacturer
	  - admin/controller/catalog/category.php
	  - admin/model/catalog/category.php
	  - admin/view/template/catalog/category_form.tpl


	customshipping


	customsearch
	  - catalog/controller/product/search.php
	  - catalog/model/catalog/product.php
	  - catalog/model/catalog/category.php
	  - catalog/model/catalog/manufacturer.php
	  - catalog/langauge/english/product/search.php
	  - catalog/view/theme/oc-bootstrap/template/product/search.tpl

	productupload
	  - admin/controller/catalog/custom_product.php
	  - admin/model/catalog/custom_product.php
	  - admin/language/english/catalog/custom_product.php

	




ALTER TABLE `oc_category`
ADD COLUMN  `manufacturer_id` int(11) NOT NULL DEFAULT'0'
AFTER       `category_id`;

ALTER TABLE `oc_manufacturer`
ADD COLUMN  `link` varchar(100) NOT NULL DEFAULT'#';

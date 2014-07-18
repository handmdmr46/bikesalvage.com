DROP TABLE IF EXISTS `oc_product_to_affiliate`;
CREATE TABLE `oc_product_to_affiliate` (
  `affiliate_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`affiliate_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `oc_product_to_shipping`;
CREATE TABLE `oc_product_to_shipping` (
  `product_id` int(11) NOT NULL,
  `shipping_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`shipping_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `oc_category`
ADD COLUMN  `manufacturer_id` int(11) NOT NULL DEFAULT'0'
AFTER       `category_id`;

ALTER TABLE `oc_manufacturer`
ADD COLUMN  `link` varchar(100) NOT NULL DEFAULT'#';

ALTER TABLE `oc_product`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0',
ADD COLUMN  `csv_import` int(11) NOT NULL DEFAULT'0',
ADD COLUMN  `linked` int(11) NOT NULL DEFAULT '0';

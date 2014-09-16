/*
Bikesalvage.com SQL install file
author: Martin Hand
contact: martin@mdhmotors.com
*/
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*
-- Blog 
*/

CREATE TABLE IF NOT EXISTS `blog` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `bottom` int(1) NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `viewed` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `blog_category` (
  `blog_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `top` tinyint(1) NOT NULL,
  `column` int(3) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`blog_category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=63 ;

CREATE TABLE IF NOT EXISTS `blog_category_description` (
  `blog_category_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `description` text COLLATE utf8_bin NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_bin NOT NULL,
  `meta_keyword` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`blog_category_id`,`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `blog_category_to_layout` (
  `blog_category_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_category_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `blog_category_to_store` (
  `blog_category_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_category_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `blog_comment` (
  `blog_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `author` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `email` varchar(96) COLLATE utf8_bin NOT NULL DEFAULT '',
  `website` varchar(128) COLLATE utf8_bin NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `rating` int(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`blog_comment_id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=17 ;

CREATE TABLE IF NOT EXISTS `blog_description` (
  `blog_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `description` text COLLATE utf8_bin NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_bin NOT NULL,
  `meta_keyword` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`blog_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `blog_image` (
  `blog_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blog_image_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2417 ;

CREATE TABLE IF NOT EXISTS `blog_link` (
  `blog_link_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_bin NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`blog_link_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `blog_link_image` (
  `blog_link_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_link_id` int(11) NOT NULL,
  `link` varchar(255) COLLATE utf8_bin NOT NULL,
  `image` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`blog_link_image_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=97 ;

CREATE TABLE IF NOT EXISTS `blog_link_image_description` (
  `blog_link_image_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `blog_link_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`blog_link_image_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `blog_product_related` (
  `blog_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `blog_related` (
  `blog_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`related_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `blog_tag` (
  `blog_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `tag` varchar(32) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`blog_tag_id`),
  KEY `blog_id` (`blog_id`),
  KEY `language_id` (`language_id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=546 ;

CREATE TABLE IF NOT EXISTS `blog_to_category` (
  `blog_id` int(11) NOT NULL,
  `blog_category_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`blog_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `blog_to_layout` (
  `blog_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `blog_to_store` (
  `blog_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `blog_video` (
  `blog_video_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `video` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blog_video_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2410;

CREATE TABLE IF NOT EXISTS `oc_store_review` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `email` varchar(96) COLLATE utf8_bin NOT NULL DEFAULT '',
  `website` varchar(128) COLLATE utf8_bin NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `rating` int(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`review_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;

/* affiliates */

/*DROP TABLE IF EXISTS `db_customer_to_customergroup`;
CREATE TABLE  `db_customer_to_customergroup` (
  `customer_id` int(11) NOT NULL,
  `customer_group_id` int(11) NOT NULL,
  PRIMARY KEY (`customer_group_id`,`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;*/

DROP TABLE IF EXISTS `oc_ebay_listing`;
CREATE TABLE `oc_ebay_listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ebay_item_id` char(100) NOT NULL,
  `product_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


DROP TABLE IF EXISTS `oc_affiliate_to_email`;
CREATE TABLE `oc_affiliate_to_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(96) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `oc_ebay_import_startdates`;
CREATE TABLE `oc_ebay_import_startdates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` varchar(64) NOT NULL,
  `end_date` varchar(64) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `text` varchar(22) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `oc_ebay_settings`;
CREATE TABLE `oc_ebay_settings` (
  `compat` int(11) NOT NULL,
  `user_token` text NOT NULL,
  `application_id` varchar(64) NOT NULL,
  `developer_id` varchar(64) NOT NULL,
  `certification_id` varchar(64) NOT NULL,
  `site_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `oc_ebay_compatibility`;
CREATE TABLE `oc_ebay_compatibility` (
  `level` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `oc_ebay_site_ids`;
CREATE TABLE `oc_ebay_site_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `site_name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `oc_shipping_method`;
CREATE TABLE `oc_shipping_method` (
  `shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `method_name` varchar(255) NOT NULL,
  `zone` enum('domestic','international') NOT NULL,
  `group` varchar(32) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `key_id` int(11) NOT NULL,
  PRIMARY KEY (`shipping_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;


INSERT INTO `oc_ebay_compatibility` (`level`, `id`) VALUES
(851, 1),
(849, 2),
(847, 3),
(845, 4),
(843, 5),
(841, 6),
(839, 7),
(837, 8),
(835, 9),
(833, 10),
(831, 11),
(829, 12),
(827, 13),
(825, 14),
(823, 15),
(821, 16),
(819, 17),
(817, 18),
(815, 19),
(813, 20),
(811, 21),
(809, 22),
(807, 23),
(805, 24),
(803, 25),
(801, 26),
(873, 27);

INSERT INTO `oc_ebay_site_ids` (`id`, `site_id`, `site_name`) VALUES
(1, 0, 'United States'),
(2, 100, 'eBay Motors'),
(3, 101, 'Italy'),
(4, 123, 'Belgium'),
(5, 146, 'Netherlands'),
(6, 15, 'Australia'),
(7, 16, 'Austria'),
(8, 186, 'Spain'),
(9, 193, 'Switzerland'),
(10, 196, 'Taiwan'),
(11, 2, 'Canada'),
(12, 201, 'Hong Kong'),
(13, 203, 'India'),
(14, 205, 'Ireland'),
(15, 207, 'Malaysia'),
(16, 210, 'Canada (French)'),
(17, 211, 'Philippines'),
(18, 212, 'Poland'),
(19, 216, 'Singapore'),
(20, 218, 'Sweden'),
(21, 223, 'China'),
(22, 23, 'Belgium (French)'),
(23, 3, 'UK'),
(24, 71, 'France'),
(25, 77, 'Germany');

INSERT INTO `oc_shipping_method` (`shipping_id`, `method_name`, `zone`, `group`, `key`, `value`, `key_id`) VALUES
(1, 'First Class Mail Parcel', 'domestic', 'usps', 'usps_domestic_00', '1', 0),
(3, 'Priority Mail ', 'domestic', 'usps', 'usps_domestic_1', '1', 1),
(4, 'Priority Mail Flat Rate Envelope', 'domestic', 'usps', 'usps_domestic_16', '1', 16),
(5, 'Priority Mail Regular Flat Rate Box', 'domestic', 'usps', 'usps_domestic_17', '1', 17),
(6, 'Priority Mail Flat Rate Large Box', 'domestic', 'usps', 'usps_domestic_22', '1', 22),
(7, 'Parcel Post', 'domestic', 'usps', 'usps_domestic_4', '1', 4),
(9, 'First Class International Parcels', 'international', 'usps', 'usps_international_15', '1', 15),
(10, 'Priority Mail International', 'international', 'usps', 'usps_international_2', '1', 2),
(11, 'Priority Mail Flat Rate Envelope', 'international', 'usps', 'usps_international_8', '1', 8),
(12, 'Priority Mail Flat Rate Box', 'international', 'usps', 'usps_international_9', '1', 9),
(13, 'Priority Mail Flat Rate Large Box', 'international', 'usps', 'usps_international_11', '1', 11);

/* alter tables */

ALTER TABLE `oc_customer_group`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';

ALTER TABLE `oc_setting`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';

ALTER TABLE `oc_product`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0',
ADD COLUMN  `csv_import` int(11) NOT NULL DEFAULT'0',
ADD COLUMN  `linked` int(11) NOT NULL DEFAULT '0';

ALTER TABLE `oc_order_product`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0',
ADD COLUMN  `ebay_response` varchar(500) NOT NULL DEFAULT'no response',
ADD COLUMN  `commission` decimal(15,4) NOT NULL DEFAULT '0.0000';

ALTER TABLE `oc_order_total`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';
ADD COLUMN  `master_total` tinyint(1) NOT NULL DEFAULT'0';

ALTER TABLE `oc_order_voucher`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';

ALTER TABLE `oc_order_option`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';

ALTER TABLE `oc_order_history`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';

ALTER TABLE `oc_order_fraud`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';

ALTER TABLE `oc_order_field`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';

ALTER TABLE `oc_order_download`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';

ALTER TABLE `oc_category`
ADD COLUMN  `manufacturer_id` int(11) NOT NULL DEFAULT'0';

ALTER TABLE `oc_ebay_listing`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';

ALTER TABLE `oc_affiliate`
ADD COLUMN  `commission_balance` decimal(15,4) NOT NULL DEFAULT '0.0000',
ADD COLUMN  `other_email` text NOT NULL;

ALTER TABLE `oc_affiliate_transaction`
ADD COLUMN  `status_id` int(11) NOT NULL DEFAULT '0',
ADD COLUMN  `last_modified` datetime NOT NULL;

ALTER TABLE `oc_return`
ADD COLUMN  `affiliate_id` int(11) NOT NULL DEFAULT'0';
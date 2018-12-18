-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.44-0ubuntu0.14.04.1 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.2.0.4947
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table joomlatest.#__search_engine
DROP TABLE IF EXISTS `#__search_engine`;
CREATE TABLE IF NOT EXISTS `#__search_engine` (
  `package_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_name` varchar(30) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  `awardpackageid` int(11) DEFAULT NULL,
  UNIQUE KEY `ap_id` (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_file_keyword
DROP TABLE IF EXISTS `#__se_file_keyword`;
CREATE TABLE IF NOT EXISTS `#__se_file_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `keyword_list` text NOT NULL,
  `package_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_file_keyword_list
DROP TABLE IF EXISTS `#__se_file_keyword_list`;
CREATE TABLE IF NOT EXISTS `#__se_file_keyword_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fileid` int(11) DEFAULT NULL,
  `textvalues` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_file_url
DROP TABLE IF EXISTS `#__se_file_url`;
CREATE TABLE IF NOT EXISTS `#__se_file_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `url_list` text NOT NULL,
  `package_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_file_url_list
DROP TABLE IF EXISTS `#__se_file_url_list`;
CREATE TABLE IF NOT EXISTS `#__se_file_url_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fileid` int(11) DEFAULT NULL,
  `textvalues` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_groups
DROP TABLE IF EXISTS `#__se_groups`;
CREATE TABLE IF NOT EXISTS `#__se_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `package_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_on` timestamp NULL DEFAULT NULL,
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `updated_by` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_ip
DROP TABLE IF EXISTS `#__se_ip`;
CREATE TABLE IF NOT EXISTS `#__se_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `ip_from` varchar(200) NOT NULL,
  `ip_to` varchar(200) NOT NULL,
  `package_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_keywordgrouplist
DROP TABLE IF EXISTS `#__se_keywordgrouplist`;
CREATE TABLE IF NOT EXISTS `#__se_keywordgrouplist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_keywordgrouplist_keywords
DROP TABLE IF EXISTS `#__se_keywordgrouplist_keywords`;
CREATE TABLE IF NOT EXISTS `#__se_keywordgrouplist_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `keywordgroup_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_penalties
DROP TABLE IF EXISTS `#__se_penalties`;
CREATE TABLE IF NOT EXISTS `#__se_penalties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `days` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `assign_to` int(11) NOT NULL DEFAULT '0',
  `soft_delete` tinyint(2) NOT NULL DEFAULT '0',
  `package_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_quizgrouplist
DROP TABLE IF EXISTS `#__se_quizgrouplist`;
CREATE TABLE IF NOT EXISTS `#__se_quizgrouplist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `published` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_quizgrouplist_quizs
DROP TABLE IF EXISTS `#__se_quizgrouplist_quizs`;
CREATE TABLE IF NOT EXISTS `#__se_quizgrouplist_quizs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL DEFAULT '0',
  `quizgroup_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_recieve_user
DROP TABLE IF EXISTS `#__se_recieve_user`;
CREATE TABLE IF NOT EXISTS `#__se_recieve_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gcid` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `status` char(5) NOT NULL,
  `giftcode_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'if 1 is for buy giftcod,0 for free',
  PRIMARY KEY (`id`),
  KEY `gcid` (`gcid`),
  KEY `lt5g1_gc_recieve_user_ibfk_1` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_rewardlist
DROP TABLE IF EXISTS `#__se_rewardlist`;
CREATE TABLE IF NOT EXISTS `#__se_rewardlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `sedescription` text NOT NULL,
  `package_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `startpublishdate` datetime DEFAULT NULL,
  `endpublishdate` datetime DEFAULT NULL,
  `usergroup` int(11) NOT NULL,
  `keywordgroup` int(11) NOT NULL,
  `urlgroup` int(11) NOT NULL,
  `surveygroup` int(11) NOT NULL,
  `quizgroup` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `usergroupfull` text NOT NULL,
  `keywordgroupfull` text NOT NULL,
  `urlgroupfull` text NOT NULL,
  `surveygroupfull` text NOT NULL,
  `quizgroupfull` text NOT NULL,
  `usergroupdesc` text NOT NULL,
  `keywordgroupdesc` text NOT NULL,
  `urlgroupdesc` text NOT NULL,
  `surveygroupdesc` text NOT NULL,
  `quizgroupdesc` text NOT NULL,
  `created_by` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_search_archive
DROP TABLE IF EXISTS `#__se_search_archive`;
CREATE TABLE IF NOT EXISTS `#__se_search_archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `awardpackage` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_archived` datetime NOT NULL,
  `number_of_user` int(11) NOT NULL,
  `number_of_prize` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_search_list
DROP TABLE IF EXISTS `#__se_search_list`;
CREATE TABLE IF NOT EXISTS `#__se_search_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniqueid` varchar(50) DEFAULT NULL,
  `user_ip` varchar(200) NOT NULL,
  `user_ip_int` bigint(20) DEFAULT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `keyword` text,
  `search_type` text,
  `user_id` int(11) DEFAULT NULL,
  `url` text,
  `url_id` int(11) DEFAULT NULL,
  `url_clicked` varchar(1000) DEFAULT NULL,
  `url_type` varchar(100) DEFAULT NULL,
  `clicked_date_time` datetime DEFAULT NULL,
  `package_id` int(11) NOT NULL,
  `rewardid` int(11) NOT NULL,
  `rewardid_clicked_date_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_search_list_urls
DROP TABLE IF EXISTS `#__se_search_list_urls`;
CREATE TABLE IF NOT EXISTS `#__se_search_list_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniqueid` varchar(50) DEFAULT NULL,
  `user_ip` varchar(200) NOT NULL,
  `user_ip_int` bigint(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `url_id` int(11) DEFAULT NULL,
  `url_clicked` varchar(1000) DEFAULT NULL,
  `url_type` varchar(100) DEFAULT NULL,
  `clicked_date_time` datetime DEFAULT NULL,
  `package_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_surveygrouplist
DROP TABLE IF EXISTS `#__se_surveygrouplist`;
CREATE TABLE IF NOT EXISTS `#__se_surveygrouplist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `published` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_surveygrouplist_surveys
DROP TABLE IF EXISTS `#__se_surveygrouplist_surveys`;
CREATE TABLE IF NOT EXISTS `#__se_surveygrouplist_surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL DEFAULT '0',
  `surveygroup_id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_urlgrouplist
DROP TABLE IF EXISTS `#__se_urlgrouplist`;
CREATE TABLE IF NOT EXISTS `#__se_urlgrouplist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `published` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_urlgrouplist_urls
DROP TABLE IF EXISTS `#__se_urlgrouplist_urls`;
CREATE TABLE IF NOT EXISTS `#__se_urlgrouplist_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `urlgroup_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_urlrewardlist
DROP TABLE IF EXISTS `#__se_urlrewardlist`;
CREATE TABLE IF NOT EXISTS `#__se_urlrewardlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `additionalsettings` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_urlrewardlist_settings
DROP TABLE IF EXISTS `#__se_urlrewardlist_settings`;
CREATE TABLE IF NOT EXISTS `#__se_urlrewardlist_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `urlrewardid` int(11) NOT NULL,
  `giftcodes` varchar(255) NOT NULL,
  `urllist` text NOT NULL,
  `additionalsettings` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_urlrewardlist_settings_gc
DROP TABLE IF EXISTS `#__se_urlrewardlist_settings_gc`;
CREATE TABLE IF NOT EXISTS `#__se_urlrewardlist_settings_gc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `urlrewardid` int(11) DEFAULT NULL,
  `gcid` int(11) DEFAULT NULL,
  `gcname` varchar(50) DEFAULT NULL,
  `gcqty` float DEFAULT NULL,
  `gccost` float DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_urlrewardlist_settings_ugs
DROP TABLE IF EXISTS `#__se_urlrewardlist_settings_ugs`;
CREATE TABLE IF NOT EXISTS `#__se_urlrewardlist_settings_ugs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `urlrewardid` int(11) DEFAULT NULL,
  `urllist` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_useraccounts
DROP TABLE IF EXISTS `#__se_useraccounts`;
CREATE TABLE IF NOT EXISTS `#__se_useraccounts` (
  `ap_account_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `firstname` varchar(30) DEFAULT NULL,
  `lastname` varchar(30) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `street` varchar(300) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `post_code` varchar(10) DEFAULT NULL,
  `country` varchar(40) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `paypal_account` varchar(100) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `is_active` int(1) DEFAULT NULL,
  `is_presentation` tinyint(1) DEFAULT NULL,
  `pug_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `free_usergroup_id` int(11) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fund` float DEFAULT NULL,
  `fund_status` tinyint(4) DEFAULT NULL,
  `donation_status` tinyint(4) DEFAULT NULL,
  `symbol_status` tinyint(4) DEFAULT NULL,
  `ticket_status` tinyint(4) DEFAULT NULL,
  `prize_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`ap_account_id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_usergroup
DROP TABLE IF EXISTS `#__se_usergroup`;
CREATE TABLE IF NOT EXISTS `#__se_usergroup` (
  `criteria_id` int(20) NOT NULL AUTO_INCREMENT,
  `package_id` int(20) NOT NULL DEFAULT '0',
  `population` tinyint(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `from_age` int(11) NOT NULL,
  `to_age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `field` varchar(30) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  `group_name` varchar(250) DEFAULT NULL,
  `is_presentation` tinyint(1) DEFAULT NULL,
  `parent_usergroup` bigint(20) DEFAULT NULL,
  `usergroup_id` int(11) NOT NULL,
  `useraccount_id` int(11) NOT NULL,
  `filter` varchar(255) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`criteria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_usergroups
DROP TABLE IF EXISTS `#__se_usergroups`;
CREATE TABLE IF NOT EXISTS `#__se_usergroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_usergroups_fields
DROP TABLE IF EXISTS `#__se_usergroups_fields`;
CREATE TABLE IF NOT EXISTS `#__se_usergroups_fields` (
  `criteria_id` int(20) NOT NULL AUTO_INCREMENT,
  `package_id` int(20) NOT NULL DEFAULT '0',
  `population` tinyint(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `from_age` int(11) NOT NULL,
  `to_age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `field` varchar(30) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '1',
  `free_usergroup_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `group_name` varchar(250) DEFAULT NULL,
  `is_presentation` tinyint(1) DEFAULT NULL,
  `var_id` int(11) NOT NULL,
  `parent_usergroup` bigint(20) DEFAULT NULL,
  `usergroup_id` int(11) NOT NULL,
  `useraccount_id` int(11) NOT NULL,
  `filter` varchar(255) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`criteria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_usergroup_age
DROP TABLE IF EXISTS `#__se_usergroup_age`;
CREATE TABLE IF NOT EXISTS `#__se_usergroup_age` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `population` int(3) NOT NULL,
  `from_age` int(3) NOT NULL,
  `to_age` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_usergroup_email
DROP TABLE IF EXISTS `#__se_usergroup_email`;
CREATE TABLE IF NOT EXISTS `#__se_usergroup_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `population` int(3) NOT NULL,
  `email` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_usergroup_gender
DROP TABLE IF EXISTS `#__se_usergroup_gender`;
CREATE TABLE IF NOT EXISTS `#__se_usergroup_gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `population` int(3) NOT NULL,
  `gender` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_usergroup_location
DROP TABLE IF EXISTS `#__se_usergroup_location`;
CREATE TABLE IF NOT EXISTS `#__se_usergroup_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `population` int(3) NOT NULL,
  `country` varchar(30) NOT NULL,
  `state` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `street` varchar(100) NOT NULL,
  `zip_postal` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_usergroup_name
DROP TABLE IF EXISTS `#__se_usergroup_name`;
CREATE TABLE IF NOT EXISTS `#__se_usergroup_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `population` int(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_usersearch_usergroup
DROP TABLE IF EXISTS `#__se_usersearch_usergroup`;
CREATE TABLE IF NOT EXISTS `#__se_usersearch_usergroup` (
  `criteria_id` int(20) NOT NULL AUTO_INCREMENT,
  `package_id` int(20) NOT NULL DEFAULT '0',
  `population` tinyint(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `from_age` int(11) NOT NULL,
  `to_age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `field` varchar(30) NOT NULL,
  `filter` varchar(255) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`criteria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_userstatus
DROP TABLE IF EXISTS `#__se_userstatus`;
CREATE TABLE IF NOT EXISTS `#__se_userstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_user_penalty
DROP TABLE IF EXISTS `#__se_user_penalty`;
CREATE TABLE IF NOT EXISTS `#__se_user_penalty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `penalty_form_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_user_usergroup_searchtemp
DROP TABLE IF EXISTS `#__se_user_usergroup_searchtemp`;
CREATE TABLE IF NOT EXISTS `#__se_user_usergroup_searchtemp` (
  `user_id` int(11) NOT NULL,
  `se_usergroup_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_utilitylabelgrouplist
DROP TABLE IF EXISTS `#__se_utilitylabelgrouplist`;
CREATE TABLE IF NOT EXISTS `#__se_utilitylabelgrouplist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table joomlatest.#__se_ut_usergroup
DROP TABLE IF EXISTS `#__se_ut_usergroup`;
CREATE TABLE IF NOT EXISTS `#__se_ut_usergroup` (
  `criteria_id` int(20) NOT NULL AUTO_INCREMENT,
  `package_id` int(20) NOT NULL DEFAULT '0',
  `population` tinyint(3) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `from_age` int(11) NOT NULL,
  `to_age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(150) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `post_code` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `field` varchar(30) NOT NULL,
  `filter` varchar(255) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`criteria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Dumping structure for table joomlatest.gzcs6_se_user_penalty_history
DROP TABLE IF EXISTS `#__se_user_penalty_history`;
CREATE TABLE IF NOT EXISTS `#__se_user_penalty_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `penalty_form_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
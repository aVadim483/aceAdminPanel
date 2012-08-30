CREATE TABLE IF NOT EXISTS `prefix_adminset` (
      `adminset_id` int(11) unsigned NOT NULL auto_increment,
      `adminset_key` varchar(100) NOT NULL,
      `adminset_val` text character set utf8 NOT NULL,
      PRIMARY KEY  (`adminset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE  IF NOT EXISTS `prefix_adminban` (
  `id` bigint(20) NOT NULL auto_increment,
  `user_id` bigint(20) NOT NULL,
  `banwarn` int(11) NOT NULL default '0',
  `bandate` datetime NOT NULL,
  `banline` datetime default NULL,
  `bancomment` varchar(255) default NULL,
  `banunlim` tinyint(4) NOT NULL default '0',
  `banactive` TINYINT DEFAULT '0' NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        
CREATE TABLE IF NOT EXISTS `prefix_adminips` (
  `id` bigint(20) NOT NULL auto_increment,
  `ip1` bigint(20) default NULL,
  `ip2` bigint(20) default '0',
  `bandate` datetime NOT NULL,
  `banline` datetime default NULL,
  `bancomment` varchar(255) default NULL,
  `banunlim` tinyint(4) NOT NULL default '0',
  `banactive` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_id` (`ip1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELETE FROM `prefix_adminset` WHERE `adminset_key`='version';
INSERT INTO `prefix_adminset` (`adminset_key`, `adminset_val`) VALUE ('version', '1.4') ;

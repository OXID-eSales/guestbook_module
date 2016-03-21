CREATE TABLE `oxgbentries` (
  `OXID` char(32) COLLATE latin1_general_ci NOT NULL COMMENT 'Entry id',
  `OXSHOPID` int(11) NOT NULL DEFAULT '1' COMMENT 'Shop id (oxshops)',
  `OXUSERID` char(32) COLLATE latin1_general_ci NOT NULL DEFAULT '' COMMENT 'User id (oxuser)',
  `OXCONTENT` text COLLATE latin1_general_ci NOT NULL COMMENT 'Content',
  `OXCREATE` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Creation time',
  `OXACTIVE` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is active',
  `OXVIEWED` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the entry was checked by admin',
  `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Timestamp',
  PRIMARY KEY (`OXID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Guestbook`s entries'
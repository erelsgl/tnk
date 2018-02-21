CREATE TABLE `prjot_xgim` (
  `xtiva` varchar(20) NOT NULL default '',
  `sdr` int(11) NOT NULL default '0',
  `kotrt` varchar(255) default NULL,
  `ktovt` varchar(255) default NULL,
  PRIMARY KEY  (`xtiva`,`sdr`)
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/prjot_xgim.txt'  INTO TABLE prjot_xgim (xtiva,sdr,kotrt,ktovt);


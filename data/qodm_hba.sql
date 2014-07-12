CREATE TABLE `qodm_hba` (
  `qodm` varchar(3) character set hebrew NOT NULL default '',
  `hba` varchar(3) character set hebrew default NULL,
  PRIMARY KEY  (`qodm`),
  UNIQUE KEY `hba` (`hba`)
) ENGINE=MyISAM CHARACTER SET latin1;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/qodm_hba.txt'  INTO TABLE qodm_hba (qodm,hba);


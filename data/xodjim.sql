CREATE TABLE `xodjim` (
  `mspr_tjry` tinyint(4) NOT NULL,
  `mspr_nisn` tinyint(4) default NULL,
  `jm_mspri` varchar(16) default NULL,
  `jm_ivri` varchar(16) default NULL,
  `jm_prsi` varchar(16) default NULL,
  PRIMARY KEY  (`mspr_tjry`)
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/xodjim.txt'  INTO TABLE xodjim (mspr_tjry,mspr_nisn,jm_mspri,jm_ivri,jm_prsi);


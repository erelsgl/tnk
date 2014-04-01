CREATE TABLE `prqim` (
  `mspr` smallint(6) NOT NULL DEFAULT '0',
  `kotrt` char(3) DEFAULT NULL,
  `qod_mlbim` char(2) DEFAULT NULL,
  `qod_snunit` char(3) DEFAULT NULL,
  PRIMARY KEY (`mspr`)
) ENGINE=MyISAM CHARACTER SET hebrew;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/prqim.txt'  INTO TABLE prqim (mspr,kotrt,qod_mlbim,qod_snunit);


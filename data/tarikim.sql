CREATE TABLE `tarikim` (
  `kotrt` varchar(255) NOT NULL DEFAULT '',
  `psuq` varchar(16) DEFAULT NULL,
  `erua` varchar(32) DEFAULT NULL,
  `yom` tinyint(4) DEFAULT NULL,
  `xodj` tinyint(4) DEFAULT NULL,
  `jna` int(11) DEFAULT NULL,
  PRIMARY KEY (`kotrt`),
  KEY `xodj` (`xodj`,`yom`)
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/tarikim.txt'  INTO TABLE tarikim (kotrt,psuq,erua,yom,xodj,jna);


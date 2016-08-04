CREATE TABLE `sfrim` (
  `qod` char(3) DEFAULT NULL,
  `qod_2otiot` char(2) DEFAULT NULL,
  `kotrt` char(12) DEFAULT NULL,
  `mspr_psukomat` int(11) NOT NULL DEFAULT '0',
  `qod_mamre` char(3) DEFAULT NULL,
  `qod_mlbim` char(2) DEFAULT NULL,
  `kmut_prqim` int(11) NOT NULL DEFAULT '0',
  `xtiva` char(15) DEFAULT NULL,
  `sfr_msora` char(25) DEFAULT NULL,
  `qod_snunit` char(4) DEFAULT NULL,
  `qod_sacred_texts` char(3) DEFAULT NULL,
  `qod_mfrjim` char(3) DEFAULT NULL,
  `qod_hareidi` char(50) DEFAULT NULL,
  `booklevel_psuqomat` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`mspr_psukomat`),
  UNIQUE KEY `qod` (`qod`),
  UNIQUE KEY `qod_mlbim` (`qod_mlbim`),
  UNIQUE KEY `qod_mamre` (`qod_mamre`),
  UNIQUE KEY `qod_2otiot` (`qod_2otiot`),
  UNIQUE KEY `qod_snunit` (`qod_snunit`),
  UNIQUE KEY `qod_mfrjim` (`qod_mfrjim`),
  UNIQUE KEY `qod_hareidi` (`qod_hareidi`)
) ENGINE=MyISAM CHARACTER SET hebrew;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/sfrim.txt'  INTO TABLE sfrim (qod,qod_2otiot,kotrt,mspr_psukomat,qod_mamre,qod_mlbim,kmut_prqim,xtiva,sfr_msora,qod_snunit,qod_sacred_texts,qod_mfrjim,qod_hareidi,booklevel_psuqomat);


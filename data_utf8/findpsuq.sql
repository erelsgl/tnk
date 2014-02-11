CREATE TABLE `findpsuq` (
  `kotrt_sfr` char(12) DEFAULT NULL,
  `kotrt` varchar(16) DEFAULT NULL,
  `ktovt` varchar(16) DEFAULT NULL,
  `verse_number` int(11) NOT NULL DEFAULT '0',
  `verse_letter` char(3) DEFAULT NULL,
  `verse_text` mediumtext,
  `ktovt_trgum` varchar(160) DEFAULT '',
  `ktovt_trgum_xdj` varchar(23) DEFAULT NULL,
  `ktovt_sikum` varbinary(35) DEFAULT NULL
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/findpsuq.txt'  INTO TABLE findpsuq (kotrt_sfr,kotrt,ktovt,verse_number,verse_letter,verse_text,ktovt_trgum,ktovt_trgum_xdj,ktovt_sikum);


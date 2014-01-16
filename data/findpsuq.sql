CREATE TABLE `findpsuq` (
  `kotrt_sfr` char(12) CHARACTER SET hebrew DEFAULT NULL,
  `kotrt` varchar(16) CHARACTER SET hebrew DEFAULT NULL,
  `ktovt` varchar(16) CHARACTER SET hebrew DEFAULT NULL,
  `verse_number` int(11) NOT NULL DEFAULT '0',
  `verse_letter` char(3) CHARACTER SET hebrew DEFAULT NULL,
  `verse_text` text CHARACTER SET hebrew,
  `ktovt_trgum` varchar(255) DEFAULT NULL,
  `ktovt_trgum_xdj` varchar(23) CHARACTER SET hebrew DEFAULT NULL,
  `ktovt_sikum` varchar(35) CHARACTER SET hebrew DEFAULT NULL
) ENGINE=InnoDB CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/findpsuq.txt'  INTO TABLE findpsuq (kotrt_sfr,kotrt,ktovt,verse_number,verse_letter,verse_text,ktovt_trgum,ktovt_trgum_xdj,ktovt_sikum);


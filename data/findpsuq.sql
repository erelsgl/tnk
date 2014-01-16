CREATE TABLE `findpsuq` (
  `kotrt_sfr` char(12) character set hebrew default NULL,
  `kotrt` varchar(16) character set hebrew default NULL,
  `ktovt` varchar(16) character set hebrew default NULL,
  `verse_number` int(11) NOT NULL default '0',
  `verse_letter` char(3) character set hebrew default NULL,
  `verse_text` text character set hebrew,
  `ktovt_trgum` varchar(160) default '',
  `ktovt_trgum_xdj` varchar(23) character set hebrew default NULL,
  `ktovt_sikum` varbinary(35) default NULL
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/findpsuq.txt'  INTO TABLE findpsuq (kotrt_sfr,kotrt,ktovt,verse_number,verse_letter,verse_text,ktovt_trgum,ktovt_trgum_xdj,ktovt_sikum);


CREATE TABLE `psuq_qodm_hba` (
  `book_code` char(3) NOT NULL DEFAULT '',
  `chapter_letter` char(3) NOT NULL DEFAULT '',
  `verse_number` int(11) NOT NULL DEFAULT '0',
  `previous_chapter` char(3) NOT NULL DEFAULT '',
  `previous_verse_number` bigint(12) NOT NULL DEFAULT '0',
  `next_chapter` char(3) NOT NULL DEFAULT '',
  `next_verse_number` bigint(12) NOT NULL DEFAULT '0'
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/psuq_qodm_hba.txt'  INTO TABLE psuq_qodm_hba (book_code,chapter_letter,verse_number,previous_chapter,previous_verse_number,next_chapter,next_verse_number);


CREATE TABLE `psuqim` (
  `id` char(9) NOT NULL DEFAULT '',
  `book_code` char(3) NOT NULL DEFAULT '',
  `book_name` char(12) NOT NULL DEFAULT '',
  `chapter_letter` char(3) NOT NULL DEFAULT '',
  `verse_number` int(11) NOT NULL DEFAULT '0',
  `verse_letter` char(3) DEFAULT NULL,
  `text_otiot` text,
  `text_niqud` text,
  `text_teamim` text,
  `ktovt_prq` varchar(16) DEFAULT NULL,
  `ktovt_sikum` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/psuqim.txt'  INTO TABLE psuqim (id,book_code,book_name,chapter_letter,verse_number,verse_letter,text_otiot,text_niqud,text_teamim,ktovt_prq,ktovt_sikum);


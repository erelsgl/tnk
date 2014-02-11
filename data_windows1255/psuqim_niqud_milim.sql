CREATE TABLE `psuqim_niqud_milim` (
  `book` char(3) NOT NULL default '',
  `book_name` char(20) NOT NULL default '',
  `book_code` char(3) NOT NULL default '',
  `chapter` char(3) NOT NULL default '',
  `chapter_code` char(3) NOT NULL default '',
  `verse` char(3) NOT NULL default '',
  `verse_number` int(11) NOT NULL default '0',
  `word_number` int(11) NOT NULL default '0',
  `word_niqud` varchar(255) default NULL,
  PRIMARY KEY  (`book_code`,`chapter_code`,`verse_number`,`word_number`),
  KEY `book_name` (`book_name`),
  KEY `chapter` (`chapter`),
  KEY `verse` (`verse`)
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/psuqim_niqud_milim.txt'  INTO TABLE psuqim_niqud_milim (book,book_name,book_code,chapter,chapter_code,verse,verse_number,word_number,word_niqud);


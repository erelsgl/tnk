CREATE TABLE `miqraot_gdolot` (
  `id` char(9) NOT NULL DEFAULT '',
  `book_code` char(3) NOT NULL DEFAULT '',
  `book_name` char(12) NOT NULL DEFAULT '',
  `chapter_letter` char(3) NOT NULL DEFAULT '',
  `verse_number` int(11) NOT NULL DEFAULT '0',
  `verse_letter` char(3) DEFAULT NULL,
  `parsed` text,
  `rjy` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/miqraot_gdolot.txt'  INTO TABLE miqraot_gdolot (id,book_code,book_name,chapter_letter,verse_number,verse_letter,parsed,rjy);


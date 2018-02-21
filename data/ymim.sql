CREATE TABLE `ymim` (
  `mspr` int(11) default NULL,
  `jm` varchar(31) default NULL
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/ymim.txt'  INTO TABLE ymim (mspr,jm);


CREATE TABLE `mspry_psuqim` (
  `sfr` char(3) NOT NULL DEFAULT '',
  `prq0` char(3) NOT NULL DEFAULT '',
  `count` bigint(21) NOT NULL DEFAULT '0'
) ENGINE=InnoDB CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/mspry_psuqim.txt'  INTO TABLE mspry_psuqim (sfr,prq0,count);


CREATE TABLE `prjot_jvua_html` (
  `sdr` int(11) NOT NULL default '0',
  `html` text,
  PRIMARY KEY  (`sdr`)
) ENGINE=MyISAM CHARACTER SET utf8;

SET character_set_database=utf8;

LOAD DATA LOCAL INFILE '$BACKUP_FILEROOT/prjot_jvua_html.txt'  INTO TABLE prjot_jvua_html (sdr,html);


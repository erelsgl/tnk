<?php 
$SCRIPT = dirname(__FILE__) . '/../script';
require_once("$SCRIPT/sql.php");
require(dirname(__FILE__) . '/db_connect_params.php');
$GLOBALS['CREATE_BACKUP_DIRECTORY'] = false; // already created by create.php
$mysql_options = MYSQL_CLIENT_INTERACTIVE; // Allow interactive_timeout seconds (instead of wait_timeout) of inactivity before closing the connection - prevent the "MySQL client has gone away" after reading a long text from wikisource
$link = sql_connect_and_select($db_host, $db_name, $db_user, $db_pass, false, $mysql_options);
sql_set_charset("utf8");
sql_query_or_die("SET default_storage_engine=MYISAM"); // Make sure all created tables have the MyISAM engine
?>
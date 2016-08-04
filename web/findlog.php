<?php
error_reporting(E_ALL);

/**
 * @file findlog.php
 * Browse the find log
 * @author Erel Segal אראל סגל
 * @date 2009-08-06
 */

require("../_script/coalesce.php");
require("admin/db_connect.php");
$DEBUG_SELECT_QUERIES = isset($_GET['debug_select']);
$DEBUG_QUERY_TIMES = isset($_GET['debug_times']);

mysql_query("set character_set_client=utf8");
mysql_query("set character_set_results=utf8");
mysql_query("set character_set_database=utf8");

sql_query_or_die("SET time_zone = '+03:00'"); // affects only timestamp fields

print "
<html dir='rtl'>
<head>
	<meta charset='utf8' />
	<link rel='stylesheet' href='/_script/klli.css' />
</head>
<body>
";

$where = coalesce($_GET['where'],1);

sql_print_query("
	SELECT 
		phrase_link `ביטוי`, 
		recommended_count `מומלץ`,
		findpsuq_count `פסוק`,
		google_count `גוגל`,
		mftx_count `מפתח`,
		ipaddress `כתובת`,
		timestamp `זמן`
		FROM findlog
		WHERE $where
	ORDER BY timestamp DESC
	LIMIT 100
	");

sql_print_query("
	SELECT 
		phrase_link `ביטוי`, 
		recommended_count `מומלץ`,
		findpsuq_count `פסוק`,
		google_count `גוגל`,
		mftx_count `מפתח`,
		ipaddress `כתובת`,
		timestamp `זמן`
		FROM findlog
		WHERE $where
		AND recommended_count=0
		AND findpsuq_count=0
	ORDER BY timestamp DESC
	LIMIT 100
	", /*$table_attributes=*/'', $title='בלי תוצאות מומלצות ובלי פסוקים');

print "
</body>
</html>
";

?>
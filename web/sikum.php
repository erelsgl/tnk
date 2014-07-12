<?php
/* קידוד חלונות */
error_reporting(E_ALL);

set_include_path(realpath(dirname(__FILE__) . "/../script") . PATH_SEPARATOR . get_include_path());
require_once('../admin/db_connect.php');
require_once('coalesce.php');
require_once('forms.php');
require_once('sikum_library.php');
?>
<html dir='rtl' lang='he'>
<head>
<title>Verse Summary</title>
<meta charset='utf-8' />
<link rel='stylesheet' href='_themes/klli.css' />
<style>
body {background: #ffe}
h1, h2, h3, div {background-color: transparent} /* override tnk1/_themes/klli.css */

form {
	clear:both;
	padding: 5px;
	text-align:center;
}

form div.row {
	clear:both;
	padding-top:10px;
}

form div.row label {
	float: right;
	width: 48%;
	text-align: left;
}

form div.row span.input {
	float: left;
	width: 48%;
	text-align: right;
}

form div.row .comment {
	font-size:0.8em
}

form div.submit {
	text-align:center;
	padding-top:10px;
	clear:both;
}

div.spacer {
	clear: both;
}

</style>
</head>
<body>

<?php
print sikum_heading();

$GLOBALS['DEBUG_QUERY_TIMES']=isset($_GET['debug_times']);

$qod_sfr = coalesce($_GET['sfr'],'');
$mspr_prq = coalesce($_GET['prq'],'');
$mspr_psuq = coalesce($_GET['psuq'],'');
if (!$qod_sfr || !$mspr_prq || !$mspr_psuq) {
	print "
<form method='GET' action=''>
".form_row('select_from_database','ספר','',"SELECT qod_mamre,kotrt FROM sfrim ORDER BY qod_mamre",'sfr')."
".form_row('select_from_database','פרק','',"SELECT mspr,kotrt FROM prqim ORDER BY mspr",'prq')."
".form_row('select_from_database','פסוק','',"SELECT mspr,kotrt FROM prqim ORDER BY mspr",'psuq')."
".form_row('submit','שליחה')."
".html_for_hidden_text('nav','1')."
".html_for_hidden_text('utf8','1')."
</form>
";
	die;
}

// if (!empty($GLOBALS['is_local'])) {
// 	print sikum($qod_sfr, $mspr_prq, $mspr_psuq,
// 			/*$include_mikraotgdolot=*/TRUE,
// 			/*$include_navigation=*/FALSE,
// 			/*$include_wikisource=*/FALSE,
// 			/*$include_google=*/FALSE,
// 			/*$include_etnachta=*/FALSE
// 			);
// } else 
if (isset($_GET['nav'])) {
	print sikum($qod_sfr, $mspr_prq, $mspr_psuq,
		/*$include_mikraotgdolot=*/TRUE,
		/*$include_navigation=*/TRUE,
		/*$include_wikisource=*/TRUE,
		/*$include_google=*/TRUE,
		/*$include_etnachta=*/TRUE
		);
} else if (isset($_GET['find'])) {
	print sikum($qod_sfr, $mspr_prq, $mspr_psuq,
		/*$include_mikraotgdolot=*/TRUE,
		/*$include_navigation=*/TRUE,
		/*$include_wikisource=*/TRUE,
		/*$include_google=*/FALSE,
		/*$include_etnachta=*/TRUE
		);
} else {
	print "<p>".sikum_explanation()."</p>";

	print "<textarea cols='80' rows='20' dir='rtl'>\n".sikum($qod_sfr, $mspr_prq, $mspr_psuq,
		/*$include_mikraotgdolot=*/TRUE,
		/*$include_navigation=*/false,
		/*$include_wikisource=*/TRUE,
		/*$include_google=*/TRUE,
		/*$include_etnachta=*/TRUE
		)."\n</textarea>\n";
}
?>
</body>
</html>

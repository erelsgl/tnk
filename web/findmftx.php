<?php
error_reporting(E_ALL);

/**
 * @file findmftx.php
 * ֹUTF8 Encoding קידוד אחיד
 * Find a string in the Tanakh Navigation Site index.
 * @author Erel Segal אראל סגל
 * @date 2009-2014
 */


$SCRIPT=realpath(dirname(__FILE__)."/../script");
require_once("$SCRIPT/mftx_lib.php");  // index search functions

$phrase = !empty($_GET['q'])? $_GET['q']: "";
$single_verse = !empty($_GET['single_verse']);
$phrase_quoted = quote_all($phrase);
$phrase_html = htmlspecialchars($phrase,ENT_QUOTES);

$title = $phrase? "*$phrase - ניווט בתנך": "ניווט בתנך*";



require("find_header.php");
global $TNKUrl;
print "
<div id='top'>
	<div class='center'>
		<h1><a href='$TNKUrl/tnk1'><img src='_themes/logo3.png' alt='תוצאות הניווט בתנך' title='תוצאות הניווט בתנך' /></a></h1>
		<form method='get' action=''>
			היעד:
			<input id='find' name='q' value='$phrase_html' />
			<input type='submit' value='חפש!' />
		</form>
	</div><!--center-->
</div><!--top-->
";


if ($phrase) {
	list($mftx_results,$mftx_count)=mftx_results($phrase_quoted);  // in mftx_lib.php
	
	print "
<div id='results'>
	<div id='mftx'>
	<h2>$mftx_count תוצאות חיפוש במפתח המאמרים</h2>
	<ul>$mftx_results</ul>
	</div><!--mftx-->
</div><!--results-->
	";
}



?>
</body></html>

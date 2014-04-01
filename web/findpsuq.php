<?php
error_reporting(E_ALL);

/**
 * @file findpsuq.php
 * ֹUTF8 Encoding קידוד אחיד
 * Find a string in the Tanakh text.
 * @author Erel Segal אראל סגל
 * @date 2009-2014
 */

$linkroot = "http://tora.us.fm";

$SCRIPT=realpath(dirname(__FILE__)."/../script");
require_once("$SCRIPT/findpsuq_lib.php");  // main search function

$phrase = !empty($_GET['q'])? $_GET['q']: "";
$single_verse = !empty($_GET['single_verse']);
$phrase_quoted = quote_all($phrase);
$phrase_html = htmlspecialchars($phrase,ENT_QUOTES);
$niqud_level = (!empty($_GET['niqud_level'])? $_GET['niqud_level']: 0);
$add_niqud = $niqud_level==1;
$find_niqud = $niqud_level==2;
$reverse = !empty($_GET['reverse']);

$title = $phrase? "*$phrase - ניווט בתנך": "ניווט בתנך*";



require("find_header.php");
print "
<div id='top'>
	<div class='center'>
		<h1><a href='$linkroot/tnk1'><img src='_themes/logo3.png' alt='תוצאות הניווט בתנך' title='תוצאות הניווט בתנך' /></a></h1>
		<form method='get' action=''>
			היעד:
			<input id='find' name='q' value='$phrase_html' />
			".($single_verse? "<input type='checkbox' name='single_verse' checked='checked' />רק פסוק אחד ": "")."<br/>
			<select name='niqud_level'>
					<option value='0' ".($niqud_level==0? " selected='selected'": "").">ללא ניקוד</option>
					<option value='1' ".($niqud_level==1? " selected='selected'": "").">תצוגה עם ניקוד</option>
					<option value='2' ".($niqud_level==2? " selected='selected'": "").">חיפוש עם ניקוד</option>
			</select>
			<input type='checkbox' name='sikum' ".(!empty($_GET['sikum'])? " checked='checked'": "")." />עם&nbsp;סיכום 
			<input type='checkbox' name='reverse' />לאחור
			<input type='submit' value='חפש!' />
		</form>
	</div><!--center-->
</div><!--top-->
";


if ($phrase) {
	$fixed_phrase = fix_regexp($phrase);
	if ($reverse)
		$fixed_phrase = strrev($fixed_phrase);
	
	list ($findpsuq_results, $findpsuq_count) = find_phrase($fixed_phrase, $single_verse, $add_niqud, $find_niqud);

	print "
<div id='results'>
	<div id='findpsuq'>
	<h2>$findpsuq_count תוצאות חיפוש ביטוי רגולרי בתנ&quot;ך</h2>
	<ol>$findpsuq_results</ol>
	</div><!--findpsuq-->
</div><!--results-->
	";
}



?>
</body></html>

<?php
error_reporting(E_ALL);

/**
 * @file find.php
 * ֹUTF8 Encoding קידוד אחיד
 * Find a string in the Tanakh text (findpsuq), Tanakh index (mftx), or Tanakh site (google)
 * @author Erel Segal אראל סגל
 * @date 2009-2014
 */

$linkroot = "http://tora.us.fm";

$SCRIPT=realpath(dirname(__FILE__)."/../script");
require_once("$SCRIPT/psuqim.php");  // utilities related to verse ids
require_once("$SCRIPT/findpsuq_lib.php");  // main search function
require_once("$SCRIPT/mftx_lib.php");  // main index function

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

$content = "";

if ($phrase) {
	$recommended_results = $mftx_results = $findpsuq_results = $google_results = $mxbr_results = '';
	$recommended_count = $mftx_count = $findpsuq_count = $google_count = $mxbr_results_count = 0;
	$redirect = false;
	
	$fixed_phrase = fix_regexp($phrase);
	if ($reverse)
		$fixed_phrase = strrev($fixed_phrase);

	list ($findpsuq_results, $findpsuq_count) = find_phrase($fixed_phrase, $single_verse, $add_niqud, $find_niqud);

	$phrase_is_regexp = preg_match("/[.*^$()|]/",$phrase);  // NOT: + -
	
	// if $phrase_is_regexp, use only findpsuq
	if (!$phrase_is_regexp) {
	
		// אם המשתמש מבקש שם של ספר, פרק או פסוק - נעביר אותו לשם מייד:
		list($link,$title) = link_to_sfr_prq_o_psuq($phrase); // in script/psuqim.php
		if ($link && $title && strpos($phrase," ")) {
			$link = "$linkroot$link";
	
// 			// אם המשתמש מבקש פסוק, ויש ביאור על הפסוק - נעביר אותו ישר לביאור:
// 			$canonical_name_of_psuq = canonical_name_of_psuq($phrase);
// 			if ($canonical_name_of_psuq) {
// 				$qod_of_beur = "ביאור:".$canonical_name_of_psuq;
// 				$qod_of_beur_utf8 = iconv('hebrew', 'utf8', $qod_of_beur);
					
// 				$ktovt_beur = sql_evaluate("SELECT ktovt FROM prt_tnk1 WHERE qod=".quote_smart($qod_of_beur_utf8));
// 				if ($ktovt_beur && preg_match("/^tnk1/",$ktovt_beur))
// 					$link = "../$ktovt_beur";
// 			}
	
			$recommended_results = "<li>
				<a href='$link'>$title</a>
				<script type='text/javascript'>window.location = '$link'</script>
				</li>";
			$recommended_count = 1;
			$redirect = true;
		} else {
			list($mftx_recommended_results,$mftx_recommended_count)=mftx_recommended_results($phrase_quoted); // below
			$recommended_results .= $mftx_recommended_results;
			$recommended_count += $mftx_recommended_count;

			list($mxbr_results,$mxbr_count)=mxbr_results($phrase_quoted);  // below
			list($mftx_results,$mftx_count)=mftx_results($phrase_quoted);  // in script/mftx_lib.php
		
// 			list ($google_results, $google_count) = $GLOBALS['is_local']?
// 				array("",0):
// 				google_results($phrase_utf8);
		}
	}
	
	if ($recommended_results) $recommended_results = "
		<div id='recommended'>
		<h2>תוצאות מומלצות</h2>
		<ul>$recommended_results</ul>
		</div><!--recommended-->
		";
	
	if ($mxbr_results) $mxbr_results = "
		<script type='text/javascript' src='sorttable.js'></script>
		<style type='text/css'>
		#mxbr_table td, #mxbr_table th {padding:2px; vertical-align:center}
		#mxbr_table td.tarik_hosfa {font-size:10px}
		div.mxbr {text-align:center}
		#mxbr_table {margin:auto}
		</style>
		<div id='mxbr'>
		<h2>מאמרים של $phrase_html ($mxbr_count)</h2>
		<table class='sortable' id='mxbr_table'>
		<tr>
		<th>מס</th>
		<th>תאריך</th>
		<th>שם</th>
		</tr>
		$mxbr_results
		</table>
		</div><!--mxbr-->
		";
	
	if ($findpsuq_results) $findpsuq_results = "
		<div id='findpsuq'>
		<h2>תוצאות חיפוש ביטוי רגולרי בתנ&quot;ך</h2>
		<ol>$findpsuq_results</ol>
		</div><!--findpsuq-->
		";
	
	if ($google_results) $google_results = "
		<div id='google'>
		<h2>תוצאות חיפוש בכל המאמרים באתר (באדיבות גוגל)</h2>
		<ol>$google_results</ol>
		</div><!--google-->
		";
	
	if ($mftx_results) $mftx_results = "
		<div id='mftx'>
		<h2>תוצאות חיפוש במפתח המאמרים</h2>
		<ul>$mftx_results</ul>
		</div><!--mftx-->
		";
	
	$content .= "
		<div id='results'>
		$recommended_results
		$mxbr_results
		$findpsuq_results
		$google_results
		$mftx_results
		</div><!--results-->
		";
	
	if (!$redirect) 	$content .= "
		<div id='tips'>
		<h2>לא מה שחיפשת?</h2>
		<ul>
		<li><a href='$linkroot/tnk1/klli/limud/xipus.html'>עצות ודוגמאות לחיפוש...</a></li>
		</ul>4
		</div><!--tips-->
		";
}
	
print $content;


function mftx_recommended_results($phrase_utf8_quoted) {
	$rows_exact = get_exact_match_rows_without_sfr($phrase_utf8_quoted); // utf8
	$mftx_recommended_results=''; $mftx_recommended_count=0;
	if (sql_num_rows($rows_exact)>0) { // found exact match
		while ($row=sql_fetch_row($rows_exact)) {
			$mftx_recommended_results .= "<li>" . get_mftx_line($row) . "</li>\n";
			$mftx_recommended_count++;
		}
	}
	return array($mftx_recommended_results, $mftx_recommended_count);
}


function mxbr_results($phrase_utf8_quoted) {
	global $linkroot;
	sql_query_or_die("SET @sdr=0");
	$rows = sql_query_or_die("
			SELECT @sdr:=@sdr+1 AS sdr, tarik_hosfa, ktovt, kotrt FROM prt_tnk1
			WHERE m=$phrase_utf8_quoted
			UNION
			SELECT @sdr:=@sdr+1 AS sdr, created_at, ktovt_bn, kotrt FROM board_tnk1
			WHERE m=$phrase_utf8_quoted
			AND sug is null
			ORDER BY tarik_hosfa
			");
	$count = sql_num_rows($rows);
	if ($count) {
		$results = '';
		while ($row=sql_fetch_assoc($rows)) {
			$results = "<tr>
			<td class='sdr'>$row[sdr]</td>
			<td class='tarik_hosfa'>$row[tarik_hosfa]</td>
			<td class='kotrt'><a href='$linkroot/$row[ktovt]'>$row[kotrt]</a></td>
			</tr>" . $results;
		}
		return array($results,$count);
	} else {
		return array('',0);
	}
}


?>
</body></html>

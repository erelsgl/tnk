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

set_include_path(realpath(dirname(__FILE__) . "/../script") . PATH_SEPARATOR . get_include_path());
require_once("psuqim.php");  // utilities related to verse ids
require_once("findpsuq_lib.php");  // regexp search function
require_once("mftx_lib.php");      // concept search function
require_once("mxbr_lib.php");      // author search function

require_once("GoogleClient.php");

$phrase = !empty($_GET['q'])? $_GET['q']: "";
$single_verse = !empty($_GET['single_verse']);
$phrase_quoted = quote_all($phrase);
$phrase_html = htmlspecialchars($phrase,ENT_QUOTES);
$add_sikum = !empty($_GET['add_sikum']);
$add_niqud = !empty($_GET['add_niqud']);
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
			<input type='checkbox' name='add_niqud' ".($add_niqud? " checked='checked'": "")." />עם&nbsp;ניקוד 
			<input type='checkbox' name='add_sikum' ".($add_sikum? " checked='checked'": "")." />עם&nbsp;סיכום 
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
	if ($reverse) {
		$fixed_phrase = mb_strrev($fixed_phrase);
		$fixed_phrase = to_txiliot($fixed_phrase);
	}

	list ($findpsuq_results, $findpsuq_count) = find_phrase($fixed_phrase, $single_verse, $add_niqud, $add_sikum);

	$phrase_is_regexp = preg_match("/[.*^$()|]/",$phrase);  // NOT: + -
	
	// if $phrase_is_regexp, use only findpsuq
	if (!$phrase_is_regexp) {
		// אם המשתמש מבקש שם של ספר, פרק או פסוק - נעביר אותו לשם מייד:
		list($link,$title) = link_to_sfr_prq_o_psuq($phrase); // in script/psuqim.php
		if ($link && $title && strpos($phrase," ")) {
			$link = "$linkroot$link";
	
			// אם המשתמש מבקש פסוק, ויש ביאור על הפסוק - נעביר אותו ישר לביאור:
			$canonical_name_of_psuq = canonical_name_of_psuq($phrase);
			if ($canonical_name_of_psuq) {
				$qod_of_beur = "ביאור:".$canonical_name_of_psuq;
				$ktovt_beur = sql_evaluate("SELECT ktovt FROM prt_tnk1 WHERE qod=".quote_smart($qod_of_beur));
				if ($ktovt_beur && preg_match("/^tnk1/",$ktovt_beur))
					$link = "$linkroot/$ktovt_beur";
			}

			$recommended_results = "<li>
				<a href='$link'>$title</a>
				<script type='text/javascript'>window.location = '$link'</script>
				</li>";
			$recommended_count = 1;
			$redirect = true;
		} else {
			list($mftx_recommended_results,$mftx_recommended_count)=mftx_recommended_results($phrase_quoted); // in script/mftx_lib.php
			$recommended_results .= $mftx_recommended_results;
			$recommended_count += $mftx_recommended_count;

			list($mftx_results,$mftx_count)=mftx_results($phrase_quoted);  // in script/mftx_lib.php

			list($mxbr_results,$mxbr_count)=strstr($linkroot,$_SERVER['HTTP_HOST'])?
				mxbr_results($phrase_quoted):  // in script/mxbr_lib.php
				mxbr_results_online($phrase_quoted,
					"<a href='$linkroot/tnk1/find.php?q=".urlencode(iconv("UTF-8", "Windows-1255", $phrase))."'>רשימה מעודכנת</a>", "UTF-8");
				
			list ($google_results, $google_count) = 
// 				$GLOBALS['is_local']?
// 					array("",0):
					google_results($phrase);
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
		<h2>$findpsuq_count תוצאות חיפוש ביטוי רגולרי בתנ&quot;ך</h2>
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
		<h2>תוצאות חיפוש במפתח הנושאים</h2>
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
		</ul>
		</div><!--tips-->
		קישור לחיפוש זה: 
		<a href='?q=$phrase&add_niqud=$add_niqud&add_sikum=$add_sikum&reverse=$reverse'>$phrase</a>
		";
}
	
print $content;


function google_results($phrase) {
	global $GOOGLE_API_KEY, $GOOGLE_CSE_ID;
	if (!$GOOGLE_API_KEY) return array(
			array("לא ניתן לחפש בגוגל - המפתח לא מוגדר - פנו למנהל האתר"),
			1);
	if (!$GOOGLE_CSE_ID) return array(
			array("לא ניתן לחפש בגוגל - המנוע לא מוגדר - פנו למנהל האתר"),
			1);
	$GoogleClient = new GoogleClient(/*$max_result_count=*/8); // use multiples of 8
	$results = $GoogleClient->search_results(
		"$phrase site:tora.us.fm", $GOOGLE_API_KEY, "iw", $GOOGLE_CSE_ID);
	if (!$results) return array(
			array("לא ניתן לחפש בגוגל - הקצאת החיפושים היומית הסתיימה - חכו למחר"),
			1);
	$google_results = ''; $google_count=0;
	foreach ($results as &$result) {
		$url = $result['unescapedUrl'];
		if (preg_match("/psuqim_.*txt/",$url)) continue;

		$title = $result['titleNoFormatting'];
		if (preg_match("/^more/",$title)) {
			$title = "עוד...";
		} else {
			++$google_count;
		}
		$content = $result['content'];
		$anchor = "<a href='".htmlspecialchars($url,ENT_QUOTES)."'>$title</a>";
		$cache_anchor = ($result['cacheUrl']? " (<a href='$result[cacheUrl]'>מטמון</a>)": "");

		$google_results .= "<li>$anchor: $content$cache_anchor</li>\n";
	}
	return array($google_results, $google_count);
}

?>
</body></html>

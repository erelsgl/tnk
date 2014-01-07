<?php
error_reporting(E_ALL);

/**
 * @file find.php
 * Windows Encoding ����� ������
 * Find a string in the Tanakh text (findpsuq), Tanakh index (mftx), or Tanakh site (google)
 * @author Erel Segal ���� ���
 * @date 2009-08-05
 */

require_once("../_script/psuqim.php");
require_once("../_script/remove_magic_quotes.php");
require_once("../_script/html_torausfm.php");
require_once("../_script/coalesce.php");
require_once("../_script/mysql_iconv.php");
require("admin/db_connect.php");

$HTML_DIRECTION='rtl';
$HTML_LANGUAGE='he';
$HTML_ENCODING='windows-1255';

$DEBUG_SELECT_QUERIES = isset($_GET['debug_select']);
$DEBUG_QUERY_TIMES = isset($_GET['debug_times']);

function sql_fix_charset() {
	//mysql_query("set character_set_client=hebrew");
	//mysql_query("set character_set_database=utf8");
	sql_set_charset("utf8");
	mysql_query("set character_set_results=hebrew");
}

sql_fix_charset();


$fileroot = "..";
$linkroot = "..";

$path_from_root_to_site = "tnk1"; // for mftx
$site = "tnk1"; // for findpsuq
$format = coalesce($_GET['format'], 'html');

$phrase = coalesce($_GET['q'], "");
$single_verse = !empty($_GET['single_verse']);
if ($phrase) {
	$phrase_utf8 = mysql_iconv('hebrew', 'utf8', $phrase);
	$phrase_quoted = quote_all($phrase);
	$phrase_utf8_quoted = quote_all($phrase_utf8);
	$phrase_html = htmlspecialchars($phrase,ENT_QUOTES);
}

if (!empty($_GET['niqud'])) {
	require_once("$fileroot/tnk1/niqud.php");
	$niqud_level = 1;
} else {
	$niqud_level = 0;
}

$reverse = !empty($_GET['reverse']);


if ($format==='taconite') {
	require_once('../_script/taconite.php');
	print jquery_taconite_header($HTML_ENCODING) .
		"<replaceContent select=\"#searchresults\">\n";
	$content = "<h2>������ ������ ����</h2>\n";
} else {
	$title = $phrase? "*$phrase - ����� ����": "����� ����*";
	$content = xhtml_header($title, 'find', array('../_script/klli.css', '_themes/klli.css', '../_script/mftx.css') ) .
	"
	<div id='top'>
	<div class='center'>
	<h1><a href='/tnk1'><img src='_themes/logo3.png' alt='������ ������ ����' title='������ ������ ����' /></a></h1>
	<form method='get' action=''>
	����:
	<input id='find' name='q' value='$phrase_html' />
	".($single_verse? "<input type='checkbox' name='single_verse' checked='checked' />�� ���� ��� ": "")."<br/>
	<input type='checkbox' name='niqud' ".($niqud_level? " checked='checked'": "")." />��&nbsp;����� 
	<input type='checkbox' name='sikum' ".(!empty($_GET['sikum'])? " checked='checked'": "")." />��&nbsp;����� 
	<input type='checkbox' name='reverse' />�����
	<input type='submit' value='���!' />
	</form>
	</div><!--center-->
	</div><!--top-->
	";
}


if (!$phrase) {
//	$content .=  "<p>�� ���� �� ������ ����!</p>";
} else {
	$recommended_results = $mftx_results = $findpsuq_results = $google_results = $mxbr_results = '';
	$recommended_count = $mftx_count = $findpsuq_count = $google_count = $mxbr_results_count = 0;
	$redirect = false;

	require_once(dirname(__FILE__)."/findpsuq_lib.php");
  $fixed_phrase = fix_regexp($phrase);
  if ($reverse)
    $fixed_phrase = strrev($fixed_phrase);
	list ($findpsuq_results, $findpsuq_count) = results_html_for_phrase($fixed_phrase, $single_verse, $niqud_level);
	sql_fix_charset();

	$phrase_is_regexp = preg_match("/[.*^$()|]/",$phrase);  // NOT: + - 

	// if $phrase_is_regexp, use only findpsuq
	if (!$phrase_is_regexp) {

	// �� ������ ���� �� �� ���, ��� �� ���� - ����� ���� ��� ����:
		list($link,$title) = link_to_sfr_prq_o_psuq($phrase); // in _script/psuqim.php
		if ($link && $title && strpos($phrase," ")) {

// �� ������ ���� ����, ��� ����� �� ����� - ����� ���� ��� ������:
			$canonical_name_of_psuq = canonical_name_of_psuq($phrase);
			if ($canonical_name_of_psuq) {
				$qod_of_beur = "�����:".$canonical_name_of_psuq;
				$qod_of_beur_utf8 = mysql_iconv('hebrew', 'utf8', $qod_of_beur);
			
				$ktovt_beur = sql_evaluate("SELECT ktovt FROM prt_tnk1 WHERE qod=".quote_smart($qod_of_beur_utf8));
				if ($ktovt_beur && preg_match("/^tnk1/",$ktovt_beur))
					$link = "../$ktovt_beur";
			}

			$recommended_results = "<li>
				<a href='$link'>$title</a>
					<script type='text/javascript'>window.location = '$link'</script>
				</li>";
			$recommended_count = 1;
			$redirect = true;
		} else {
			require_once('mftx_lib.php');
			list($mftx_recommended_results,$mftx_recommended_count)=mftx_recommended_results($phrase_utf8_quoted);
			$recommended_results .= $mftx_recommended_results;
			$recommended_count += $mftx_recommended_count;

			list($mxbr_results,$mxbr_count)=mxbr_results($phrase_utf8_quoted);
			list($mftx_results,$mftx_count)=mftx_results($phrase_utf8_quoted);

			require_once(dirname(__FILE__).'/../_script/system.php');
			list ($google_results, $google_count) = $GLOBALS['is_local']?
				array("",0):
				google_results($phrase_utf8);
		}
	}

	if ($recommended_results) $recommended_results = "
		<div id='recommended'>
		<h2>������ �������</h2>
		<ul>$recommended_results</ul>
		</div><!--recommended-->
		";

	if ($mxbr_results) $mxbr_results = "
		<script type='text/javascript' src='../_script/sorttable.js'></script>
		<style type='text/css'>
			#mxbr_table td, #mxbr_table th {padding:2px; vertical-align:center}
			#mxbr_table td.tarik_hosfa {font-size:10px}
			div.mxbr {text-align:center}
			#mxbr_table {margin:auto}
		</style>
		<div id='mxbr'>
		<h2>������ �� $phrase_html ($mxbr_count)</h2>
		<table class='sortable' id='mxbr_table'>
			<tr>
				<th>��</th>
				<th>�����</th>
				<th>��</th>
			</tr>
			$mxbr_results
		</table>
		</div><!--mxbr-->
		";

	if ($findpsuq_results) $findpsuq_results = "
		<div id='findpsuq'>
		<h2>������ ����� ����� ������ ���&quot;�</h2>
		<ol>$findpsuq_results</ol>
		</div><!--findpsuq-->
		";

	if ($google_results) $google_results = "
		<div id='google'>
		<h2>������ ����� ��� ������� ���� (������� ����)</h2>
		<ol>$google_results</ol>
		</div><!--google-->
		";

	if ($mftx_results) $mftx_results = "
		<div id='mftx'>
		<h2>������ ����� ����� �������</h2>
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
	<h2>�� �� ������?</h2>
	<ul>
	<li><a href='/tnk1/klli/limud/xipus.html'>���� �������� ������...</a></li>
	</ul>
	</div><!--tips-->
	";
}

if ($format==='taconite') {
	$content .= "
	</replaceContent>
	" . 
	jquery_taconite_footer();
} else {

	$content .= xhtml_footer();
}

//print htmlspecialchars($content);
print $content;





/* log */

if ($phrase) {
	$phrase_link = "<a href='/tnk1/find.php?q=".htmlspecialchars(urlencode($phrase))."'>".htmlspecialchars($phrase_utf8)."</a>";
	/*
	$log_file = dirname(__FILE__)."/findlog.html";
	$log_line = "<p><a href='/tnk1/find.php?q=$phrase'>$phrase</a>:\t$phrase\t	$recommended_count\t$findpsuq_count\t$google_count\t$mftx_count\t</p>\n";
	file_put_contents($log_file,"<html dir='rtl'>\n<p>�����:\t�����\t�����\t������\t����\t����</p>\n");
	file_put_contents($log_file,$log_line,FILE_APPEND);
	*/
	$phrase_link_quoted = quote_all($phrase_link);
	$ipaddress_quoted = quote_all(coalesce($_SERVER['REMOTE_ADDR'],'local'));
	sql_query_or_die("
		INSERT INTO findlog(phrase_link,phrase,recommended_count,findpsuq_count,google_count,mftx_count,ipaddress)
		VALUES($phrase_link_quoted,$phrase_utf8_quoted,$recommended_count,$findpsuq_count,$google_count,$mftx_count,$ipaddress_quoted)
		");
}





/* functions */

function google_results($phrase) {
	require_once(dirname(__FILE__).'/../_script/fix_include_path.php'); // for Zend
	require_once(dirname(__FILE__).'/../sites/GoogleClient.php');
	$GLOBALS['GoogleClient'] = new GoogleClient(/*$max_result_count=*/8); // use multiples of 8

	$GLOBALS['GOOGLE_CSE_ID'] = '010178520503316434778%3Aufdjhgdvtao';
	$results = $GLOBALS['GoogleClient']->search_results(
		"$phrase site:tora.us.fm", "iw", $GLOBALS['GOOGLE_CSE_ID']/*,
		$GLOBALS['mysql_encoding']*/);
	$google_results = ''; $google_count=0;
	foreach ($results as &$result) {
		$url = $result['unescapedUrl'];
		if (preg_match("/psuqim_.*txt/",$url)) continue;

		$title = utf8_to_windows1255($result['titleNoFormatting']);
		if (preg_match("/^more/",$title)) {
			$title = "���...";
		} else {
			++$google_count;
		}
		$content = utf8_to_windows1255($result['content']);
		$anchor = "<a href='".htmlspecialchars($url,ENT_QUOTES)."'>$title</a>";
		$cache_anchor = ($result['cacheUrl']? " (<a href='$result[cacheUrl]'>�����</a>)": "");

		$google_results .= "<li>$anchor: $content$cache_anchor</li>\n";
	}
	return array($google_results, $google_count);
}

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
				<td class='kotrt'><a href='../$row[ktovt]'>$row[kotrt]</a></td>
			</tr>" . $results;
		}
		return array($results,$count);
	} else {
		return array('',0);
	}
}

?>

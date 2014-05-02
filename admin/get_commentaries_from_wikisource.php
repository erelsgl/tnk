<?php
/**
 * @file get_commentaries_from_wikisource.php
 * @author Erel Segal
 * @date 2014-04
 */

error_reporting(E_ALL);

set_include_path(realpath(dirname(__FILE__) . "/../script") . PATH_SEPARATOR . get_include_path());
require_once("MediawikiClient.php");
require_once("sql.php");
require_once("db_connect.php");
$GLOBALS['MediawikiClient'] = new MediawikiClient();

$get_commentaries = false;
$get_miqraot_gdolot = true;

if ($get_commentaries) {
	$psuqim = sql_query_or_die("SELECT * FROM miqraot_gdolot LIMIT 10");
	$prjn = 'רש"י';
	while ($row = sql_fetch_assoc($psuqim)) {
		$title = "$prjn על $row[book_name] $row[chapter_letter] $row[verse_letter]";
		print "$title\n";
		$contents = @$GLOBALS['MediawikiClient']->page_source($title);
		if (!$contents)
			$contents = @$GLOBALS['MediawikiClient']->page_source("קטע: ".$title);
		//$contents = preg_replace("#<noinclude>.*?</noinclude>#s","",$contents);
		print " :  $contents\n";
		sql_query_or_die("UPDATE miqraot_gdolot SET rjy=".quote_all($contents)." WHERE id='$row[id]'");
	}
}

if ($get_miqraot_gdolot) {
	$psuqim = sql_query_or_die("SELECT * FROM miqraot_gdolot");
	$mg = 'מ"ג';
	while ($row = sql_fetch_assoc($psuqim)) {
		$title = "$mg $row[book_name] $row[chapter_letter] $row[verse_letter]";
		print "$title\n";
		$contents = miqraot_gdolot($row['book_name'],$row['chapter_letter'],$row['verse_letter']);
		print " :  $contents\n";
		sql_query_or_die("UPDATE miqraot_gdolot SET parsed=".quote_all($contents)." WHERE id='$row[id]'");
	}
	
	function miqraot_gdolot($sfr, $prq, $psuq) {
		$title = "{{ציטוטים של מקראות גדולות על פסוק|$sfr|$prq|-|$psuq|-|}}";
		$contents = $GLOBALS['MediawikiClient']->parse($title,false);
		$contents = preg_replace("#<!--.*?-->#s", "", $contents);
		return $contents;
	}
}

?>
</body>
</html>

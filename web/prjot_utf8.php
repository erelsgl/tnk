<?php
/** קידוד אחיד
 * @file prjot_utf8.php - weekly torah portion calculations קידוד חלונות
 * @author Erel Segal http://tora.us.fm
 * @date 2009-08-09
 */

$SCRIPT=realpath(dirname(__FILE__)."/../script");
global $TNKUrl;

require_once("$SCRIPT/html.php");
require_once("$SCRIPT/taconite.php");
$HTML_ENCODING = 'utf-8';
$HTML_DIRECTION = 'rtl';
$HTML_LANGUAGE = 'he';

require_once("$SCRIPT/prjot_utf8.php");
require_once("$SCRIPT/coalesce.php");
$from_day = coalesce($_GET['from_day'],0);
$to_day = coalesce($_GET['to_day'],0);
sql_set_charset('utf8');
$format = isset($_GET['format'])? $_GET['format']: 'html';
$content = prjot($from_day,$to_day);
if ($format=='taconite') {
	print jquery_taconite_header($HTML_ENCODING)."
	<replaceContent select=\"#whatsnew_prjot\">
	$content
	</replaceContent>
	".jquery_taconite_footer();
} else if ($format=='html') {
	print xhtml_header("פרשת השבוע", "", array("$TNKUrl/tnk1/_themes/klli.css"))."
	$content
	".xhtml_footer();
} else {
	print xhtml_header("פרשת השבוע", "", array("$TNKUrl/tnk1/_themes/klli.css"))."
	<!--NiwutElyon0-->
	<div class='NiwutElyon'>
	<div class='NiwutElyon'><a href='$TNKUrl/tnk1/'>אתר הניווט בתנ&quot;ך</a>&gt;</div>
	</div>
	<!--NiwutElyon1-->
	$content
	".xhtml_footer();
}
?>

<?php
/** קידוד אחיד
 * @file prjot.php
 * @author Erel Segal http://tora.us.fm
 * @date 2009-08-09
 */
$SCRIPT=realpath(dirname(__FILE__)."/../script");

require_once("$SCRIPT/prjot_utf8.php");

$HTML_ENCODING = 'utf-8';
$format = isset($_GET['format'])? $_GET['format']: 'html';
if ($format=='taconite') {
	require_once(dirname(__FILE__).'/../_script/taconite.php');
	print jquery_taconite_header($HTML_ENCODING)."
	<replaceContent select=\"#whatsnew_prjot\">
	".prjot(time())."
	</replaceContent>
	".jquery_taconite_footer();
} else {
	require_once(dirname(__FILE__).'/../_script/html.php');
	$HTML_ENCODING = 'utf-8';
	$HTML_DIRECTION = 'rtl';
	$HTML_LANGUAGE = 'he';
	print xhtml_header("פרשת השבוע", "", array("/tnk1/_themes/klli.css"))."
	<!--NiwutElyon0-->
	<div class='NiwutElyon'>
	<div class='NiwutElyon'><a href='index.html'>אתר הניווט בתנ&quot;ך</a>&gt;</div>
	</div>
	<!--NiwutElyon1-->
	".prjot(time())."
	".xhtml_footer();
}

?>
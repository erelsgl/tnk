<?php
/** קידוד חלונות
 * @file index_ajax.php - loaded from index.html using jquery ajax
 * @author Erel Segal http://tora.us.fm
 * @date 2009-08-30
 */


require_once("../script/prjot_1255.php");  // in tnk/script
require_once("../script/taconite.php");
$HTML_ENCODING = 'windows-1255';

sql_set_charset('hebrew');
$prjot_content = prjot(0,0);
$prjot_content = "<replaceContent select=\"#whatsnew_prjot\">$prjot_content</replaceContent>";
print jquery_taconite_header($HTML_ENCODING)."
$prjot_content
".jquery_taconite_footer();

?>

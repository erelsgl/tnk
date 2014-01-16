<?php
error_reporting(E_ALL);

/**
 * @file findpsuq.php
 * Windows Encoding ����� ������
 * Find a string in the Tanakh text.
 * @author Erel Segal ���� ���
 * @date 2009-08-05
 */

$linkroot = "http://tora.us.fm";

$SCRIPT=realpath(dirname(__FILE__)."/../script");
require_once("$SCRIPT/niqud.php");  // for adding dots (niqud) to the displayed verses
require_once("$SCRIPT/findpsuq_lib.php");  // main search function

$phrase = !empty($_GET['q'])? $_GET['q']: "";
$single_verse = !empty($_GET['single_verse']);
$phrase_utf8 = iconv('hebrew', 'utf8', $phrase);
$phrase_quoted = quote_all($phrase);
$phrase_utf8_quoted = quote_all($phrase_utf8);
$phrase_html = htmlspecialchars($phrase,ENT_QUOTES);
$niqud_level = (!empty($_GET['niqud'])? 1: 0);
$reverse = !empty($_GET['reverse']);

$title = $phrase? "*$phrase - ����� ����": "����� ����*";

require("find_header.php");
print "
<div id='top'>
	<div class='center'>
		<h1><a href='$linkroot/tnk1'><img src='_themes/logo3.png' alt='������ ������ ����' title='������ ������ ����' /></a></h1>
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


if ($phrase) {
  $fixed_phrase = fix_regexp($phrase);
  if ($reverse)
    $fixed_phrase = strrev($fixed_phrase);

	list ($findpsuq_results, $findpsuq_count) = find_phrase($fixed_phrase, $single_verse, $niqud_level);

	print "
<div id='results'>
	<div id='findpsuq'>
	<h2>������ ����� ����� ������ ���&quot;�</h2>
	<ol>$findpsuq_results</ol>
	</div><!--findpsuq-->
</div><!--results-->
	";
}



?>
</body></html>

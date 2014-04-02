<?php
/**
 * @file mxbr_lib.php - library functions for searching in the author index of the Tanakh Navigation Site
 */

require_once("../admin/db_connect.php");


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

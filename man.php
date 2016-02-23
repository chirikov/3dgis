<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Content-type: x-world/x-vrml");

include_once("../inc/my_connect.php");

$q1 = mysql_query("select * from 3d_online where id = ".$_GET['id']);
$row = mysql_fetch_assoc($q1);

print '#VRML V2.0 utf8

';

include_once("manproto.txt");

print '
Man
{
	id '.$row['id'].'
	color '.$row['color'].'
	translation '.$row['position'].'
	name "'.iconv("windows-1251", "UTF-8", $row['nick']).'"
}
';

?>
<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Content-type: x-world/x-vrml");

$mysql = mysql_connect("localhost", "root", "password");
mysql_select_db("ufa3d", $mysql);

$q1 = mysql_query("update online set trans = '".$_GET['pos']."' where id = ".$_GET['id']);

/*
$file = "log.txt";

$fp = fopen($file, "a");

fwrite($fp, $_GET['id']."@".$_GET['pos']."\r\n");

fclose($fp);
*/

?>
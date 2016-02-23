<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Content-type: x-world/x-vrml");

$mysql = mysql_connect("localhost", "root", "password");
mysql_select_db("ufa3d", $mysql);

$q1 = mysql_query("select * from online where 1 order by id asc");
$num = mysql_num_rows($q1);

print '#VRML V2.0 utf8

Script
{
	field MFVec3f translation [';

$i = 1;
while($row = mysql_fetch_assoc($q1))
{
	if($i != 1) print ', ';
	print $row['trans'];
	$i++;
}

print ']
	field MFRotation orientation [';

$q1 = mysql_query("select * from online where 1 order by id asc");
$i = 1;
while($row = mysql_fetch_assoc($q1))
{
	if($i != 1) print ', ';
	print $row['rotate'];
	$i++;
}

print ']

}
'

?>
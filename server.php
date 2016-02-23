<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Content-type: x-world/x-vrml");

include_once("../inc/my_connect.php");

if($_GET['act'] == "move")
{
	$q1 = mysql_query("update 3d_online set position = '".$_GET['pos']."', checktime = ".time()." where id = ".$_GET['id']);
}
elseif($_GET['act'] == "rotate")
{
	$q1 = mysql_query("update 3d_online set orientation = '".$_GET['rotate']."', checktime = ".time()." where id = ".$_GET['id']);
}
elseif($_GET['act'] == "update")
{
	$q0 = mysql_query("update 3d_online set checktime = ".time()." where id = ".$_GET['id']);
	$q2 = mysql_query("delete from 3d_online where checktime < ".(time()-10)); //." and checktime >= ".(time()-6)
	$q1 = mysql_query("select * from 3d_online where id != ".$_GET['id']); //updatetime > ".(time()-7)." && 
	
	print '#VRML V2.0 utf8

';
	if(mysql_num_rows($q1) > 0)
	{
		print '
Script
{
	field MFInt32 id [';
		for($i=0; $i<mysql_num_rows($q1); $i++)
		{
			if($i > 0) print ", ";
			print mysql_result($q1, $i, "id");
		}
		print ']
	field MFVec3f translation [';
		for($i=0; $i<mysql_num_rows($q1); $i++)
		{
			if($i > 0) print ", ";
			print mysql_result($q1, $i, "position");
		}
		print ']
	field MFRotation orientation [';
		for($i=0; $i<mysql_num_rows($q1); $i++)
		{
			if($i > 0) print ", ";
			print mysql_result($q1, $i, "orientation");
		}
		print ']
}';
	}
	//mysql_free_result($q1);
	/*
	if(mysql_num_rows($q2) > 0)
	{
		print '
Group
{
	children
	[
		Script
		{
			field MFInt32 ids [';
		while($row = mysql_fetch_assoc($q2))
		{
			print $row['id'].", ";
		}
		print '0]
		}
	]
}';		
	}
	$q2 = mysql_query("delete from 3d_online where checktime < ".(time()-20));
	*/
}

//mysql_close($mysql);

?>
<?php

include_once("../inc/my_connect.php");

$q2 = mysql_query("delete from 3d_online where checktime < ".(time()-10));
$q0 = mysql_query("insert into 3d_online (nick, color, position, orientation, updatetime, checktime) values ('".$_POST['nick']."', '".$_POST['color']."', '".$_POST['position']."', '0 1 0 0', '".time()."', '".time()."')");

$myid = mysql_insert_id($mysql);

?>

<html>

<embed id="scene" src="city.php?id=<?php print $myid ?>" width="600" height="500">

</html>
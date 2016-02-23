<?php

$mysql = mysql_connect("localhost", "root", "password");
mysql_select_db("ufa3d", $mysql);

$num = mysql_result(mysql_query("select count(*) from online"), 0);

//if($num > 1) {mysql_query("truncate table `online`"); $num = 0;}

$id = ++$num;

$colors = array("1 0 0", "0 1 0", "0 0 1", "1 0 0", "0 1 0", "0 0 1", "1 0 0", "0 1 0", "0 0 1");

//mysql_query("insert into online values (".$id.", '".$colors[$id-1]."', '1 1 1')");

?>

<html>
<body>

<iframe src="vrml.php?id=<?php print $_GET['id'] ?>" width="600" height="500" align="right"></iframe>

</body>
</html>
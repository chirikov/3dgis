<html>

<body>
<form action="city.php" method="POST">
Имя: <input type="text" name="nick" maxlength="20">
Цвет: <select name="color" style="width: 100px">
<option value="1 0 0" style="background: #ff0000">&nbsp;
<option value="0 1 0" style="background: #00ff00">&nbsp;
<option value="0 0 1" style="background: #0000ff">&nbsp;
</select>
Начало: <select name="position">
<?php

include_once("my_connect.php");

$q1 = mysql_query("select * from 3d_places where 1");

while($row = mysql_fetch_assoc($q1))
{
	print '<option value="'.$row['position'].'">'.$row['name'];
}

?>
</select>
<input type="submit">
</form>
</body>
</html>
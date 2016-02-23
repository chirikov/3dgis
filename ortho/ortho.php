<?php

if($_GET['act'] == "transform")
{
	$fp = imagecreatefromjpeg($_GET['file']);
	
	$c1 = array($_GET['c1x'], $_GET['c1y']); // bot-right
	$c2 = array($_GET['c2x'], $_GET['c2y']); // top-right
	$c3 = array($_GET['c3x'], $_GET['c3y']); // top-left
	$c4 = array($_GET['c4x'], $_GET['c4y']); // bot-left
	
	$kw = $_GET['width'];
	$kh = $_GET['height'];
	$fp2 = imagecreatetruecolor($kw, $kh);
	$black = imagecolorallocate($fp2, 0, 0, 0);
	
	$fraktions = array(0, 1);
	for($step = 0; $step <= 10; $step++)
	{
		for($ch = 1; $ch <= pow(2, $step)-1; $ch+=2)
		{
			$fraktions[] = $ch/pow(2, $step);
		}
	}
	
	foreach($fraktions as $fr)
	{
		$isx = $c3[0] + ($c4[0] - $c3[0])*$fr;
		$isy = $c3[1] + ($c4[1] - $c3[1])*$fr;
		$iex = $c2[0] + ($c1[0] - $c2[0])*$fr;
		$iey = $c2[1] + ($c1[1] - $c2[1])*$fr;
		
		$ksx = 0;
		$ksy = $kh*$fr;
		$kex = $kw;
		$key = $kh*$fr;
		
		for($dp = $ksx; $dp < $kex; $dp++)
		{
			imagecopy($fp2, $fp, $dp, $ksy, $isx+$dp*($iex-$isx)/$kw, $isy+$dp*($iey-$isy)/$kw, 1, 1);
		}
	}
	
	header("Content-type: image/jpeg");
	imagejpeg($fp2, '', 100);
	exit;
}

?>
<html>
<body>
<form action="ortho.php?act=transform" method="GET">
<input type="file" name="file"><br>
Width: <input type="text" name="width"><br>
Height: <input type="text" name="height"><br><br>
Bottom-right: <input type="text" name="c1x"> <input type="text" name="c1y"><br>
Top-right: <input type="text" name="c2x"> <input type="text" name="c2y"><br>
Top-left: <input type="text" name="c3x"> <input type="text" name="c3y"><br>
Bottom-left: <input type="text" name="c4x"> <input type="text" name="c4y"><br><br>
<input type="submit">
</form>
</body>
</html>
<?php

function getwidth($points, $househeight, $image, $matrix, $focallength)
{
	$h1p = abs($points[0][1] - $points[3][1]);
	$h2p = abs($points[1][1] - $points[2][1]);

	$len1 = $focallength*($househeight/($matrix[1]*$h1p/$image[1]) + 1);
	$len2 = $len1*$h1p/$h2p;
	
	$m = $househeight/$h1p;
	
	$alphap = abs($points[2][0] - $points[3][0]);
	$alpha = $alphap*$m;
	
	$beta = 2*asin($alpha/(2*$len1));
	//print $beta." = ";
	
	//$beta = 2*atan(($alphap/2*$matrix[0])/($image[0]*$focallength));
	//print $beta."<br>";
	
	$housewidth = sqrt(pow($len1, 2) - 2*$len1*$len2*cos($beta) + pow($len2, 2));
	
	return array($housewidth, $househeight);
}

//$data = "1353,13|435,565|378,1260|1306,1374@631,662|555,696|538,886|615,866";
$data = "1353,11|436,565|378,1260|1306,1375@631,662|554,695|537,887|614,866@742,413|650,467|632,661|723,622@571,513|503,552|487,725|555,696@487,727|428,751|413,919|471,904@976,278|850,351|830,576|958,522@830,577|722,621|706,843|812,815@1306,91|1128,192|1113,460|1298,384@1112,459|958,522|941,781|1098,743@538,886|471,903|457,1086|523,1081@705,842|615,866|599,1077|690,1069@941,782|813,814|798,1063|927,1052@1286,697|1098,743|1085,1042|1275,1027@1083,1042|926,1052|916,1330|1070,1348@797,1063|689,1069|682,1303|787,1314@599,1077|523,1082|513,1281|590,1292@457,1087|399,1091|388,1261|445,1271";
//$data = "198,113|2174,301|2223,1234|149,1275@123";
//$data = "956,484|1200,70|1192,1197|949,1064@123";
//$data = "884,692|1403,725|1393,1052|876,1100@123";

$househeight = 0.209;
$focallength = 0.006; //0.0058
$image = array(2272, 1704);
$matrix = array(0.0058, 0.0043);

$data = explode("@", $data);
for($i = 0; $i < count($data); $i++)
{
	$data[$i] = explode("|", $data[$i]);
	for($j = 0; $j < count($data[$i]); $j++)
	{
		$data[$i][$j] = explode(",", $data[$i][$j]);
	}
}

$house = getwidth($data[0], $househeight, $image, $matrix, $focallength);

//print $house[0];

$im = imagecreate($house[0]*600, $house[1]*600);
$gray = imagecolorallocate($im, 200, 200, 200);
$black = imagecolorallocate($im, 0, 0, 0);

for($i = 1; $i < count($data); $i++)
{
	$ax = $data[$i][2][0];
	$ay = ($data[0][2][1] - $data[0][3][1])/($data[0][2][0] - $data[0][3][0])*($ax - $data[0][3][0]) + $data[0][3][1];
	
	$bx = $data[$i][3][0];
	$by = ($data[0][2][1] - $data[0][3][1])/($data[0][2][0] - $data[0][3][0])*($bx - $data[0][3][0]) + $data[0][3][1];
	
	$cx = $data[$i][1][0];
	$cy = ($data[0][1][1] - $data[0][0][1])/($data[0][1][0] - $data[0][0][0])*($cx - $data[0][0][0]) + $data[0][0][1];
	
	$dx = $data[$i][0][0];
	$dy = ($data[0][1][1] - $data[0][0][1])/($data[0][1][0] - $data[0][0][0])*($dx - $data[0][0][0]) + $data[0][0][1];
	
	$part1 = getwidth(array($data[0][0], array($dx, $dy), array($bx, $by), $data[0][3]), $househeight, $image, $matrix, $focallength);
	$part2 = getwidth(array($data[0][0], array($cx, $cy), array($ax, $ay), $data[0][3]), $househeight, $image, $matrix, $focallength);
	
	$elemheight = $househeight*abs($data[$i][0][1] - $data[$i][3][1])/abs($dy - $by);
	$elemwidth = abs($part1[0] - $part2[0]);
	
	$cmx = $house[0] - $part2[0];
	$cmy = $househeight*abs($cy - $data[$i][1][1])/abs($cy - $ay);
	
	//print $cmx;
	
	imagefilledrectangle($im, $cmx*600, $cmy*600, ($cmx+$elemwidth)*600, ($cmy+$elemheight)*600, $black);
}

header("Content-type: image/png"); imagepng($im);

?>
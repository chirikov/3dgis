<?php

$househeight = 0.209;
$focallength = 0.006; //0.0058
$image = array(2272, 1704);
$matrix = array(0.0058, 0.0043);
$shtativ = 0.024;
$far = 0.33;

$data = "1279,41|317,591|241,1300|1222,1406";

$data = explode("|", $data);
for($j = 0; $j < count($data); $j++)
{
	$data[$j] = explode(",", $data[$j]);
}

$c = $data[3];
$d = $data[0];

$u = 1/(1/$focallength - 1/$far); // = $focallength;
//$u = $focallength;

$sdp = abs($image[1]/2 - $d[1]);
$scp = abs($image[1]/2 - $c[1]);
//print $sdp;

$sd = $sdp*$matrix[1]/$image[1];
$sc = $scp*$matrix[1]/$image[1];

$an1 = atan($sc/$u);
$an2 = atan($sd/$u);

$gamma = $an1 + $an2;

$ae = $shtativ;
$be = $househeight - $shtativ;

$alpha = atan((-$househeight + sqrt(pow($househeight, 2) + 4*$ae*$be*pow(tan($gamma), 2)))/(2*$ae*tan($gamma)));

$oe = $shtativ/tan($alpha);

$len1 = $focallength*($househeight/($matrix[1]*($scp+$sdp)/$image[1]) + 1);

print $len1;

?>
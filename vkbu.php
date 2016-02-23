<?php

set_time_limit(0);

$a = "format=json&method=getServerTime&api_id=1670248&v=2.0";
$sig = "69e1ce843c6d0ec16fd2366d6db0654e";
$pre = "1380497api_id=1670248format=jsonmethod=getServerTimev=2.0";

//$ar = array_merge(range(0,9), range("a","z"), range("A","Z"));
$ar = range("A","Z");

foreach($ar as $s1)
{
foreach($ar as $s2)
{
foreach($ar as $s3)
{
foreach($ar as $s4)
{
foreach($ar as $s5)
{
if(md5($pre.$s1.$s2.$s3.$s4.$s5) == $sig) {print $s1.$s2.$s3.$s4.$s5; break;}
}
}
}
}
}

/*
for($i=1000000; $i<10000000; $i++)
{
	if(md5($pre.$i) == $sig) {print $i; break;}
}
*/
?>
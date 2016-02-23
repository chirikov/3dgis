<?php

function getcolor($im, $x, $y)
{
	$rgb = imagecolorat($im, $x, $y);
	return ((($rgb >> 16) & 0xFF) + (($rgb >> 8) & 0xFF) + ($rgb & 0xFF))/3;
}

function setred($im)
{
	$red = imagecolorallocate($im, 255, 0, 0);
	for($x=0; $x<imagesx($im); $x++)
	{
		for($y=0; $y<imagesy($im); $y++)
		{
			$rgb = imagecolorat($im, $x, $y);
			$rgbar['r'] = ($rgb >> 16) & 0xFF;
			$rgbar['g'] = ($rgb >> 8) & 0xFF;
			$rgbar['b'] = $rgb & 0xFF;
			
			if($rgbar['r'] == $rgbar['g'] && $rgbar['r'] == $rgbar['b'])
			{
				imagesetpixel($im, $x, $y, $red);
			}
		}
	}
}

function setgray($im, &$matrix)
{
	$black = imagecolorallocate($im, 0, 0, 0);
	$white = imagecolorallocate($im, 255, 255, 255);
	
	for($x=0; $x<imagesx($im); $x++)
	{
		for($y=0; $y<imagesy($im); $y++)
		{
			$rgb = imagecolorat($im, $x, $y);
			$rgbar['r'] = ($rgb >> 16) & 0xFF;
			$rgbar['g'] = ($rgb >> 8) & 0xFF;
			$rgbar['b'] = $rgb & 0xFF;
			
			if(!($rgbar['r'] == 255 && $rgbar['g'] == 0 && $rgbar['b'] == 0))
			{
				$avcolor = ($rgbar['r'] + $rgbar['g'] + $rgbar['b'])/3;
				if($avcolor < 192) {imagesetpixel($im, $x, $y, $black); $matrix[$x][$y] = 0;}
				else {imagesetpixel($im, $x, $y, $white); $matrix[$x][$y] = 255;}
			}
			else
			{
				$matrix[$x][$y] = 85;
			}
		}
	}
}
/*
function getcolor($im, $x, $y)
{
	$rgb = imagecolorat($im, $x, $y);
	$rgbar['r'] = ($rgb >> 16) & 0xFF;
	$rgbar['g'] = ($rgb >> 8) & 0xFF;
	$rgbar['b'] = $rgb & 0xFF;
	$color = ($rgbar['r'] + $rgbar['g'] + $rgbar['b'])/3;
	
	if($rgbar['r'] == $rgbar['g'] && $rgbar['r'] == $rgbar['b'])
	{
		$rgb = imagecolorat($im, $x, $y-2);
		$rgbar['r'] = ($rgb >> 16) & 0xFF;
		$rgbar['g'] = ($rgb >> 8) & 0xFF;
		$rgbar['b'] = $rgb & 0xFF;
		
		//$red = imagecolorallocate($im, $rgbar['r'], $rgbar['g'], $rgbar['b']);
		$red = imagecolorallocate($im, 255, 0, 0);
		
		imagesetpixel($im, $x, $y, $red);
	}
	
	if($color < 200) $color = 0;
	
	return $color;
}
*/


$minx = 100;
$miny = 100;
$maxx = -100;
$maxy = -100;

for($x = -10; $x <= 10; $x++)
{
	for($y = -10; $y <= 10; $y++)
	{
		if(file_exists("tiles/tiles".$x.$y.".png"))
		{
			if($x < $minx) $minx = $x;
			if($y < $miny) $miny = $y;
			if($x > $maxx) $maxx = $x;
			if($y > $maxy) $maxy = $y;
		}
	}
}

$imall = imagecreatetruecolor(($maxx - $minx + 1)*256, ($maxy - $miny + 1)*256);

for($x = $minx; $x <= $maxx; $x++)
{
	for($y = $miny; $y <= $maxy; $y++)
	{
		if(file_exists("tiles/tiles".$x.$y.".png"))
		{
			$imt = imagecreatefrompng("tiles/tiles".$x.$y.".png");
			imagecopy($imall, $imt, ($x - $minx)*256, ($y - $miny)*256, 0, 0, 256, 256);
		}
	}
}
imagepng($imall, "tile_all.png");



$file = "tile_all.png";
$matrix = array();

$im = imagecreatefrompng($file);

$imw = imagesx($im);
$imh = imagesy($im);

$im3 = imagecreatetruecolor($imw, $imh);
imagecopy($im3, $im, 0, 0, 0, 0, $imw, $imh);
imagedestroy($im);
$im = $im3;

$red = imagecolorallocate($im, 255, 0, 0);
$green = imagecolorallocate($im, 0, 255, 0);
$blue = imagecolorallocate($im, 0, 0, 255);
$yellow = imagecolorallocate($im, 255, 255, 0);
$gray = imagecolorallocate($im, 200, 200, 200);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);

setred($im);
setgray($im, $matrix);

//$stack = array(25, 255, 255, 255, 255, 255, 255);


/*
for($x=0; $x<$imw; $x++)
{
	for($y=0; $y<$imh; $y++)
	{
		if(getcolor($im, $x, $y) == 0 && getcolor($im, $x-1, $y) == 255 && getcolor($im, $x-2, $y) == 255 && getcolor($im, $x-3, $y) == 0) imagesetpixel($im, $x, $y, $black);
	}
}
*/
/*
for($x=0; $x<$imw; $x++)
{
	for($y=0; $y<$imh; $y++)
	{
		$color = getcolor($im, $x, $y);
		
		if($color == 85)
		{
			//if($stack[0] == 255 && $stack[1] == 255 && $stack[2] == 255 && $stack[3] == 255 && $stack[4] == 255 && $stack[5] == 255 && $stack[6] == 255 && $stack[7] == 255 && $stack[8] == 255 && $stack[9] == 255)
			//{
				//imagesetpixel($im, $x, $y, $white);
				//$color = 255;
			//}
			/*
			elseif((($stack[0] == 255 || $stack[0] == 0) && $stack[1] == 0) || (($stack[0] == 255 || $stack[0] == 0) && $stack[1] == 255 && $stack[2] == 0))
			{
				imagesetpixel($im, $x, $y, $black);
				$color = 0;
			}
			
			
			//elseif($stack[0] == 0)
			//{
				//imagesetpixel($im, $x, $y, $black);
				//$color = 0;
			//}
			
			//else
			//{	
				$isblack = false;
				for($i=9; $i>0; $i--)
				{
					if($stack[$i] == 0)
					{
						$isblack = true;
						break;
					}
				}
				if($isblack)
				{
					for($j=0; $j<$i; $j++)
					{
						imagesetpixel($im, $x, $y-1-$j, $black);
						$stack[$j] = 0;
					}
					imagesetpixel($im, $x, $y, $black);
					$color = 0;
				}
				else
				{
					imagesetpixel($im, $x, $y, $white);
					$color = 255;
				}
			//}
			
		}
		push($stack, $color);
	}
}
*/

function push(&$stack, $color)
{
	for($i=11; $i>0; $i--)
	{
		$stack[$i] = $stack[$i-1];
	}
	$stack[0] = $color;
}

//$stack = array(25, 255, 255, 255, 255, 255, 255);

$stack = array();
for($x=0; $x<$imw; $x++)
{
	for($y=0; $y<$imh; $y++)
	{
		$color = getcolor($im, $x, $y);
		
		if($color == 85 && $stack[0] == 255 && $stack[1] == 255 && $stack[2] == 255 && $stack[3] == 255 && $stack[4] == 255 && $stack[5] == 255 && $stack[6] == 255 && $stack[7] == 255 && $stack[8] == 255 && $stack[9] == 255)
		{
			imagesetpixel($im, $x, $y, $white);
			$color = 255;
		}
		elseif($color == 255)
		{
			$isred = false;
			for($i=11; $i>0; $i--)
			{
				if($stack[$i] == 85)
				{
					$isred = true;
					break;
				}
			}
			/*
			$isw = false;
			for($j=11; $j>0; $j--)
			{
				if($stack[$j] == 255)
				{
					$isw = true;
					break;
				}
			}
			*/
			if($isred)
			{
				if($stack[11] == 0)
				{
					//$i = max($i, $j);
					//imagesetpixel($im, $x, $y-1-$i, $green);
					for($j=0; $j<=$i; $j++)
					{
						imagesetpixel($im, $x, $y-1-$j, $black);
						$stack[$j] = 0;
					}
				}
			}
		}
		push($stack, $color);
	}
}


for($y=0; $y<$imh; $y++)
{
	for($x=0; $x<$imw; $x++)
	{
		if(getcolor($im, $x, $y) == 0 && getcolor($im, $x-1, $y) != 0 && getcolor($im, $x-2, $y) == 0)
		{
			imagesetpixel($im, $x-1, $y, $black);
		}
		elseif(getcolor($im, $x, $y) == 0 && getcolor($im, $x-1, $y) != 0 && getcolor($im, $x-2, $y) != 0 && getcolor($im, $x-3, $y) == 0)
		{
			imagesetpixel($im, $x-1, $y, $black);
			imagesetpixel($im, $x-2, $y, $black);
		}
		elseif(getcolor($im, $x, $y) == 0 && getcolor($im, $x-1, $y) != 0 && getcolor($im, $x-2, $y) != 0 && getcolor($im, $x-3, $y) != 0 && getcolor($im, $x-4, $y) == 0)
		{
			imagesetpixel($im, $x-1, $y, $black);
			imagesetpixel($im, $x-2, $y, $black);
			imagesetpixel($im, $x-3, $y, $black);
		}
		elseif(getcolor($im, $x, $y) == 0 && getcolor($im, $x-1, $y) != 0 && getcolor($im, $x-2, $y) != 0 && getcolor($im, $x-3, $y) != 0 && getcolor($im, $x-4, $y) != 0 && getcolor($im, $x-5, $y) == 0)
		{
			imagesetpixel($im, $x-1, $y, $black);
			imagesetpixel($im, $x-2, $y, $black);
			imagesetpixel($im, $x-3, $y, $black);
			imagesetpixel($im, $x-4, $y, $black);
		}
	}
}
for($x=0; $x<$imw; $x++)
{
	for($y=0; $y<$imh; $y++)
	{
		if(getcolor($im, $x, $y) == 0 && getcolor($im, $x, $y-1) != 0 && getcolor($im, $x, $y-2) == 0)
		{
			imagesetpixel($im, $x, $y-1, $black);
		}
		elseif(getcolor($im, $x, $y) == 0 && getcolor($im, $x, $y-1) != 0 && getcolor($im, $x, $y-2) != 0 && getcolor($im, $x, $y-3) == 0)
		{
			imagesetpixel($im, $x, $y-1, $black);
			imagesetpixel($im, $x, $y-2, $black);
		}
		elseif(getcolor($im, $x, $y) == 0 && getcolor($im, $x, $y-1) != 0 && getcolor($im, $x, $y-2) != 0 && getcolor($im, $x, $y-3) != 0 && getcolor($im, $x, $y-4) == 0)
		{
			imagesetpixel($im, $x, $y-1, $black);
			imagesetpixel($im, $x, $y-2, $black);
			imagesetpixel($im, $x, $y-3, $black);
		}
	}
}

/*
imagefilter($im, IMG_FILTER_GRAYSCALE);

imagefilter($im, IMG_FILTER_BRIGHTNESS, -100);
imagefilter($im, IMG_FILTER_CONTRAST, -100);
imagefilter($im, IMG_FILTER_BRIGHTNESS, 100);
imagefilter($im, IMG_FILTER_CONTRAST, -100);
*/
/*
for($y=0; $y<$imh; $y++)
{
	for($x=0; $x<$imw; $x++)
	{
		if($matrix[$x][$y] == 85)
		{
			if($matrix[$x-3][$y] == 0 || $matrix[$x+3][$y] == 0 || $matrix[$x][$y-3] == 0 || $matrix[$x][$y+3] == 0)
			{
				imagesetpixel($im, $x, $y, $black);
				$matrix[$x][$y] = 0;
			}
		}
	}
}
*/

//imagefilledrectangle($im, 65, 30, 100, 70, $black);

header("Content-type: image/png");
imagepng($im, "tiles.png"); //






###################################################

	//$rgbar['r'] = ($rgb >> 16) & 0xFF;
	//$rgbar['g'] = ($rgb >> 8) & 0xFF;
	//$rgbar['b'] = $rgb & 0xFF;
	
	//return ($rgbar['r'] + $rgbar['g'] + $rgbar['b'])/3;

/*
				$colorl = $matrix[$x-1-$dl][$y];
				while($colorl == 0)
				{
					$dl++;
					$colorl = $matrix[$x-1-$dl][$y];
				}
				*/


/*
		// upper
		for($ns=$xi*10; $ns<($xi+1)*10; $ns++)
		{
			$color = $matrix[$ns][$yi*10+9];
			if($color == 0)
			{
				$vsc = true;
				for($vs=$xi*10; $vs<($xi+1)*10; $vs++)
				{
					$color = $matrix[$vs][$yi*10];
					if($color == 0)
					{
						$vsc = false;
						break;
					}
				}
				if($vsc == true)
				{
					imagerectangle($im, $xi*10, $yi*10, ($xi+1)*10, ($yi+1)*10, $red);
					//$toseek[] = array($xi*10, $yi*10);
					continue 2;
				}
			}
		}
		*/

/*
			if($matrix[$x][$y] == 0 && $matrix[$x][$y-1] != 0 && $matrix[$x-3][$y] != 0 && $matrix[$x+3][$y] != 0)
			{
				imagesetpixel($im, $x, $y, $red);
				continue 3;
				//$points[];
				//$i++;
			}
			*/


/*
foreach($toseek as $area)
{
	for($y=$area[1]; $y<$area[1]+10; $y++)
	{
		for($x=$area[0]; $x<$area[0]+10; $x++)
		{
			if($matrix[$x][$y] == 0 && $matrix[$x][$y-1] != 0 && $matrix[$x-3][$y] != 0 && $matrix[$x+3][$y] != 0)
			{
				imagesetpixel($im, $x, $y, $red);
				
				//print $y." start<br>";
				//print "Point 1: ".$x." ".$y."<br>";
				
				$prevstate = "corner";
				$prevlen = 0;
				$prevser = $x;
				for($yi=$y+1; $yi<$y+5; $yi++)
				{
					$xi = $prevser;
					$lenl = 0;
					$colorl = getcolor($im, $xi-1, $yi);
					while($colorl == 0)
					{
						$lenl++;
						$xi--;
						$colorl = getcolor($im, $xi, $yi);
					}
					$xl = $xi;
					
					$xi = $prevser;
					$lenr = 0;
					$colorr = getcolor($im, $xi+1, $yi);
					while($colorr == 0)
					{
						$lenr++;
						$xi++;
						$colorr = getcolor($im, $xi, $yi);
					}
					$xr = $xi;
					$len = $lenr + $lenl;
					
					$ser = round(($xr + $xl)/2);
					
					if($len <= $prevlen+2 && $len >= $prevlen-2 && $prevstate == "corner")
					{
						//print $yi." ".$len." parallel<br>";
						//if($ser < $prevser) print $yi." ".$len." right corner<br>";
						//else print $yi." ".$len." left corner<br>";
						if($ser < $prevser) imagesetpixel($im, $xl, $yi, $red);//print "Point 2: ".$xl." ".$yi."<br>";
						else imagesetpixel($im, $xr, $yi, $red);//print "Point 2: ".$xr." ".$yi."<br>";
						$prevstate = "parallel";
						//if($ser < $prevser) imagesetpixel($im, $xr, $yi, $red);
						//else imagesetpixel($im, $xl, $yi, $red);
					}
					elseif($len < $prevlen-2 && $prevstate == "parallel")
					{
						//if($ser < $prevser) print $yi." ".$len." right corner<br>";
						//else print $yi." ".$len." left corner<br>";
						if($ser > $prevser) imagesetpixel($im, $xl, $yi, $red);//print "Point 2: ".$xl." ".$yi."<br>";
						else imagesetpixel($im, $xr, $yi, $red);//print "Point 3: ".$xr." ".$yi."<br>";
						$prevstate = "corner";
					}
					elseif($len < 2)
					{
						//print $yi." ".$len." end<br>";
						//print "Point 4: ".$xi." ".$yi."<br>";
						imagesetpixel($im, $xi, $yi, $red);
						break 1;
					}
					//print $ser." ".$len."<br>";
					
					$prevlen = $len;
					$prevser = $ser;
				}
				//imagesetpixel($im, $x, $y, $red);
				//$x += 20;
				break 2;
			}
		}
	}
}
*/

/*
$im2 = imagecreate($imw, $imh);
$red = imagecolorallocate($im2, 255, 255, 255);
$black = imagecolorallocate($im2, 0, 0, 0);

for($y=0; $y<$imh; $y++)
{
	for($x=0; $x<$imw; $x++)
	{
		if($matrix[$x][$y] == 0) imagesetpixel($im2, $x, $y, $black);
	}
}
*/

?>
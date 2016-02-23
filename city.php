<?php

//$mysql = mysql_connect("localhost", "root", "password");
//mysql_select_db("gos", $mysql);
include_once("../inc/my_connect.php");

//$q2 = mysql_query("delete from 3d_online where checktime < ".(time()-10));

//$q0 = mysql_query("insert into 3d_online (nick, color, position, orientation, updatetime, checktime) values ('".$_POST['nick']."', '".$_POST['color']."', '".$_POST['position']."', '0 1 0 0', '".time()."', '".time()."')");

//print mysql_error();

//$myid = mysql_insert_id($mysql);

$q0 = mysql_query("select position from 3d_online where id = ".$_GET['id']);
$me = mysql_fetch_assoc($q0);

header("Content-type: x-world/x-vrml");

$mashtab = 10;

// 5.55 m/pixel

//$centerx = 155;
//$centery = -6;

$centerx = -89;
$centery = -65;

$ims = getimagesize("map.png");

$w = $ims[0];
$h = $ims[1];
$host = "localhost/vm";

print '#VRML V2.0 utf8

WorldInfo
{
	info ["&copy; Второй Мир, 2008-2009"]
	title "Уфа - Второй Мир"
}

NavigationInfo
{
	avatarSize [0.05 0.18 0.05]
	headlight TRUE
	speed 0.5
	#type ["WALK"]
	visibilityLimit 0.0
}

DEF Avatar Viewpoint
{
	position '.$_POST['position'].'
	description "Start"
	jump FALSE
	#fieldOfView 0.9
}

DEF proxy ProximitySensor
{
	center 0 0 0
	size 1000 100 1000
	enabled TRUE
}

Viewpoint
{
	position 10 40 60
	orientation -99 0 1 0.5
	description "View all"
	jump FALSE
}

Background
{
	skyColor [0.76 0.93 0.97]
	skyAngle [1.5]
	groundColor [0 0.9 0]
	groundAngle [1.5]
}

';

include_once("manproto.txt");

print '

Transform
{
	translation '.($centerx/$mashtab).' -0.005 '.($centery/$mashtab).'
	children
	[
		Shape
		{
			geometry Box
			{
				size '.($w/$mashtab).' 0.01 '.($h/$mashtab).'
			}
			appearance Appearance
			{
				texture ImageTexture
				{
					url "http://'.$host.'/3d/map.png"
				}
			}
		}
	]
}
Transform
{
	translation '.($centerx/$mashtab).' -0.015 '.($centery/$mashtab).'
	children
	[
		Shape
		{
			geometry Box
			{
				size '.($w/$mashtab).' 0.01 '.($h/$mashtab).'
			}
			appearance Appearance
			{
				material Material
				{
					diffuseColor 0 0.9 0
				}
			}
		}
	]
}

Shape
{
	geometry Extrusion
	{
		beginCap FALSE
		crossSection ['.(-$w/2+$centerx)/$mashtab.' '.(-$h/2+$centery)/$mashtab.', '.(-$w/2+$centerx)/$mashtab.' '.($h/2+$centery)/$mashtab.', '.($w/2+$centerx)/$mashtab.' '.($h/2+$centery)/$mashtab.', '.($w/2+$centerx)/$mashtab.' '.(-$h/2+$centery)/$mashtab.', '.(-$w/2+$centerx)/$mashtab.' '.(-$h/2+$centery)/$mashtab.']
		endCap FALSE
		solid FALSE
		spine [0 0 0, 0 2 0]
	}
	appearance Appearance
	{
		material Material
		{
			diffuseColor 0 0 1
		}
	}
}

DEF time1 TimeSensor {
	cycleInterval 2
	enabled       TRUE
	loop          TRUE
	startTime     0
	stopTime      0
}

';

$houses = file("houses.txt");

foreach($houses as $house)
{
	$angles = explode("|", $house);
	
	print '
Shape
{
	geometry Extrusion
	{
		beginCap TRUE
		crossSection [';
$i = 1;
foreach($angles as $angle)
{
	$xy = explode(",", $angle);
	if($i != 1) print ', ';
	print $xy[0]/$mashtab.' '.$xy[1]/$mashtab;
	$i++;
}
$xy = explode(",", $angles[0]);
print ', '.$xy[0]/$mashtab.' '.$xy[1]/$mashtab;
print ']
		endCap TRUE
		solid TRUE
		spine [0 0 0, 0 2 0]
	}
	appearance Appearance
	{
		texture ImageTexture
		{
			url "http://'.$host.'/3d/4.jpg"
			repeatS FALSE
			repeatT FALSE
		}
		material Material
		{
			#diffuseColor 1 0 0
		}
	}
}
	';
}


$q1 = mysql_query("select * from 3d_online where id != ".$_GET['id']);
$num = mysql_num_rows($q1);

// PositionInterpolators
print '
DEF posint Group
{
	children
	[
';
for($i = 0; $i < $num; $i++)
{
	print '
DEF posint'.$i.' PositionInterpolator
{
	key [0, 1]
	keyValue []
}
	';
}
print '
	]
}';
/////////////////
// TimeSensors
print '
DEF timeint Group
{
	children
	[
';
for($i = 0; $i < $num; $i++)
{
	print '
DEF timeint'.$i.' TimeSensor {
	cycleInterval 1
	enabled FALSE
	loop TRUE
}
	';
}
print '
	]
}';
/////////////////
// Men
print '
DEF men Group
{
	children
	[
';
$i = 0;
while($row = mysql_fetch_assoc($q1))
{
	print '
DEF man'.$i.' Man
{
	id '.$row['id'].'
	translation '.$row['position'].'
	color '.$row['color'].'
	name "'.iconv("windows-1251", "UTF-8", $row['nick']).'"
}
	';
	$i++;
}
print '
	]
}';
/////////////////
// Routes
for($i = 0; $i < $num; $i++)
{
	print '
ROUTE timeint'.$i.'.fraction_changed TO posint'.$i.'.set_fraction
ROUTE posint'.$i.'.value_changed TO man'.$i.'.set_translation
	';
}
/////////////////

print '
DEF script1 Script
{
	eventIn SFVec3f move
	eventIn SFRotation rotate
	eventIn MFNode func1
	eventIn MFNode get_trans
	eventIn MFNode addman
	eventIn SFTime update
	eventIn SFFloat stoptrans
	
	directOutput TRUE
	
	field SFNode myself USE	script1
	field MFString server_url []
	
	field SFVec3f mypos '.$_POST['position'].'
	field SFRotation myrot 0 1 0 0
	
	field SFNode men USE men
	field SFNode posint USE posint
	field SFNode timeint USE timeint
	field SFNode time1 USE time1
	'; 
?>
	
	url	"javascript:
		function move(val)
		{
			val[1] = 0.18;
			
			if(Math.abs(mypos[0] - val[0]) > 0.01 || Math.abs(mypos[2] - val[2]) > 0.01)
			{
				mypos = val;
				
				server_url[0] = 'http://<?php print $host ?>/3d/server.php?act=move&id=<?php print $_GET['id'] ?>&pos=' + val;
				Browser.createVrmlFromURL(server_url, myself, 'func1');
			}
		}
		function rotate(val)
		{
			if(Math.abs(val[3] - myrot[3]) > 0.05)
			{	
				myrot = val;
				
				server_url[0] = 'http://<?php print $host ?>/3d/server.php?act=rotate&id=<?php print $_GET['id'] ?>&rotate=' + val;
				Browser.createVrmlFromURL(server_url, myself, 'func1');
			}
		}
		function update(val)
		{
			time1.set_enabled = FALSE;
			server_url[0] = 'http://<?php print $host ?>/3d/server.php?act=update&id=<?php print $_GET['id'] ?>';
			Browser.createVrmlFromURL(server_url, myself, 'get_trans');
		}
		function stoptrans(val)
		{
			if(val > 0.93 && val <= 1)
			{
				for(j = 0; j < timeint.children.length; j++)
				{
					if(timeint.children[j] != null)
					{
						if(timeint.children[j].fraction_changed > 0.93 && timeint.children[j].fraction_changed <= 1)
						{
							timeint.children[j].set_enabled = FALSE;
							//break;
							//men.children['.$i.'].translation = posint.children['.$i.'].keyValue[1];
						}
					}
				}
			}
		}
		function get_trans(val)
		{
			time1.set_fraction = 0;
			time1.set_enabled = TRUE;
			if(val.length > 0)
			{
			trace(val[0].id);
				for(i = 0; i < val[0].id.length; i++)
				{
					var found = false;
					for(j = 0; j < men.children.length; j++)
					{
						if(men.children[j] != null)
						{
							if(men.children[j].id == val[0].id[i])
							{
								found = true;
								break;
							}
						}
					}
					if(found)
					{
						posint.children[j].keyValue[0] = men.children[j].translation;
						posint.children[j].keyValue[1] = val[0].translation[i];
						
						men.children[j].set_orientation = val[0].orientation[i];
						
						timeint.children[j].set_enabled = TRUE;
					}
					else
					{
						var t = Browser.createVrmlFromString('DEF timeint'+timeint.children.length+' TimeSensor {cycleInterval 1 enabled FALSE loop TRUE}');
						timeint.children[timeint.children.length] = t[0];
						
						var p = Browser.createVrmlFromString('DEF posint'+posint.children.length+' PositionInterpolator {key [0, 1] keyValue []}');
						posint.children[posint.children.length] = p[0];
						
						Browser.addRoute(timeint.children[timeint.children.length-1], 'fraction_changed', posint.children[posint.children.length-1], 'set_fraction');
						Browser.addRoute(timeint.children[timeint.children.length-1], 'fraction_changed', myself, 'stoptrans');
						
						server_url[0] = 'http://<?php print $host ?>/3d/man.php?id='+val[0].id[i];
						Browser.createVrmlFromURL(server_url, myself, 'addman');
					}
				}
			
				for(j = 0; j < men.children.length; j++)
				{
					if(men.children[j] != null)
					{
						var found = false;
						for(i = 0; i < val[0].id.length; i++)
						{
							if(men.children[j].id == val[0].id[i])
							{
								found = true;
								break;
							}
						}
						if(!found)
						{
							Browser.deleteRoute(timeint.children[j], 'fraction_changed', posint.children[j], 'set_fraction');
							Browser.deleteRoute(timeint.children[j], 'fraction_changed', myself, 'stoptrans');
							Browser.deleteRoute(posint.children[j], 'value_changed', men.children[j], 'set_translation');
							
							men.children[j] = null;
							posint.children[j] = null;
							timeint.children[j] = null;
							break;
						}
					}
				}
			}
		}
		function addman(val)
		{
			men.children[men.children.length] = val[0];
			Browser.addRoute(posint.children[posint.children.length-1], 'value_changed', men.children[men.children.length-1], 'set_translation');
		}
	"
}
<?php

for($i = 0; $i < $num; $i++)
{
	print '
	ROUTE timeint'.$i.'.fraction_changed TO script1.stoptrans
	';
}

?>
ROUTE time1.cycleTime TO script1.update
ROUTE proxy.position_changed TO script1.move
ROUTE proxy.orientation_changed TO script1.rotate
#ROUTE proxy.position_changed TO proxy.set_center
#ROUTE proxy.orientation_changed TO Avatar.set_orientation
#ROUTE proxy.position_changed TO Avatar.set_position
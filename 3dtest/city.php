<?php

$mysql = mysql_connect("localhost", "root", "password");
mysql_select_db("ufa3d", $mysql);

$colors = array("1 0 0", "0 1 0", "0 0 1", "1 0 0", "0 1 0", "0 0 1", "1 0 0", "0 1 0", "0 0 1");

$q0 = mysql_query("update online set trans = '0 0 ".($_GET['id']+2)."', rotate = '0 0 1 0' where id = ".$_GET['id']);

header("Content-type: x-world/x-vrml");

$mashtab = 10;

$centerx = 155;
$centery = -6;
$w = 512;
$h = 512;

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
	type ["WALK"]
	visibilityLimit 0.0
}

DEF Avatar Viewpoint
{
	position 0 0.18 '.($_GET['id']+2).'
	description "Avatar"
	jump FALSE
}


DEF proxy ProximitySensor
{
	center 0 0 '.($_GET['id']+2).'
	size 50 10 50
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

PROTO Man
[
	field SFColor color 1 1 1
	exposedField SFVec3f translation 0 0 0
	exposedField SFRotation orientation 0 0 1 0
]
{
	Transform
	{
		translation IS translation
		rotation IS orientation
		children
		[
			Transform
			{
				translation 0 0.045 0
				children
				[
					Shape
					{
						appearance Appearance
						{
							material Material
							{
								diffuseColor IS color
							}
						}
						geometry Box
						{
							size 0.03 0.09 0.02
						}
					}
				]
			}
			Transform
			{
				translation 0 0.135 0
				children
				[
					Shape
					{
						appearance Appearance
						{
							material Material
							{
								diffuseColor IS color
							}
						}
						geometry Box
						{
							size 0.05 0.09 0.02
						}
					}
				]
			}
			Transform
			{
				translation 0 0.195 0
				children
				[
					Shape
					{
						appearance Appearance
						{
							material Material
							{
								diffuseColor IS color
							}
						}
						geometry Sphere
						{
							radius 0.015
						}
					}
				]
			}
		]
	}
}

Transform
{
	translation '.($centerx/$mashtab).' 0 '.($centery/$mashtab).'
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
					url "map.png"
				}
			}
		}
	]
}
Transform
{
	translation '.($centerx/$mashtab).' -0.01 '.($centery/$mashtab).'
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
		crossSection ['.(-256+$centerx)/$mashtab.' '.(-256+$centery)/$mashtab.', '.(-256+$centerx)/$mashtab.' '.(-256+$centery+$h)/$mashtab.', '.(-256+$centerx+$w)/$mashtab.' '.(-256+$centery+$h)/$mashtab.', '.(-256+$centerx+$w)/$mashtab.' '.(-256+$centery)/$mashtab.', '.(-256+$centerx)/$mashtab.' '.(-256+$centery)/$mashtab.']
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
		material Material
		{
			diffuseColor 1 0 0
		}
	}
}
	';
}

$myid = $_GET['id'];

$q1 = mysql_query("select * from online where id != ".$myid);
$num = mysql_num_rows($q1);

while($row = mysql_fetch_assoc($q1))
{
	print '
DEF man'.$row['id'].' Man
{
	translation '.$row['trans'].'
	color '.$row['color'].'
}

DEF timeint'.$row['id'].' TimeSensor {
	cycleInterval 2
	enabled FALSE
	loop TRUE
}

DEF posint'.$row['id'].' PositionInterpolator
{
	key [0, 1]
	keyValue []
}

ROUTE timeint'.$row['id'].'.fraction_changed TO posint'.$row['id'].'.set_fraction
ROUTE posint'.$row['id'].'.value_changed TO man'.$row['id'].'.set_translation
	';
}

print '
DEF script1 Script
{
	eventIn SFVec3f move
	eventIn SFRotation rotate
	eventIn MFNode func1
	eventIn MFNode get_trans
	eventIn SFTime update
	
	field SFNode myself USE	script1
	field SFVec3f mypos 0 0 '.($_GET['id']+2).'
	field SFRotation myrot 0 1 0 0
	#field SFNode transme USE man'.$myid.'
	'; 
for($i = 0; $i < $num; $i++)
{
	$id = mysql_result($q1, $i, 'id');
	if($id == $myid) continue;
	print '
	eventIn SFFloat stoptrans'.$id.'
	field SFNode trans'.$id.' USE man'.$id.'
	field SFNode posint'.$id.' USE posint'.$id.'
	field SFNode timeint'.$id.' USE timeint'.$id.'
	';
}
print '
	field MFString server_url [""]
	
	url	"javascript:
'; ?>
		function move(val)
		{
			val[1] = 0;
			
			if(Math.abs(mypos[0] - val[0]) > 0.1 || Math.abs(mypos[2] - val[2]) > 0.1)
			{
				mypos = val;
				
				server_url[0] = 'http://localhost/vm/beta/3dtest/server.php?id=<?php print $myid ?>&pos=' + val;
				Browser.createVrmlFromURL(server_url, myself, 'func1');
			}
		}
		function rotate(val)
		{
			if(Math.abs(val[3] - myrot[3]) > 0.1)
			{
				myrot = val;
				
				val[0] = 0;
				val[1] = 1;
				val[2] = 0;
				
				server_url[0] = 'http://localhost/vm/beta/3dtest/serverrotate.php?id=<?php print $myid ?>&rotate=' + val;
				Browser.createVrmlFromURL(server_url, myself, 'func1');
			}
		}
		function update(val)
		{
			server_url[0] = 'http://localhost/vm/beta/3dtest/serverupdate.php?id=<?php print $myid ?>';
			Browser.createVrmlFromURL(server_url, myself, 'get_trans');
		}
<?php
for($i = 0; $i < $num; $i++)
{
	$id = mysql_result($q1, $i, 'id');
	if($id == $myid) continue;
	print '
	function stoptrans'.$id.'(val)
	{
		if(val > 0.98 && val < 1) timeint'.$id.'.enabled = FALSE;
	}
	';
}
print '
		function get_trans(val)
		{
		
			';
for($i = 0; $i < $num; $i++)
{
	$id = mysql_result($q1, $i, 'id');
	print '
	posint'.$id.'.keyValue[0] = trans'.$id.'.translation;
	posint'.$id.'.keyValue[1] = val[0].translation['.($id-1).'];
	
	trans'.$id.'.set_orientation = val[0].orientation['.($id-1).'];
	//trans'.$id.'.set_translation = val[0].translation['.($id-1).'];
	
	if(Math.abs(trans'.$id.'.translation[0] - val[0].translation['.($id-1).'][0]) > 0.1 || Math.abs(trans'.$id.'.translation[2] - val[0].translation['.($id-1).'][2]) > 0.1) timeint'.$id.'.enabled = TRUE;
	';
}
print '
		}
	"
}
';

for($i = 0; $i < $num; $i++)
{
	$id = mysql_result($q1, $i, 'id');
	if($id == $myid) continue;
	print '
ROUTE timeint'.$id.'.fraction_changed TO script1.stoptrans'.$id.'
';
}

print '
ROUTE time1.cycleTime TO script1.update
ROUTE proxy.position_changed TO script1.move
ROUTE proxy.orientation_changed TO script1.rotate
ROUTE proxy.position_changed TO proxy.set_center
#ROUTE proxy.orientation_changed TO Avatar.set_orientation
#ROUTE proxy.position_changed TO Avatar.set_position
';

?>
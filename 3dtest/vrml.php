<?php

header("Content-type: x-world/x-vrml");

$mysql = mysql_connect("localhost", "root", "password");
mysql_select_db("ufa3d", $mysql);

$myid = $_GET['id'];

//$q1 = mysql_query("select * from online where 1");
//$num = mysql_num_rows($q1);

print '#VRML V2.0 utf8

Viewpoint
{
	position 0 5 5
	orientation -99 0 0 0.7
}

Transform
{
	children
	[
		Shape
		{
			geometry Box
			{
				size 3 0.01 3
			}
			appearance Appearance
			{
				material Material
				{
					diffuseColor 0.3 0.3 0.3 
				}
			}
		}
		DEF touch TouchSensor
		{
			enabled TRUE
		}
	]
}

DEF time1 TimeSensor {
	cycleInterval 1
	enabled       TRUE
	loop          TRUE
	startTime     0
	stopTime      0
}

DEF timeint1 TimeSensor {
	cycleInterval 2
	enabled       FALSE
	loop TRUE
}

DEF timeint2 TimeSensor {
	cycleInterval 2
	enabled       FALSE
	loop TRUE
}

DEF posint1 PositionInterpolator
{
	key [0, 1]
	keyValue []
}

DEF posint2 PositionInterpolator
{
	key [0, 1]
	keyValue []
}

DEF transform1 Transform
{
	translation -1.25 0.25 -1.25
	children
	[
		Shape
		{
			geometry Box
			{
				size 0.5 0.5 0.5
			}
			appearance Appearance
			{
				material Material
				{
					diffuseColor 1 0 0 
				}
			}
		}
	]
}

DEF transform2 Transform
{
	translation -0.75 0.25 -0.75
	children
	[
		Shape
		{
			geometry Box
			{
				size 0.5 0.5 0.5
			}
			appearance Appearance
			{
				material Material
				{
					diffuseColor 0 1 0 
				}
			}
		}
	]
}
'; ?>

DEF	script1 Script
{
	eventIn SFVec3f move
	eventIn MFNode func1
	eventIn MFNode get_trans
	eventIn SFTime update
	eventIn SFFloat stoptrans
	
	field SFNode myself USE	script1
	field SFNode transhim USE transform<?php print 3-$myid ?>
	field SFNode transme USE transform<?php print $myid ?>
	field SFNode posinthim USE posint<?php print 3-$myid ?>
	field SFNode timehim USE timeint<?php print 3-$myid ?>
	field MFString server_url [""]
	field SFVec3f tempvec 0 0 0
	
	url	"javascript:
		function move(val)
		{
			transme.set_translation = val;
			server_url[0] = 'http://localhost/vm/beta/3dtest/server.php?id=<?php print $myid ?>&pos=' + val;
			Browser.createVrmlFromURL(server_url, myself, 'func1');
		}
		function update(val)
		{
			server_url[0] = 'http://localhost/vm/beta/3dtest/serverupdate.php?id=<?php print $myid ?>';
			Browser.createVrmlFromURL(server_url, myself, 'get_trans');
		}
		function stoptrans(val)
		{
			if(val > 0.98 && val < 1) timehim.enabled = FALSE;
		}
		function get_trans(val)
		{
			//trace(val[0].translation2);
			//transhim.set_translation =  val[0].translation;
			
			//var dx = (val[0].translation[0] - transhim.translation[0])/20;
			//var dz = (val[0].translation[2] - transhim.translation[2])/20;
			
			posinthim.keyValue[0] = transhim.translation;
			//posinthim.keyValue[1][0] = transhim.translation[0] + dx;
			//posinthim.keyValue[1][1] = 0.25;
			//posinthim.keyValue[1][2] = transhim.translation[2] + dz;
			posinthim.keyValue[1] = val[0].translation;
			
			//trace(posinthim.keyValue);
			
			var d = new Date();
			var t = d.getTime();
			
			
			if(Math.abs(transhim.translation[0] - val[0].translation[0]) > 0.1 || Math.abs(transhim.translation[2] - val[0].translation[2]) > 0.1) timehim.enabled = TRUE;
			
			//tempvec[0] = transhim.translation[0];
			//tempvec[1] = transhim.translation[1];
			//tempvec[2] = transhim.translation[2];
			
			//window.setTimeout('trace(tempvec)', 2000)
			/*
			while(t < d.getTime()+2000)
			{
				tempvec[0] += dx;
				tempvec[2] += dz;
				transhim.set_translation = tempvec;
				var d2 = new Date();
				t = d2.getTime();
				//trace(tempvec);
			}
			*/
		}
	"
}

ROUTE touch.hitPoint_changed TO script1.move
ROUTE time1.cycleTime TO script1.update
ROUTE timeint<?php print 3-$myid ?>.fraction_changed TO posint<?php print 3-$myid ?>.set_fraction
ROUTE posint<?php print 3-$myid ?>.value_changed TO transform<?php print 3-$myid ?>.set_translation
#ROUTE timeint<?php print 3-$myid ?>.cycleTime TO script1.stoptrans
ROUTE timeint<?php print 3-$myid ?>.fraction_changed TO script1.stoptrans

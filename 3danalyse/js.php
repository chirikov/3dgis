<html>
<body style="margin: 0px">

<script language="javascript">
<!--//

var action = "none";
//var map = document.getElementById('map');
//var area = document.getElementById('area');

function mapclick()
{
	var area = document.getElementById('area');
	area.value += event.offsetX + ',' + event.offsetY + '|';
}

//-->
</script>

<img src="photo5.jpg" id="map" onclick="javascript: mapclick();"><br>
<a href="#" id="houseedges" onclick="javascript: if(action == 'none') {action = 'houseedges'; this.innerHTML = 'Готово';} else {document.getElementById('area').value = document.getElementById('area').value.substr(0, document.getElementById('area').value.length - 1) + '@'; action = 'none'; this.innerHTML = 'Углы дома';}">Углы дома</a><br>
<textarea rows=10 cols=40 id="area"></textarea>

</body>
</html>
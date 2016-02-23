
<html>
<body style="margin: 0px">

<img src="../map.png" id="map" onclick="javascript:
var map = document.getElementById('map');
var area = document.getElementById('area');

area.value += (256 - event.x) + ',' + (256 - event.y) + '|';
area.focus();
"><br>
<textarea rows=10 cols=40 id="area"></textarea>

</body>
</html>
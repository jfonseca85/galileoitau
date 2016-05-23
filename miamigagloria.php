<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8 />
<title>GeoJSON from live realtime data</title>
<meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
<script src='mapbox-js/v1.6.2/mapbox.js'></script>
<link href='mapbox-js/v1.6.2/mapbox.css' rel='stylesheet' />
<style>
<style>
  body { margin:0; padding:0; }
  #map { position:absolute; top:0; bottom:0; width:100%; }

    </style>
</style>
</head>
<body>

<div id='map'></div>
<script>

  var map = L.mapbox.map('map');
  L.tileLayer('http://190.128.226.6:8090/bcf_tiles/{z}/{x}/{y}.png', {
   "attribution": "BCF"
    }).addTo(map);

	map.setView(new L.LatLng(-25.286377,-57.5861024), 14);
	

var featureLayer = L.mapbox.featureLayer()
    .loadURL('http://190.128.226.6:8090/GalileoAEX_WAN/GetTracking.php')
    // Once this layer loads, we set a timer to load it again in a few seconds.
    .on('ready', run)
    .addTo(map);

function run() {
    //featureLayer.eachLayer(function(l) {
    //    map.panTo(l.getLatLng());
    //});
    window.setTimeout(function() {
        featureLayer.loadURL('http://190.128.226.6:8090/GalileoAEX_WAN/GetTracking.php');
    }, 10000);
}
</script>

</body>
</html>


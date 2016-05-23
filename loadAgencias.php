<!DOCTYPE html>
<html lang="pt">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <head>
    <title>BCF Solutions S.A. - New EasyAnalysis</title>
    <!-- Leaflet -->
	<link rel="stylesheet" href="leaflet-js/v0.7.7/leaflet.css" />
	<script src="leaflet-js/v0.7.7/leaflet.js"></script>
	<link rel="stylesheet" href="css/menu.css" />
	<link rel="stylesheet" href="css/menuBotones.css" />
    
    <!-- Leaflet Plugins -->
	<link rel="stylesheet" href="css/Control.Zoomslider.css" />
	<script src="plugins/Control.Zoomslider.js" ></script>
	
	<link rel="stylesheet" href="css/Control.MiniMap.css" />
	<script src="plugins/Control.MiniMap.js" type="text/javascript"></script>

	<link rel="stylesheet" href="css/Control.MousePosition.css" />
	<script src="plugins/Control.MousePosition.js" type="text/javascript"></script>
	
	<link rel="stylesheet" href="css/leaflet.fullscreen.css" />
	<script src="plugins/Leaflet.fullscreen.js" type="text/javascript"></script>
	
	<script src="plugins/leaflet.markercluster-src.js"></script> 
	<link rel="stylesheet" href="css/MarkerCluster.css" />
	<link rel="stylesheet" href="css/MarkerCluster.Default.css" />
	
	<script src="plugins/jquery-1.11.1.min.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.min.css">
	<link rel="stylesheet" href="css/jquery-ui.theme.min.css">
	<script src="plugins/jquery-ui.min.js"></script>

		
	<link rel="stylesheet" href="css/BarPPal.css" />
	
	<link href='css/leaflet.draw.css' rel='stylesheet' />
	<script src='plugins/leaflet.draw.js'></script>
	
	<script src="plugins/leaflet.ajax.min.js"></script>
	
	<script src="plugins/Bing.js"></script> 
	
	<link rel="stylesheet" href="css/style.css">
	
	<link rel="stylesheet" href="css/leaflet-gps.css" />
	<link rel="stylesheet" href="css/demos/style.css" />
	<script src="leaflet-js/leaflet-gps.js"></script>
	
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
	
<style>
  
    body { margin:0; padding:0; }
  
	#map { position:absolute; top:0; bottom:0; width:100%; }
  
	.ui-widget {
	font-size: 0.7em;
	font-style: normal;
	}
	
	.container { border:2px solid #ccc; width:200px; height: 100px; overflow-y: scroll; background: #e5f5f9 url('../images/icons/message-boxes/information.png') no-repeat 0.833em center;
	border: 1px solid #cae0e5;
	color: #5a9bab;}
	
</style>

</head>
<body>
<div id='map'></div>

	
  <script src="../leaflet-routing/src/utils/LineUtil.Snapping.js"></script>
  <script src="../leaflet-routing/src/utils/Marker.Snapping.js"></script>
  <script src="../leaflet-routing/src/L.Routing.js"></script>
  <script src="../leaflet-routing/src/L.Routing.Storage.js"></script>
  <script src="../leaflet-routing/src/L.Routing.Draw.js"></script>
  <script src="../leaflet-routing/src/L.Routing.Edit.js"></script>
  <script src="http://tyrasd.github.io/osmtogeojson/osmtogeojson.js"></script>

<script>			
		function onEachFeature(feature, layer) {
		// does this feature have a property named popupContent?
			if (feature.properties && feature.properties.name) {
			layer.bindPopup(feature.properties.name);
			}
		}
	    function getColorBarrio(d) {
        return d > 9500000 ? '#0000FF' :
               d > 8000000 ? '#5959FF' :
               d > 6500000 ? '#7A7AFF' :
               d > 5000000 ? '#9191FF' :
               d > 3500000 ? '#A3A3FF' :
               d > 2500000 ? '#AFAFFF' :
               d > 1000000 ? '#BFBFFF' :
        		    '#D8D8FF';
    	}
			
	   function styleBarrio(feature) {
        	return {
            	weight: 2,
            	opacity: 1,
            	color: 'white',
            	dashArray: '3',
            	fillOpacity: 0.7,
            	fillColor: getColorBarrio(feature.properties.name)
        	};
    	}
		
		function highlightFeature(e) {
		
			var layer = e.target;
			layer.setStyle({
				fillColor: '#0000FF',
				dashArray: 'BUjaru Tiler',
				fillOpacity: 0.7
			});

			if (!L.Browser.ie && !L.Browser.opera) {
				layer.bringToFront();
			}
			
			info.update(layer.feature.properties);
		}
		
		function resetHighlightESTADOS(e) {
			ZonaESTADOS.resetStyle(e.target);
			info.update();
		}
		
		function zoomToFeature(e) {
			map.fitBounds(e.target.getBounds());
		}
		
		var EntregasAEX;
		
		var MapaCycle = new L.TileLayer('http://{s}.tile2.opencyclemap.org/transport/{z}/{x}/{y}.png');	//base layer
		
		var maptiler = new google.maps.ImageMapType({
			getTileUrl: function(coord, zoom) { 
			return zoom + "/" + coord.x + "/" + (Math.pow(2,zoom)-coord.y-1) + ".jpg";
			},
			tileSize: new google.maps.Size(256, 256),
			isPng: false
		});
		
		var MapaBingHybrid = new L.BingLayer("AvcpKC4awcn042na3LJ5vIzVQs5xSw1iykahgh7JiA93oVrjSeClwf_k71367Aez", {type: "AerialWithLabels", maxZoom: 18, minZoom: 1, detectRetina: true});
		
		var MapaBing = new L.BingLayer("AvcpKC4awcn042na3LJ5vIzVQs5xSw1iykahgh7JiA93oVrjSeClwf_k71367Aez", {type: "Aerial", maxZoom: 18, minZoom: 1, detectRetina: true});
	
		
		var lat = -23.554700;
		var lon = -46.634438;
		var osmAttrib = '(c) OpenStreetMap contributors'
		TileSvrURL = 'http://187.108.192.76:8089/tiles/{z}/{x}/{y}.png'
		inicialZoom = 14;
		var IconAgenciasITAU = L.icon({
			iconUrl: 	'images/itauPoi.png',
			iconSize: 	[26, 38], // size of the icon
			iconAnchor: [25, 25], // point of the icon which will correspond to marker's location
			shadowUrl: 	'images/marker-shadow.png'
		});
	
		var MapaBase  = new L.tileLayer(TileSvrURL, {
			maxZoom: 18,
			minZoom: 3,
			attribution: osmAttrib
        });		
		
		
	   function httpGet(httpURL)
		{
			var xmlHttp = null;

			xmlHttp = new XMLHttpRequest();
			xmlHttp.open( "GET", httpURL, false );
			xmlHttp.send( null );
			return xmlHttp.responseText;
		}
		
		var map = L.map('map',{
			zoomsliderControl: false,
			zoomControl: true,
			center: [lat,lon], 
			zoom: inicialZoom,
			layers: [MapaBase]
		});	
		
			// Snapping Layer
			snapping = new L.geoJson(null, {
			  style: {
				opacity:0
				,clickable:true
			  }
			}).addTo(map);

		
		 // OSM Router
			router = function(m1, m2, cb) {
			var proxy = 'http://www2.turistforeningen.no/routing.php?url=';
			var route = 'http://www.yournavigation.org/api/1.0/gosmore.php&format=geojson&v=foot&fast=1&layer=mapnik';
			var params = '&flat=' + m1.lat + '&flon=' + m1.lng + '&tlat=' + m2.lat + '&tlon=' + m2.lng;
			$.getJSON(proxy + route + params, function(geojson, status) {
				if (!geojson || !geojson.coordinates || geojson.coordinates.length === 0) {
				  if (typeof console.log === 'function') {
					console.log('OSM router failed', geojson);
				  }
				  return cb(new Error());
				}
				return cb(null, L.GeoJSON.geometryToLayer(geojson));
			  });
			}
			 routing = new L.Routing({
			  position: 'topleft'
			  ,routing: {
				router: router
			  }
			  ,snapping: {
				layers: []
			  }
			  ,snapping: {
				layers: [snapping]
				,sensitivity: 15
				,vertexonly: false
			  }
			});
		map.addControl(routing);
		routing.draw()
		
		map.addControl( new L.Control.Gps({autoActive: true, autoTracking: true}) );//inizialize control
		
		var ToolbarGroup = L.featureGroup().addTo(map);
		
		var drawControl = new L.Control.Draw({
		edit: {
		featureGroup: ToolbarGroup
		}
		}).addTo(map);

		map.on('draw:created', function(e) {
			ToolbarGroup.addLayer(e.layer);
		}); // ---- BARA DE HERRAMIENTAS 
		
		var AgenciasITAUGeoJSON = JSON.parse(httpGet("http://localhost/GalileoITAU/GetAgencia.php?DWTPPOI=500"));
		var AgenciasITAU = L.geoJson(AgenciasITAUGeoJSON, {
			style: function (feature) {
				return {color: feature.properties.color};
			},
			pointToLayer: function (feature, latlng) {
				return L.marker(latlng, {icon: IconAgenciasITAU});
			},
			onEachFeature: function (feature, layer) {
				var popupText = '<a target="_blank" href="' + 'https://www.itau.com.br/' + '">' +
								'<img src="' + feature.properties.icon + ' "width="48" height="64" align="middle">' +
								'</a>' +
								'<h4>Nome: ' + feature.properties.name + '</h4>' +
								'<h5>Dir: ' + feature.properties.desc + '</h5>' +
								'<h5>Tel: ' + feature.properties.tel + '</h5>' +
								'<h5>eMail: ' + feature.properties.email + '</h5>' +
								'<h5>Date: ' + feature.properties.date + '</h5>' +
								'<h5>Lat: ' + feature.properties.lat + '</h5>' + 
								'<h5>Long: ' + feature.properties.long + '</h5>' 
								//'<h5>IMGs: ' + feature.properties.images + '</h5>' 
				layer.bindPopup(popupText);
			}
		});		
		
		var AgenciasITAUAgrupadas = L.markerClusterGroup({ spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: false });
		//var OcurrenciasITAUAgrupadas = L.markerClusterGroup();
		// COMENTAR LA SIGUIENTE FUNCION PARA DESACTIVAR EL SPIDER DE POIS E INTERCAMBIAR LAS DOS FUNCIONES DE ARRIBA
		AgenciasITAUAgrupadas.on('clusterclick', function (a) {
			a.layer.spiderfy();
		});
		
		AgenciasITAUAgrupadas.addLayer(AgenciasITAU);
		
		var baseMaps = {"Cartografico": MapaBase, "Satelite": MapaBing, "Hybrido": MapaBingHybrid, "Cycle": MapaCycle };
		
		var rota = {"Routing": routing};
		
		// ------- AGREGO FUNCION PARA VER LAT Y LONG CON EL MOVIEMIENTO DEL MOUSE
		L.control.mousePosition({
			position: 'bottomleft',
			separator: ' : ',
			prefix: 'Latitude | Longitude: '
		}).addTo(map);
		// --------- FIN ------ AGREGO FUNCION PARA VER LAT Y LONG CON EL MOVIEMIENTO DEL MOUSE
		
		
		var ESTADOS = JSON.parse(httpGet("http://localhost/GeoJSON/Cidades.json"));
		L.geoJson(ESTADOS, {
         onEachFeature: onEachFeature}).addTo(map);

		var overlayMaps = {
			"Agencias ITAU (Single)": AgenciasITAU,
			"Agencias ITAU (Group)": AgenciasITAUAgrupadas
		};
		
		L.control.fullscreen().addTo(map);
		L.control.scale().addTo(map);
		L.control.layers(baseMaps, overlayMaps).addTo(map)
		//map.addLayer(AgenciasITAUAgrupadas);
		
		//MINI MAPA -- AQUI SE COLOCA EL CODIGO DEL MINI MAPA 
		var osm2 = new L.TileLayer(TileSvrURL, {minZoom: 2, maxZoom: 18, attribution: osmAttrib });
		var miniMap = new L.Control.MiniMap(osm2, {toggleDisplay: true}).addTo(map);
 
</script>

</body>
</html>
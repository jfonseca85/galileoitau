<?php
        //print("Conectando");
	# Connect to PostgreSQL database
	$conn = pg_connect("dbname='ITAU_BR' user='osmuser' password='osmuser' host='192.168.1.21'");
	if (!$conn) {
		echo "Not connected : " . pg_error();
	exit;
	}
	
	//print("Conectado");
	# Build SQL SELECT statement and return the geometry as a GeoJSON element in EPSG: 4326
	$sql = "SELECT DWCDCLIE,
			trunc(DWCDCOGX,8) as DWCDCOGX,
			trunc(DWCDCOGY,8) as DWCDCOGY,
			DWTPPOI,
			DWDSINDI,
			DWCXTEL0,
			DWCXEMAI,
			DWDSINFO,
			DWDSUSR0,
			DWDSUSR1,
			DWDSUSR2,
			DWDSUSR3,
			DWDSUSR4,
			DWDSUSR5,
			DWFLUSR0,
			DWFLUSR1,
			DWCXURL,
			DWCXCLA1,
			DWCXUSR5,
			DWCXCLA0 
			FROM DWATPDI 
			WHERE DWTPPOI = '100'
			";
			
			//WHERE DWTPPOI = '3599340' AND (DWFLUSR1 = '0' OR DWFLUSR1 = ' ' OR DWFLUSR1 IS NULL) AND (DWDSUSR3 > '2014-05-27' AND DWDSUSR3 < '2014-05-28')

	//echo $sql;
 
	# Try query or error
	$rs = pg_query($conn, $sql);
	if (!$rs) {
		//echo "An SQL error occured.\n";
	//exit;
	}
 
	$geojson = array( 
		'type' => 'FeatureCollection', 
		'features' => array()
	);
 
	while ($row = pg_fetch_array($rs, null, PGSQL_ASSOC+PGSQL_RETURN_NULLS)) {
		$marker = array(
                    'type' => 'Feature',
                    'features' => array(
                        'type' => 'Feature',
                        'properties' => array(
                            'name' => "".$row['dwdsusr0']."",
							'dir'	=> "".$row['dwdsinfo']."",
							'tel'	=> "".$row['dwdsusr2']."",
							'email'	=> "".$row['dwdsusr4']."",
							'long'	=> "".$row['dwcdcogy']."",
							'lat'	=> "".$row['dwcdcogx']."",
							'date'	=> "".$row['dwdsusr3']."",
							'images'	=> "".$row['dwcxurl']."",
							'icon'	=>	'images/itau3_small'							
                            //'marker-color' => '#f00',
                            //'marker-size' => 'small'
                            //'url' => 
                            ),
                        "geometry" => array(
                            'type' => 'Point',
                            'coordinates' => array( 
                                            $row['dwcdcogx'],
                                            $row['dwcdcogy']
                            )
                        )
                    )
        );
		array_push($geojson['features'], $marker['features']);
	}

	//Liberamos la memoria (no creo que sea necesario con consultas tan simples)
	pg_free_result($res);
	//Cerramos la conexión
	pg_close($conn);
	
	//print (json_encode($geojson, JSON_NUMERIC_CHECK));	

?>

<?php
        //print("Conectando");
	# Connect to PostgreSQL database
	$conn = pg_connect("dbname='ITAU_BR' user='osmuser' password='osmuser' host='192.168.1.21'");
	if (!$conn) {
		echo "Not connected : " . pg_error();
	exit;
	}
	
	//print("Conectado");
	# Build SQL SELECT statement and return the geometry as a GeoJSON element in EPSG: 4326
	$sql = "SELECT DWCDCLIE,
			trunc(DWCDCOGX,8) as DWCDCOGX,
			trunc(DWCDCOGY,8) as DWCDCOGY,
			DWDSPRES,
			DWDSESPN,
			DWTPPOI,
			DWDSINDI,
			DWCXTEL0,
			DWCXEMAI,
			DWDSINFO,
			DWDSUSR0,
			DWDSUSR1,
			DWDSUSR2,
			DWDSUSR3,
			DWDSUSR4,
			DWDSUSR5,
			DWFLUSR0,
			DWFLUSR1,
			DWCXUSR5,
			DWCXURL,
			DWCXCLA1,
			DWCXCLA0 
			FROM DWATPDI 
			WHERE DWTPPOI = '115'
			";
			
			//WHERE DWTPPOI = '3599340' AND (DWFLUSR1 = '0' OR DWFLUSR1 = ' ' OR DWFLUSR1 IS NULL) AND (DWDSUSR3 > '2014-05-27' AND DWDSUSR3 < '2014-05-28')

	//echo $sql;
 
	# Try query or error
	$rs = pg_query($conn, $sql);
	if (!$rs) {
		//echo "An SQL error occured.\n";
	//exit;
	}
 
	$geojsonOcurrencia = array( 
		'type' => 'FeatureCollection', 
		'features' => array()
	);
 
	while ($row = pg_fetch_array($rs, null, PGSQL_ASSOC+PGSQL_RETURN_NULLS)) {
		$markerOcurrencia = array(
                    'type' => 'Feature',
                    'features' => array(
                        'type' => 'Feature',
                        'properties' => array(
							'sendby' => "".$row['dwcxusr5']."",
						    'clase' => "".$row['dwdspres']."",
							'tipo'	=> "".$row['dwdswspn']."",
							'desc'	=> "".$row['dwdsinfo']."",
							'long'	=> "".$row['dwcdcogy']."",
							'lat'	=> "".$row['dwcdcogx']."",
							'date'	=> "".$row['dwdsusr3']."",
							'images'	=> "".$row['dwcxurl']."",
							'icon'	=>	'images/itau3_small'							
                            //'marker-color' => '#f00',
                            //'marker-size' => 'small'
                            //'url' => 
                            ),
                        "geometry" => array(
                            'type' => 'Point',
                            'coordinates' => array( 
                                            $row['dwcdcogx'],
                                            $row['dwcdcogy']
                            )
                        )
                    )
        );
		array_push($geojsonOcurrencia['features'], $markerOcurrencia['features']);
	}

	//Liberamos la memoria (no creo que sea necesario con consultas tan simples)
	pg_free_result($res);
	//Cerramos la conexión
	pg_close($conn);
	
	//print (json_encode($geojson, JSON_NUMERIC_CHECK));	

?>

<!DOCTYPE html>
<html lang="pt">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <head>
    <title>BCF Solutions S.A. - Galileo v2.0</title>

    <!-- Leaflet -->
	<link rel="stylesheet" href="leaflet-js/v7.3/leaflet.css" />
	<script src="leaflet-js/v7.3/leaflet.js"></script>
	<link rel="stylesheet" href="css/menu.css" />
    
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
	
	<script src="plugins/jquery-1.11.0.min.js"></script>
	
	<link rel="stylesheet" href="css/BarPPal.css" />
	
	<link href='css/leaflet.draw.css' rel='stylesheet' />
	<script src='plugins/leaflet.draw.js'></script>
	
	<script language="JavaScript" src="plugins/ts_picker.js"></script>
	<script src="plugins/leaflet.ajax.min.js"></script>
	
<style>
  body { margin:0; padding:0; }
  #map { position:absolute; top:0; bottom:0; width:100%; }

</style>

</head>
 <body onload="initialize()" >

<div id='map'></div>
<script>

timeElapsed = 0;


function onEachFeature(feature, layer) {
		if (feature.properties && feature.properties.popupContent) {
    	    layer.bindPopup('<a target="_blank" href="' + feature.properties.url + '">' +
                            '<img src="' + feature.properties.image + '"width="128" height="128">' +
                    	    '<h2>' + feature.properties.name + '</h2>' +
                    	    '</a>'
			  ,{closeButton: true,
			    minWidth: 135}
			);
		}
    }
	

function initialize() {
        
		var EntregasAEX;
		
		var lat = -23.554700;
		var lon = -46.634438;
		var osmAttrib = 'Galileo ITAU Tack v2.0'
		TileSvrURL = 'http://200.42.109.109/bcf_tiles/{z}/{x}/{y}.png'
		inicialZoom = 12;
		
		var MapaBase  = new L.tileLayer(TileSvrURL, {
			maxZoom: 18,
			minZoom: 1,
			attribution: osmAttrib
        });	
		
		var map = L.map('map',{
			zoomsliderControl: true,
			zoomControl: false,
			center: [lat,lon], 
			zoom: inicialZoom,
			layers: [MapaBase]
		});	
		

		
		var IconLocatorOrange = L.icon({
			iconUrl: 'images/icons/locator-orange.png',
			shadowUrl: 'images/marker-shadow'

			//iconSize:     [38, 95], // size of the icon
			//shadowSize:   [50, 64], // size of the shadow
			//iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
			//shadowAnchor: [4, 62],  // the same for the shadow
			//popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
		});
		
		var IconOcurrenciaRed = L.icon({
			iconUrl: 'images/icons/caution-red.png',
			shadowUrl: 'images/marker-shadow'

			//iconSize:     [38, 95], // size of the icon
			//shadowSize:   [50, 64], // size of the shadow
			//iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
			//shadowAnchor: [4, 62],  // the same for the shadow
			//popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
		});		
		
		 
		
		var IconCadastroBlue = L.icon({
			iconUrl: 'images/icons/cadastro-blue.png',
			shadowUrl: 'images/marker-shadow'

			//iconSize:     [38, 95], // size of the icon
			//shadowSize:   [50, 64], // size of the shadow
			//iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
			//shadowAnchor: [4, 62],  // the same for the shadow
			//popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
		});
		

		var markers = new L.layerGroup();
		
		function loadPois() {
		elapsedTime = new Date().getMilliseconds();
		
		var bBoxSW = map.getBounds().getSouthWest();
		var bBoxNE = map.getBounds().getNorthEast();
		console.log("current bounding box: SouthWest " + bBoxSW + ", NorthEast: " + bBoxNE);
		console.log("BUSCANDO POIS EN EL BOUNDINBOX ACTUAL ...");
		var requestString = "http://200.42.109.109/GalileoITAU/GetTracking.php";
		console.log("request URL: " + requestString);
		$.getJSON(requestString,
			function(pois) {
				console.log(pois.features.length + " ENCONTRADOS PARA ACTUALIZAR POSICION!");
				console.log("checking for POI layer from previous bounding box...");
				if (map.hasLayer(markers)) {
					console.log("previous POI layer found!");
					console.log("removing previous POI layer...");
					//markers.clearLayers(); // better redeclare variable 'markers', see next two lines
					map.removeLayer(markers);
					markers = new L.layerGroup();
					console.log("previous POI layer removed!");
				} else {
					console.log("no previous POI layer found!"); //only happens on initial pois load
				}
				console.log("creating clustered POI layer...");
				for (var i in pois.features) {
					var popupText = '<a target="_blank" href="' + 'http://www.itau.com.br' + '">' +
									'<img src="' + pois.features[i].properties.icon + ' "width="48" height="64" align="middle">' +
									'</a>' +
									'<h4>User Name: ' + pois.features[i].properties.UserName + '</h4>' +
									'<h5>User ID: ' + pois.features[i].properties.id + '</h4>' +
									'<h5>Last Contact: ' + pois.features[i].properties.time + '</h5>' +
									'<h5>Lat: ' + pois.features[i].properties.lat + '</h5>' +
									'<h5>Long: ' + pois.features[i].properties.long + '</h5>' 
									//if (feature.properties.color) {
										//	popupText += '<br/>color: ' + feature.properties.color
									//}
				
				
					var marker = new L.Marker(new L.LatLng(pois.features[i].geometry.coordinates[1], pois.features[i].geometry.coordinates[0]), {icon: IconLocatorOrange});
					marker.bindPopup(popupText);
					markers.addLayer(marker);
				}
				console.log("clustered POI layer created!");
				console.log("adding clustered POI layer to map...");
				map.addLayer(markers);
				console.log("clustered POI layer added to map!");
				
				elapsedTime = new Date().getMilliseconds() - elapsedTime;
				setTimeout(loadPois, 15000 - elapsedTime);
			}
		)
		.fail(function(e) {
			console.debug("error while fetching POIs:\n" + e.responseText);
		});
		}
		
		
		var CadastroITAGeoJSON = <?php print json_encode($geojson,JSON_NUMERIC_CHECK); ?>;
		var CadastroITAU = L.geoJson(CadastroITAGeoJSON, {
			style: function (feature) {
			
				return {color: feature.properties.color};
			},
			pointToLayer: function (feature, latlng) {
				return L.marker(latlng, {icon: IconCadastroBlue});
			},
			onEachFeature: function (feature, layer) {
		
				var popupText = '<a target="_blank" href="' + 'https://www.itau.com.br/' + '">' +
								'<img src="' + feature.properties.icon + ' "width="48" height="64" align="middle">' +
								'</a>' +
								'<h4>Nome: ' + feature.properties.name + '</h4>' +
								'<h5>Dir: ' + feature.properties.dir + '</h5>' +
								'<h5>Tel: ' + feature.properties.tel + '</h5>' +
								'<h5>eMail: ' + feature.properties.email + '</h5>' +
								'<h5>Date: ' + feature.properties.date + '</h5>' +
								'<h5>Lat: ' + feature.properties.lat + '</h5>' + 
								'<h5>Long: ' + feature.properties.long + '</h5>' 
								//'<h5>IMGs: ' + feature.properties.images + '</h5>' 
				var IMGs	= feature.properties.images.split("|")				
				for (var i in IMGs)
						{
						popupText = popupText + '<a target="_blank" href="' + 'http://200.42.109.109/ITAU_IMGs/'+ IMGs[i] + '">' + 
						'<img src="' + 'http://200.42.109.109/ITAU_IMGs/'+ IMGs[i] + ' "width="128" height="96" align="middle" border="1">'
						}
				layer.bindPopup(popupText);
			}
		});
		
		
		var OcurrenciaITAGeoJSON = <?php print json_encode($geojsonOcurrencia,JSON_NUMERIC_CHECK); ?>;
		var OcurrenciaITAU = L.geoJson(OcurrenciaITAGeoJSON, {
			style: function (feature) {
			
				return {color: feature.properties.color};
			},
			pointToLayer: function (feature, latlng) {
				return L.marker(latlng, {icon: IconOcurrenciaRed});
			},
			onEachFeature: function (feature, layer) {
		
				var popupText = '<a target="_blank" href="' + 'https://www.itau.com.br/' + '">' +
								'<img src="' + feature.properties.icon + ' "width="48" height="64" align="middle">' +
								'</a>' +
								'<h4>Send By: ' + feature.properties.sendby + '</h4>' +
								'<h5>Clase: ' + feature.properties.clase + '</h5>' +
								'<h5>Desc.: ' + feature.properties.desc + '</h5>' +
								'<h5>Date: ' + feature.properties.date + '</h5>' +
								'<h5>Lat: ' + feature.properties.lat + '</h5>' + 
								'<h5>Long: ' + feature.properties.long + '</h5>' 
								//'<h5>IMGs: ' + feature.properties.images + '</h5>' 
				var IMGs	= feature.properties.images.split("|")				
				for (var i in IMGs)
						{
						popupText = popupText + '<a target="_blank" href="' + 'http://200.42.109.109/ITAU_IMGs/'+ IMGs[i] + '">' + 
						'<img src="' + 'http://200.42.109.109/ITAU_IMGs/'+ IMGs[i] + ' "width="128" height="96" align="middle" border="1">'
						}
				layer.bindPopup(popupText);
			}
		});
		
		
		var CadastroITAUAgrupadas = L.markerClusterGroup({ spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: false });
		//var CadastroITAUAgrupadas = L.markerClusterGroup();
		// COMENTAR LA SIGUIENTE FUNCION PARA DESACTIVAR EL SPIDER DE POIS E INTERCAMBIAR LAS DOS FUNCIONES DE ARRIBA
		CadastroITAUAgrupadas.on('clusterclick', function (a) {
			a.layer.spiderfy();
		});
		
		CadastroITAUAgrupadas.addLayer(CadastroITAU);
		
		var OcurrenciasITAUAgrupadas = L.markerClusterGroup({ spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: false });
		//var OcurrenciasITAUAgrupadas = L.markerClusterGroup();
		// COMENTAR LA SIGUIENTE FUNCION PARA DESACTIVAR EL SPIDER DE POIS E INTERCAMBIAR LAS DOS FUNCIONES DE ARRIBA
		OcurrenciasITAUAgrupadas.on('clusterclick', function (a) {
			a.layer.spiderfy();
		});
		
		OcurrenciasITAUAgrupadas.addLayer(OcurrenciaITAU);
		
				
		var baseMaps = {"Mapa Inteactivo": MapaBase};
		
		var overlayMaps = {
			"Cadastro (Single)": CadastroITAU,
			"Cadastro (Group)": CadastroITAUAgrupadas,
			"Ocurrencias (Single)": OcurrenciaITAU,
			"Ocurrencias (Group)": OcurrenciasITAUAgrupadas
		};

		console.log("LLAMO A LA FUNCION DE TRACKING POIS");
		loadPois();
		
		L.control.fullscreen().addTo(map);
		
		L.control.layers(baseMaps, overlayMaps).addTo(map)
		
		//MINI MAPA -- AQUI SE COLOCA EL CODIGO DEL MINI MAPA 
		var osm2 = new L.TileLayer(TileSvrURL, {minZoom: 2, maxZoom: 18, attribution: osmAttrib });
		var miniMap = new L.Control.MiniMap(osm2, {toggleDisplay: true}).addTo(map);
		
	
	
}

</script>

</body>
</html>


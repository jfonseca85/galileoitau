<?php
    # DEFINO LAS VARIALES DE CONEXION
	define('DB_HOST','10.0.0.200');
	define('DB_PORT','5432');
	define('DB_NAME','AEX_PY');
	define('DB_USER','osmuser');
	define('DB_PASS','osmuser');
		
	$conn_string = "host=" . DB_HOST ." port=".DB_PORT ." dbname=".DB_NAME ." user=". DB_USER ." password=" . DB_PASS;
	$conn = pg_connect($conn_string);
	if (!$conn) {
		echo "Not connected : " . pg_error();
	exit;
	}
	
	//print("Conectado");
	# Build SQL SELECT statement and return the geometry as a GeoJSON element in EPSG: 4326
	$sql = "SELECT * FROM DWAEXMON";

 
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
                            'title' => "".$row['dwdsrags']."",
							'dir'	=> "".$row['dwdsindi']."",
							'estado'	=> "".$row['dwdscomu']."",
							'barrio'	=> "".$row['dwdsloca']."",
							'color'	=>	'blue',
							'icon'	=>	'../NewGeos/images/icons/itau_logo_big.png'							
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
	
	print (json_encode($geojson, JSON_NUMERIC_CHECK));

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
	
	<link rel="stylesheet" href="css/style.css">
    
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

    <style>
		body {
			padding: 0;
			margin: 0;
			background-color: #EBE8E4;
			color: #222;
			font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
			font-weight: 300;
			font-size: 15px;
		}

		html, body, #map {
			height: 98%;
		}	
	
		.info {
			padding: 6px 8px;
			font: 14px/16px Arial, Helvetica, sans-serif;
			background: white;
			background: rgba(255,255,255,0.75);
			box-shadow: 0 0 15px rgba(0,0,0,0.2);
			border-radius: 5px;
			border:1px solid #fff;
		}
	
		.info h4 {
			margin: 0 0 5px;
			color: #065581;
		}

		.legend {
			text-align: left;
			line-height: 18px;
			color: #555;
		}
	
		.legend i {
			width: 18px;
			height: 18px;
			float: left;
			margin-right: 8px;
			opacity: 0.7;
		}

    </style>

    <script>

    function getColor(d) {
        return d > 9500000 ? '#800026' :
               d > 8000000 ? '#BD0026' :
               d > 6500000 ? '#E31A1C' :
               d > 5000000 ? '#FC4E2A' :
               d > 3500000 ? '#FD8D3C' :
               d > 2500000 ? '#FEB24C' :
               d > 1000000 ? '#FED976' :
        		    '#FFEDA0';
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
            	fillColor: getColorBarrio(feature.properties.AREA)
        	};
    	}

    function style(feature) {
        	return {
            	weight: 2,
            	opacity: 1,
            	color: 'white',
            	dashArray: '3',
            	fillOpacity: 0.7,
            	fillColor: getColor(feature.properties.AREA)
        	};
    	}

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
        
		var lat = -25.309115;
		var lon = -57.552223;
		var osmAttrib = 'Galileo v2.0'
		TileSvrURL = 'http://190.128.226.6:8090/bcf_tiles/{z}/{x}/{y}.png'
		inicialZoom = 13;

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
		
		// ---- BARA DE HERRAMIENTAS 
		var ToolbarGroup = L.featureGroup().addTo(map);
		
		var drawControl = new L.Control.Draw({
		edit: {
		featureGroup: ToolbarGroup
		}
		}).addTo(map);

		  map.on('draw:created', function(e) {
			ToolbarGroup.addLayer(e.layer);
		}); // ---- BARA DE HERRAMIENTAS 
		
		// ------- AGREGO FUNCION PARA VER LAT Y LONG CON EL MOVIEMIENTO DEL MOUSE
		L.control.mousePosition({
			position: 'bottomleft',
			separator: ' : ',
			prefix: 'Latitude | Longitude: '
		}).addTo(map);
		// --------- FIN ------ AGREGO FUNCION PARA VER LAT Y LONG CON EL MOVIEMIENTO DEL MOUSE

		// ------- FUNCION PARA AGREGAR EL LAYER INFO  
		var info = L.control({position: 'bottomleft'});

		info.onAdd = function (map) {
			this._div = L.DomUtil.create('div', 'info');
			this.update();
			return this._div;
		};

		info.update = function (props) {
			this._div.innerHTML = '<img src="../NewGeos/images/information_icon.png">' +  (props ?
			'<br /><b>REGION: </b>' + props.dwdsregi +  
			'<br /><b>ESTADO: </b>' + props.dwdsprov 
			: '');
		};
	
		info.addTo(map);
		// ----- FIN --- FUNCION PARA AGREGAR EL LAYER INFO

		function highlightFeature(e) {
		
			var layer = e.target;
			layer.setStyle({
				fillColor: '#78A700',
				dashArray: '',
				fillOpacity: 0.7
			});

			if (!L.Browser.ie && !L.Browser.opera) {
				layer.bringToFront();
			}
			
			info.update(layer.feature.properties);
		}

		var ZonaREGIONES;
		var ZonaESTADOS;
		var ZonaESTADO_SAO_PAULO;
		var SucursalesITAU;

		function resetHighlightREGIONES(e) {
			ZonaREGIONES.resetStyle(e.target);
			info.update();
		}

		function resetHighlightESTADOS(e) {
			ZonaESTADOS.resetStyle(e.target);
			info.update();
		}

		function zoomToFeature(e) {
			map.fitBounds(e.target.getBounds());
		}

		function onEachFeatureREGIONES(feature, layer) {
			layer.on({
			mouseover: highlightFeature,
			mouseout: resetHighlightREGIONES,
			click: zoomToFeature
			});
		}

		function onEachFeatureESTADOS(feature, layer) {
			layer.on({
			mouseover: highlightFeature,
			mouseout: resetHighlightESTADOS,
			click: zoomToFeature
			});
	   }

		var RegionesBrazil = new L.LayerGroup();
		$.getJSON("../NewGeos/GeoJSON/REGIONES_BR_V2.js", function (REGIONES) {
		//alert("REGIONES Loaded");
			ZonaREGIONES = new L.geoJson(REGIONES, {
				style: style,
					onEachFeature: onEachFeatureREGIONES
			}).addTo(RegionesBrazil);
		});

		var EstadosBrazil = new L.LayerGroup();
		$.getJSON("../NewGeos/GeoJSON/ESTADOS_ALL_V2.js", function (ESTADOS) {
		//alert("GeoJSON Loaded");
			ZonaESTADOS = new L.geoJson(ESTADOS, {
				style: styleBarrio,
					onEachFeature: onEachFeatureESTADOS
			}).addTo(EstadosBrazil);
		});
		
		var EstadoSaoPaulo = new L.LayerGroup();
		$.getJSON("../NewGeos/GeoJSON/ESTADOS/SAO_PAULO_V1.js", function (ZonaESTADO_SAO_PAULO) {
		//alert("GeoJSON Loaded");
			ZonaESTADOS = new L.geoJson(ZonaESTADO_SAO_PAULO, {
				style: styleBarrio,
					onEachFeature: onEachFeatureESTADOS
			}).addTo(EstadoSaoPaulo);
		});
		
		var SucursalesITAUGroup = new L.LayerGroup();
		var SucursalesITAUGeoJSON = <?php print json_encode($geojson,JSON_NUMERIC_CHECK); ?>;
		var SucursalesITAU = L.geoJson(SucursalesITAUGeoJSON, {
			style: function (feature) {
				return {color: feature.properties.color};
			},
			onEachFeature: function (feature, layer) {
				var popupText = '<a target="_blank" href="' + 'https://www.itau.com.br/' + '">' +
								'<img src="' + feature.properties.icon + ' "width="64" height="64" align="middle">' +
								'</a>' +
								'<h4>Nombre: ' + feature.properties.title + '</h4>' +
								'<h5>Dir: ' + feature.properties.dir + '</h5>' +
								'<h5>Estado: ' + feature.properties.estado + '</h5>' +
								'<h5>Barrio: ' + feature.properties.barrio + '</h5>' 
								//if (feature.properties.color) {
									//	popupText += '<br/>color: ' + feature.properties.color
								//}
				layer.bindPopup(popupText);
			}
		});
		
		var SucursalesITAUOverlay = L.markerClusterGroup({ spiderfyOnMaxZoom: false, showCoverageOnHover: false, zoomToBoundsOnClick: false });
		// var SucursalesITAUOverlay = L.markerClusterGroup();
		// COMENTAR LA SIGUIENTE FUNCION PARA DESACTIVAR EL SPIDER DE POIS E INTERCAMBIAR LAS DOS FUNCIONES DE ARRIBA
		SucursalesITAUOverlay.on('clusterclick', function (a) {
			a.layer.spiderfy();
		});
		
		SucursalesITAUOverlay.addLayer(SucursalesITAU);
		
		var baseMaps = {"Mapa Inteactivo": MapaBase};

		var overlayMaps = {
			"Regiones": RegionesBrazil,
			"Estados (All)": EstadosBrazil,
			"Estado - Sao Paulo": EstadoSaoPaulo,
			"Agencias ITAU": SucursalesITAUOverlay
		};

		L.control.fullscreen().addTo(map);
		
		L.control.layers(baseMaps, overlayMaps).addTo(map);

		//MINI MAPA -- AQUI SE COLOCA EL CODIGO DEL MINI MAPA 
		var osm2 = new L.TileLayer(TileSvrURL, {minZoom: 2, maxZoom: 18, attribution: osmAttrib });
		var miniMap = new L.Control.MiniMap(osm2, {toggleDisplay: true}).addTo(map);
		//var miniMap = new L.Control.MiniMap(osm2, {zoomLevelFixed: 5, autoToggleDisplay: true}).addTo(map); 

	}
	
    </script>
  </head>
  <body onload="initialize()" >
 <!--    <div id="map" style="width: 800px; height: 600px;"></div>   -->

	<form name="tstest" method="post">
	<input type="Text" name="timestamp" value="">
	<a href="javascript:show_calendar('document.tstest.timestamp', document.tstest.timestamp.value);">
	<img src="images/cal.gif" width="16" height="16" border="0" alt="Desde...:" > 
	</a>
	<div class="information-box round"><select><option id=1>English</option><option id=2>Italiano</option><option id=3>Portugues</option><option id=4>Castellano</option></select></div>
	</form>

	<div id ="nav">
		<nav>
		<ul>
			<li><a href="index.php">Logout</a></li>
			<li>
				<a>Seguimiento Courier<span class="caret"></span></a>
				<div>
					<ul>
						<li><a href="products.html#chair">Entregas</a></li>
						<li><a href="products.html#table">Ruta realizada</a></li>
						<li><a href="products.html#table">Traking ON LINE</a></li>
					</ul>
				</div>
			</li>
			<li><a href="about.html">About</a></li>
		</ul>
		</nav>

	</div>
	<!-- MAP DIVISION -->
 	<div id="map"></div>
	
  </body>
</html>
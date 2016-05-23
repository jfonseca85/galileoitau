<?php

	// Inialize session
	session_start();
	include_once "conexion.php";
	
	// Check, if username session is NOT set then this page will jump to login page
	if (!isset($_SESSION['username'])) {
		header('Location: index.php');
	}
	else
	{ 
		$sql="SELECT dwtppoi, dwdsinfo FROM dwitaumon ORDER BY dwdsinfo";
		//echo "se ejecuta el SQL";
		$query=pg_query($sql);
		//$select= '<div class="confirmation-box round dark"><label for="TipoPOI">Usuario Habilitados: </label><select name="TipoPOI" id="TipoPOI"><optgroup label="Haga su seleccion"><option value="16">Opcion 1</option><option value="2">Opcion 2</option><option value="3">Opcion 3</option></optgroup></select></div>'  
		$select= '<div class="confirmation-box round dark"><label for="TipoPOI">Show User: </label><select multiple SIZE=5 name="TipoPOI" id="TipoPOI"><option selected value="9999999999">Show All</option>';
		if(pg_num_rows($query) > 0)
		{
			while($rs=pg_fetch_row($query)){
				$select.='<option value="'.$rs[0].'">'.$rs[1].'</option>'; 
				$CheckBoxUsers.= '<input type="checkbox" name="topping0" id="topping0" value="Pepperoni"/><label for="topping0">Pepperoni</label>';
			}
			pg_free_result($res);
			//Cerramos la conexión
			pg_close($conn);
		}
		$select.='</optgroup></select></div>';
		//echo $select;  
	}
	
?>

<!DOCTYPE html>
<html lang="pt">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <head>
    <title>BCF Solutions S.A. - Galileo v2.1.1</title>

	<link rel="stylesheet" type="text/css" href="pro_dropdown_3/pro_dropdown_3.css" />

	
    <!-- Leaflet -->
	<link rel="stylesheet" href="leaflet-js/v7.3/leaflet.css" />
	<script src="leaflet-js/v7.3/leaflet.js"></script>
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
 <body onload="initialize()" >
<div id='map'></div>

<script>

timeElapsed = 0;
var googleLayer_roadmap ;
var googleLayer_satellite ;
var googleLayer_hybrid ;
var googleLayer_terrain ;

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
	
	
function ReturnSQL(){
			 
			 var Selector = document.getElementById("TipoPOI");
			 var SelBranchVal = "";
			 var x = 0;

			 for (x=0; x < Selector.length; x++)
			 {
				if (Selector[x].selected)
				{
					if 	(SelBranchVal == "")
					{
						SelBranchVal = Selector[x].value;
					}
					else
					{
						SelBranchVal = SelBranchVal + "," + Selector[x].value;
					}
				}
			 }
			 alert(SelBranchVal);
}	

function initialize() {
        
		var EntregasAEX;
		
		var lat = -23.554700;
		var lon = -46.634438;
		var osmAttrib = '(c) OpenStreetMap contributors'
		TileSvrURL = 'http://10.0.0.200/bcf_tiles/{z}/{x}/{y}.png'
		inicialZoom = 14;
	
		var MapaBase  = new L.tileLayer(TileSvrURL, {
			maxZoom: 18,
			minZoom: 3,
			attribution: osmAttrib
        });		
	
		var map = L.map('map',{
			zoomsliderControl: true,
			zoomControl: false,
			center: [lat,lon], 
			zoom: inicialZoom,
			layers: [MapaBase]
		});	
		
		var MapaBingHybrid = new L.BingLayer("AvcpKC4awcn042na3LJ5vIzVQs5xSw1iykahgh7JiA93oVrjSeClwf_k71367Aez", {type: "AerialWithLabels", maxZoom: 18, minZoom: 1, detectRetina: true});
		
		var MapaBing = new L.BingLayer("AvcpKC4awcn042na3LJ5vIzVQs5xSw1iykahgh7JiA93oVrjSeClwf_k71367Aez", {type: "Aerial", maxZoom: 18, minZoom: 1, detectRetina: true});
		
	
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
		
		
		var IconLocatorOrange = L.icon({
			iconUrl: 	'images/icons/locator-orange.png',
			iconAnchor: [25, 25], // point of the icon which will correspond to marker's location
			iconSize: 	[32, 37], // size of the icon
			shadowUrl: 	'images/marker-shadow'
		});
		
		var IconOcurrenciaRed = L.icon({
			iconUrl: 	'images/icons/caution-red.png',
			shadowUrl: 	'images/marker-shadow',
			iconSize: 	[32, 37], // size of the icon
			iconAnchor: [25, 25] // point of the icon which will correspond to marker's location
		});		
		
		var IconCadastroBlue = L.icon({
			iconUrl: 	'images/icons/male-2-blue.png',
			iconSize: 	[32, 37], // size of the icon
			iconAnchor: [25, 25], // point of the icon which will correspond to marker's location
			shadowUrl: 	'images/marker-shadow'
		});
		
		var IconAgenciasITAU = L.icon({
			iconUrl: 	'images/itauPoi.png',
			iconSize: 	[26, 38], // size of the icon
			iconAnchor: [25, 25], // point of the icon which will correspond to marker's location
			shadowUrl: 	'images/marker-shadow'
		});
		
		
		map.on('moveend', function() { 
			//alert(map.getBounds());
		});

		var markers = new L.layerGroup();
		
		function loadPoisTracking() {
			elapsedTime = new Date().getMilliseconds();
			
			//var bBoxSW = map.getBounds().getSouthWest();
			//var bBoxNE = map.getBounds().getNorthEast();
			//console.log("current bounding box: SouthWest " + bBoxSW + ", NorthEast: " + bBoxNE);
			console.log("BUSCANDO POIS EN EL BOUNDINBOX ACTUAL ...");
			var requestString = "http://10.0.0.200/GalileoITAU/GetTracking.php";
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
					setTimeout(loadPoisTracking, 30000 - elapsedTime);
				}
		)
		.fail(function(e) {
			console.debug("error while fetching POIs:\n" + e.responseText);
		});
		}
		
		function httpGet(httpURL)
		{
			var xmlHttp = null;

			xmlHttp = new XMLHttpRequest();
			xmlHttp.open( "GET", httpURL, false );
			xmlHttp.send( null );
			return xmlHttp.responseText;
		}
		
		//window.alert(httpGet());
		
		var AgenciasITAUGeoJSON = JSON.parse(httpGet("http://10.0.0.200/GalileoITAU/GetPoi.php?DWTPPOI=500"));
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
		
		//var CadastroITAGeoJSON = <?php print json_encode($geojson,JSON_NUMERIC_CHECK); ?>;
		var CadastroITAUGeoJSON = JSON.parse(httpGet("http://10.0.0.200/GalileoITAU/GetPoi.php?DWTPPOI=100"));
		var CadastroITAU = L.geoJson(CadastroITAUGeoJSON, {
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
								'<h5>Dir: ' + feature.properties.desc + '</h5>' +
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
		
	
		
		//var OcurrenciaITAGeoJSON = <?php print json_encode($geojsonOcurrencia,JSON_NUMERIC_CHECK); ?>;
		var OcurrenciaITAGeoJSON = JSON.parse(httpGet("http://10.0.0.200/GalileoITAU/GetPoi.php?DWTPPOI=115"));
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
				
				
		var baseMaps = {"Cartografico": MapaBase, "Satelital": MapaBing, "Hybrido": MapaBingHybrid};
		
		var overlayMaps = {
			"Agencias ITAU (Single)": AgenciasITAU,
			"Agencias ITAU (Group)": AgenciasITAUAgrupadas,
			//"Cadastro (Single)": CadastroITAU,
			"Cadastro All (Group)": CadastroITAUAgrupadas,
			//"Ocurrencias (Single)": OcurrenciaITAU,
			"Ocorrencias All (Group)": OcurrenciasITAUAgrupadas
		};

		console.log("LLAMO A LA FUNCION DE TRACKING POIS");
		loadPoisTracking();
		
		L.control.fullscreen().addTo(map);
		
		L.control.layers(baseMaps, overlayMaps).addTo(map)
		
		
		//MINI MAPA -- AQUI SE COLOCA EL CODIGO DEL MINI MAPA 
		var osm2 = new L.TileLayer(TileSvrURL, {minZoom: 2, maxZoom: 18, attribution: osmAttrib });
		var miniMap = new L.Control.MiniMap(osm2, {toggleDisplay: true}).addTo(map);
		
	/*
		var AddButton= L.Control.extend({//creating the buttons
			options: {
				position: 'topright'
			},
			onAdd: function (map) {
				// create the control container with a particular class name
				var div = L.DomUtil.create('div','bgroup');
				
				var addButton = L.DomUtil.create('button', 'addStuff',div);
				addButton.type="button";
				addButton.innerHTML="Filtar POIs";
				L.DomEvent.addListener(addButton,"click",function(){
					morePoints(map.getCenter().lat,map.getCenter().lng);//make sure it's where you currently are.
					});
				var allButton = L.DomUtil.create('button', 'allStuff',div);
				allButton.type="button";
				allButton.innerHTML="ShowAll";
				L.DomEvent.addListener(allButton,"click",function(){
					redoBox();
					});
				return div;
			}
			});
			//add them to the map
			map.addControl(new AddButton());
	*/		

		// COMBOBOX  DE USUARIOS
		var legend = L.control({position: 'topright'});
			legend.onAdd = function (map) {
				var div = L.DomUtil.create('div', 'info legend');
				div.innerHTML = ' <?php print $select; ?>  ';
				div.firstChild.onmousedown = div.firstChild.ondblclick = L.DomEvent.stopPropagation;
			return div;
			};
		legend.addTo(map);
	
	
	
		//VERIFICO CUANDO SE PRECIONO SI SE ELIGIO ALGUN VALOR Y LO PROCESO
		/*
		$('select').change(function(){
				
			var e = document.getElementById("TipoPOI");
			var strUser = e.options[e.selectedIndex].value;
			alert(strUser); //COMPRUEBO EL ID SELECCIONADO
		});
*/


		
		var ADDFechaDesde = L.control({position: 'topright'});
			ADDFechaDesde.onAdd = function (map) {
				var div = L.DomUtil.create('div', 'info ADDFechaDesde');
				div.innerHTML = '<div class="confirmation-box round dark"><label for="first">Fecha de Seleccion: </label><input type="text" name="FechaDesde" id="DatePickerDesde" size="10" value=""/></div>';
				div.firstChild.onmousedown = div.firstChild.ondblclick = L.DomEvent.stopPropagation;
			return div;
			};
		ADDFechaDesde.addTo(map);
		
		
		$(function () {
				$("#DatePickerDesde").datepicker({
					firstDay: 1,
					closeText: 'Cerrar',
					currentText: 'Hoy',
					monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
					'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
					monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
					'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
					dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
					dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié;', 'Juv', 'Vie', 'Sáb'],
					dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
					weekHeader: 'Sm',
					dateFormat: 'yy-mm-dd',
					onSelect: function (date) {
						//alert(date)  //AQUI EL CODIGO CUANDO SELECCIONE FECHA
				},
				})
				.datepicker("setDate", new Date());
		});

		var BotonShowTracking = L.control({position: 'topright'});
			BotonShowTracking.onAdd = function (map) {
				var div = L.DomUtil.create('div', 'info BotonShowTracking');
				div.innerHTML = '<input type="submit" id="MostrarTrack" class="button round blue image-right ic-right-arrow" value="Show Tracking">';
				div.firstChild.onmousedown = div.firstChild.ondblclick = L.DomEvent.stopPropagation;
			return div;
			};
		BotonShowTracking.addTo(map);		
		
		var BotonShowOcorrencia = L.control({position: 'topright'});
			BotonShowOcorrencia.onAdd = function (map) {
				var div = L.DomUtil.create('div', 'info BotonShowOcorrencia');
				div.innerHTML = '<input type="submit" id="BotonShowOcorrencia" class="button round blue image-right ic-right-arrow" value="Ocorrencias (Single)">';
				div.firstChild.onmousedown = div.firstChild.ondblclick = L.DomEvent.stopPropagation;
			return div;
			};
		BotonShowOcorrencia.addTo(map);
		
		var BotonShowCadastro = L.control({position: 'topright'});
			BotonShowCadastro.onAdd = function (map) {
				var div = L.DomUtil.create('div', 'info BotonShowCadastro');
				div.innerHTML = '<input type="submit" id="BotonShowCadastro" class="button round blue image-right ic-right-arrow" value="Cadastros (Single)">';
				div.firstChild.onmousedown = div.firstChild.ondblclick = L.DomEvent.stopPropagation;
			return div;
			};
		BotonShowCadastro.addTo(map);
		

		$("input").click(function(e){
			var idClicked = e.target.id;
			if (idClicked == "MostrarTrack"){
				//var popup_message = "You typed: " + document.getElementById("DatePickerDesde").value;
				//alert(popup_message); 
				ReturnSQL();
			}
		});
		

}

</script>

</body>
</html>


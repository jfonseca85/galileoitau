<?php
        //print("Conectando");
	# Connect to PostgreSQL database
	$conn = pg_connect("dbname='AEX_PY' user='osmuser' password='osmuser' host='10.10.50.9'");
	if (!$conn) {
		echo "Not connected : " . pg_error();
	exit;
	}
	
	//print("Conectado");
	# Build SQL SELECT statement and return the geometry as a GeoJSON element in EPSG: 4326
	$sql = "SELECT DWTPPOI,
			trunc(DWCDCOGX,8) as DWCDCOGX,
			trunc(DWCDCOGY,8) as DWCDCOGY,			
			DWDSUSR3, 
			DWDSINFO
			FROM DWAEXMON 
			WHERE DWTPPOI = '16' ORDER BY DWDSUSR3 DESC LIMIT 1
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
                        "geometry" => array(
                            'type' => 'Point',
                            'coordinates' => array( 
                                            $row['dwcdcogx'],
                                            $row['dwcdcogy']
                            )
                        ),
						'type' => 'Feature', 
						'properties '=> array (
								'recibio' => "".$row['dwdsusr1']."",
								'dir'	=> "".$row['dwdsinfo']."",
								'long'	=> "".$row['dwcdcogy']."",
								'lat'	=> "".$row['dwcdcogx']."",
								'color'	=>	'blue',
								'icon'	=>	'images/aex_logo_round_small'
								)
						
                                        
        );
		array_push($geojson['features'], $marker['features']);
	}

	//Liberamos la memoria (no creo que sea necesario con consultas tan simples)
	pg_free_result($res);
	//Cerramos la conexión
	pg_close($conn);
	
	print (json_encode($marker, JSON_NUMERIC_CHECK));
?>
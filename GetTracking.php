<?php
       
	# Connect to PostgreSQL database
	$conn = pg_connect("dbname='ITAU_BR' user='osmuser' password='osmuser' host='192.168.1.21'");
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
			DWDSUSR4,			
			DWDSINFO
			FROM DWITAUMON
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
                            'id' => "".$row['dwtppoi']."",
							'UserName' => "".$row['dwdsinfo']."",
							'time' => "".$row['dwdsusr4']."",
							'long'	=> "".$row['dwcdcogy']."",
							'lat'	=> "".$row['dwcdcogx']."",
							'color'	=>	'blue',
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
	
	print (json_encode($geojson, JSON_NUMERIC_CHECK));
?>
<?php
	
	#$DWTPPOI=$_GET['DWTPPOI'];
	
	//$v2=$_GET['var2'];
	
        //print("Conectando");
	# Connect to PostgreSQL database
	#$conn = pg_connect("dbname='ITAU_BR' user='osmuser' password='osmuser' host='10.0.0.200'");
	$conn = oci_connect('galileostra','galileostra','187.108.192.76:7000/EEO11G');
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
			DWCXUSR0,
			DWCXCLA0 
			FROM DWATPDI 
			WHERE DWTPPOI = 500
			";
			
			//WHERE DWTPPOI = '3599340' AND (DWFLUSR1 = '0' OR DWFLUSR1 = ' ' OR DWFLUSR1 IS NULL) AND (DWDSUSR3 > '2014-05-27' AND DWDSUSR3 < '2014-05-28')

	#echo $sql;
	$stid = oci_parse($conn, $sql);
    oci_execute($stid);
	if (!$stid) {
		//echo "An SQL error occured.\n";
	//exit;
	}
 
	$geojson = array( 
		'type' => 'FeatureCollection', 
		'features' => array()
	);
 
	while (oci_fetch($stid)) {
    #echo oci_result($stid, 'DWDSINFO') . " is ";
	$marker = array(
                    'type' => 'Feature',
                    'features' => array(
                        'type' => 'Feature',
                        'properties' => array(
                        'name' => "".oci_result($stid, 'DWDSUSR0')."",
						'tel'	=> "".oci_result($stid, 'DWDSUSR2')."",
						'email'	=> "".oci_result($stid, 'DWDSUSR4')."",
						'long'	=> "".oci_result($stid, 'DWCDCOGY')."",
						'lat'	=> "".oci_result($stid, 'DWCDCOGX')."",
						'date'	=> "".oci_result($stid, 'DWDSUSR3')."",
						'sendby' => "".oci_result($stid,'DWCXUSR5')."",
						'clase' => "".oci_result($stid, 'DWDSPRES')."",
						'tipo'	=> "".oci_result($stid, 'DWCXUSR0')."",
						'desc'	=> "".oci_result($stid, 'DWDSINFO')."",
						'images'	=> "".oci_result($stid, 'DWCXURL')."",
						'icon'	=>	'images/itau3_small'							
                        //'marker-color' => '#f00',
                        //'marker-size' => 'small'
                        //'url' => 
                        ),
                        "geometry" => array(
                            'type' => 'Point',
                            'coordinates' => array( 
                                            oci_result($stid,'DWCDCOGX'),
                                            oci_result($stid,'DWCDCOGY')
                            )
                        )
                    )
        );
		array_push($geojson['features'], $marker['features']);
   }
	oci_free_statement($stid);
    oci_close($conn);
	print (json_encode($geojson, JSON_NUMERIC_CHECK));	

?>
<?php

	$conn = oci_connect('galileoitau', 'galileoitau', '192.168.111.111:1521/EEO11G');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

	// Prepare the statement
	$stid = oci_parse($conn, 'SELECT * FROM DWATPDI WHERE DWTPPOI = 500');
	if (!$stid) {
		$e = oci_error($conn);
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	// Perform the logic of the query
	$r = oci_execute($stid);
	if (!$r) {
		$e = oci_error($stid);
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$geojson = array( 
		'type' => 'FeatureCollection', 
		'features' => array()
	);

	// Fetch the results of the query
	while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
  		$marker = array(
                    'type' => 'Feature',
                    'features' => array(
                        'type' => 'Feature',
                        'properties' => array(
                            'title' => "".$row[DWDSRAGS]."",
							'dir'	=> "".$row[DWDSINDI]."",
							'estado'	=> "".$row[DWDSCOMU]."",
							'barrio'	=> "".$row[DWDSLOCA].""
                            //'marker-color' => '#f00',
                            //'marker-size' => 'small'
                            //'url' => 
                            ),
                        "geometry" => array(
                            'type' => 'Point',
                            'coordinates' => array( 
                                            $row[DWCDCOGX],
                                            $row[DWCDCOGY]
                            )
                        )
                    )
        );
		array_push($geojson['features'], $marker['features']);
	}
	print (json_encode($geojson, JSON_NUMERIC_CHECK));

	oci_free_statement($stid);
	oci_close($conn);

?>

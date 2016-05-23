<?php
		$conn = oci_connect('galileostra','galileostra','187.108.192.76:7000/EEO11G');
        if (!$conn) {
                echo "Not connected : " . pg_error();
				//exit;
        }
	?>	
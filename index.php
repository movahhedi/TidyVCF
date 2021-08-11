<?php
	$chunks = array_chunk(file("input.vcf"), 1);
	$contacts = array();
	$contacts2 = array();
	$contacts2Names = array();
	$lastid = 0;
	$temp = array();
	foreach ($chunks as &$t) {
		$t = str_replace("\r\n", "", $t);
		$t = $t[0];
		if ($t == 'END:VCARD') {
			array_push($contacts, $temp);
			$temp = array();
		}
		else if ($t == 'BEGIN:VCARD');
		else if ($t == 'VERSION:2.1');
		else array_push($temp, $t);

	}
	

	foreach ($contacts as $key => &$each) {
		$hasTel = false;
		foreach ($each as &$t) {
			if (substr($t, 0, 4) === "TEL;") {
				$hasTel = true;
				break;
			}
		}
		if ( ! $hasTel) unset($contacts[$key]);
		else {
			if (false !== $key = array_search($each[0], $contacts2Names)) {
                for ($i=1; $i < count($each); $i++) {
                    if ( ! in_array($each[$i], $contacts2[$key])) array_push($contacts2[$key], $each[$i]);
                }
			} else {
				array_push($contacts2, $each);
				array_push($contacts2Names, $each[0]);
			}
		}

	}
	
	
	foreach ($contacts2 as $key => &$each) {
		foreach ($each as $keyt => &$t) {
            $foundcount = 0;
            foreach ($each as $keyi => &$i) {
                if (substr($i, -10) == substr($t, -10)) $foundcount++;
            }
            if ($foundcount >= 2) {
                unset($each[$keyt]);
                $foundcount = 0;
            }
            
            $foundcountFN = 0;
            foreach ($each as &$i) {
                if (substr($i, 0, 3) === "FN:" &&substr($t, 0, 3) === "FN:" ) $foundcountFN++;
            }
            if ($foundcountFN >= 2) {
                unset($each[$keyi]);
                $foundcountFN = 0;
            }
        }
	}
	var_dump($contacts2);

    $s="";
    foreach ($contacts2 as $key => $each) {
        $s .= "BEGIN:VCARD\r\nVERSION:2.1\r\n";
        foreach ($each as $keyt => $t) {
            $s .= $t . "\r\n";
        }
        $s .= "END:VCARD\r\n";
    }

    file_put_contents("output.vcf", $s);
?>
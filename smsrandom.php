<?php
	//echo "SMS Random" . RAND_OTP(100000,999999);
	
	$salt = "12e26f454";
	echo SHA1(CONCAT($salt, SHA1(CONCAT($salt, SHA1('sreeji')))));
	
	function RAND_OTP($min = 0, $max = 0) {
		$min		= flattenSingleValue($min);
		$max		= flattenSingleValue($max);

		if ($min == 0 && $max == 0) {
			return (rand(0,10000000)) / 10000000;
		} else {
			return rand($min, $max);
		}
	}	
	function flattenSingleValue($value = '') {
		while (is_array($value)) {
			$value = array_pop($value);
		}

		return $value;
	}	//	function flattenSingleValue()
?>
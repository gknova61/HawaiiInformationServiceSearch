<?php
function bit_to_bool($bit) {
	if(gettype($bit) === 'string') {
		$bit = intval($bit);
	}
	switch($bit) {
		case 0: 
			return false;
		break;
		case 1:
			return true;
		break;
		default:
			echo closeScript('ERROR: Invalid data input given at function bin_to_bool(): ' . $bit);
			return false;
		break;
	}
}

function bool_to_bit($bool) {
	switch($bool) {
		case false: 
			return 0;
		break;
		case true:
			return 1;
		break;
		default:
			closeScript('ERROR: Invalid data input given at function bool_to_bin(): ' . $bool);
			return false;
		break;
	}
}

function cleanString($string) {
   $string = str_replace(' ', '-', $string); 

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); 
}

function closeScript($message = false) {
	global $con;
	if($con) {mysqli_close($con);}
	if($message) {die($message);}
}

function getBase62Char($num) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return $chars[$num];
}

function generate_random_string($nbLetters){
    $randString="";

    for($i=0; $i < $nbLetters; $i++){
        $randChar = getBase62Char(mt_rand(0,61));
        $randString .= $randChar;
    }

    return $randString;
}

function get_client_ip() {
    if (@$_SERVER["HTTP_CLIENT_IP"])
        $ipaddress = $_SERVER["HTTP_CLIENT_IP"];
    else if(@$_SERVER["HTTP_X_FORWARDED_FOR"])
        $ipaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if(@$_SERVER["HTTP_X_FORWARDED"])
        $ipaddress = $_SERVER["HTTP_X_FORWARDED"];
    else if(@$_SERVER["HTTP_FORWARDED_FOR"])
        $ipaddress = $_SERVER["HTTP_FORWARDED_FOR"];
    else if(@$_SERVER["HTTP_FORWARDED"])
        $ipaddress = $_SERVER["HTTP_FORWARDED"];
    else if(@$_SERVER["REMOTE_ADDR"])
        $ipaddress = $_SERVER["REMOTE_ADDR"];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

function get_random_string($valid_chars, $length)
{
    $random_string = "";
    $num_valid_chars = strlen($valid_chars);

    for ($i = 0; $i < $length; $i++)
    {
        $random_pick = mt_rand(1, $num_valid_chars);
        $random_char = $valid_chars[$random_pick-1];
        $random_string .= $random_char;
    }
    return $random_string;
}

function query($query) {
	global $con;
	$result = mysqli_query($con,$query);
	if (!$result) {
		printf("Please contact support with this Error: %s\n", mysqli_error($con));
		die();
	}elseif ($result === true) {
		return true;
	}
	$row = mysqli_fetch_all($result,MYSQLI_ASSOC);
	if($row != null) {
		return $row;
	} else {
		return false;
	}
}

function toInt($string) {
	return intval(preg_replace("/[^0-9]/","",$string));
}
?>
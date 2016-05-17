<?php
/**
 * A version of die() or exit() that will also kill the MySQL Connection.
 * 
 * @param string $message //Specify a $message for the script to die()
 */
function closeScript($message = '') {
	global $con;
	if($con) {mysqli_close($con);}
	if($message != '') {die($message);}
}

/**
 * Convert binary bit to boolean.
 * 
 * @param $bit //0 or 1
 * @return bool
 */
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

/**
 * Convert boolean to binary bit.
 * 
 * @param $bool
 * @return bool|int //Will only return false if an invalid/no bool is specified
 */
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

/**
 * Strips out extra characters in a given string.
 * 
 * @param $string
 * @return string
 */
function cleanString($string) {
   $string = str_replace(' ', '-', $string); 

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); 
}

/**
 * Returns a random string.
 * 
 * @param int $nbLetters //length of string
 * @return string
 */
function generate_random_string($nbLetters){
	function getBase62Char($num) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    	return $chars[$num];
	}
	
    $randString="";

    for($i=0; $i < $nbLetters; $i++){
        $randChar = getBase62Char(mt_rand(0,61));
        $randString .= $randChar;
    }

    return $randString;
}

/**
 * A Better way to get a client's ip address.
 * 
 * @return bool|string //Will return false if none is found
 */
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
        $ipaddress = false;
 
    return $ipaddress;
}

/**
 * Returns a random string.
 * 
 * @param array $valid_chars //array of valid characters
 * @param int $length //length of random string
 * @return string
 */
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

/**
 * Run a MySQL query on $con connection variable (must be already set).
 *
 * @param string $query //Your MySQL query
 * @return array|bool //Will return the result, or it will die with an error printed to screen
 */
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

/**
 * Connect to MySQL based on $dbServer, $dbUsername, $dbPassword, $dbDatabse variables that must be already set. Returns the connection variable
 * Recommended: Set the output of this function to a variable $con if you plan on using the above query function i.e. $con = MySQLConnect();
 *
 * @return mysqli
 */
function MySQLConnect() {
	global $dbServer;
	global $dbUsername;
	global $dbPassword;
	global $dbDatabase;
	
	$con=mysqli_connect($dbServer,$dbUsername,$dbPassword,$dbDatabase);
	if (mysqli_connect_errno())
	{
    	die('Please contact support with this error. Failed to connect to MySQL: ' . mysqli_connect_error());
	}
	
	return $con;
}


/**
 * Convert any string into a float, stripping out all extra characters.
 * 
 * @param string $string
 * @return float
 */
function toFloat($string) {
	return floatval(preg_replace("/[^0-9]/","",$string));
}

<?php

/**
 * @param string $pass The user submitted password
 * @param string $hashed_pass The hashed password pulled from the database
 * @param string $salt The salt pulled from the database
 * @param string $hash_method The hashing method used to generate the hashed password
 */



function createHash($string, $hash_method = 'sha1') {
	// generate random salt
	
	if (function_exists('hash') && in_array($hash_method, hash_algos()))
	{
		return hash($hash_method,  $string);
	}
	return sha1( $string);
}

function randomSalt($len = 24) 
{
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^&*()-=_+';
	$l = strlen($chars) - 1;
	$str = '';
	for($i = 0; $i < $len; ++$i)
	{
		$str .= $chars[rand(0, $l)];
 	}
	return $str;
}

function randomString($len = 24) 
{
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$l = strlen($chars) - 1;
	$str = '';
	for($i = 0; $i < $len; ++$i)
	{
		$str .= $chars[rand(0, $l)];
 	}
	return $str;
}

?>
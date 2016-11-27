<?php
$config = include 'config.inc.php';
// Example of config.inc.php
/*
 * <?php
 *
 * return array(
 * "username" => "username",
 * "password" => "password",
 * "url" => "https://myhostingprovider.com:2222",
 * "domain" => "domain.name.to.use"
 * );
 *
 */
function DARequest($request) {
	global $config;
	$headers = array (
			'Authorization: Basic ' . base64_encode ( $config ['username'] . ':' . $config ['password'] ) 
	);
	
	$ch = curl_init ();
	curl_setopt_array ( $ch, array (
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_URL => $config ['url'] . "/$request",
			CURLOPT_FAILONERROR => 1,
			CURLOPT_TIMEOUT => 15,
			CURLOPT_RETURNTRANSFER => 1 
	) );
	
	if (isset ( $config ['curlopts'] )) {
		curl_setopt_array ( $ch, $config ['curlopts'] );
	}
	
	$res = curl_exec ( $ch );
	
	$output = null;
	if (! $res) {
		error_log ( "CONNECTION ERROR " . curl_errno ( $ch ) . ": " . curl_error ( $ch ) );
	} else {
		parse_str ( $res, $output );
	}
	
	curl_close ( $ch );
	
	return $output;
}

$name = $_GET ['name'];
$ip = $_SERVER ['REMOTE_ADDR'];

DARequest ( 'CMD_API_DNS_CONTROL?domain=' . $config ['domain'] . '&action=select&arecs0=' . urlencode ( "name=$name&value=$ip" ) );
DARequest ( 'CMD_API_DNS_CONTROL?domain=' . $config ['domain'] . "&action=add&type=A&name=$name&value=$ip" );

echo $name . '.' . $config ['domain'] . ' ' . $ip;

?>
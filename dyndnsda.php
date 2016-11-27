<?php

/*
 * Example of config.inc.php
 *
 * <?php
 *
 * return array(
 * "username" => "username",
 * "password" => "password",
 * "url" => "https://myhostingprovider.com:2222",
 * "domain" => "domain.name.in.admin.panel.to.use"
 * );
 */
$config = include 'config.inc.php';
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
	
	if (! $res) {
		error_log ( "CONNECTION ERROR " . curl_errno ( $ch ) . ": " . curl_error ( $ch ) );
	}
	
	curl_close ( $ch );
	
	return $res;
}

$ddns_name = strtolower ( $_GET ['name'] ? $_GET ['name'] : $argv [1] );
$ddns_ip = $_SERVER ['REMOTE_ADDR'] ? $_SERVER ['REMOTE_ADDR'] : $argv [2];

$exists = false;
$dnsconf = DARequest ( 'CMD_API_DNS_CONTROL?domain=' . $config ['domain'] );

foreach ( explode ( "\n", $dnsconf ) as $row ) {
	list ( $name, $ttl, $in, $type, $value ) = explode ( "\t", $row );
	$name = strtolower ( $name );
	
	if ($type == "A") {
		if ($name == $ddns_name) {
			if ($value == $ddns_ip) {
				$exists = true;
			} else {
				DARequest ( 'CMD_API_DNS_CONTROL?domain=' . $config ['domain'] . '&action=select&arecs0=' . urlencode ( "name=$name&value=$value" ) );
			}
		}
	}
}

if (! $exists) {
	DARequest ( 'CMD_API_DNS_CONTROL?domain=' . $config ['domain'] . "&action=add&type=A&name=$ddns_name&value=$ddns_ip" );
}

echo $ddns_name . '.' . $config ['domain'] . ' ' . $ddns_ip;

?>
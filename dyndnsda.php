<?php
include 'config.inc.php';
// Example of config.inc.php
/*
<?php
define ( "DIRECT_ADMIN_USER", "username" );
define ( "DIRECT_ADMIN_PASS", "password" );
define ( "DIRECT_ADMIN_URL", "https://myhostingprovider.com:2222" );
define ( "DNS_DOMAIN", "domain.name.to.use" );
 */


function DARequest($request) {
	$headers = array (
			'Authorization: Basic ' . base64_encode ( DIRECT_ADMIN_USER . ':' . DIRECT_ADMIN_PASS ) 
	);
	
	$ch = curl_init ();
	curl_setopt_array ( $ch, array (
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_URL => DIRECT_ADMIN_URL . "/$request",
			CURLOPT_FAILONERROR => 1,
			CURLOPT_TIMEOUT => 15,
			CURLOPT_RETURNTRANSFER => 1 
	) );
	
	if (defined ( 'DIRECT_ADMIN_CURL_OPTS' )) {
		curl_setopt_array ( $ch, DIRECT_ADMIN_CURL_OPTS );
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

DARequest ( 'CMD_API_DNS_CONTROL?domain=' . DNS_DOMAIN . '&action=select&arecs0=' . urlencode ( "name=$name&value=$ip" ) );
DARequest ( 'CMD_API_DNS_CONTROL?domain=' . DNS_DOMAIN . "&action=add&type=A&name=$name&value=$ip" );

?>
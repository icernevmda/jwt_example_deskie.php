<?php

/*
JWT можно скачать по адресу https://github.com/firebase/php-jwt
Также можно использовать JWT PEAR package http://pear.php.net/pepr/pepr-proposal-show.php?id=688
*/

include "Authentication/JWT.php";

define('CURRENT_TIME', strtotime(gmdate("M d Y H:i:s").'+0000'));

$payload = array(
	"iat" => CURRENT_TIME,
	"exp" => CURRENT_TIME+86400,
	"email" => 'testuser@gmail.com',
	"name" => 'Василий Пупкин',
	"company_name" => 'Мосгорстрой',
	"company_position" => 'PHP Programmer',
	"remote_photo_url" => 'http://mydomain.com/myimage.jpg',
);

$marker='uMxNpq7WSsUI6KSHPBv1eTLf4X6txCwj';
$subdomain = 'yourcompany';

$encoded = JWT::encode($payload, $marker);

$url = 'http://'.$subdomain.'.omnidesk.ru/access/jwt?jwt='.$encoded;
if(isset($_GET["return_to"])) {
  $url .= "&return_to=" . urlencode($_GET["return_to"]);
}

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
$result = curl_exec( $ch );

$headers = curl_getinfo($ch);
if($headers['http_code']==200) {
	header("Location: ".$result);
} else {
	echo "Error: ".$result;
}

?>

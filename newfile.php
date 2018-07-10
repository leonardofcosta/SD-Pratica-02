<?php
$json_str = '{"nome":"Leonardo F Costa", "idade":"39", "sexo":"M"}';

//echo $json_str;

$obj = json_decode($json_str);

//print_r($obj);

$array = json_decode($json_str, TRUE);

$array = array( "nome" => "Leonardo F Costa", "idade" => "39", "sexo" => "M" );

$json = json_encode($array);

//$result = file_get_contents("http://177.105.40.87/ws-cep/public/api/endereco/cep/37200000");

// create a new cURL resource
$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "http://177.105.40.87/ws-cep/public/api/endereco/cep/37200");
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

    
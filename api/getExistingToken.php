<!--
//author: J. Robert
//creation date: 09/09/2016
//modification date: 09/09/2016
-->

<?php
//use \Firebase\JWT\JWT;
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;


$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;

$tokenArray = array();
//Search in DB wheter there is a token for this user and this infoItem.
$client = Elasticsearch\ClientBuilder::create()->build();
$params = [
    'index' => 'iotbnb',
    'type' => 'secu',
    'body' => [
        'query' => [
            'prefix' => [
                'name' => $username
            ]
        ]
    ]
];

$response = $client->search($params);

if ($response["hits"]["total"]>0)
{

$max = $response["hits"]["total"];
for($i = 0; $i < $max;$i++)
{

    $name = explode("_",$response["hits"]["hits"][$i]["_source"]["name"] );
    $token = $response["hits"]["hits"][$i]["_source"]["token"];
    $id = $name[1];

    $idT[$i] = array('id' => $id, 'token' => $token );
}
$msg = array('stat' => '1', 'token' => $idT); 
}
else{
$msg = array('stat' => '0', 'token' => null); 
}

//$msg = array('stat' => '1', 'id' => $idT, 'token' => $tokenArray); 

$json = $msg;
header('content-type: application/json');
echo json_encode($json);


?>
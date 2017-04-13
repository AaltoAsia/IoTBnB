<?php
//author: J. Robert
//creation date: 01/03/2016
//modification date: 18/08/2016 
require 'vendor/autoload.php';
$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;
$client = Elasticsearch\ClientBuilder::create()->build();
$params = [
    'index' => 'iotbnb',
    'type' => 'users',
    'body' => [
        'query' => [
            'match' => [
                'username' => $username
            ]
        ]
    ]
];
$response = $client->search($params);
if ($response["hits"]["total"] == 1)
{
$msg = array('stat' => '1', 'username' => $username, 'email' => $response["hits"]["hits"][0]["_source"]["email"],
  'omiURL' => $response["hits"]["hits"][0]["_source"]["omiURL"], 'omiName' => $response["hits"]["hits"][0]["_source"]["omiName"], 
  'omiAddr' => $response["hits"]["hits"][0]["_source"]["omiAddr"], );
}
else {
$err = 'Unknown account. Please, save your profile.';
$msg = array('stat' => '0', 'msg' => $err);
}
$json = $msg;
header('content-type: application/json');
echo json_encode($json);
?>
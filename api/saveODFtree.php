<?php

//author: J. Robert
//creation date: 01/03/2016
//modification date: 18/08/2016

require 'vendor/autoload.php';

$dataPOST = file_get_contents("php://input");
$dataJsonDecode = json_decode($dataPOST);
$omiURL = $dataJsonDecode->omiURL;
$odfDATA = $dataJsonDecode->odfDATA;

$client = Elasticsearch\ClientBuilder::create()->build();

$params = [
    'index' => 'iotbnb',
    'type' => 'odf',
    'body' => [
        'query' => [
            'match' => [
                'odfURL' => $omiURL
            ]
        ]
    ]
];

$response = $client->search($params);

if ($response["hits"]["total"]==1)
{

$id = $response["hits"]["hits"][0]["_id"];

$params = [
    'index' => 'iotbnb',
    'type' => 'odf',
    'id' => $id,
    'body' => [
        'doc' => [
           'odfURL' => $omiURL,
           'odfTree' => $odfDATA
        ]
    ]
];


$result = $client->update($params);

$msg = array('stat' => '0', 'msg' => 'A message is sent to OpenDataSoft platform to take into account new services and store in your own database for visualisation purposes!');
$json = $msg;
header('content-type: application/json');
echo json_encode($json);
}
else {

$params = array();
$params['body'] = array(
  'odfURL' => $omiURL,
  'odfTree' => $odfDATA
);

$params['index'] = 'iotbnb';
$params['type']  = 'odf';

$result = $client->index($params);

$msg = array('stat' => '1', 'msg' => 'ODF tree added !');
$json = $msg;
header('content-type: application/json');
echo json_encode($json);
}

?>
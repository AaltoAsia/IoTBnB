 <?php

//author: J. Robert
//creation date: 01/03/2016
//modification date: 18/08/2016 

require 'vendor/autoload.php';

$client = Elasticsearch\ClientBuilder::create()->build();

$params = [
    'index' => 'iotbnb',
    'type' => 'stat',
    "size" => 10000, //Be carefull, set up to the maximum allowed value 
    //--> should be a smarter request according to the display !!! 
    'body' => [
        'query' => [
            'match_all' => []
        ]
    ]
];

$response = $client->search($params);

if ($response["hits"]["total"] > 0)
{

for($i = 0; $i < $response["hits"]["total"];$i++)
{

$id = $response["hits"]["hits"][$i]["_source"]["infoUrl"];

$statArray[$id] = array('stat' => '1', 
  'format' => $response["hits"]["hits"][$i]["_source"]["format"],
  'vocab' => $response["hits"]["hits"][$i]["_source"]["vocab"], 
  'metadata' => $response["hits"]["hits"][$i]["_source"]["metadata"], 
  'reputation' => $response["hits"]["hits"][$i]["_source"]["reputation"],
  'billing' => $response["hits"]["hits"][$i]["_source"]["billing"],
  'techno' => $response["hits"]["hits"][$i]["_source"]["techno"],
  'price' => $response["hits"]["hits"][$i]["_source"]["price"] );
}

$msg = $statArray;

}
else {
$err = 'Error';
$msg = array('stat' => '0', 'msg' => $err);
}



$json = $msg;
header('content-type: application/json');
echo json_encode($json);

?>
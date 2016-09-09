
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
$omiURL = $dataJsonDecode->omiURL;
//$items = $dataJsonDecode->items;

//$odfTree = "";
//$str=$username."*";
//$path=get_object_vars($items[$i])["_id"];    
//$name=$username."_".$path;
//Search in DB wheter there is a token for this user and this infoItem.
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

if ($response["hits"]["total"]>0)
{
$max = $response["hits"]["total"];
for($i = 0; $i < $max;$i++)
{
$odfTree= $response["hits"]["hits"][$i]["_source"]["odfTree"];
}
}

$msg = array('stat' => '1', 'tree' => $odfTree); 


$json = $msg;
header('content-type: application/json');
echo json_encode($json);


?>
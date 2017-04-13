<?php
require 'vendor/autoload.php';
//set_include_path('.:/usr/local/pear/share/pear');
//require_once('System.php');
$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;
$email = $dataJsonDecode->email;
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
if ($response["hits"]["total"]==1)
{
$id = $response["hits"]["hits"][0]["_id"];
$client = Elasticsearch\ClientBuilder::create()->build();
$params = [
    'index' => 'iotbnb',
    'type' => 'users',
    'id' => $id,
    'body' => [
        'doc' => [
            'email' => $email,
        ]
    ]
];
$result = $client->update($params);
$msg = array('stat' => '1', 'msg' => 'user updated !');
}
else {
$params = array();
$params['body']  = array(
  'username' => $username,
  'email' => $email,
  'omiURL' => null,
  'omiName' => null,
  'omiAddr' => null
);
$params['index'] = 'iotbnb';
$params['type']  = 'users';
$result = $client->index($params);
if ($result["created"]==true)
{
  $msg = array('stat' => '1', 'msg' => 'user added !');  
} else
{
 $err = 'Not created.';
 $msg = array('stat' => '0', 'msg' => $err); 
}
  //$msg = array('stat' => '0', 'msg' => "Please, contact the administrator of the website");
}
//$msg = array('test' => '1');
$json = $msg;
header('content-type: application/json');
echo json_encode($json);
?>
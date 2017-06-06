<?php

//author: J. Robert
//creation date: 09/09/2016
//modification date: 09/09/2016

//use \Firebase\JWT\JWT;
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;
$items = $dataJsonDecode->items;
$arrayUrlOMInode = $dataJsonDecode->urlOMI;


//print_r($items)
$tokenArray = array();

$max = sizeof($items);
for($i = 0; $i < $max;$i++)
{
$path=get_object_vars($items[$i])["_id"]; 
$urlOMI=$arrayUrlOMInode[$i];

//Search for the url of the seller O-MI node for getting a token
$client = Elasticsearch\ClientBuilder::create()->build();
$params = [
    'index' => 'iotbnb',
    'type' => 'users',
    'body' => [
        'query' => [
            'match' => [
                'omiURL' => $urlOMI
            ]
        ]
    ]
];

$response = $client->search($params);

//if there is a seller account with the O-MI node information...
if ($response["hits"]["total"]==1)
{

//then we can send the POST request to get the token...
$secuURL = $response["hits"]["hits"][0]["_source"]["secuUrl"];
$clientId = $response["hits"]["hits"][0]["_source"]["clientId"];
$clientSecret = $response["hits"]["hits"][0]["_source"]["clientSecret"];
//$AuthKey = $response["hits"]["hits"][0]["_source"]["securAuthorizationKey"];


$data = array(
    'clientId' => $clientId,
    'clientSecret' => $clientSecret,
    'hid' => $path,
    'username' => $username,
    'validity' => '100'
);

$jsonData = json_encode($data);
//$fullUrl = $secuURL."/api/getToken";
$fullUrl = $secuURL;
$headerContent = array('Content-Type: application/json; charset=utf8');

    $curl = curl_init($fullUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerContent);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $json_response = curl_exec($curl);
    

$newToken = $json_response;
curl_close($curl);

    //and store each new token in the DB
           
        $name=$username."_".$path;
        //Search in DB wheter there is a token for this user and this infoItem.
        $client = Elasticsearch\ClientBuilder::create()->build();
        $params = [
            'index' => 'iotbnb',
            'type' => 'secu',
            'body' => [
                'query' => [
                    'match' => [
                        'name' => $name
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
                'type' => 'secu',
                'id' => $id,
                'body' => [
                    'doc' => [
                        'token' => $newToken,
                        'issuedAt' => "0",
                        'exp' => "0"
                    ]
                ]
            ];

            $result = $client->update($params);

            //$idT[$i] = array('id' => $path, 'token' => $jwt );

            $msg = array('stat' => '1', 'msg' => 'token updated !');
        }
        else {
                $params = array();
                $params['body']  = array(
                  'name' => $name,
                  'token' => $newToken,
                  'issuedAt' => "0",
                  'exp' => "0"
                );

                $params['index'] = 'iotbnb';
                $params['type']  = 'secu';

                $result = $client->index($params);

                if ($result["created"]==true)
                {
                  //$idT[$i] = array('id' => $path, 'token' => $jwt );
                  $msg = array('stat' => '1', 'msg' => 'token added !');
                  
                } else
                {
                 $err = 'Not created.';
                 $msg = array('stat' => '0', 'msg' => $err); 
                }

        }

//$msg = array('stat' => '0', 'msg' => $newToken); 

}
else {

$msg = array('stat' => '0', 'msg' => 'error with the seller account. Please retry later on !');

}
}





/**
 * IMPORTANT:
 * You must specify supported algorithms for your application. See
 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
 * for a list of spec-compliant algorithms.
 */
//$jwt = JWT::encode($token, $key);
//$decoded = JWT::decode($jwt, $key, array('HS256'));

//print_r($jwt);


//$msg = array('stat' => '1', 'token' => $tokenArray);


//get_object_vars($items[0])["_id"]

$json = $msg;
header('content-type: application/json');
echo json_encode($json);

/*
 NOTE: This will now be an object instead of an associative array. To get
 an associative array, you will need to cast it as such:
*/

//$decoded_array = (array) $decoded;

//print_r(get_object_vars($decoded_array["data"])['path']);
/**
 * You can add a leeway to account for when there is a clock skew times between
 * the signing and verifying servers. It is recommended that this leeway should
 * not be bigger than a few minutes.
 *
 * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
 */
//JWT::$leeway = 60; // $leeway in seconds
//$decoded = JWT::decode($jwt, $key, array('HS256'));

?>
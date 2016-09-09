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
$items = $dataJsonDecode->items;


//print_r($items)
$tokenArray = array();

$max = sizeof($items);
for($i = 0; $i < $max;$i++)
{
$path=get_object_vars($items[$i])["_id"];    
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
/************** At this time, create Token and update DB ************/
$nb = get_object_vars($items[$i])["_quantity"];
$issuedAt   = time();
$notBefore  = $issuedAt;             
$expire     = $notBefore + $nb * 10080;            // Adding 60 seconds

$key = "TheKeyOfTheBioTopeProject";
$token = array(
    "iss" => "http://iotbnb.lo",
    "iat" => $notBefore,
    "nbf" => $issuedAt,
    "exp" => $expire,
    'data' => [                  // Data related to the user
            'userName' => $username, // User name
            'path' => $path
        ]
);

$jwt = JWT::encode($token, $key);

$id = $response["hits"]["hits"][0]["_id"];

$client = Elasticsearch\ClientBuilder::create()->build();

$params = [
    'index' => 'iotbnb',
    'type' => 'secu',
    'id' => $id,
    'body' => [
        'doc' => [
            'token' => $jwt,
            'issuedAt' => $issuedAt,
            'exp' => $expire
        ]
    ]
];


$result = $client->update($params);

//$idT[$i] = array('id' => $path, 'token' => $jwt );

$msg = array('stat' => '1', 'msg' => 'token updated !');

}
else {

    /************** Create Token and update DB ************/

$nb = get_object_vars($items[$i])["_quantity"];
$issuedAt   = time();
$notBefore  = $issuedAt;             
$expire     = $notBefore + $nb * 10080;            // Adding 60 seconds

$path = get_object_vars($items[$i])["_id"];

$key = "TheKeyOfTheBioTopeProject";
$token = array(
    "iss" => "http://iotbnb.lo",
    "iat" => $notBefore,
    "nbf" => $issuedAt,
    "exp" => $expire,
    'data' => [                  // Data related to the user
            'userName' => $username, // User name
            'path' => $path
        ]
);

$jwt = JWT::encode($token, $key);

$params = array();
$params['body']  = array(
  'name' => $name,
  'token' => $jwt,
  'issuedAt' => $issuedAt,
  'exp' => $expire
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
/* TODO */ 

/* if (res = empty){ 
      Create the token
}
else 
      Get the expire time of the stored token and increase the expire date to generate a new one that replace the old one
*/


 

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
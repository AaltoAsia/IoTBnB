<?php
require 'vendor/autoload.php';
//set_include_path('.:/usr/local/pear/share/pear');
//require_once('System.php');


$location="";
$price="";
$reputation="";
$vocabulary="";

$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
if (isset($dataJsonDecode->location)){
$location=$dataJsonDecode->location;
$lat = $dataJsonDecode->location->lat;
$long = $dataJsonDecode->location->long;
$distance = $dataJsonDecode->location->distance;
}
if (isset($dataJsonDecode->price)){
$price = $dataJsonDecode->price;
}
if (isset($dataJsonDecode->reputation)){
$reputation = $dataJsonDecode->reputation;
}
if (isset($dataJsonDecode->vocabulary)){
$vocabulary = $dataJsonDecode->vocabulary;
}

$tata=$dataJsonDecode->tata;
$msg = array('stat' => 'es', 'msg' => 'test only !', 'val' => $tata);


if (isset($location)){
$url="https://biotope.opendatasoft.com/api/records/1.0/search/?dataset=iotbnb-v2&apikey=5677f3197edde5512e65fddbd752eca3056dee9e9693930f18bbb38f&rows=200&facet=path&geofilter.distance=".$lat."%2C".$long."%2C".$distance;

//Get the service description (in the ODS) based only on the location 
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$json = json_decode($response);
curl_close($ch);

//Parse the output of ODS ($json) and produce the needed output
$nbhits = $json->nhits;
$result = $json->records;

//$msg = array('stat' => 'es', 'msg' => 'test only !', 'val' => $location);

$array=[];
for ($i=0;$i<$nbhits;$i++){
$dist=$json->records[$i]->fields->dist; 
$url=$json->records[$i]->fields->url;
$valuetype=$json->records[$i]->fields->valuetype; 
$coordinates=$json->records[$i]->fields->location->coordinates;  
$units=$json->records[$i]->fields->units;
$path=$json->records[$i]->fields->path;
$type=$json->records[$i]->fields->type;
$price=$json->records[$i]->fields->price;
$reputation=$json->records[$i]->fields->reputation;
$format=$json->records[$i]->fields->format;
$vocabulary=$json->records[$i]->fields->vocabulary;
$metadata=$json->records[$i]->fields->metadata;

$entireURL=$url."Objects/".$path;
$entireURLarray[$i]=$entireURL;

$arr[$i]= array('type' => $type, 'url' => $entireURL, 'price' => $price, 'reputation' => $reputation, 'format' => $format,
  'vocabulary' => $vocabulary, 'metadata' => $metadata );
}

$result=$arr;
//$msg = array('results' => $result);
    
}
else
{
$msg = array('stat' => '0', 'msg' => 'error !');
}

$json = $msg;
header('content-type: application/json');
echo json_encode((object)$json);

?>
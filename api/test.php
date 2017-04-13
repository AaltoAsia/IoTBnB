<?php
require 'vendor/autoload.php';
//set_include_path('.:/usr/local/pear/share/pear');
//require_once('System.php');


$tata="";

$tata=$dataJsonDecode->tata;
$msg = array('stat' => 'es', 'msg' => 'test only !', 'val' => $tata);

$json = $msg;
header('content-type: application/json');
echo json_encode((object)$json);

?>
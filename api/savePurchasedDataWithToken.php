<?php
//author: J. Robert
//creation date: 07/06/2017
//modification date: 07/06/2017
//require 'vendor/autoload.php';
//use \Firebase\JWT\JWT;

$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;
$items = $dataJsonDecode->items;
//$arrayUrlOMInode = $dataJsonDecode->urlOMI;
//$arrayHID = $dataJsonDecode->hid;
$arrayServices = $dataJsonDecode->services;
$newToken = $dataJsonDecode->token;


if($username){
include './credentials/db.php';

//mysql_connect($servernameDB,$usernameDB,$passwordDB);
//mysql_select_db($dbname);

$conn = mysqli_connect($servernameDB,$usernameDB,$passwordDB,$dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$msg=array();

$max = sizeof($items);

 for($i = 0;$i<$max;$i++)
 {
  //$dataURLitem=get_object_vars($items[$i])["_id"]; 
  $dataURLitem=$arrayServices[$i];
  $price=get_object_vars($items[$i])["_price"];
  //$price=$items[$i]["_price"];
  $APIcalls=0;
  //$urlOMI=$arrayUrlOMInode[$i];
  //$path=$arrayHID[$i];

//}

//END 

  //$msg = array('stat' => '1', 'msg' => $newToken);
$existence=0;


$sql = 'SELECT * FROM `PurchasedData` WHERE `id_user`=(SELECT `id` FROM `User` WHERE `login`="'.$username.'")&&`dataURL`="'.$dataURLitem.'"';
//$result = mysql_query($sql);
$result = mysqli_query($conn, $sql);

while ($row = $result->fetch_assoc()) {
    $idUser=$row["id_user"];
    //$currentURL=$row["dataURL"];

    //if (strcmp($currentURL, $dataURLitem)==0){
      $existence=1;
    //} 
    }
  
  

  if ($existence==1){
    $sql123 = 'UPDATE `PurchasedData` SET `dataURL`="'.$dataURLitem.'",`price`="'.$price.'",`APIcalls`="'.$APIcalls.'",`Token`="'.$newToken.'" WHERE `id_user`="'.$idUser.'"&&`dataURL`="'.$dataURLitem.'"';
      //$result2 = mysql_query($sql123);
      $result2 = mysqli_query($conn, $sql123);
      if ($result2) {
        $msg = array('stat' => '1', 'msg' => "Purchased data updated!");
      }
      else 
      {
        $msg = array('stat' => '0', 'msg' => "Purchased data not updated!");
      }
  }
  else {
      $sql1 = 'SELECT `id` FROM `User` WHERE `login`="'.$username.'"';
        //$result1 = mysql_query($sql1);
        $result1 = mysqli_query($conn, $sql1);
        if ($result1){

        //while ($row = mysql_fetch_assoc($result1)) {
          while ($row = $result1->fetch_assoc()) {
          $idUser=$row["id"];
        }
      }
      else {
        $errorSQL="error SQL";
      }
  
    $sql2 = 'INSERT INTO `PurchasedData`(`id_user`, `dataURL`, `price`, `APIcalls`, `Token`) VALUES ("'.$idUser.'","'.$dataURLitem.'","'.$price.'","'.$APIcalls.'","'.$newToken.'")';
      //$result3 = mysql_query($sql2);
      $result3 = mysqli_query($conn, $sql2);

      if ($result3) {
        $msg = array('stat' => '1', 'msg' => "Purchased data created!");
      }
      else 
      {
        $msg = array('stat' => '0', 'msg' => "Purchased data not created!".$idUser."+".$errorSQL);
      }
  }

  $existence=0;
 }
  
  //while ($row = mysql_fetch_assoc($result)) {
  

//mysql_close();
 mysqli_close($conn);

//$msg = array('stat' => '0', 'msg' => "OK");

$json = $msg;
header('content-type: application/json');
echo json_encode($json);
}
?>
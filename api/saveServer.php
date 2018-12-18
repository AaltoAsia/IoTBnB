<?php

$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;
$omiURL = $dataJsonDecode->omiURL;
$omiName = $dataJsonDecode->omiName;
$omiAddr = $dataJsonDecode->omiAddr;
$themes = $dataJsonDecode->themes; 
$secuUrl = $dataJsonDecode->secuUrl; 
$clientId = $dataJsonDecode->clientId; 
$clientSecret = $dataJsonDecode->clientSecret; 
$omiVersion = $dataJsonDecode->omiVersion; 
$billingUrl = $dataJsonDecode->billingUrl; 

if($username){
include './credentials/db.php';

//mysql_connect($servernameDB,$usernameDB,$passwordDB);
//mysql_select_db($dbname);

$conn = mysqli_connect($servernameDB,$usernameDB,$passwordDB,$dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

  $sql = 'SELECT * FROM `OmiServer` WHERE `id_user`=(SELECT `id` FROM `User` WHERE `login`="'.$username.'")';
   //$result = mysql_query($sql);
  $result = mysqli_query($conn, $sql);

$msg=array();
$i=0;
if ($result) {
  // output data of each row
  //while ($row = mysql_fetch_assoc($result)) {
  while ($row = $result->fetch_assoc()) {
    $i++;
    $idUser=$row["id_user"];
    // $msg[] = array('stat' => '1','msg' => $row["email"]);
  }
  if ($i==1){

  //$sql = 'UPDATE `OmiServer` SET `url`="'.$omiURL.'",`name`="'.$omiName.'",`address`="'.$omiAddr.'",`themes`="'.$themes.'" WHERE `id_user`="'.$idUser.'"';
  $sql = 'UPDATE `OmiServer` SET `url`="'.$omiURL.'",`name`="'.$omiName.'",`address`="'.$omiAddr.'",`version`="'.$omiVersion.'",`themes`="'.$themes.'",`secuUrl`="'.$secuUrl.'", 
  `clientId`="'.$clientId.'", `clientSecret`="'.$clientSecret.'", `billingUrl`="'.$billingUrl.'" WHERE `id_user`="'.$idUser.'"';
  //$result2 = mysql_query($sql);
  $result2 = mysqli_query($conn, $sql);


  if ($result2) {
    $msg = array('stat' => '1', 'msg' => "User profile (OMI server) updated!");
  }
  else 
  {
    $msg = array('stat' => '0', 'msg' => "User profile (OMI server) not updated!");
  }
  }
  else{

    $sql = 'SELECT id, login, email FROM `User` WHERE login="'.$username.'"';
    //$result = mysql_query($sql);
    $result = mysqli_query($conn, $sql);

    if ($result) {
  // output data of each row
        //while ($row = mysql_fetch_assoc($result)) {
        while ($row = $result->fetch_assoc()) {
        $idUser=$row["id"];
        // $msg[] = array('stat' => '1','msg' => $row["email"]);
        }
    }


    //$sql = 'INSERT INTO `OmiServer`(`id_user`, `url`, `name`, `address`, `themes`) VALUES ("'.$idUser.'","'.$omiURL.'","'.$omiName.'","'.$omiAddr.'","'.$themes.'")';
  $sql = 'INSERT INTO `OmiServer`(`id_user`, `url`, `name`, `address`, `version`, `themes`, `secuUrl`, `clientId`, `clientSecret`,`billingUrl`) VALUES ("'.$idUser.'","'.$omiURL.'","'.$omiName.'","'
      .$omiAddr.'","'.$omiVersion.'","'.$themes.'","'.$secuUrl.'","'.$clientId.'","'.$clientSecret.'","'.$billingUrl.'")';
  //$result3 = mysql_query($sql);
  $result3 = mysqli_query($conn, $sql);

  if ($result3) {
    $msg = array('stat' => '1', 'msg' => "User profile (OMI server) created!");
  }
  else 
  {
    $msg = array('stat' => '0', 'msg' => "User profile (OMI server) not created!".$sql);
  }

  }

} 
else {

  $err = 'Database unreachable ';
  $msg = array('stat' => '0', 'msg' => $err);
  //echo "0 results";
}

//mysql_close();
 mysqli_close($conn);

$json = $msg;

//echo 'toto';
header('content-type: application/json');
echo json_encode($json);
}

?>
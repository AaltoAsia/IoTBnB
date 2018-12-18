<?php

$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;
$wallet = $dataJsonDecode->wallet;

if($username){
include './credentials/db.php';

//mysql_connect($servernameDB,$usernameDB,$passwordDB);
//mysql_select_db($dbname);

$conn = mysqli_connect($servernameDB,$usernameDB,$passwordDB,$dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

  $sql = 'SELECT * FROM `Wallet` WHERE `id_user`=(SELECT `id` FROM `User` WHERE `login`="'.$username.'")';
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

  $sql = 'UPDATE `Wallet` SET `walletAddress`="'.$wallet.'" WHERE `id_user`="'.$idUser.'"';
  //$result2 = mysql_query($sql);
  $result2 = mysqli_query($conn, $sql);


  if ($result2) {
    $msg = array('stat' => '1', 'msg' => "User profile (wallet) updated!");
  }
  else 
  {
    $msg = array('stat' => '0', 'msg' => "User profile (wallet) not updated!");
  }
  }
  else{

    $sql = 'SELECT id, login, email FROM `User` WHERE login="'.$username.'"';
    $result = mysql_query($sql);

    if ($result) {
  // output data of each row
        //while ($row = mysql_fetch_assoc($result)) {
        while ($row = $result->fetch_assoc()) {
        $idUser=$row["id"];
        // $msg[] = array('stat' => '1','msg' => $row["email"]);
        }
    }


    $sql = 'INSERT INTO `Wallet`(`id_user`,`walletAddress`) VALUES ("'.$idUser.'","'.$wallet.'")';
  //$result3 = mysql_query($sql);
  $result3 = mysqli_query($conn, $sql);

  if ($result3) {
    $msg = array('stat' => '1', 'msg' => "User profile (wallet) created!");
  }
  else 
  {
    $msg = array('stat' => '0', 'msg' => "User profile (wallet) not created!".$sql);
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
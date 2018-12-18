<?php
//author: J. Robert
//creation date: 07/06/2017
//modification date: 07/06/2017

$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;

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

if ($result) {
  // output data of each row
  //while ($row = mysql_fetch_assoc($result)) {
  	while ($row = $result->fetch_assoc()) {
  	$walletAddress=$row["walletAddress"];
  }

  $msg = array('stat' => '1', 'walletAddress' => $walletAddress); 
}
else {
  $err = 'Database unreachable ';
  $msg = array('stat' => '0', 'msg' => $err);
}

//mysql_close();
 mysqli_close($conn);

//$msg = array('stat' => '0', 'msg' => "OK");

$json = $msg;
header('content-type: application/json');
echo json_encode($json);
}
?>
<?php
//author: J. Robert
//creation date: 01/03/2016
//modification date: 07/06/2017

$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;

if($username){
include './credentials/db.php';

$conn = mysqli_connect($servernameDB,$usernameDB,$passwordDB,$dbname);

//mysql_connect($servernameDB,$usernameDB,$passwordDB);

//mysql_select_db($dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

  $sql = 'SELECT * FROM `OmiServer` WHERE `url`="'.$username.'"';
  //$result = mysql_query($sql);
  $result = mysqli_query($conn, $sql);

$msg=array();
if ($result) {
  // output data of each row
  //while ($row = mysql_fetch_assoc($result)) {
  while ($row = $result->fetch_assoc()) {

    $msg = array('stat' => '1', 'username' => $username, 'omiURL' => $row["url"], 'omiName' => $row["name"], 'omiAddr' => $row["address"], 'themes' => $row["themes"],
      'secuUrl' => $row["secuUrl"],'clientId' => $row["clientId"],'clientSecret' => $row["clientSecret"], 'omiVersion' => $row["version"], 'billingUrl' => $row["billingUrl"]);
  }
    // $msg[] = array('stat' => '1','msg' => $row["email"]);
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
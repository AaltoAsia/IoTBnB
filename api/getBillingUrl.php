<?php
//author: J. Robert
//creation date: 07/03/2018
//modification date: 07/03/2018

$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$urlNode = $dataJsonDecode->urlNode;

if($username){
include './credentials/db.php';

$conn = mysqli_connect($servernameDB,$usernameDB,$passwordDB,$dbname);

//mysql_connect($servernameDB,$usernameDB,$passwordDB);

//mysql_select_db($dbname);
$msg=array();
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
     $msg = array('stat' => '0', 'msg' => "error");
}

  $sql = 'SELECT * FROM `OmiServer` WHERE `url`="'.$urlNode.'"';
  //$result = mysql_query($sql);
  $result = mysqli_query($conn, $sql);


if ($result) {
  // output data of each row
  //while ($row = mysql_fetch_assoc($result)) {
  while ($row = $result->fetch_assoc()) {

    $msg = array('stat' => '1', 'billingUrl' => $row["billingUrl"]);
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
$msg = array('stat' => '1');
//echo 'toto';
header('content-type: application/json');
echo json_encode($json);
}
?>
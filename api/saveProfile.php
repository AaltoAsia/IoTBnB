<?php

$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;
$email = $dataJsonDecode->email;


if($username){
include './credentials/db.php';

//mysql_connect($servernameDB,$usernameDB,$passwordDB);
//mysql_select_db($dbname);

$conn = mysqli_connect($servernameDB,$usernameDB,$passwordDB,$dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

  $sql = 'SELECT id, login, email FROM `User` WHERE login="'.$username.'"';
  //$result = mysql_query($sql);
  $result = mysqli_query($conn, $sql);


$msg=array();
$i=0;
if ($result) {
  // output data of each row
  //while ($row = mysql_fetch_assoc($result)) {
  while ($row = $result->fetch_assoc()) {
    $i++;
    // $msg[] = array('stat' => '1','msg' => $row["email"]);
  }
  if ($i==1){

  $sql = 'UPDATE `User` SET `email`="'.$email.'" WHERE `login`="'.$username.'"';
  //$result2 = mysql_query($sql);
  $result2 = mysqli_query($conn, $sql);

  if ($result2) {
    $msg = array('stat' => '1', 'msg' => "User profile updated!");
  }
  else 
  {
    $msg = array('stat' => '0', 'msg' => "User profile not updated!");
  }
  }
  else{
    $sql = 'INSERT INTO `User`(`login`, `email`) VALUES ("'.$username.'","'.$email.'")';
  //$result3 = mysql_query($sql);
  $result3 = mysqli_query($conn, $sql);

  if ($result3) {
    $msg = array('stat' => '1', 'msg' => "User profile created!");
  }
  else 
  {
    $msg = array('stat' => '0', 'msg' => "User profile not created!");
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
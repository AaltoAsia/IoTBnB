<?php
require 'vendor/autoload.php';
//set_include_path('.:/usr/local/pear/share/pear');
//require_once('System.php');

$data = file_get_contents("php://input");
$dataJsonDecode = json_decode($data);
$username = $dataJsonDecode->username;
$email = $dataJsonDecode->email;
$omiURL = $dataJsonDecode->omiURL;
$omiName = $dataJsonDecode->omiName;
$omiAddr = $dataJsonDecode->omiAddr;

$client = Elasticsearch\ClientBuilder::create()->build();

$params = [
    'index' => 'iotbnb',
    'type' => 'users',
    'body' => [
        'query' => [
            'match' => [
                'name' => $username
            ]
        ]
    ]
];

$response = $client->search($params);


if ($response["hits"]["total"]==1)
{

$id = $response["hits"]["hits"][0]["_id"];

$client = Elasticsearch\ClientBuilder::create()->build();


$params = [
    'index' => 'iotbnb',
    'type' => 'users',
    'id' => $id,
    'body' => [
        'doc' => [
            'email' => $email,
            'omiURL' => $omiURL,
            'omiName' => $omiName,
            'omiAddr' => $omiAddr
        ]
    ]
];


$result = $client->update($params);



}
else {

$params = array();
$params['body']  = array(
  'name' => $username,
  'password' => $password,
  'email' => $email,
  'omiURL' => null,
  'omiName' => null,
  'omiAddr' => null
);

$params['index'] = 'iotbnb';
$params['type']  = 'users';

$result = $client->index($params);

if ($result["created"]==true)
{
  $msg = array('stat' => '1', 'msg' => 'user added !');
  
} else
{
 $err = 'Not created.';
 $msg = array('stat' => '0', 'msg' => $err); 
}
  //$msg = array('stat' => '0', 'msg' => "Please, contact the administrator of the website");
}


//$msg = array('test' => '1');

$json = $msg;
header('content-type: application/json');
echo json_encode($json);


  //$base = mysql_connect ('localhost:3306/', 'root', 'mysqlpass');
  //mysql_select_db ('IoTBnB', $base);
/*
$servernameDB = "localhost:3306/";
$usernameDB = "root";
$passwordDB = "mysqlpass";
$dbname = "IoTBnB";

// Create connection
$conn = mysqli_connect($servernameDB, $usernameDB, $passwordDB, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

  // on teste si une entrée de la base contient ce couple login / pass
  $sql = 'SELECT count(*) FROM Members WHERE login="'.$username.'" AND password="'.md5($password).'"';
  //$req = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
  //$data = mysql_fetch_array($req);

 //$result = $conn->query($sql);
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
      while($row = $result->fetch_assoc()) {
      if ($row["count(*)"] == 0){
        $sql = 'INSERT INTO Members (`login`, `password`,`email`) VALUES ("'.$username.'", "'.md5($password).'", "'.$email.'")';
        if (mysqli_query($conn, $sql)){
              $sql = 'INSERT INTO `omiNode`(`id_member`) SELECT `Members`.id FROM `Members` WHERE `Members`.`login`="'.$username.'"';
              if (mysqli_query($conn, $sql)){
        $msg = array('stat' => '1', 'msg' => 'user added !');
      }
      else{
        $msg = array('stat' => '0', 'msg' => 'Second insert error ! ');
      }
      }
      else {
        $msg = array('stat' => '0', 'msg' => 'insert error ! ');
      }

      }
      elseif ($row["count(*)"] >= 1) {
        $err = 'Existing account. ';
        $msg = array('stat' => '0', 'msg' => $err);
      }
    }
  }
      
      //  echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";    
 else {
          $err = 'Database unreachable ';
          $msg = array('stat' => '0', 'msg' => $err);
      //echo "0 results";
}



  //mysql_free_result($req);
  mysqli_close($conn);

  $json = $msg;
 
  header('content-type: application/json');
  echo json_encode($json);
*/


?>
<?php 
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = "";
$DATABASE_NAME = 'licenta';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

$token = $_GET['token'];
if ($stmt = $con->prepare('SELECT user_id, password FROM users WHERE username = ?')) {
  $stmt->bind_param('s', $_GET['username']);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    if($stmt = $con->prepare('UPDATE users SET Activated = 1 WHERE username = ?')){
      $stmt->bind_param('s',$_GET['username']);
      $stmt->execute();
      echo "Your accound has been validated";
    }else{
      exit('Nu s-a putut verifica token-ul');
    }
  } else {
    exit('Username-ul nu a fost gasit');
  }
  $stmt->close();
} else {
  echo 'Nu se poate face prepare statement!';
}
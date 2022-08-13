<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = "";
$DATABASE_NAME = 'licenta';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    exit('Password must be 5-20 characters long!');
}

$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$query = "UPDATE users SET password = '$password' WHERE username = '".$_POST['username']."'";
if (mysqli_query($con, $query)) {
    echo "Your password has been updated";
} else {
    exit('User cannot change password');
}

<?php
session_start();
require("../../../vendor/sendgrid/sendgrid-php/sendgrid-php.php");

use SendGrid\Mail\Mail;

include("../../../database/conectare.php");
$error = '';

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = "";
$DATABASE_NAME = 'licenta';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
    exit('Nu se poate conecta la MySQL: ' . mysqli_connect_error());
}
if (!isset($_POST['last_name'], $_POST['first_name'], $_POST['username'], $_POST['email'], $_POST['password'])) {
    exit('Fill out the form!');
}
if (empty($_POST['last_name']) || empty($_POST['first_name']) || empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
    exit('Fill out the form2!');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    exit('Invalid email!');
}
if (preg_match('/@stud.ubbcluj.ro/', $_POST['email']) == 0) {
    // exit('Invalid email!');
}

if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
    exit('Invalid username!');
}

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    exit('Password must be 5-20 characters long!');
}

if ($stmt = $con->prepare('SELECT user_id, password FROM users WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'Username already exists';
    } else {
        $token = rand(10000000, 99999999);
        if ($stmt = $con->prepare('INSERT INTO users (last_name, first_name, username, password, email, token) VALUES (?, ?, ?, ?, ?,?)')) {

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->bind_param('ssssss', $_POST['last_name'], $_POST['first_name'], $_POST['username'], $password, $_POST['email'], $token);
            $stmt->execute();
            sendAccountValidationEmail($_POST['email'], $_POST['username'], $token);
            echo 'Success!';
            header('Location: /licenta/resources/views/login.html');
        } else {
            echo 'Nu se poate face prepare statement!';
        }
    }
    $stmt->close();
} else {
    echo 'Nu se poate face prepare statement!';
}
$con->close();

function sendAccountValidationEmail($address, $username, $token)
{
    $email = new Mail();
    $email->setFrom("denisa.hosu8@gmail.com", "UBBdonates support");
    $email->setSubject("Account validatioon");
    $email->addTo($address, "Support");
    $email->addContent(
        "text/html",
        "Please click <a href='http://localhost/licenta/app/http/controllers/verified.php?username=$username&token=$token' target='_blank'>this</a> link to activate your account"
    );
    $sendgrid = new \SendGrid('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');
    try {
        $response = $sendgrid->send($email);
    } catch (Exception $e) {
        echo 'Caught exception: ' .  $e->getMessage() . "\n";
    }
}

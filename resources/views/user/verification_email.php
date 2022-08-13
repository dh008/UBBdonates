<?php
require("../licenta/vendor/sendgrid/sendgrid-php/sendgrid-php.php");
use SendGrid\Mail\Mail;

$email = new Mail();
$email->setFrom("", "Example User");
$email->setSubject("Sending with Twilio SendGrid is Fun");
$email->addTo(" test@stud.ubbcluj.ro", "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
);
$sendgrid = new \SendGrid(getenv(''));
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '.  $e->getMessage(). "\n";
}
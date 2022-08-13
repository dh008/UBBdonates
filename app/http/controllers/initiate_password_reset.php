<?php
session_start();
require("../../../vendor/sendgrid/sendgrid-php/sendgrid-php.php");
include("../../../database/conectare.php");
use SendGrid\Mail\Mail;

if(!empty($_GET['username'])) {
    $username = $_GET['username'];
    $email = $_GET['email'];

    $query = "SELECT token FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($mysqli, $query)or die("An unexpected error occured");

    if($result->num_rows == 0) exit("Couldn't find this username");

    $user = $result->fetch_assoc();
    $token = $user['token'];

    sendFundraiserRejectionEmail($email, $username, $token);
    exit("Please check your email for further information");
}

function sendFundraiserRejectionEmail($address, $username, $token)
{
    $email = new Mail();
    $email->setFrom("denisa.hosu8@gmail.com", "UBBdonates support");
    $email->setSubject("Reset password");
    $email->addTo($address, "Customer");
    $email->addContent(
        "text/html",
        "Please click <a href='http://localhost/licenta/app/http/controllers/forgot_password.php?username=$username&token=$token' target='_blank'>this</a> link to reset your password"
    );
    $sendgrid = new \SendGrid('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');
    try {
        $response = $sendgrid->send($email);
    } catch (Exception $e) {
        echo 'Caught exception: ' .  $e->getMessage() . "\n";
    }
}
?>

<head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot password</title>
        <link rel="stylesheet" href="../../../public/css/style.css">

    </head>

    <body>
        <div class="container">
            <div class="circle">
                <form action="" class="sign-form" method="get">
                    <h3>Send the reset password email</h3>

                    <input type="text" class="form-input" name="username" placeholder="Username">
                    <input type="email" class="form-input" name="email" placeholder="Email" required>

                    <input type="submit" class="form-submit" value="Send">
                </form>
            </div>
        </div>
    </body>


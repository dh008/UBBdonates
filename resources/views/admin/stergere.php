<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /licenta/resources/views/login.html');
  exit;
}
require("../../../vendor/sendgrid/sendgrid-php/sendgrid-php.php");

use SendGrid\Mail\Mail;

include("../../../database/conectare.php");
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id = $_GET['id'];

  $query = "SELECT user_email FROM fundraising WHERE fundraising_id = $id LIMIT 1";
  $result = mysqli_query($mysqli, $query) or die("Couldn't find any fundraisers with that id");
  $row = $result->fetch_assoc();
  $user_email = $row['user_email'];

  if ($stmt = $mysqli->prepare("DELETE FROM fundraising WHERE fundraising_id = ? LIMIT 1")) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    sendFundraiserRejectionEmail($user_email);
    $stmt->close();
  } else {
    $success = false;
  }
  $mysqli->close();
  $success = true;
}

function sendFundraiserRejectionEmail($address)
{
  $email = new Mail();
  $email->setFrom("denisa.hosu8@gmail.com", "UBBdonates support");
  $email->setSubject("Fundraiser declined");
  $email->addTo($address, "Customer");
  $email->addContent(
    "text/html",
    "Your fundraiser didn't meet the requirements. It has been declined"
  );
  $sendgrid = new \SendGrid('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');
  try {
    $response = $sendgrid->send($email);
  } catch (Exception $e) {
    echo 'Caught exception: ' .  $e->getMessage() . "\n";
  }
}
?>

<!DOCTYPE html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stergere</title>
  <link rel="stylesheet" href="../../../public/css/style.css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
</head>

<body>
  <div class="container">
    <section class="menu">
      <div class="logo">
        <img src="../../../storage/app/media/logo.png">
        <h2>UBBdonates</h2>
      </div>
      <div class="user">
        <i class="far fa-user"></i>
        <span>Hello,<?= $_SESSION['name'] ?></span>
      </div>
      <div class="items">
        <a class="active" href=""><i class="fas fa-globe-europe"></i>Delete</a>
        <a href="dashboard.php"><i class="fas fa-globe-europe"></i>Users</a>
        <a href="all.php"><i class="fas fa-globe-europe"></i>Accept Fundraising</a>
        <a href="customers.php"><i class="fas fa-sign-out-alt"></i>All donations</a>
        <a href="transactions.php"><i class="fas fa-sign-out-alt"></i>All transactions</a>
        <a href="feedbacks.php"><i class="fas fa-award"></i>Feedbacks</a>
        <a href="../../../app/http/controllers/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
      </div>
    </section>
    
    <div class="content">
      <?php
        if($success == true) {
          echo "<p>---Inregistrarea a fost stearsa---</p>";
        } else {
          echo "<p>Nu s-a putut sterge inregistrarea</p>";
        }
      ?>
    </div>
  </div>
</body>

</html>
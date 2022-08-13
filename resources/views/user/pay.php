<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /licenta/resources/views/user/fundraiser.php');
  exit;
}

$id = $_GET['fundraiserId'];
$title = $_GET['fundraiserName'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pay Page</title>
  <link rel="stylesheet" href="../../../public/css/style.css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
</head>

<body>
  <div class="container">
    <section class="menu">
      <div class="logo">
        <img src="../../../storage/app/media/logo.png">
        <h2>FSEGAdonates</h2>
      </div>
      <div class="user">
        <i class="far fa-user"></i>
        <span>Hello,<?= $_SESSION['name'] ?></span>
      </div>
      <div class="items">
      <a href="fundraiser.php"><i class="fas fa-donate"></i>Start a fundraiser</a>
        <a href="donate.php"><i class="fas fa-globe-europe"></i>All donations</a>
        <a href="feedback.php"><i class="fas fa-award"></i>Feedback</a>
        <a href="../../../app/http/controllers/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
      </div>
    </section>
    <div class="content">
      <h2>Donate now for</h2>
      <form action="./charge.php" class="sign-form" method="post" id="payment-form">
        <div class="content">
          <h2><?php echo $title ?></h2>
          <input type="hidden" name="fundraiser_id" value='<?php echo $id ?>'>
          <input type="text" name="first_name" class="form-input w50" placeholder="First Name">
          <input type="text" name="last_name" class="form-input w50" placeholder="Last Name">
          <input type="number" name="amount" class="form-input w50" placeholder="Donation Amount (EUR)">
          <input type="email" name="email" class="form-input w50" placeholder="Email Address">
          <div id="card-element" class="form-control" style="width:50%">
          </div>
          <div id="card-errors" role="alert"></div>
        </div>
        <button>Submit Payment</button>
      </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="../../../public/js/charge.js"></script>

</body>

</html>
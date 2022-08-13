<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /licenta/resources/views/user/login.html');
  exit;
}
include("../../../database/conectare.php");
$error = '';
if (isset($_POST['submit'])) {
  $feedback = htmlentities($_POST['feedback'], ENT_QUOTES);
  
  if ($feedback == '') {
    $error = 'ERROR: Campuri goale!';
  } else {
    if ($stmt = $mysqli->prepare("INSERT into feedbacks (feedback) VALUES (?)")) {
      $stmt->bind_param("s", $feedback,);
      $stmt->execute();
      $stmt->close();
      header('Location: /licenta/resources/views/user/donate.php');
    } else {
      echo "ERROR: Nu se poate executa insert.";
    }
  }
}

$mysqli->close();
?>

<!DOCTYPE html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback</title>
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
        <a href="fundraiser.php"><i class="fas fa-donate"></i>Start a fundraiser</a>
        <a href="donate.php"><i class="fas fa-globe-europe"></i>All donations</a>
        <a class="active" href="feedback.php"><i class="fas fa-award"></i>Feedback</a>
        <a href="../../../app/http/controllers/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
      </div>
    </section>

    <div class="content">
      <h2>Give us feedback </h2>
      <p class="sign-title">
        <?php if ($error != '') {
          echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
        } ?></p>
      <form action="feedback.php" method="post" class="sign-form" enctype="multipart/form-data">
        <label> </label>
        <textarea class="form-input descriptionarea" placeholder="Feedback" rows="5" name="feedback"></textarea>
        <button class="form-submit" name="submit">Send</button>
      </form>
    </div>
  </div>
</body>

</html>
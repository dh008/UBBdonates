<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /licenta/resources/views/login.html');
  exit;
}
include("../../../database/conectare.php");
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id = $_GET['id'];
  if ($stmt = $mysqli->prepare("UPDATE fundraising SET approved=1 WHERE fundraising_id = ? LIMIT 1")) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
  } else {
    $success = false;
  }
  $mysqli->close();
  $success = true;
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
        <a class="active" href=""><i class="fas fa-globe-europe"></i>Accept</a>
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
      if ($success == true) {
        echo "<p>---Inregistrarea a fost acceptata---</p>";
      } else {
        echo "<p>Nu s-a putut sterge inregistrarea</p>";
      }
      ?>
    </div>
  </div>

</body>

</html>
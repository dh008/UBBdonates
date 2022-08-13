<?php

session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /licenta/resources/views/login.html');
  exit;
}

require_once("../../../database/conectare.php");

$query = "SELECT username, last_name, first_name, email FROM users";
$users = mysqli_query($mysqli, $query)or die("Couldn't fetch users");
?>
<!DOCTYPE html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
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
        <a class="active" href="dashboard.php"><i class="fas fa-globe-europe"></i>Users</a>
        <a href="all.php"><i class="fas fa-globe-europe"></i>Accept Fundraising</a>
        <a href="customers.php"><i class="fas fa-sign-out-alt"></i>All donations</a>
        <a href="transactions.php"><i class="fas fa-sign-out-alt"></i>All transactions</a>
        <a href="feedbacks.php"><i class="fas fa-award"></i>Feedbacks</a>
        <a href="../../../app/http/controllers/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
      </div>
    </section>

    <div class="content">
      <h2>Users</h2>
      <table class="transactions-table">
        <thead>
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($user = mysqli_fetch_assoc($users)) : ?>
            <tr>
              <td><?php echo $user["first_name"]; ?></td>
              <td><?php echo $user["last_name"]; ?></td>
              <td><?php echo $user["username"]; ?></td>
              <td><?php echo $user["email"]; ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
  </div>
</body>
<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /licenta/resources/views/login.html');
  exit;
}
require_once('../../../config/db.php');
require_once('../../../database/pdo_db.php');
require_once('../../../app/models/Transaction.php');

$transaction = new Transaction();

$transactions = $transaction->getTransactions();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../../../public/css/style.css">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

  <title>View Transactions</title>
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
        <a href="dashboard.php"><i class="fas fa-globe-europe"></i>Usersd</a>
        <a href="all.php"><i class="fas fa-globe-europe"></i>Accept Fundraising</a>
        <a href="customers.php"><i class="fas fa-sign-out-alt"></i>All donations</a>
        <a class="active" href="transactions.php"><i class="fas fa-sign-out-alt"></i>All transactions</a>
        <a href="feedbacks.php"><i class="fas fa-award"></i>Feedbacks</a>
        <a href="../../../app/http/controllers/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
      </div>
    </section>

    <div class="content">
      <h2>Transactions</h2>
      <table class="transactions-table">
        <thead>
          <tr>
            <th>Transaction ID</th>
            <th>Donor</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transactions as $t) : ?>
            <tr>
              <td><?php echo $t->id; ?></td>
              <td><?php echo $t->customer_id; ?></td>
              <td><?php echo $t->product; ?></td>
              <td><?php echo sprintf('%.2f', $t->amount / 100); ?> <?php echo strtoupper($t->currency); ?></td>
              <td><?php echo $t->created_at; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>
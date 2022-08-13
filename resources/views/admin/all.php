<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /licenta/resources/views/login.html');
  exit;
}
?>
<!DOCTYPE html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vizualizare</title>
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
        <a href="dashboard.php"><i class="fas fa-globe-europe"></i>Users</a>
        <a class="active" href="all.php"><i class="fas fa-globe-europe"></i>Accept Fundraising</a>
        <a href="customers.php"><i class="fas fa-sign-out-alt"></i>All donations</a>
        <a href="transactions.php"><i class="fas fa-sign-out-alt"></i>All transactions</a>
        <a href="feedbacks.php"><i class="fas fa-award"></i>Feedbacks</a>
        <a href="../../../app/http/controllers/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
      </div>
    </section>

    <div class="content">
      <h2>Form strangeri de fonduri</h2>
      <?php
      include("../../../database/conectare.php");
      if ($result = $mysqli->query("SELECT * FROM fundraising WHERE approved = 0 ORDER BY fundraising_id ")) {
        if ($result->num_rows > 0) {
          echo "<table class='transactions-table'>";
          echo "<tr><th>Nume</th><th>Descriere</th><th>Suma</th><th></th></tr>";
          while ($row = $result->fetch_object()) {
            echo "<tr>";
            echo "<td>" . $row->title . "</td>";
            echo "<td>" . $row->description . "</td>";
            echo "<td>" . $row->amount . "</td>";
            echo "<td><a class='modify' href='aprobare.php?id=" . $row->fundraising_id . "'>Aproba</a></td>";
            echo "<td><a class='modify' href='modificare.php?id=" . $row->fundraising_id . "'>Modifica</a></td>";
            echo "<td><a class='modify' href='stergere.php?id=" . $row->fundraising_id . "'>Sterge</a></td>";
            echo "</tr>";
          }
          echo "</table>";
        } else {
          echo "Nu sunt inregistrari!";
        }
      } else {
        exit("An error occured when trying to get the fundraisers");
      }
      $mysqli->close();
      ?>
    </div>
  </div>



</body>

</html>
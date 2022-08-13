<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /licenta/resources/views/user/fundraiser.php');
  exit;
}
?>
<!DOCTYPE html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donate</title>
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
        <a class="active" href="donate.php"><i class="fas fa-globe-europe"></i>All donations</a>
        <a href="feedback.php"><i class="fas fa-award"></i>Feedback</a>
        <a href="../../../app/http/controllers/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
      </div>
    </section>

    <div class="content">
      <div class="donation-slider">
        <?php
        include("../../../database/conectare.php");
        if ($result = $mysqli->query("SELECT * FROM fundraising WHERE approved = 1 ORDER BY fundraising_id ")) {
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_object()) {
              $donated_result = $mysqli->query("SELECT fundraising_id, SUM(amount) as sum_amount FROM fundraising_transactions WHERE fundraising_id = $row->fundraising_id GROUP BY fundraising_id");
              $donated = 0;

              if($donated_result->num_rows > 0) {
                $donated_row = $donated_result->fetch_object();
                $donated = $donated_row->sum_amount;
              }

              echo "<div class='donation-card'>";
              echo "<h3>";
              echo $row->title;
              echo "</h3>";
              $image = $row->image;
              echo "<div class='image'>";
              echo "<img src='../../../storage/app/uploads/$image'>";
              echo "</div>";
              echo "<p class='description'>";
              echo  $row->description;
              echo "</p>";
              echo "<p class='goal'>";
              echo "<span>Goal amount</span>";
              echo "<span>$$row->amount</span>";
              echo "<span>Progress</span>";
              echo "<span>$$donated / $$row->amount</span>";
              echo "</p>";
              echo "<a class='donate' href='pay.php?fundraiserName=".$row->title."&fundraiserId=".$row->fundraising_id."'>Donate now</a>";
              echo "</div>";
            }
          } else {
            echo "Nu exista inregistrari!";
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


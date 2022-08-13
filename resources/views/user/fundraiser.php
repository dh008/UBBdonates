<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /licenta/resources/views/user/login.html');
  exit;
}
include("../../../database/conectare.php");
$error = '';
if (isset($_POST['submit'])) {
  $path = dirname(dirname(dirname(getcwd()))) . '/storage/app/uploads/';
  $fileOriginalName = $_FILES['image']['name'];
  $fileExt = explode('.', $fileOriginalName);
  $fileActualExt = strtolower(end($fileExt));
  $filename = rand(1000000, 9999999) . "." . $fileActualExt;
  $title = htmlentities($_POST['title'], ENT_QUOTES);
  $file = $_FILES['image'];
  $description = htmlentities($_POST['description'], ENT_QUOTES);
  $amount = htmlentities($_POST['amount'], ENT_QUOTES);
  $imagedata = $_FILES['image']['tmp_name'];
  move_uploaded_file($imagedata, $path . $filename);

  if ($title == '' || $description == '' || $amount == '') {
    $error = 'ERROR: Campuri goale!';
  } else {
    if ($stmt = $mysqli->prepare("INSERT into fundraising (title,image, description, amount, user_email) VALUES (?, ?, ?, ?, ?)")) {
      $stmt->bind_param("sssds", $title, $filename, $description, $amount, $_SESSION['email']);
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
  <title>Start a fundraiser</title>
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
      <a class="active" href="fundraiser.php"><i class="fas fa-donate"></i>Start a fundraiser</a>
        <a href="donate.php"><i class="fas fa-globe-europe"></i>All donations</a>
        <a href="feedback.php"><i class="fas fa-award"></i>Feedback</a>
        <a href="../../../app/http/controllers/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
      </div>
    </section>

    <div class="content">
      <h2>Let's start </h2>
      <p class="sign-title">
        <?php if ($error != '') {
          echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
        } ?></p>
      <form action="fundraiser.php" method="post" class="sign-form" enctype="multipart/form-data">
        <label> Title:</label>
        <input type="text" class="form-input" placeholder="Title" name="title">
        <label> Image:</label>
        <input type="file" class="form-input file" name="image" />
        <label> Tell us more:</label>
        <textarea class="form-input descriptionarea" placeholder="Description" rows="5" name="description"></textarea>
        <label> Goal amount:</label>
        <input class="form-input" type="number" placeholder="Donation goal (EUR)" name="amount">
        <button class="form-submit" name="submit">Send</button>
      </form>
    </div>
  </div>
</body>

</html>
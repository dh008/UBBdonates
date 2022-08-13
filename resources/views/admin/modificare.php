<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: /licenta/resources/views/login.html');
    exit;
}
include("../../../database/conectare.php");
$error = '';
if (!empty($_POST['id'])) {
    if (isset($_POST['submit'])) {
        if (is_numeric($_POST['id'])) {
            $id = $_POST['id'];
            $titlu = htmlentities($_POST['titlu'], ENT_QUOTES);
            $descriere = htmlentities($_POST['descriere'], ENT_QUOTES);
            $suma = htmlentities($_POST['suma'], ENT_QUOTES);

            if ($titlu == '' || $descriere == '' || $suma == '') { // daca sunt goale afisam mesaj de eroare
                echo "<div> ERROR: Completati campurile obligatorii!</div>";
            } else {
                if ($stmt = $mysqli->prepare("UPDATE fundraising SET title=?,description=?,amount=? WHERE fundraising_id='" . $id . "'")) {
                    $stmt->bind_param("sss", $titlu, $descriere, $suma);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    echo "ERROR: nu se poate executa update.";
                }
            }
        } else {
            echo "id incorect!";
        }
    }
}
?>

<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php if ($_GET['id'] != '') {
                echo "Modificare inregistrare";
            } ?> </title>
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
                <a class="active" href=""><i class="fas fa-globe-europe"></i>Modify</a>
                <a href="dashboard.php"><i class="fas fa-globe-europe"></i>Users</a>
                <a href="all.php"><i class="fas fa-globe-europe"></i>Accept Fundraising</a>
                <a href="customers.php"><i class="fas fa-sign-out-alt"></i>All donations</a>
                <a href="transactions.php"><i class="fas fa-sign-out-alt"></i>All transactions</a>
                <a href="feedbacks.php"><i class="fas fa-award"></i>Feedbacks</a>
                <a href="../../../app/http/controllers/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
            </div>
        </section>

        <div class="content">
            <h2>
                <?php if ($_GET['id'] != '') {
                    echo "Modificare Inregistrare";
                } ?>
            </h2>

            <?php if ($error != '') {
                echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
            } ?>

            <form action="" method="post" class="sign-form">
                <div class="content">
                    <?php if ($_GET['id'] != '') : ?>
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
                        <p>Id: <?php echo $_GET['id']; ?>
                            <?php if ($result = $mysqli->query("SELECT * FROM fundraising where fundraising_id='" . $_GET['id'] . "'")) :
                                if ($result->num_rows > 0) :
                                    $row = $result->fetch_object(); ?></p>
                        <label> Nume donatie:</label>
                        <input type="text" class="form-input" name="titlu" value="<?php echo $row->title; ?>">
                        <label> Descriere:</label>
                        <textarea type="text" rows="5" class="form-input descriptionarea" name="descriere">
                            <?php echo $row->description; ?>
                        </textarea>
                        <label> Suma maxima:</label>
                        <input type="number" class="form-input" name="suma" value="<?php echo $row->amount; ?>">
            <?php endif;
                            endif;
                        endif; ?>

                </div>
                <input type="submit" name="submit" class="form-submit" value="Submit" />
            </form>
        </div>
    </div>
</body>

</html>
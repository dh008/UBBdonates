<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = "";
$DATABASE_NAME = 'licenta';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

$token = $_GET['token'];
if ($stmt = $con->prepare('SELECT user_id, password FROM users WHERE username = ? AND token = ?')) {
    $stmt->bind_param('ss', $_GET['username'], $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $success = true;
    }
} else {
    exit('Username-ul sau token-ul sunt gresite');
}

?>
<?php if ($success == true) : ?>
    <!DOCTYPE html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LogIn</title>
        <link rel="stylesheet" href="../../../public/css/style.css">

    </head>

    <body>
        <div class="container">
            <div class="circle">
                <form action="update_password.php" class="sign-form" method="post">
                    <h3>Update your password</h3>

                    <input type="hidden" name="username" value="<?php echo $_GET['username'] ?>">
                    <input type="password" class="form-input" name="password" placeholder="New password" required>

                    <input type="submit" class="form-submit" value="Update">
                </form>
            </div>
        </div>
    </body>
<?php endif ?>
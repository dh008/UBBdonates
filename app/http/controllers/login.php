<?php
session_start();

if (isset($_POST['username'])) {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'licenta';

    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    if (mysqli_connect_errno()) {
        exit('Esec conectare MySQL: ' . mysqli_connect_error());
    }

    if (!isset($_POST['username'], $_POST['password'])) {
        exit('Completati username si password !');
    }

    if ($stmt = $con->prepare('SELECT user_id, password, role, Activated, email FROM users WHERE username = ?')) {
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $password, $role, $activated, $email);
            $stmt->fetch();
            if($activated == 0) exit("You must activate your account before logging in. Check your inbox.");
            if (password_verify($_POST['password'], $password)) {
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $_POST['username'];
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = $role;
                $_SESSION['email'] = $email;

                if ($role == "admin") {
                    header('Location: /licenta/resources/views/admin/dashboard.php');
                } else {
                    header('Location: /licenta/resources/views/user/fundraiser.php');
                }
            } else {
                exit('Incorrect username or password!');
            }
        } else {
            exit('Incorrect username or password!');
        }
        $stmt->close();
    }
}

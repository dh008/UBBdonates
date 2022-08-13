<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: /licenta/resources/views/login.html');
  exit;
} else {
  header('Location: /licenta/resources/views/user/fundraiser.php');
}
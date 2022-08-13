<?php
session_start();
session_destroy();
header('Location: /licenta/resources/views/login.html');
?>
<?php
session_start();

if (isset($_SESSION['id'])) {
    session_unset();
    session_destroy();
    header("Location:" . "http://localhost/espacemembre/index.php");
} else {
    echo 'Vous n\'êtes pas connecté !';
}


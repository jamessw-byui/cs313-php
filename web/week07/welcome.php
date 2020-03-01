<?php
// Start the session
session_start();

if(isset($_SESSION['username'])) {
    echo 'Welcome ' . $_SESSION['username'];
} else {
    $newURL = "login.php";
    header('Location: ' . $newURL);
}

?>
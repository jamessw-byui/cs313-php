<?php
    // Start the session
    session_start();
    // Get the db connection
    require("dbConnect.php");
    require("sqlCalls.php");
    $db = get_db();

    // Variable to see if credentials are correct
    $correctCredentials = true;
    // If coming from logout, clear variables
    if(isset($_POST['logout'])) {
        session_unset();
    };
    // If they are already logged in, send them to the logout page
    if(isset($_SESSION['UserId'])) {
        $newURL = "logout.php";
        header('Location: ' . $newURL);
        die();
    };
    // If user just attempted to login, validate credentials
    if (isset($_POST['user'])){
        $user = $_POST['user'];
        $password = $_POST['password'];
        $userResult = get_user($db, $user);
        if(password_verify($password, $userResult['password'])) {
            $_SESSION['UserId']=$userResult['userid'];
            $_SESSION['Username']=$userResult['username'];
            $_SESSION['Admin']=$userResult['admin'];
            $newURL = "browseGames.php";
            header('Location: ' . $newURL);
            die();
        } else {
            $correctCredentials = false;
        }
    };
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="gameDatabase.css">
    <title>Your Game Closet</title>
</head>
<body>
    <div id="header">
        <h1>Your Game Closet</h1>
        <div id="nav">
            <?php include("navigation.php"); ?>
        </div> <!-- nav -->
    </div> <!-- end of header -->
    <div class="main">
        <p>
            You are not currently logged in as any particular user. Please choose a user from the 
            dropdown below.
        </p>
        <?php 
            if ($correctCredentials === false) {
                echo('<p style="color:red">Username and password do not match</p>');
            } 
        ?>
        <form action="" method="post">
            <label for="user">Username:</label>
            <input type="text" name="user"><br>
            <label for="password">Password:</label>
            <input type="password" name="password">
            <button type="submit">Submit</button>
        </form>
        <p><a href="createUser.php">Create New User</a>
    </div> <!-- end of main -->
</body>
</html>
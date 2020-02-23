<?php
    // Start the session
    session_start();
    // Get the db connection
    require("dbConnect.php");
    require("sqlCalls.php");
    $db = get_db();

    $usernameExists = false;
    // If user just attempted to create account, create it and start the session
    if (isset($_POST['user'])){
        $user = $_POST['user'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $userCreated = create_user($db, $user, $password);
        if($userCreated === 'Success') {
            $newURL = "browseGames.php";
            header('Location: ' . $newURL);
            die();
        } else {
            $usernameExists = true;
        }
    };
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="gameDatabase.css">
    <title>Your Game Closet</title>
    <script src="createUser.js"></script>
</head>
<body>
    <div id="header">
        <h1>Your Game Closet</h1>
        <div id="nav">
            <?php include("navigation.php"); ?>
        </div> <!-- nav -->
    </div> <!-- end of header -->
    <?php 
        if ($usernameExists === true) {
            echo('<p style="color:red">Previously entered username already exists, please choose another one.</p>');
        } 
    ?>
    <p style="color:red; display:none;" id=alertMessage>Password doesn't meet the requirements of being at least 7 characters and needing a number</p>
    <form action="createUser.php" method="POST">
        <label for="user">Username:</label>
        <input type="text" name="user"><br>
        <label for="password">Password:</label>
        <input type="password" name="password" oninput="checkPasswordRequirements()" id="password"> 
        <p style="color:red; display:none;" id='matchMessage'>Passwords don't match</p><br>
        <label for="password">Verify Password:</label>
        <input type="password" name="passwordVerify" oninput="checkPasswordRequirements()" id="passwordVerify"><br>
        <button type="submit" id="submit" disabled>Submit</button>
    </form>
</body>
</html>
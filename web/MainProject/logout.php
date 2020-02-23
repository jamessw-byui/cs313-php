<?php
// Start the session
session_start();
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
                You are currently logged in as <?php echo $_SESSION['Username'];?>. Feel free to select a different user from the dropdown below.
            </p>
            <form action="login.php" method="POST">
              <input type="hidden" name="logout" value=1>
              <button type="submit">Submit</button>
            </form>
    </div> <!-- end of main -->
</body>
</html>

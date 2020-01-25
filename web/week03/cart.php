<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="week03.css">
    <title>Aimee White's Music</title>
</head>
<body>
    <div id="header">
        <h1>Aimee White's Music</h1>
        <div id="nav">
            <span class="page">
                <a href="main.php">Browse Music</a>
            </span>
            <span class="page">
                <a href="assignments.html">Cart</a>
            </span>
        </div> <!-- nav -->
    </div> <!-- end of header -->
    <div class="main">
        <div id="bio">
            <p>Hi,</p>
            <p>
                My name is Jim White. I live in Utah with my wife and two kids. I 
                currently work at a software company called Qualtrics as a Software 
                Test Engineer.
            </p>
        </div> <!-- end of bio --> 
        <img src="IMG_2575.JPG" alt="Jim and Wife" width="100" height="100">
        <div>
            <p>
                <?php
                    echo "Today's date is: ";
                    echo date("M d Y");
                ?>
            </p>
    </div> <!-- end of main -->
</body>
</html>

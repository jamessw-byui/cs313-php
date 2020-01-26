<?php
// Start the session
session_start();
$_SESSION['cart']=array();
// $_SESSION["12Tribes"]=0;
// $_SESSION["AskSeek"]=0;
// $_SESSION["BoM"]=0;
// $_SESSION["LackWisdom"]=0;
// $_SESSION["LivingChrist"]=0;
// $_SESSION["Trust"]=0;
?>
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
                <a href="browse.php">Browse Music</a>
            </span>
            <span class="page">
                <a href="cart.php">Cart</a>
            </span>
        </div> <!-- nav -->
    </div> <!-- end of header -->
    <div class="main">
        <div class="song">
            <img src="12Tribes.png" alt="12TribesSong">
            <button type="button" id="12Tribes" onclick="">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="AskSeek.png" alt="AskSeekSong">
            <button type="button" id="AskSeek" onclick="">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="BoM.png" alt="BookOfMormonSong">
            <button type="button" id="BoM" onclick="">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="LackWisdom.png" alt="LackWisdomSong">
            <button type="button" id="LackWisdom" onclick="">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="LivingChrist.png" alt="LivingChristSong">
            <button type="button" id="LivingChrist" onclick="">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="Trust.png" alt="TrustSong">
            <button type="button" id="Trust" onclick="">Add to Cart</button>
        </div>
        <div>
            <!-- <button type="button" id="Destroy" onclick="<?php session_unset(); session_destroy(); ?>">Destroy</button>
            <button type="button" id="Start" onclick="<?php session_start(); $_SESSION['cart']=array(); ?>">Start</button> -->
        </div>
    </div> <!-- end of main -->
    <?php
        print_r($_SESSION);
    ?>
</body>
</html>

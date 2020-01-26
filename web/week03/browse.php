<?php
// Start the session
session_start();
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
$_SESSION['12Tribes']=0;
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
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="week03.js"></script>
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
            <button type="button" id="12Tribes" onclick="addToCart('12Tribes')">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="AskSeek.png" alt="AskSeekSong">
            <button type="button" id="AskSeek" onclick="addToCart('AskSeek')">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="BoM.png" alt="BookOfMormonSong">
            <button type="button" id="BoM" onclick="addToCart('BoM')">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="LackWisdom.png" alt="LackWisdomSong">
            <button type="button" id="LackWisdom" onclick="addToCart('LackWisdom')">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="LivingChrist.png" alt="LivingChristSong">
            <button type="button" id="LivingChrist" onclick="addToCart('LivingChrist')">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="Trust.png" alt="TrustSong">
            <button type="button" id="Trust" onclick="addToCart('Trust')">Add to Cart</button>
        </div>
        <div>
        </div>
    </div> <!-- end of main -->
    <?php
        print_r($_SESSION);
    ?>
</body>
</html>

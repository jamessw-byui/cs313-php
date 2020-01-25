<?php
// Start the session
session_start();
$_SESSION['cart']=array();
array_push($_SESSION['cart'],array(array('product'=>'12Tribes','quantity'=>1)));
$_SESSION["12Tribes"]=0;
$_SESSION["AskSeek"]=0;
$_SESSION["BoM"]=0;
$_SESSION["LackWisdom"]=0;
$_SESSION["LivingChrist"]=0;
$_SESSION["Trust"]=0;
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
            <button type="button" id="12Tribes" onclick="array_push($_SESSION['cart'],array(array('product'=>'12Tribes','quantity'=>1)));">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="AskSeek.png" alt="AskSeekSong">
            <button type="button" id="AskSeek">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="BoM.png" alt="BookOfMormonSong">
            <button type="button" id="BoM">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="LackWisdom.png" alt="LackWisdomSong">
            <button type="button" id="LackWisdom">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="LivingChrist.png" alt="LivingChristSong">
            <button type="button" id="LivingChrist">Add to Cart</button>
        </div> 
        <div class="song">
            <img src="Trust.png" alt="TrustSong">
            <button type="button" id="Trust">Add to Cart</button>
        </div>
    </div> <!-- end of main -->
    <?php
        echo "beginning PHP";
        $max=sizeof($_SESSION['cart']);
        echo "$max";
        for($i=0; $i<$max; $i++) { 

        while (list ($key, $val) = each ($_SESSION['cart'][$i])) { 
        echo "$key -> $val ,"; 
        } // inner array while loop
        echo "<br>";
        } // outer array for loop
    ?>
</body>
</html>

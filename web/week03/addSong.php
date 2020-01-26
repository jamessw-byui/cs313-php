<?php 
	$song = $_GET["s"];
	array_push($_SESSION['cart'],array('product'=> $song,'quantity'=>1)); 
?>

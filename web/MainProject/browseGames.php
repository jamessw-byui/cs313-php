<?php
// Start the session
session_start();
if (!isset($_SESSION['UserId'])){
    $newURL = "login.php";
    header('Location: ' . $newURL);
    die();
};
?>

<?php
$edit="";
if(isset($_POST['edit'])) {
  $edit = $_POST['edit'];
};
require("dbConnect.php");
require("sqlCalls.php");
$db = get_db();

$players = $_POST['players'];
$time = $_POST['time'];
$minAge = $_POST['minAge'];
$categories = $_POST['categories'];

$gameResults = get_user_games($db, $_SESSION['UserId'], $players, $time, $minAge, $categories);

$categoryResults = get_user_categories($db, $_SESSION['UserId']);

?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="gameDatabase.css">
  <title>Your Game Closet</title>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script type="text/javascript" src="games.js"></script>
</head>
<body>
  <div id="header">
    <h1>Your Game Closet</h1>
    <div id="nav">
        <?php include("navigation.php"); ?>
    </div> <!-- nav -->
  </div> <!-- end of header -->
  <div class="main">
    <form action="" method="POST" style="margin: 0; padding: 0;">
      <p>
        Here is a list of all your games, or your filtered games:
        <input type="hidden" name="edit" value=1>
        <button class="button" type="submit" style="display: inline;">Edit</button>
      </p>
    </form>
    <form action="" method="POST">
        <p>Number of Players: <input type="number" name="players" min="1"></p>
        <p>Time Available: <input type="number" name="time" min="1"></p>
        <p>Youngest Player's Age: <input type ="number" name="minAge" min="1"></p>
        <p>Categories: 
          <select name="categories[]" multiple>
            <?php
              foreach($categoryResults as $category) {
                echo '<option value="' . $category['categoryid'] . '">';
                echo $category['categoryname'] . '</option>';
              };
            ?>  
          </select>
        </p><!-- TODO -->
      <button type="submit">Search</button>
    </form>
    <div class="games">
      <?php 
        foreach($gameResults as $game) { 
            echo '<p>';
            echo '<a href = "gameDetails.php?id=' . $game['gameid'] . '">';
            echo '<b>' . $game['gamename'] . '</b>';
            echo ' - ' . $game['summary'];
            echo '</a>';
            if($edit==1) {
              echo '<button class="delete" type="button" value="' . $game['gameid'];
              echo '" onclick="deleteGame(this.value,' . $_SESSION['UserId'] . ')">Delete</button></p>';
            }
            echo '</p>';
        };
      ?>
    </div>      
  </div> <!-- end of main -->
</body>
</html>

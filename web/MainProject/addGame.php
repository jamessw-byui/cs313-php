<?php
// Start the session
session_start();
?>

<?php
  $edit="";
  if(isset($_POST['edit'])) {
    $edit = $_POST['edit'];
  };

  require("dbConnect.php");
  require("sqlCalls.php");
  $db = get_db();
  $gameName=$_POST['gameName'];
  $minPlayers=$_POST['minPlayers'];
  $maxPlayers=$_POST['maxPlayers'];
  $minDuration=$_POST['minDuration'];
  $minAge=$_POST['minAge'];
  $categories=$_POST['categories'];
  $summary=$_POST['summary'];

  if (isset($_POST['gameName'])) {
    $gameAdded = add_game($db, $gameName, $minPlayers, $maxPlayers, $minDuration, $minAge, $summary);

    foreach($categories as $category) {
      add_category($db, $_SESSION['UserId'], $gameName, $category);
    };

    
  };

  $categoryResults = get_user_categories($db, $_SESSION['UserId']);
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="gameDatabase.css">
  <title>Your Game Closet</title>
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script type="text/javascript" src="categories.js"></script>
</head>
<body>
  <div id="header">
    <h1>Your Game Closet</h1>
    <div id="nav">
        <?php include("navigation.php"); ?>
    </div> <!-- nav -->
  </div> <!-- end of header -->
  <div class="main">
    <form action="" method="POST">
        <p>Input information for your new game here:</p>
        <p>Name: <input type="text" name="gameName"></p>
        <p>Minimum Players: <input type="number" name="minPlayers" min="1"></p>
        <p>Maximum Players: <input type="number" name="maxPlayers" min="1"></p>
        <p>Minimum Duration: <input type="number" name="minDuration" min="1"></p>
        <p>Minimum Age: <input type ="number" name="minAge" min="1"></p>
        <p>Summary: <input type="text" name="summary"></p>
        <p>Categories: 
          <select name="categories[]" multiple>
            <?php
              foreach($categoryResults as $category) {
                echo '<option value="' . $category['categoryid'] . '">';
                echo $category['categoryname'] . '</option>';
              };
            ?>  
          </select>
        </p>
      <button type="submit">Submit</button>
    </form>    
  </div> <!-- end of main -->
</body>
</html>

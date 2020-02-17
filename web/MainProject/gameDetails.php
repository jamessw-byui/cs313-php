<?php
// Start the session
session_start();
?>

<?php
$dbUrl = getenv('DATABASE_URL');

if (empty($dbUrl)) {
 // example localhost configuration URL with postgres username and a database called cs313db
 $dbUrl = "";
}

$dbopts = parse_url($dbUrl);

$dbHost = $dbopts["host"];
$dbPort = $dbopts["port"];
$dbUser = $dbopts["user"];
$dbPassword = $dbopts["pass"];
$dbName = ltrim($dbopts["path"], '/');

try {
  $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);

  // this line makes PDO give us an exception when there are problems,
  // and can be very helpful in debugging! (But you would likely want
  // to disable it for production environments.)
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
  echo "<p>error: " . $ex->getMessage() . "</p>";
  die();
}

if(isset($_GET['id'])) {
  $statement = $db->prepare(
    "SELECT g.gameId, g.gameName, g.minPlayers, g.maxPlayers, g.minDuration, g.minAge, ug.summary
        FROM factGame g
        Left Join dimUserGameMapping ug on g.gameId = ug.gameId
        	And ug.userId = :userId
      Where g.gameId = :gameId
      ;"
  );
  $statement->bindParam(':gameId',$_GET['id']);
  $statement->bindParam(':userId',$_SESSION['UserId']);
  // execute the statement
  $executeSuccess = $statement->execute();
}


// convert to array
$gameResults = $statement->fetchAll(PDO::FETCH_ASSOC);

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
        <span class="page">
            <a href="landingPage.php">User</a>
        </span>
        <span class="page">
            <a href="browseGames.php">Browse Games</a>
        </span>
        <span class="page">
            <a href="categories.php">Categories</a>
        </span>
        <span class="page">
            <a href="addGame.php">Add Game</a>
        </span>
    </div> <!-- nav -->
  </div> <!-- end of header -->
  <div class="main">
    <?php 
      foreach($gameResults as $game) { 
          echo '<p>Game Name: ' . $game['gamename'] . '</p>';
          echo '<p>Minimum Players: ' . $game['minplayers'] . '</p>';
          echo '<p>Maximum Players: ' . $game['maxplayers'] . '</p>';
          echo '<p>Minimum Duration: ' . $game['minduration'] . '</p>';
          echo '<p>Minimum Age: ' . $game['minage'] . '</p>';
          echo '<p>Summary: ' . $game['summary'] . '</p>';
      };
    ?>
    </div>      
  </div> <!-- end of main -->
</body>
</html>

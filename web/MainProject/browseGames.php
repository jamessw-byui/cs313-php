<?php
// Start the session
session_start();
if (isset($_POST['user'])){
    $_SESSION['UserId']=$_POST['user'];
};
?>

<?php
$edit="";
if(isset($_POST['edit'])) {
  $edit = $_POST['edit'];
};
$dbUrl = getenv('DATABASE_URL');

if (empty($dbUrl)) {
 // example localhost configuration URL with postgres username and a database called cs313db
 // adding a comment
 $dbUrl = "postgres://rawwaxqaoumooe:13912bd2cb35c4281651af094768fd4aab65a7e536cf75d3852f71c12fa5165a@ec2-52-71-122-102.compute-1.amazonaws.com:5432/db79tlucjrllqr";
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

$playersClause;
$timeClause;
$minAgeClause;
$categoriesClause;

if (isset($_POST['players']) && $_POST['players'] <> '') {
  $playersClause='And g.minPlayers <= ' . $_POST['players'] . ' And g.maxPlayers >= ' . $_POST['players'];
};

if (isset($_POST['time']) && $_POST['time'] <> '') {
  $timeClause='And g.minDuration <= ' . $_POST['time'];
};

if (isset($_POST['minAge']) && $_POST['minAge'] <> '') {
  $minAgeClause='And g.minAge <= ' . $_POST['minAge'];
};
if (isset($_POST['categories'])) {
  $categoriesClause ='And gc.categoryId in (';
  foreach($_POST['categories'] as $category) {
    $categoriesClause .= $category .', ';
  }
  $categoriesClause = rtrim($categoriesClause, ", ");
  $categoriesClause .=')';
};
$sql = 'SELECT g.gameId, g.gameName, g.minPlayers, g.maxPlayers, g.minDuration, g.minAge, ug.summary FROM dimUserGameMapping ug Left Join factGame g on g.gameId = ug.gameId Left Join dimUserGameCategoryMapping gc on gc.gameId = g.gameId Where ug.userId =' . $_SESSION['UserId'] . ' ' . $playersClause . ' ' . $timeClause . ' ' . $minAgeClause . ' ' . $categoriesClause . ' Group by g.gameId, ug.summary;';
/*$statement = $db->prepare(
    "SELECT g.gameId, g.gameName, g.minPlayers, g.maxPlayers, g.minDuration, g.minAge, ug.summary
        FROM dimUserGameMapping ug
        Left Join factGame g on g.gameId = ug.gameId
        Left Join dimUserGameCategoryMapping gc on gc.gameId = g.gameId
      Where ug.userId = :userId
      :playersClause
      :timeClause
      :minAgeClause
      :categoriesClause
      Group by g.gameId
      ;"
  );
  $statement->bindParam(':userId',$_SESSION['UserId']);
  $statement->bindParam(':playersClause',$playersClause);
  $statement->bindParam(':timeClause',$timeClause);
  $statement->bindParam(':minAgeClause',$minAgeClause);
  $statement->bindParam(':categoriesClause',$categoriesClause);*/
  $statement = $db->prepare($sql);

  // execute the statement
  $executeSuccess = $statement->execute();

// convert to array
$gameResults = $statement->fetchAll(PDO::FETCH_ASSOC);

$newStatement = $db->prepare(
    "SELECT c.categoryId, c.categoryName
        FROM factCategory c
        Left Join dimUserCategoryMapping uc on uc.categoryId = c.categoryId
          And uc.userId = :userId
      Where uc.userId is not null
      Group by c.categoryId
      ;"
  );
  $newStatement->bindParam(':userId',$_SESSION['UserId']);
  // execute the statement
  $newExecuteSuccess = $newStatement->execute();

// convert to array
$categoryResults = $newStatement->fetchAll(PDO::FETCH_ASSOC);

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

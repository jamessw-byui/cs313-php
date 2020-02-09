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

$newStatement = $db->prepare(
    "SELECT c.categoryId, c.categoryName
        FROM factCategory c
      ;"
  );
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
    </div> <!-- nav -->
  </div> <!-- end of header -->
  <div class="main">
    <p>Here is a list of all the different categories:</p>
    <div class="categories">
      <?php 
        foreach($categoryResults as $category) { 
            echo '<p>' . $category['categoryname'] . '</p>';
        };
      ?>
    </div>      
  </div> <!-- end of main -->
</body>
</html>

<?php
// Start the session
session_start();
if (isset($_POST['user'])){
    $_SESSION['UserId']=$_POST['user'];
};
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

$statement = $db->prepare(
    "SELECT userId, userName
        FROM factUser
      ;"
  );
  // execute the statement
  $executeSuccess = $statement->execute();

// convert to array
$userResults = $statement->fetchAll(PDO::FETCH_ASSOC);

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
                // Start the session
                if (isset($_SESSION['UserId'])){
                    echo '<p>You are currently logged in as ';
                    foreach($userResults as $user) { 
                        if($user['userid']==$_SESSION['UserId']) {
                            echo $user['username'];
                        };
                    };
                    echo '. Feel free to select a different user from the dropdown below.</p>';
                } else {
                    echo '<p>You are not currently logged in as any particular user. Please choose a user from the 
                        dropdown below.</p>';
                }
            ?>
            <form action="browseGames.php" method="POST">
                User: <select name="user">
                    <?php foreach($userResults as $user) {
                        echo '<option value="' . $user['userid'] . '">' . $user['username'] . '</option>';
                    }?>
                </select>
              <button type="submit">Submit</button>
            </form>
    </div> <!-- end of main -->
</body>
</html>

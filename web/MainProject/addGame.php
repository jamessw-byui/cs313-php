<?php
// Start the session
session_start();
?>

<?php
  $edit="";
  if(isset($_POST['edit'])) {
    $edit = $_POST['edit'];
  };
  $gameName="";
  $minPlayers="";
  $maxPlayers="";
  $minDuration="";
  $minAge="";
  $categories;
  $summary="";
  if (isset($_POST['gameName'])) {
    $gameName = $_POST['gameName'];
  };

  if (isset($_POST['minPlayers'])) {
    $minPlayers = $_POST['minPlayers'];
  };

  if (isset($_POST['maxPlayers'])) {
    $maxPlayers = $_POST['maxPlayers'];
  };

  if (isset($_POST['minDuration'])) {
    $minDuration = $_POST['minDuration'];
  };

  if (isset($_POST['minAge'])) {
    $minAge = $_POST['minAge'];
  };

  if (isset($_POST['categories'])) {
    $categories = $_POST['categories'];
  };

  if (isset($_POST['summary'])) {
    $summary = $_POST['summary'];
  };
  $dbUrl = getenv('DATABASE_URL');

  if (empty($dbUrl)) {
   // example localhost configuration URL with postgres username and a database called cs313db
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

  if (isset($_POST['gameName'])) {
    $statement = $db->prepare(
      "INSERT INTO factGame (gameName, minPlayers, maxPlayers, minDuration, minAge) VALUES (:gameName, :minPlayers, :maxPlayers, :minDuration, :minAge);
      ;"
    );
    $statement->bindParam(':gameName',$gameName);
    $statement->bindParam(':minPlayers',$minPlayers);
    $statement->bindParam(':maxPlayers',$maxPlayers);
    $statement->bindParam(':minDuration',$minDuration);
    $statement->bindParam(':minAge',$minAge);
    // execute the statement
    $executeSuccess = $statement->execute();

    $userGameStatement = $db->prepare(
      "INSERT INTO dimUserGameMapping (userId, gameId, summary) VALUES ((Select userId from factUser Where userId = :userId), (Select gameId from factGame Where gameName = :gameName), :summary);"
    );
    $userGameStatement->bindParam(':userId',$_SESSION['UserId']);
    $userGameStatement->bindParam(':gameName',$gameName);
    $userGameStatement->bindParam(':summary',$summary);

    $userGameExecuteSuccess = $userGameStatement->execute();

    foreach($categories as $category) {
      error_log(strval($category));
      $userGameCategoryStatement = $db->prepare(
        "INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userId = :userId), (Select gameId from factGame Where gameName = :gameName), :categoryId);"
      );
      $userGameCategoryStatement->bindParam(':userId',$_SESSION['UserId']);
      $userGameCategoryStatement->bindParam(':gameName',$gameName);
      $userGameCategoryStatement->bindParam(':categoryId',$category);
      $userGameCategoryExecuteSuccess = $userGameCategoryStatement->execute();
    };

    
  };

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
  <script type="text/javascript" src="categories.js"></script>
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

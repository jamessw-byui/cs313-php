<?php
// Start the session
session_start();
$dbUrl = getenv('DATABASE_URL');
$gameId = $_POST['gameId'];
$userId = $_POST['userId'];

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
    "DELETE FROM dimUserGameMapping
    Where userId = :userId
    And gameId = :gameId
      ;"
  );
  $statement->bindParam(':userId',$userId);
  $statement->bindParam(':gameId',$gameId);
  // execute the statement
  $executeSuccess = $statement->execute();

  $deleteUserGameCateogyrStatement = $db->prepare(
    "DELETE FROM dimUserGameCategoryMapping
    Where userId = :userId
    And gameId = :gameId
      ;"
  );
  $deleteUserGameCateogyrStatement->bindParam(':userId',$userId);
  $deleteUserGameCateogyrStatement->bindParam(':gameId',$gameId);
  // execute the statement
  $executeSuccess = $deleteUserGameCateogyrStatement->execute();

  $newStatement = $db->prepare(
    "Select count(ug.userId) as users FROM factGame g
      Left Join dimUserGameMapping ug on ug.gameId = g.gameId
    Where g.gameId = :gameId
      ;"
  );
  $newStatement->bindParam(':gameId',$gameId);
  // execute the statement
  $newExecuteSuccess = $newStatement->execute();

  $users = $newStatement->fetchAll(PDO::FETCH_ASSOC);

  foreach($users as $user) {
    if($user['users']==0) {
      $deleteGameStatement = $db->prepare(
        "DELETE FROM factGame
        Where gameId = :gameId
        ;"
      );
      $deleteGameStatement->bindParam(':gameId',$gameId);
      $deleteGameExecuteStatement = $deleteGameStatement->execute();
    };
  };
?>
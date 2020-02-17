<?php
// Start the session
session_start();
$dbUrl = getenv('DATABASE_URL');
$categoryId = $_POST['categoryId'];
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
    "DELETE FROM dimUserCategoryMapping
    Where userId = :userId
    And categoryId = :categoryId
      ;"
  );
  $statement->bindParam(':userId',$userId);
  $statement->bindParam(':categoryId',$categoryId);
  // execute the statement
  $executeSuccess = $statement->execute();

  $newStatement = $db->prepare(
    "Select count(uc.userId) as users FROM factCategory c
      Left Join dimUserCategoryMapping uc on uc.categoryId = c.categoryId
    Where c.categoryId = :categoryId
      ;"
  );
  $newStatement->bindParam(':categoryId',$categoryId);
  // execute the statement
  $newExecuteSuccess = $newStatement->execute();

  $users = $newStatement->fetchAll(PDO::FETCH_ASSOC);
  if($users[0][0]==0) {
    $deleteCategoryStatement = $db->prepare(
      "DELETE FROM factCategory
      Where  categoryId = :categoryId
      ;"
    );
    $deleteCategoryStatement->bindParam(':categoryId',$categoryId);
    // execute the statement
    $newExecuteSuccess = $deleteCategoryStatement->execute();
  };
?>
<?php
// Start the session
session_start();
$dbUrl = getenv('DATABASE_URL');
$categoryName = $_POST['categoryName'];
$userId = $_POST['userId'];

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

$newStatement = $db->prepare(
  "Select count(categoryName) as categories FROM factCategory c
  Where c.categoryName = :categoryName
    ;"
);
$newStatement->bindParam(':categoryName',$categoryName);
// execute the statement
$newExecuteSuccess = $newStatement->execute();

$categories = $newStatement->fetchAll(PDO::FETCH_ASSOC);
foreach($categories as $category) {
  if($category['categories']==0) {
    $categoryStatement = $db->prepare(
      "INSERT INTO factCategory (categoryName) VALUES (:categoryName);
        ;"
    );
    $categoryStatement->bindParam(':categoryName',$categoryName);
    // execute the statement
    $newExecuteSuccess = $categoryStatement->execute();

    $userCategoryStatement = $db->prepare(
      "INSERT INTO dimUserCategoryMapping(userId, categoryId) VALUES (:userId, (Select categoryId from factCategory Where categoryName = :categoryName))"
    );
    $userCategoryStatement->bindParam(':userId',$userId);
    $userCategoryStatement->bindParam(':categoryName',$categoryName);

    // execute the statement
    $newExecuteSuccess = $userCategoryStatement->execute();
  };
};

?>
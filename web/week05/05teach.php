<?php
$dbUrl = getenv('DATABASE_URL');

if (empty($dbUrl)) {
  // This gets us the heroku credentials without revealing credentials in our code
  $dbUrl = exec("heroku config:get DATABASE_URL");
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
  print "<p>error: " . $ex->getMessage() . "</p>\n\n";
  die();
}

if (isset($_GET['book'])){
  $statement = $db->prepare(
    "SELECT book, chapter, verse, content, id
        FROM Scriptures WHERE book = :book
      ;"
  );
  $statement->bindParam(':book',$_GET['book']);
  // execute the statement
  $executeSuccess = $statement->execute();
  
} else {
  $statement = $db->prepare(
    "SELECT book, chapter, verse, id
        FROM Scriptures
      ;"
  );
  // execute the statement
  $executeSuccess = $statement->execute();

}



// convert to array
$scriptureResults = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Teach 05</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta charset="utf-8">

</head>

<body>
<h2>Scripture Resources</h2>
<?php foreach($scriptureResults as $scripture) {
 echo '<p>';
 echo '<a href = "scripture_details.php?id=' . $scripture['id'] . '">';
 echo '<b>' . $scripture['book'];
 echo ' ' . $scripture['chapter'];
 echo ':' . $scripture['verse'];
 echo '</b>';
 echo '</a>';
 echo '</p>';
}?>

<form action="" method="GET">
  Search term: <input type="text" name="book"><br>
  <button type="submit">Search</button>
</form>

</body>


</html>
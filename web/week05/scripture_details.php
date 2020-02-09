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

if (isset($_GET['id'])) {
  $statement = $db->prepare(
    "SELECT book, chapter, verse, content, id
        FROM Scriptures WHERE id = :id
      ;"
  );
  $statement->bindParam(':id', $_GET['id']);
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
  <?php foreach ($scriptureResults as $scripture) {
    echo '<h2>' . $scripture['book'];
    echo ' ' . $scripture['chapter'];
    echo ':' . $scripture['verse'];
    echo '</h2>';
    echo '<br>';
    echo '<p>' . $scripture['content'];
    echo '</p>';
  } ?>

</body>


</html>
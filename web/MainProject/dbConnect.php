<?php
// Function to create a database connection
function get_db() {
  $db = NULL;
  try {
    $dbUrl = getenv('DATABASE_URL');

    // single place where I save localhost configuration URL, but strip it before publishing to github
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
    $db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);

    // this line makes PDO give us an exception when there are problems,
    // and can be very helpful in debugging! (But you would likely want
    // to disable it for production environments.)
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db;
  }
  catch (PDOException $ex) {
    echo "<p>error: " . $ex->getMessage() . "</p>";
    die();
  }
};

?>

<?php
// Start the session
session_start();

$dbUrl = getenv('DATABASE_URL');

if (empty($dbUrl)) {
 // example localhost configuration URL with postgres username and a database called cs313db
 // adding a comment
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
$falseEntered = false;
if(isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $newStatement = $db->prepare(
        "SELECT id,username, password FROM users WHERE username = :username;"
      );
      $newStatement->bindParam(':username',$username);
      // execute the statement
      $newExecuteSuccess = $newStatement->execute();
      $userResults = $newStatement->fetch(PDO::FETCH_ASSOC);
      $usernem1 = $userResults['username'];
      if(password_verify($_POST['password'], $userResults['password'])) {

        $_SESSION['username'] = $userResults['username'];    
        $newURL = "welcome.php";
        header('Location: ' . $newURL);
      } else {
        $falseEntered = true;
      }

  
      
    
    
};

?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
        <h1>Login</h1>


        <?php if ($falseEntered == true) {
            echo('<h2 style="color:red">Username and password do not match</h2>');
        } 
        ?>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id=""><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="">
        <button type="submit">Submit</button>
    </form>
</body>
</html>
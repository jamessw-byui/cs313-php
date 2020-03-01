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

$passwordMeetsRequirements = false;

if(isset($_POST['password'])) {
    if(strlen($_POST['password']) >= 7 && preg_match('~[0-9]~', $_POST['password'])) {
        $passwordMeetsRequirements = true;
    }
}


$passwordMatch=true;
if($_POST['password'] != null && $passwordMeetsRequirements && $_POST['password'] == $_POST['passwordVerify']) {
    if(isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $newStatement = $db->prepare(
            "INSERT INTO users (username, password) VALUES (:username, :password)"
          );
          $newStatement->bindParam(':username',$username);
          $newStatement->bindParam(':password',$password);
          // execute the statement
          $newExecuteSuccess = $newStatement->execute();
        
        $newURL = "login.php";
        header('Location: ' . $newURL);
    };
} else {
    if($_POST['password'] != null) {
        $passwordMatch=false;
    };
};



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <script src="sign_up.js"></script>
</head>
<body>
    <?php 
        if($passwordMatch==false && $_POST['password']) {
            echo '<p style="color:red">Password does not match</p>';
        }

        if($passwordMeetsRequirements == false && $_POST['password']) {
            echo '<p style="color:red">Password Requires at least 7 Characters and needs to contain a number</p>';
        }
    ?>
    <p style="color:red; visibility:hidden;" id=alertMessage>They don't meet the requirements of 7 characters and needing a number</p>
    <form action="sign_up.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username"><br>
        <label for="password">Password:</label>
        <input type="password" name="password" oninput="checkPasswordRequirements(this.value)" id="password"> <p style="color:red; visibility:hidden;" id='matchMessage'>Passwords don't matvh</p><br>
        <label for="password">Verify Password:</label>
        <input type="password" name="passwordVerify" oninput="checkPasswordsMatch()" id="passwordVerify"><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
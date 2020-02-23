<?php
// Start the session
session_start();
?>

<?php
$edit="";
if(isset($_POST['edit'])) {
  $edit = $_POST['edit'];
};
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

$newStatement = $db->prepare(
    "SELECT c.categoryId, c.categoryName
        FROM dimUserCategoryMapping uc
        Left Join factCategory c on c.categoryId = uc.categoryId
        	And uc.userId = :userId
    Where uc.userId = :userId
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
        <?php include("navigation.php"); ?>
    </div> <!-- nav -->
  </div> <!-- end of header -->
  <div class="main">
    <form action="" method="POST" style="margin: 0; padding: 0;">
    	<p>
	    	Here is a list of all your different categories:
	    	<input type="hidden" name="edit" value=1>
	    	<button class="button" type="submit" style="display: inline;">Edit</button>
	    </p>
    </form>
    <div class="categories">
      <?php 
        foreach($categoryResults as $category) { 
            echo '<p>' . $category['categoryname'] . ' ';
            if($edit==1) {
            	echo '<button class="delete" type="button" value="' . $category['categoryid'];
              echo '" onclick="deleteUserCategory(this.value,' . $_SESSION['UserId'] . ')">Delete</button></p>';
            }
        };
      ?>
    </div>
    <input type="text" name="category" id="newCategory">
    <button class="button" type="button" onclick="addUserCategory(newCategory,<?php echo $_SESSION['UserId'];?>)">Add</button>    
  </div> <!-- end of main -->
</body>
</html>

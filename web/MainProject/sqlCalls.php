<?php
// Function to get a specific user
function get_user($db, $user) {
  $statement = $db->prepare(
    "SELECT userId, userName, password, admin
        FROM factUser
        Where username = :user;
      ;"
  );
  // Bind UserId parameter
  $statement->bindParam(':user', $user);
  // execute the statement
  $executeSuccess = $statement->execute();

  // convert to array
  $userResult = $statement->fetch(PDO::FETCH_ASSOC);  

  return $userResult;
};

function create_user($db, $user, $password) {
  $statement = $db->prepare(
    "SELECT UserId as count
        FROM factUser
        Where username = :user;
      ;"
  );
  // Bind UserId parameter
  $statement->bindParam(':user', $user);
  // execute the statement
  $executeSuccess = $statement->execute();

  // convert to array
  $userExists = $statement->fetch(PDO::FETCH_ASSOC);
  if($userExists === false) {
    $statement = $db->prepare(
      "INSERT INTO factUser (userName, password, admin) VALUES (:user, :password, false);"
    );
    // Bind UserId parameter
    $statement->bindParam(':user', $user);
    $statement->bindParam(':password', $password);
    // execute the statement
    $executeSuccess = $statement->execute();
    // grab userId
    $newId = $db->lastInsertId('factuser_userid_seq');

    // create categories
    $categories = ['Party', 'Strategy', 'Card', 'Kid-Friendly', 'Dice', 'Easy to Learn', 'Get to Know You', 'Guys Night', 'Cooperative', 'Deck Building'];
    foreach($categories as $category) {
      $statement = $db->prepare(
        "INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = :user), (Select categoryId from factCategory Where categoryName = :category));"
      );
      // Bind UserId parameter
      $statement->bindParam(':user', $user);
      $statement->bindParam(':category', $category);
      // execute the statement
      $executeSuccess = $statement->execute();
    }

    // Set Session Variables
    $_SESSION['UserId']=$newId;
    $_SESSION['Username']=$user;
    $_SESSION['Admin']=false;

    return 'Success';
    
  } else {
    return 'Failure';
  } 
};

function get_user_games($db, $userId, $players, $time, $minAge, $categories) {
  $playersClause;
  $timeClause;
  $minAgeClause;
  $categoriesClause;

  if (isset($players) && $players <> '') {
    $playersClause='And g.minPlayers <= ' . $players . ' And g.maxPlayers >= ' . $players;
  };

  if (isset($time) && $time <> '') {
    $timeClause='And g.minDuration <= ' . $time;
  };

  if (isset($minAge) && $minAge <> '') {
    $minAgeClause='And g.minAge <= ' . $minAge;
  };
  if (isset($categories)) {
    $categoriesClause ='And gc.categoryId in (';
    foreach($categories as $category) {
      $categoriesClause .= $category .', ';
    }
    $categoriesClause = rtrim($categoriesClause, ", ");
    $categoriesClause .=')';
  };

  $sql = 'SELECT g.gameId, g.gameName, g.minPlayers, g.maxPlayers, g.minDuration, g.minAge, ug.summary FROM dimUserGameMapping ug Left Join factGame g on g.gameId = ug.gameId Left Join dimUserGameCategoryMapping gc on gc.gameId = g.gameId Where ug.userId =' . $userId . ' ' . $playersClause . ' ' . $timeClause . ' ' . $minAgeClause . ' ' . $categoriesClause . ' Group by g.gameId, ug.summary;';

  $statement = $db->prepare($sql);

  // execute the statement
  $executeSuccess = $statement->execute();

  // convert to array
  $gameResults = $statement->fetchAll(PDO::FETCH_ASSOC);

  return $gameResults;
};

function get_user_categories($db, $userId) {
  $statement = $db->prepare(
    "SELECT c.categoryId, c.categoryName
        FROM factCategory c
        Left Join dimUserCategoryMapping uc on uc.categoryId = c.categoryId
          And uc.userId = :userId
      Where uc.userId is not null
      Group by c.categoryId
      ;"
  );
  $statement->bindParam(':userId',$userId);
  // execute the statement
  $newExecuteSuccess = $statement->execute();

  // convert to array
  $categoryResults = $statement->fetchAll(PDO::FETCH_ASSOC);

  return $categoryResults;
};

function add_game($db, $gameNameVar, $minPlayersVar, $maxPlayersVar, $minDurationVar, $minAgeVar, $summaryVar) {
  $gameName="";
  $minPlayers="";
  $maxPlayers="";
  $minDuration="";
  $minAge="";
  $categories;
  $summary="";
  if (isset($gameNameVar)) {
    $gameName = $gameNameVar;
  };

  if (isset($minPlayersVar)) {
    $minPlayers = $minPlayersVar;
  };

  if (isset($maxPlayersVar)) {
    $maxPlayers = $maxPlayersVar;
  };

  if (isset($minDurationVar)) {
    $minDuration = $minDurationVar;
  };

  if (isset($minAgeVar)) {
    $minAge = $minAgeVar;
  };

  if (isset($summaryVar)) {
    $summary = $summaryVar;
  };
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
  return 'Success';
};

function add_category($db, $userId, $gameName, $categoryId) {
  $userGameCategoryStatement = $db->prepare(
      "INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userId = :userId), (Select gameId from factGame Where gameName = :gameName), :categoryId);"
    );
  $userGameCategoryStatement->bindParam(':userId',$userId);
  $userGameCategoryStatement->bindParam(':gameName',$gameName);
  $userGameCategoryStatement->bindParam(':categoryId',$categoryId);
  $userGameCategoryExecuteSuccess = $userGameCategoryStatement->execute();
};

?>
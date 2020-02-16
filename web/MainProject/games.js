function deleteGame(game, user) {
  $.ajax({
    type: "POST",
    data: {
      gameId: game,
      userId: user
    },
    url: "deleteGame.php",
    success : function(result, status) {
      console.log(status);
      console.log('Success Received');
      //  function below reloads current page
      // location.reload();

    }
  });
  location.reload();
}
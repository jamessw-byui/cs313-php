function deleteUserCategory(category, user) {
  $.ajax({
    type: "POST",
    data: {
      categoryId: category,
      userId: user
    },
    url: "deleteUserCategory.php",
    success : function(result, status) {
      console.log(status);
      console.log('Success Received');
      //  function below reloads current page
      // location.reload();

    }
  });
  location.reload();
}

function addUserCategory(newCategory, user) {
  var category = newCategory.value
  $.ajax({
    type: "POST",
    data: {
      categoryName: category,
      userId: user
    },
    url: "addUserCategory.php",
    success : function(result, status) {
      console.log(status);
      console.log('Success Received');
      //  function below reloads current page
      // location.reload();

    }
  });
  location.reload();
}
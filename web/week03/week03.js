function addToCart(song) {
    $.ajax({
        type: "POST",
        url: "12Tribes.php" ,
        success : function(result, status) {
        	console.log(status);
            console.log('Success Received');
            // function below reloads current page
            // location.reload();

        }
    });
}

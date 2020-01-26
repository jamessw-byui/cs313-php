function addToCart(song) {
	$.ajax({
        type: "GET",
        url: "12Tribes.php" ,
        data: { s: song },
        success : function() {
        	console.log('Received Success');
            // function below reloads current page
            location.reload();

        }
    });
}

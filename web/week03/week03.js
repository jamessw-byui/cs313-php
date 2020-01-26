function addToCart(song) {
	$.ajax({
        type: "POST",
        url: "addSong.php" ,
        data: { "s": song },
        success : function() {
        	console.log('Received Success');
            // function below reloads current page
            location.reload();

        }
    });
}

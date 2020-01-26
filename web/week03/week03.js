function addToCart(song) {
	$.ajax({
        type: "GET",
        url: "addSong.php" ,
        data: { s: song },
        success : function() {

            // function below reloads current page
            location.reload();

        }
    });
}

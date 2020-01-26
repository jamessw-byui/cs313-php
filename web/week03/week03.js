function addToCart(song) {
    $.ajax({
        type: "POST",
        url: "addSong.php" ,
        data: { "s": song },
        success : function() {
        	console.log(result);
            console.log('Success Received');
            // function below reloads current page
            // location.reload();

        }
    });
}

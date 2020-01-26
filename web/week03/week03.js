function addToCart(song) {
    $.ajax({
        type: "POST",
        url: "addSong.php" ,
        data: { "s": song },
        success : function(textStatus, status) {
        	console.log(textStatus);
            console.log(status);
            console.log('Success Received');
            // function below reloads current page
            // location.reload();

        }
    });
}

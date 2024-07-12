isAuth = $("form").attr("isAuth");
if(isAuth == "true"){
    $("body").append("<button class = 'logout'> Log out </button>");
}
$(".logout").click(function () {
    $.ajax({
        url: '../login/logout.php',  
			method: "post",
			dataType: 'html',
			success: function(data){     
				$(".logout").remove();
		    }
    });
});

//<!--REVIEWED 8/6/20-->

/*----------------------------------*/
//JQuery script for register.php
//parameter passed is a function

$(document).ready(function()
{
	//Upon clicking 'signup', hide login form (#first) and show registration form (#second)
	$("#signup").click(function()
	{
		$("#first").slideUp("slow", function()
		{
			$("#second").slideDown("slow");
		});
	});


	//Upon clicking 'signin', hide registration form (#second) and show login form (#first)

	$("#signin").click(function()
	{
		$("#second").slideUp("slow", function()
		{
			$("#first").slideDown("slow");
		});
	});

});
/*----------------------------------*/
<!--new_rep.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/new_rep.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<?php 

include("info_panels.php");
//includes file to display header and sidebar
require('includes/other_handlers/access_handler.php');
//halts unauthorised access to page

?>

<script type="text/javascript">
	
    function toggle() 
    {
	  var x = document.getElementById("listing_page");
	  if (x.style.display === "none") 
	  {
	    x.style.display = "block";
	  } 

	  else {
	    x.style.display = "none";
	 }
	}
</script>

<div class="index_main">

	<img src="assets/images/backgrounds/cinema_chairs.jpg">

	<!--------- Page Title --------->
	<div class="page_title">	

		My Presentation

		<button  class="help_button" onclick="toggle()" type="button">
	         		?
	    </button>


	    <?php

	    	if(isset($_SESSION['username']))
			{
				$presentation=$_SESSION['username'];
			}
			else
			{
				$presentation = "";

			}
		?>


	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="listing_page">

    	<h1>Events Page</h1><br>
 
    	<p>
    		If you're like many people, you've had skydive on your bucket list for as long as you can remember. It probably sits right up there at the top (somewhere around "ride an elephant through the jungle" and "throw an opening pitch at Wrigley Field.") You know how incredible it'll be, but you're also pretty sure that once will be enough.
    	</p><br>
		<center>
	    	<a href="mailto:planetofthegrapes.ft01@gmail.com?subject=Present Customer Support"title="Contact Support">
				Contact Support
			</a>
		</center>
    </div>

	<div class="listing_container">

		
		<!--------- Template --------->
		<div class="msg_box">
			<center>
				<h4>You have 1 new presentation!</h4>
				<h4>Click 'upload' to add details!</h4>
				<br>
				<h2><?php echo $presentation; ?></h2>
				<br><br>
				 <button  class="begin_button_rep" onclick="location.href='upload_presentation_form.php'" type="button">
		         		Upload
		    	</button>
		    </center>
		</div>
		<!--------- Template --------->

		

</div>


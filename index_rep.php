<!--index_rep.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/index_rep.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<?php 

	include("info_panels.php");
	//includes file to display header and sidebar
	require('includes/other_handlers/access_handler.php');
	//halts unauthorised access to page
	include("includes/classes/Presentation.php");
	//include the Presentation class

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

		Upload Presentation	

		<button  class="help_button" onclick="toggle()" type="button">
	         		?
	    </button>

	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="listing_page">

    	<h1>Upload Page</h1><br>
 
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

		<?php

			$presentation="";

		    if(isset($_SESSION['username']))
			{
				$presentation=$_SESSION['username'];
			}
			else
			{
				$presentation = "Error: No Presentation Available";
			}

			$presentation_obj = new Presentation($con_mysqli, $username);
			//create a new presentation object
			$user_obj = new User($con_mysqli, $username);
			//create a new presentation object

			$presentation_obj->refresh_db();
			//refresh database entries
			
			if($user_obj->isNewRep())
			{
				$presentation_obj->load_new_rep_page($presentation);
				//load page prompt to upload presentation
			}
			else
			{
				//load presentation preview page
			}

		?>
		
	</div>



</div>


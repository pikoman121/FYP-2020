<!--index_main.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/index_main.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<?php 

include("info_panels.php");
//includes file to display header and sidebar
include("includes/classes/Presentation.php");
//include the Presentation class
?>

<script type="text/javascript">
	
function toggle(section) 
{
  var x = document.getElementById(section);
  if (x.style.display === "none") 
  {
    x.style.display = "block";
  } 

  else {
    x.style.display = "none";
 }
}


//don't display presentation listing button	
  //var x = document.getElementById('pl');
  //fetch side bar
  //x.style.display = "none";
  //hide sidebar

</script>

<div class="index_main">

	<img src="assets/images/backgrounds/cinema_chairs.jpg">

	<!--------- Page Title --------->
	<div class="page_title">	

		Presentation Listing	

		<button  class="help_button" onclick="toggle('listing_page')" type="button">
	         		?
	    </button>

	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="listing_page">

    	<h1>Listing Page</h1><br>
 
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

			$username="";
			//stores a username
			$acctype="";
			//store an acc type
		
	    	if(isset($_SESSION['username']) && isset($_SESSION['acctype']))
	    	//if a user is logged into the session
			{
				$username=$_SESSION['username'];
				//fetch username
				$acctype=$_SESSION['acctype'];
				//fetch user acc type
			}
	
			$presentation_obj = new Presentation($con_mysqli, $username);
			//create a new presentation object
			$presentation_obj->refresh_db();
			//refresh database entries
			$presentation_obj->load_all_presentations();
			//create script to load all presenations

		?>

	</div>

</div>


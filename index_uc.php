<!--index_uc.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/index_uc.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<?php 
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	include("info_panels.php");
	//includes file to display header and sidebar
	require('includes/other_handlers/access_handler.php');
	//halts unauthorised access to page
	include("includes/classes/Presentation.php");
	//include the Presentation class

	$username="";
	$acctype="";

	if(isset($_SESSION['username']))
	{
		$username=$_SESSION['username'];
		$acctype=$_SESSION['acctype'];
	}

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
</script>

<div class="index_main">

	<img src="assets/images/backgrounds/cinema_chairs.jpg">

	<!--------- Page Title --------->
	<div class="page_title" title="Page Title">	

		My Events	

		<button  class="help_button" onclick="toggle('help');" title="Page Help">
	         		?
	    </button>

	    <button  class="new_event_button" onclick="location.href='new_event_form.php'" title="Create a New Event">
	         		+ New Event 
	    </button>

	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="help" title="Page Help">
    	<h1>UC Events Page</h1><br>

    	<p>
    		If you're like many people, you've had skydive on your bucket list for as long as you can remember. It probably sits right up there at the top (somewhere around "ride an elephant through the jungle" and "throw an opening pitch at Wrigley Field.") You know how incredible it'll be, but you're also pretty sure that once will be enough.
    	</p>
    	<br>
		<center>
	    	<a href="mailto:planetofthegrapes.ft01@gmail.com?subject=Present Customer Support"title="Contact Support">
				Contact Support
			</a>
		</center>
    </div>

     <!--Display 'form alerts'-->

	 <div id="pending" class="form_alert" style="display:none;">Feedback pending. Present App will automatically request feedback from participants via email upon completion of the presentation.</div>

	<?php

	 if(in_array("<div class='form_alert'> ✓ New presentation added! A notification email has been sent to the representative.</div>",$_SESSION['user_alert'])) echo "<div class='form_alert'> ✓ New presentation added! A notification email has been sent to the representative.</div>"; 
	 //display message stored in user_alerts array

	 if(in_array("<div class='form_alert'> ✓ Your event has been successfully added.</div>",$_SESSION['user_alert'])) echo "<div class='form_alert'> ✓ Your event has been successfully added.</div>"; 
	 //display message stored in user_alerts array

	 if(in_array("<div class='form_alert'> ✓ Presentation Updated! A notification email has been sent to the representative.</div>",$_SESSION['user_alert'])) echo "<div class='form_alert'> ✓ Presentation Updated! A notification email has been sent to the representative.</div>"; 
	 //display message stored in user_alerts array

	 if(in_array("<div class='form_alert'> ✓ Presentation Updated!</div>",$_SESSION['user_alert'])) echo "<div class='form_alert'> ✓ Presentation Updated!</div>"; 
	 //display message stored in user_alerts array

	 if(in_array("<div class='form_alert'> ✓ Presentation Deleted</div>",$_SESSION['user_alert'])) echo "<div class='form_alert'> ✓ Presentation Deleted</div>"; 
	 //display message stored in user_alerts array

	 if(in_array("<div class='form_alert'> ✓ Event Updated!</div>",$_SESSION['user_alert'])) echo "<div class='form_alert'> ✓ Event Updated!</div>"; 
	 //display message stored in user_alerts array

	  if(in_array("<div class='form_alert'> ✓ Event Deleted</div>",$_SESSION['user_alert'])) echo "<div class='form_alert'> ✓ Event Deleted</div>"; 
	 //display message stored in user_alerts array

	 unset($_SESSION['user_alert']); 
	 // break references 

	?>

	<div class="listing_container">

		<?php

			$presentation_obj = new Presentation($con_mysqli, $username);
			//create a new presentation object
			$user_obj = new User($con_mysqli, $username);
			//create a new presentation object
			$presentation_obj->refresh_db();
			//refresh database entries
			$uc_id=$user_obj->getID();
			//get user id
			$event_query=mysqli_query($con_mysqli, "SELECT * FROM Events WHERE  e_uc_id='$uc_id'");
			//get UC data
			
			if(mysqli_num_rows($event_query)>0)
			{

				$presentation_obj->load_presentations_uc();
				//load all presenations for this UC
			}
			else
			{

				$presentation_obj->load_new_uc_page();
				//load page prompt to add new events
			}
		?>

	</div>

</div>


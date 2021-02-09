<?php
require('includes/form_handlers/export_feedback_handler.php');
//halts unauthorised access to page
?>

<!--export_feedback.php-->

<!----------------------------------------------------->
<!--http://localhost/present_app/export_feedback.php -->
<!--http://localhost/phpmyadmin/--> 
<!----------------------------------------------------->

<?php 

	include("info_panels.php");
	//includes file to display header and sidebar
	require('includes/other_handlers/access_handler.php');
	//halts unauthorised access to page
	include("includes/classes/Presentation.php");
	//include the Presentation class
	include("includes/classes/Assessment.php");
	//include the Presentation class

	$username="";
	$p_id="";
	$rep_id="";
	$event_id="";

	/*----fetch user data----*/

	if(isset($_SESSION['username']))
	//if session variable contains username (rep user logged in)
	{
		$username=$_SESSION['username'];
	}

	if(isset($_GET['p_id']))
	//if session variable contains username (rep user logged in)
	{
		$p_id = $_GET['p_id'];
		//fetch presentation id
		$form_action_str = "export_feedback.php?p_id=".$p_id;
		//get form action str
		$present_query = mysqli_query($con_mysqli, "SELECT * FROM Presentations WHERE p_id='$p_id'"); //ok
		//fetch presentation 	
		$present_row = mysqli_fetch_array($present_query); 
		//push data into an array
		$rep_id = $present_row['p_rep_id'];
		//fetch rep id
		$event_id = $present_row['p_event_id'];
		//fetch ebent id
		$ass_id = $present_row['p_assessment_id'];
	}

?>

<!--------- Javascript function--------->

<script type="text/javascript">	

    function toggle(section) 
    {
	  var x = document.getElementById(section);
	  if (x.style.display === "none") 
	  {
	    x.style.display = "block";
	  } 
	  else
	  {
	    x.style.display = "none";
	  }
	}

</script>


<div class="index_main">

	<img src="assets/images/backgrounds/cinema_chairs.jpg">

	<!--------- Page Title --------->
	<div class="page_title">	

		Export Feedback	

		<button  class="help_button" onclick="toggle('help_box')" type="button">
	         		?
	    </button>
	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="help_box">

    	<h1>Feedback Page</h1><br>
 
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

		<!--------- Event Module --------->
		<div class="event_container">

	    	<?php 
		    	/*---Displays the header bar conatining presentation information---*/
		    	$present_obj = new Presentation($con_mysqli, $username);
				//create a new presentation object
				$present_obj->showPresentationInfoFeedback($event_id,$rep_id);
				//display presentation information
	    	?>

			<!-- P-Module -->
			<div class="presentation_container">

				<div class= "event_form_container">

					<form action="<?php echo $form_action_str ?>" method="POST">

						<h3 style="font-size:24px;">Participant Feedback at a Glance</h3>
						<h4> The following page displays the average scores and sample comments given by presentation attendees. A full dataset is available for export by clicking on the 'Export to CSV' button.</h4>
						<br>
						<!--export button-->
						<input type="submit" name="export_feedback_button" value="⤓ Export to CSV">
						<br><br><br>
						
						<!-----------------feedback module----------------->

						<?php

							$ass_obj = new Ass($con_mysqli);
							//create new assessment object
							$ass_obj->display_results($p_id, $ass_id);
							//print assessment form
						?>
						
						<!-----------------feedback module----------------->

						<br>
						<h3>Click on 'Export to CSV' to receive the full dataset.</h3>
						<h4>A CSV file containing all participant responses will be downloaded to your desktop.</h4>
						<br><br><br>
				
						<!--hidden field that stores presentation id-->
						<!--On submit this field can still be accessed to retrieve the p_id to update values in Presentation table-->
						<input type="text"  style="display:none;" name="p_id" value="<?php echo $p_id;?>">
						
						<!--export button-->
						<input type="submit" name="export_feedback_button" value="⤓ Export to CSV">
						<!--back to UC index-->
						<a href="index_uc.php" class="back">Back</a>

						<br>

					</form>
				</div>

			</div>
			<!-- P-Module -->
		</div>
		<!--------- End of Event Module -------->

	</div>

</div>

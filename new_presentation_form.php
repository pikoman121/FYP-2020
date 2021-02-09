<!--new_presentation_form.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/new_presentation_form.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<?php 

	include("info_panels.php");
	//includes file to display header and sidebar
	require('includes/other_handlers/access_handler.php');
	//halts unauthorised access to page
	require('includes/form_handlers/new_presentation_handler.php');
	//Require new_presentation_handler.php (uses $event_placeholder)
	require('includes/classes/Assessment.php');
	//Require Logger class
	require('includes/form_handlers/selection_refresh_handler.php');
	//includes file to display header and sidebar

	$e_id="";
	$event_name="";

	if(isset($_GET['e_id']))
	{
		$e_id=$_GET['e_id'];
		//fetch event id
		$form_action_str = "new_presentation_form.php?e_id=".$e_id;
		//get form action string 
		$event_query = mysqli_query($con_mysqli, "SELECT * FROM Events WHERE e_id='$e_id'");
		//fetch event information
		$event_row = mysqli_fetch_array($event_query);
		//push event data into array

		$event_name= $event_row['e_unit_code']." ".$event_row['e_teaching_period']." ".$event_row['e_unit_title'];
		//get event name
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

		Add a Presentation

		<button  class="help_button" onclick="toggle('help_box')" type="button">
	         		?
	    </button>

	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="help_box">

    	<h1>Add a Presentation</h1><br>
 
    	<p>
    		If you're like many people, you've had skydive on your bucket list for as long as you can remember. It probably sits right up there at the top (somewhere around "ride an elephant through the jungle" and "throw an opening pitch at Wrigley Field.") You know how incredible it'll be, but you're also pretty sure that once will be enough.
    	</p><br>
		<center>
	    	<a href="mailto:planetofthegrapes.ft01@gmail.com?subject=Present Customer Support"title="Contact Support">
				Contact Support
			</a>
		</center>
    </div>
	
    <!--Display 'form alerts'-->

    <?php if(in_array("<div class='form_alert'> ! Presentation creation unsuccessful. Contact Support.</div>",$error_array)) echo "<div class='form_alert'> ! Presentation creation unsuccessful. Contact Support.</div>"; ?>

    <?php if(in_array("<div class='form_alert'> ! Presentation creation unsuccessful. Invalid input.</div>",$error_array)) echo "<div class='form_alert'> ! Presentation creation unsuccessful. Invalid input.</div>"; ?>

    <!--Form Contents-->

	<div class="listing_container">

		<!--------- Event Module --------->
		<div class="event_container">
			
			<div class="event_header">

				<center><h2>Presentation Details</h2></center>		

		    </div>

			<!-- P-Module -->
			<div class="presentation_container">

				<div class= "event_form_container">

					<form action="<?php echo $form_action_str;?>" style="color:#F00000;" method="POST"> 

						<h3>Schedule your presentation</h3>
						<h4>Tell participants when and where the presentation will take place</h4>
						<br>

						<!--Presentation Date -->
						<label>Date of Presentation</label>
						<input type="date"  name="present_date" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['present_date'])){
							echo $_SESSION['present_date'];unset($_SESSION['present_date']);}?>"required >


						<!--Error Messages -->
						<?php if(in_array("Presentation date cannot be earlier than current date.<br>",$error_array))
						//Display error messages in error array 
						echo "Presentation date cannot be earlier than current date.<br>";?>

						<!--Presentation Time -->
						<label>Time of Presentation</label>
						<input type="Time"  name="present_time"  value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['present_time'])){
							echo $_SESSION['present_time'];unset($_SESSION['present_time']);}?>"required >


						<!--Presentation Location -->
						<label>Location of Presentation</label>
						<input type="text" name="present_room" placeholder="The location of the presentation" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['present_room'])){
							echo $_SESSION['present_room'];unset($_SESSION['present_room']);}?>"required >

						<!--Error Messages -->
						<?php if(in_array("Location must be between 2 and 50 characters.<br>",$error_array))
						//Display error messages in error array 
						echo "Location must be between 2 and 50 characters.<br>";?>


						<br><br><hr><br>
						<h3>Assgin a representative</h3>
						<br>
						<h4>Your assigned representative will be notified via email to upload information for this presentation.</h4>
						<br>
	
						<!--Rep first name -->
						<label> First Name of Representative</label>
						<input type="text" name="rep_fname" placeholder="Representative's First Name" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['rep_fname'])){
							echo $_SESSION['rep_fname'];unset($_SESSION['rep_fname']);}?>"required >

						<!--Error Messages -->
						<?php if(in_array("First name must be between 2 and 25 characters.<br>",$error_array))
						//Display error messages in error array 
						echo "First name must be between 2 and 25 characters.<br>";?>

						<!--Rep last name -->
						<label> Last Name of Representative </label>
						<input type="text" name="rep_lname" placeholder="Representative's Last Name" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['rep_lname'])){
							echo $_SESSION['rep_lname'];unset($_SESSION['rep_lname']);}?>"required >

						<!--Error Messages -->
						<?php if(in_array("Last name must be between 2 and 25 characters.<br>",$error_array))
						//Display error messages in error array 
						echo "Last name must be between 2 and 25 characters.<br>";?>

						<!--Rep Email -->
						<label> Email of Representative (required)</label>
						<input type="email" name= "rep_email" class="input_group_b" placeholder="Representative's Email" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['rep_email'])){
							echo $_SESSION['rep_email'];unset($_SESSION['rep_email']);}?>"required >

						<!--Error Messages -->
						<?php if(in_array("Invalid email format.<br>",$error_array))
						//Display error messages in error array 
						echo "Invalid email format.<br>";?>

						<!--Error Messages -->
						<?php if(in_array("This email is restricted. Enter a different email.<br>",$error_array))
						//Display error messages in error array 
						echo "This email is restricted. Enter a different email.<br>";?>

						<br><br><hr><br> 

						<h3>Choose your preferred assessment</h3>
						<h4>Participants will provide feedback according to your chosen assessment.</h4>

						<!--Assessment Selector -->
						<label>Assessment Type</label>
						 <?php
						 	$ass_obj = new Ass ($con_mysqli);
						 	//create a new assessment obj
						 	$ass_obj-> display_assessment_options();
						 	//display menu of all assessment options
						 ?>

						 <!--refresh selection-->
						 <input type="submit" name="refresh_sel_button" style="background-color:#FFF; color:#CD0000; width:22.5%; "value="⟳ Refresh List">
						 <br><br><br>
						<!--new custom form link--> 
						<a class="new_tab" href="form_builder.php" target="_blank">✎ New Custom Form</a>
						<!--preview forms link-->
						<a class="new_tab" href="form_preview.php" target="_blank">🔍 Preview Forms</a>
						<br><br>


						<br><br><hr><br>
						<h4>Clicking 'Submit' adds a new presentation to the following event:</h4>
						<h3><?php echo $event_name;?> </h3>
						<br><br>

						<!--stores e_id passes e_id to handler-->
						<input type="text" style="display:none;" name="event_id" value="<?php echo $e_id;?>">
						<!--Submit Button-->
						<input type="submit" name="new_present_button" value="Submit">
						<!--back to UC index-->
						<a href="index_uc.php" class="back">Back</a>

					</form>
				</div>
			</div>
			<!-- P-Module -->
		</div>
		<!--------- End of Event Module -------->
	</div>
</div>



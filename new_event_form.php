<!--new_event_form.php-->

<!----------------------------------------------------->
<!--http://localhost/present_app/new_event_form.php -->
<!--http://localhost/phpmyadmin/--> 
<!----------------------------------------------------->

<!--------- Include Files Section --------->
<?php 

	include("info_panels.php");
	//includes file to display header and sidebar
	require('includes/other_handlers/access_handler.php');
	//halts unauthorised access to page
	require('includes/form_handlers/new_event_handler.php');
	//Require new_event_handler.php

?>
<!--------- Javascript Functions --------->
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
	<div class="page_title">	
		Create an Event	
		<button  class="help_button" onclick="toggle('help_box')" type="button">
	         		?
	    </button>
	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="help_box">
    	<h1>New Event Form</h1><br>
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

	<?php if(in_array("<div class='form_alert'> ! Event creation unsuccessful. Contact support.</div>",$error_array)) echo "<div class='form_alert'> ! Event creation unsuccessful. Contact support.</div>"; ?>

	<?php if(in_array("<div class='form_alert'> ! Event creation unsuccessful. Invalid input.</div>",$error_array)) echo "<div class='form_alert'> ! Event creation unsuccessful. Invalid input.</div>"; ?>

	<!--form contents-->
	
	<div class="listing_container">

		<!--------- Event Module --------->
		<div class="event_container">
			
			<div class="event_header">
				<center><h2>Event Details</h2></center>				
		    </div>

			<!-- P-Module -->
			<div class="presentation_container">
				<div class= "event_form_container">
					<form id="new_event_form" action="new_event_form.php" style="color:#F00000;" method="POST">

						<!--'Add Event' Form Fields-->
						<h3>Enter unit information</h3>
						<h4>This will identify your event on the listings page.</h4>
						<br>

						<!--Event Unit Code-->
						<label> Unit Code</label>
						<input type="text" name="event_unit_code" placeholder="The unit code" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_unit_code'])){
							echo $_SESSION['event_unit_code'];}?>"required >
						
						<!--Error Messages -->
						<?php if(in_array("Unit code must be between 2 and 20 characters.<br>",$error_array))
						//Display error messages in error array 
						echo "Unit code must be between 2 and 20 characters.<br>";?>
		
						<!--Event Unit Title-->
						<label> Unit Title</label>
						<input type="text" name="event_unit_title" placeholder="The title of the unit" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_unit_title'])){
							echo $_SESSION['event_unit_title'];}?>"required >
						
						<!--Error Messages -->
						<?php if(in_array("Unit title must be between 2 and 50 characters.<br>",$error_array))
						//Display error messages in error array 
						echo "Unit title must be between 2 and 50 characters.<br>";?>


						<!--Event Teaching Period-->
						<label> Teaching Period</label>
						<input type="text" name="event_teaching_period" placeholder="The teaching period of the unit" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_teaching_period'])){
							echo $_SESSION['event_teaching_period'];}?>"required >
						
						<!--Error Messages -->
						<?php if(in_array("Teaching Period must be between 2 and 50 characters.<br>",$error_array))
						//Display error messages in error array 
						echo "Teaching Period must be between 2 and 50 characters.<br>";?>


						<!--Event Unit Description-->
						<label> Unit Description</label><br>
						<!--Event Unit Description-->
						<?php 
							$text_start="<textarea class=\"description_box\" name=\"event_unit_desc\" form=\"new_event_form\" required>";
							//text area html start
							$text_end="</textarea>";
							//text area html end

							if(isset($_SESSION['event_unit_desc']))
							//session variable contains unit description
							{
								echo $text_start.$_SESSION['event_unit_desc'].$text_end;
							}
							else
							//get unti description from database
							{
								echo $text_start."A description of the unit...".$text_end;
							}
						?>

						<br><hr><br>
						<h3>Event duration and location</h3>
						<h4>Tell participants about when and where your event will be held.</h4>
						<br>
	
						<!--Event Start date -->
						<label> Event Start Date</label>
						<input type="date" name="event_start_date" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_start_date'])){
							echo $_SESSION['event_start_date'];}?>"required >

						<!--Event End date -->
						<label> Event End Date</label>
						<input type="date" name="event_end_date" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_end_date'])){
							echo $_SESSION['event_end_date'];}?>"required >

						<!--Error Messages -->
						<?php if(in_array("Event start date cannot be later than the end date.<br>",$error_array))
						//Display error messages in error array 
						echo "Event start date cannot be later than the end date.<br>";?>

						<!--Event Campus -->
						<label> Campus</label>
						<input type="text" name="event_campus" placeholder="A campus within Murdoch University" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_campus'])){
							echo $_SESSION['event_campus'];}?>"required >
						<!--Error Messages -->
						<?php if(in_array("Event campus must be between 2 and 50 characters.<br>",$error_array))
						//Display error messages in error array 
						echo "Event campus must be between 2 and 50 characters.<br>";?>
						<br><hr><br>
						<h3>Clicking 'Submit' adds a new event to your list. </h3>
						<h4>You can add presentations to your event once it has been created.</h4>
						<br><br>

						<!--Submit Button-->
						<input type="submit" name="new_event_button" value="Submit">
						<!--Back Button-->
						<a href="index_uc.php" class="back">Back</a>
					</form>
				</div>
			</div>
			<!-- P-Module -->
		</div>
		<!--------- End of Event Module -------->
	</div>
</div>

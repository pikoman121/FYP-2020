<!--edit_event_form.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/edit_event_form.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<!--------- Include Files Section --------->
<?php 

	include("info_panels.php");
	//includes file to display header and sidebar
	require('includes/form_handlers/edit_event_handler.php');
	//Require new_event_handler.php
	require('includes/form_handlers/delete_event_handler.php');
	//Require new_event_handler.php
	require('includes/other_handlers/access_handler.php');
	//halts unauthorised access to page

	$e_id="";
	$event_name="";

	if(isset($_GET['e_id']))
	{
		$e_id=$_GET['e_id'];
		//fetch event id
		$form_action_str="edit_event_form.php?e_id=".$e_id;
		//get form action string
		$event_query = mysqli_query($con_mysqli, "SELECT * FROM Events WHERE e_id='$e_id'");
		//fetch event information
		$event_row = mysqli_fetch_array($event_query);
		//push event data into array
		$event_name= $event_row['e_unit_code']." ".$event_row['e_teaching_period']." ".$event_row['e_unit_title'];
		//get event name
	}

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
		Edit Event	
		<button  class="help_button" onclick="toggle('help_box')" type="button">
	         		?
	    </button>
	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="help_box">
    	<h1>New Event Form</h1><br>
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

	<?php if(in_array("<div class='form_alert'> ! Event update unsuccessful. Contact Support.</div>",$error_array)) echo "<div class='form_alert'> ! Event update unsuccessful. Contact Support.</div>"; ?>

	<?php if(in_array("<div class='form_alert'> ! Event update unsuccessful. Invalid input.</div>",$error_array)) echo "<div class='form_alert'> ! Event update unsuccessful. Invalid input.</div>"; ?>

	<!--form contents-->
	
	<div class="listing_container">

		<!--------- Event Module --------->
		<div class="event_container">
			
			<div class="event_header">
				<center><h2>Edit Event Details</h2></center>				
		    </div>

			<!-- P-Module -->
			<div class="presentation_container">
				<div class= "event_form_container">
					<form id="new_event_form" action="<?php echo $form_action_str?>" style="color:#F00000;" method="POST">

						<!--'Add Event' Form Fields-->
						<h4>You are modifying the following event:</h4>
						<h3><?php echo $event_name;?></h3>
						<br>

						<!--Event Unit Code-->
						<label> Unit Code</label>
						<input type="text" name="event_unit_code" placeholder="The unit code" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_unit_code'])){
							echo $_SESSION['event_unit_code'];unset($_SESSION['event_unit_code']);}
						else{echo $event_row['e_unit_code'];}?>"required >

						<!--Error Messages -->
						<?php if(in_array("Unit code must be between 2 and 20 characters.<br>",$error_array))
						//Display error messages in error array 
						echo "Unit code must be between 2 and 20 characters.<br>";?>
		
						<!--Event Unit Title-->
						<label> Unit Title</label>
						<input type="text" name="event_unit_title" placeholder="The title of the unit" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_unit_title'])){
							echo $_SESSION['event_unit_title']; unset($_SESSION['event_unit_title']);}
						else{echo $event_row['e_unit_title'];}?>"required >

						<!--Error Messages -->
						<?php if(in_array("Unit title must be between 2 and 50 characters.<br>",$error_array))
						//Display error messages in error array 
						echo "Unit title must be between 2 and 50 characters.<br>";?>

						<!--Event Teaching Period-->
						<label> Teaching Period</label>
						<input type="text" name="event_teaching_period" placeholder="The teaching period of the unit" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_teaching_period'])){
							echo $_SESSION['event_teaching_period'];unset($_SESSION['event_teaching_period']);}
						else{echo $event_row['e_teaching_period'];}?>"required >

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
								echo $text_start.$event_row['e_unit_description'].$text_end;
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
							echo $_SESSION['event_start_date']; unset($_SESSION['event_start_date']);}
						else{echo $event_row['e_start_date'];}?>"required>


						<!--Event End date -->
						<label> Event End Date</label>
						<input type="date" name="event_end_date" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_end_date'])){
							echo $_SESSION['event_end_date'];unset($_SESSION['event_end_date']);}
						else{echo $event_row['e_end_date'];}?>"required >

						<!--Error Messages -->
						<?php if(in_array("Event start date cannot be later than the end date.<br>",$error_array))
						//Display error messages in error array 
						echo "Event start date cannot be later than the end date.<br>";?>

						<!--Event Campus -->
						<label> Campus</label>
						<input type="text" name="event_campus" placeholder="A campus within Murdoch University" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['event_campus'])){
							echo $_SESSION['event_campus'];unset($_SESSION['event_campus']);}
						else{echo $event_row['e_campus'];}?>"required >

						<!--Error Messages -->
						<?php if(in_array("Event campus must be between 2 and 50 characters.<br>",$error_array))
						//Display error messages in error array 
						echo "Event campus must be between 2 and 50 characters.<br>";?>

						<br><hr><br>
						<h3>Click 'Save' to apply changes to the event:</h3>
						<h4><?php echo $event_name;?></h4>
						<br><br>

						<!-- *** stores e_id passes e_id to handler **** -->
						<input type="text" style="display:none;" name="eid" value="<?php echo $e_id;?>">

						<!--Submit Button-->
						<input type="submit" name="edit_event_button" value="Save">	

						<!--Delete Button-->
						<input type="submit" style="background-color: #FFF; color:#CD0000; margin-right:10px;" name="delete_event_button" value="Delete Event">

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

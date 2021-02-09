<!--edit_event_form.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/edit_event_form.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<!--------- Include Files Section --------->
<?php 

	include("info_panels.php");
	//includes file to display header and sidebar
	require('includes/form_handlers/edit_uc_handler.php');
	//Require new_event_handler.php

	//Require new_event_handler.php


	$username="";
	$event_name="";
	$user_name = $user_obj->getUsername();
	$user_id = $user_obj->getID();
	$uc_email = $user_obj->getEmail();
	$form_action_str="edit_uc_info.php?u_id=".$user_id;
	$user_query = mysqli_query($con_mysqli, "SELECT * FROM users WHERE u_id='$user_id'");
		//fetch event information
	$user_row = mysqli_fetch_array($user_query);
		//push event data into array
?>


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
    	</p>
    	<br>
		<center>
	    	<a href="mailto:planetofthegrapes.ft01@gmail.com?subject=Present Customer Support"title="Contact Support">
				Contact Support
			</a>
		</center>
    </div>

    <!--Display 'form alerts'-->

	<?php if(in_array("<div class='form_alert'> ! UC info update unsuccessful. Contact Support.</div>",$error_array)) echo "<div class='form_alert'> ! UC info update unsuccessful. Contact Support.</div>"; ?>

	<?php if(in_array("<div class='form_alert'> ! UC info update unsuccessful. Invalid input.</div>",$error_array)) echo "<div class='form_alert'> ! UC info update unsuccessful. Invalid input.</div>"; ?>

	<!--form contents-->
	
	<div class="listing_container">

		<!--------- Event Module --------->
		<div class="event_container">
			
			<div class="event_header">
				<center><h2>Reset UC information</h2></center>				
		    </div>
				<!-------------------------Registration Form---------------------------->

				<div class="presentation_container">
				<div class= "event_form_container">
					<form id="new_event_form" action="<?php echo $form_action_str?>" style="color:#0a0a0a;" method="POST">

					<!--Reset Form Fields-->
					<label>User Name: </label>
					<?php echo $user_name ;?>
					<br>
					<label>User ID: </label>
					<?php echo $user_id;?>
					
					<br>
					<!--First Name Section -->
					<input type="text" name="edit_fname" placeholder="First Name" value="<?php
					//PHP code to restore previously entered data  (in event of error)
					if(isset($_SESSION['edit_fname'])){
						echo $_SESSION['edit_fname'];}?>"required >
					<!--Error Messages -->
					<?php if(in_array("<br> Your first name must be between 2 and 25 characters.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Your first name must be between 2 and 25 characters.";?>		
					<br>


					<!--Last Name Section -->
					<input type="text" name="edit_lname" placeholder="Last Name" value="<?php
					//PHP code to restore previously entered data  (in event of error)
					if(isset($_SESSION['edit_lname'])){
						echo $_SESSION['edit_lname'];}?>"required >
					
					<!--Error Messages -->
					<?php if(in_array("<br> Your last name must be between 2 and 25 characters.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Your last name must be between 2 and 25 characters.";?>
					<br>

					<!--Telephone Section -->
					<input type="text" name="edit_tel" placeholder="Telephone" value="<?php
					//PHP code to restore previously entered data  (in event of error)
					if(isset($_SESSION['edit_tel'])){
						echo $_SESSION['edit_tel'];}?>"required >

					<!--Error Messages -->
					<?php if(in_array("<br> Your telephone number can only contain numbers.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Your telephone number can only contain numbers.<br>";?>
					<br>
		

					<!--Email Section -->
					<input type="email" name="edit_email" placeholder="Email" value="<?php
					//PHP code to restore previously entered data  (in event of error)
					if(isset($_SESSION['edit_email'])){
						echo $_SESSION['edit_email'];}?>"required >
					<br>
					<input type="email" name="edit_email2" placeholder="Confirm Email" value="<?php
					//PHP code to restore previously entered data (in event of error)
					if(isset($_SESSION['edit_email2'])){
						echo $_SESSION['edit_email2'];}?>" required>


					<!--Error Messages -->	
					<?php if(in_array("<br> Email already in use.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Email already in use.";
					else if(in_array("<br> Emails don't match.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Emails don't match.";
					else if(in_array("<br> Invalid email format.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Invalid email format.";?>
				    <br>
					<input type="text" style="display:none;" name="uid" value="<?php echo $user_id;?>">
					<input type="text" style="display:none;" name="umail" value="<?php echo $uc_email;?>">
					<!--Register Button-->
					<input type="submit" name="reset_uc_button" value="Reset">
					<br>
					<!--Back Button-->
					<a href="uc_info.php" class="back">Back</a>
					</form>
					
				</div>
				</div>
			<!-- P-Module -->
		</div>
		<!--------- End of Event Module -------->
	</div>
</div>

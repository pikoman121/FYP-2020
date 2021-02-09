<!--edit_event_form.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/edit_event_form.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<?php 

include("info_panels.php");
//includes file to display header and sidebar
include("includes/classes/Users.php");
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

		UC profile	

		<button  class="help_button" onclick="toggle()" type="button">
	         		?
	    </button>

	    <?php

	    //temp
	    	if(isset($_SESSION['username']))
			{
				$user_name = $_SESSION['username'];
				$acctype=$_SESSION['acctype'];
				echo "<h4>This is ".$user_name ."'s ".$acctype." page!</h4>";
				$uc_name = $user_obj->getFirstAndLastName();
				$email = $user_obj->getEmail();
				$date = $user_obj->getSignDate();
				$tel = $user_obj->getTel();
				$user_id = $user_obj->getID();
			}
		?>


	</div>
	<div class="help_box" id="listing_page">

    	<h1>User Profile</h1><br>
 
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
			
			<div class="event_header">

				<h2>Profile information below</h2>				

		    </div>

			<!-- P-Module -->
			<div class="presentation_container">

				<div class= "event_form_container">

					<form action="login_uc.php" method="POST">

					<!--Add Event Form Fields-->
						<br>

						<!--Unit Code Input-->
						<hr style="height:2px;border-width:0;color:gray;background-color:gray">
						<label> Full Name: </label>
						<?php echo '<label><span style="color:#000000;text-align:left;">'.$uc_name.'</span></label>'; ?>
						<br><hr style="width:30%;text-align:left;margin-left:0"><br>
						<!--Unit Description Input -->
						<label> User Name: </label>
						<?php echo '<label><span style="color:#000000;text-align:left;">'.$user_name .'</span></label>'; ?>
						<br><hr style="width:30%;text-align:left;margin-left:0"><br>
						<label> User ID: </label>
						<?php echo '<label><span style="color:#000000;text-align:left;">'.$user_id .'</span></label>'; ?>
						<br><hr style="width:30%;text-align:left;margin-left:0"><br>
						<label> Telephone: </label>
						<?php echo '<label><span style="color:#000000;text-align:left;">'.$tel.'</span></label>'; ?>
						<br><hr style="width:30%;text-align:left;margin-left:0"><br>
						<label> Email: </label>
						<?php echo '<label><span style="color:#000000;text-align:left;">'.$email.'</span></label>'; ?>
						<br><hr style="width:30%;text-align:left;margin-left:0"><br>
						<label> Sign Up Date: </label>
						<?php echo '<label><span style="color:#000000;text-align:left;">'.$date.'</span></label>'; ?>
						<br>
						<br>
						<input  type="button" onclick="location.href='edit_uc_info.php'" value = "Change">
						<br><hr style="height:2px;border-width:0;color:gray;background-color:gray"><br><br>
						
						<label> Password </label>
						<?php echo '<label><span style="color:#000000;text-align:left;">*********</span></label>'; ?>
						<input  type="button" onclick="location.href='includes/other_handlers/uc_reset_password.php'" value = "Change">
						
						<br><br><hr style="height:2px;border-width:0;color:gray;background-color:gray"><br><br>
						
						<a href="index_uc.php" class="back"> Back </a>
						<!--return to site button-->


					</form>
				</div>

			</div>
			<!-- P-Module -->
		</div>
		<!--------- End of Event Module -------->

		

	</div>

</div>

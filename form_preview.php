<!--form_preview.php-->

<!----------------------------------------------------->
<!--http://localhost/present_app/form_preview.php -->
<!--http://localhost/phpmyadmin/--> 
<!----------------------------------------------------->

<!--------- Include Files Section --------->
<?php 

	include("info_panels.php");
	//includes file to display header and sidebar
	require('includes/other_handlers/access_handler.php');
	//halts unauthorised access to page
	include("includes/classes/Assessment.php");
	//include the Presentation class

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
		Assessment Previewer
		<button  class="help_button" onclick="toggle('help_box')" type="button">
	         		?
	    </button>
	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="help_box">
    	<h1>Feedback</h1><br>
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


	<!--form contents-->
	
	<div class="listing_container">

		<!--------- Event Module --------->
		<div class="event_container">
			
			<div class="event_header">
				<center><h2>Explore Assessments</h2></center>				
		    </div>

			<!-- P-Module -->
			<div class="presentation_container">

				<div class= "event_form_container">

					<form id="form_builder" action="form_preview.php" method="POST">

						<h3>Explore Present's wide-variety of assessments.</h3>
						<h4>Select an assessment from the drop down menu and then click preview.</h4>
				

						<!--Assessment Selector -->
						<label>Assessment Type</label>

						 <?php

						 	$ass_obj = new Ass ($con_mysqli);
						 	//create a new assessment obj
						 	$ass_obj-> display_assessment_options();
						 	//display menu of all assessment options
						 ?>

						 <!--Preview Button-->
						<input type="submit" name="preview_form_button" style="width:20%;" value="Preview">

						<!-----------------question modules----------------->

						<?php

						 	if(isset($_POST['preview_form_button']))
							//preview button is clicked
							{
								
								$choice = $_POST['assessment_type'];
								//get the assessment type
								$ass_query=mysqli_query($con_mysqli, "SELECT * FROM Assessments WHERE ass_id = $choice");
								//query event database
								$ass_row = mysqli_fetch_array($ass_query);
								//push results into array

								$title = $ass_row['ass_title'];
								//fetch title
								$desc = $ass_row['ass_description'];
								//fetch description

								echo "<br><br><center><h4>------- SAMPLE -------</h4></center><br>";
								echo "<hr><br><h3 style=\"font-size:30px;\">$title</h3><h4>$desc</h4><br>";

								$ass_obj->display_form($choice);
							 	//display form

							 	echo "<h4><center>------- SAMPLE -------</center></h4><br>";
							}
						?>

						<!--set selector-->
						 <script type="text/javascript">
						 	document.getElementById("sel").value = <?php echo $choice;?>
						 	//set previous selection
						 </script>

						<br><br><br>
						<button type="button" class="close_tab" onclick="window.open('', '_self', ''); window.close();">x Close Tab</button>
						
					</form>
				</div>
			</div>
			<!-- P-Module -->
		</div>
		<!--------- End of Event Module -------->
	</div>
</div>

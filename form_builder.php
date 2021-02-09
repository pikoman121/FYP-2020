<!--form_builder.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/form_builder.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<!--------- Include Files Section --------->
<?php 

	include("info_panels.php");
	//includes file to display header and sidebar
	require('includes/other_handlers/access_handler.php');
	//halts unauthorised access to page
	require('includes/form_handlers/form_builder_handler.php');
	//includes file to display header and sidebar
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
		Assessment Builder
		<button  class="help_button" onclick="toggle('help_box')" type="button">
	         		?
	    </button>
	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="help_box">
    	<h1>Assessment Builder</h1><br>
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

	<?php if(in_array("<div class='form_alert'> ✓ New assessment added to list!</div>",$error_array)) echo "<div class='form_alert'> ✓ New assessment added to list!</div>"; ?>

	<?php if(in_array("<div class='form_alert'> ! Assessment creation unsuccessful. Contact Support.</div>",$error_array)) echo "<div class='form_alert'> ! Assessment creation unsuccessful. Contact Support.</div>"; ?>

	<?php if(in_array("<div class='form_alert'> ! Too few qustions. Enter at least one question.</div>",$error_array)) echo "<div class='form_alert'> ! Too few qustions. Enter at least one question.</div>"; ?>

	<!--form contents-->
	
	<div class="listing_container">

		<!--------- Event Module --------->
		<div class="event_container">
			
			<div class="event_header">
				<center><h2>Create an Assessment</h2></center>				
		    </div>

			<!-- P-Module -->
			<div class="presentation_container">

				<div class= "event_form_container">

					<form id="form_builder" action="form_builder.php" style="color:#CCCCCC;" method="POST">

						<h3 style="font-size:24px;">New Assessment</h3>
						<h4>Fill up the fields below to create a custom assessment.</h4>
						<h4>Give your assessment a name and a description.</h4>
						<h4>Add up to 10 questions. Each question requires a minimum of 5 characters.</h4>
						<br><hr><br>
						
						<!--Assessment  Name-->
						<label>Name your assessment</label>
						<input type="text" name="ass_name" placeholder="Assessment name" value="" required>

						<!--Assessment  Description-->
						<label>Describe your assessment</label>
						<textarea class="description_box" name="ass_desc" form="form_builder" required>Assessment Description...</textarea>

						<br><br><hr><br>

						<!--Question Field-->
						<button  class="add_question_button" onclick="toggle('q1')" type="button">
	         				+ Add Question 1
	    				</button>
						<div class="question_input" id="q1">
							<label>Question 1</label>
							<input type="text" name="q1_question" placeholder="Enter a question here..." value="">
							<label>Select a Response Type</label>
							<select name="q1_input">
							  <option value="n">Numeric (1-5)</option>
							  <option value="t">Text</option>
							</select>
						</div>
						<br><br><hr><br>

						<!--Question Field-->
						<button  class="add_question_button" onclick="toggle('q2')" type="button">
	         				+ Add Question 2
	    				</button>
						<div class="question_input" id="q2">
							<label>Question 2</label>
							<input type="text" name="q2_question" placeholder="Enter a question here..." value="">
							<label>Select a Response Type</label>
							<select name="q2_input">
							  <option value="n">Numeric (1-5)</option>
							  <option value="t">Text</option>
							</select>
						</div>
						<br><br><hr><br>

						<!--Question Field-->
						<button  class="add_question_button" onclick="toggle('q3')" type="button">
	         				+ Add Question 3
	    				</button>
						<div class="question_input" id="q3">
							<label>Question 3</label>
							<input type="text" name="q3_question" placeholder="Enter a question here..." value="">
							<label>Select a Response Type</label>
							<select name="q3_input">
							  <option value="n">Numeric (1-5)</option>
							  <option value="t">Text</option>
							</select>
						</div>
						<br><br><hr><br>

						<!--Question Field-->
						<button  class="add_question_button" onclick="toggle('q4')" type="button">
	         				+ Add Question 4
	    				</button>
						<div class="question_input" id="q4">
							<label>Question 4</label>
							<input type="text" name="q4_question" placeholder="Enter a question here..." value="">
							<label>Select a Response Type</label>
							<select name="q4_input">
							  <option value="n">Numeric (1-5)</option>
							  <option value="t">Text</option>
							</select>
						</div>
						<br><br><hr><br>

						<!--Question Field-->
						<button  class="add_question_button" onclick="toggle('q5')" type="button">
	         				+ Add Question 5
	    				</button>
						<div class="question_input" id="q5">
							<label>Question 5</label>
							<input type="text" name="q5_question" placeholder="Enter a question here..." value="">
							<label>Select a Response Type</label>
							<select name="q5_input">
							  <option value="n">Numeric (1-5)</option>
							  <option value="t">Text</option>
							</select>
						</div>
						<br><br><hr><br>

						<!--Question Field-->
						<button  class="add_question_button" onclick="toggle('q6')" type="button">
	         				+ Add Question 6
	    				</button>
						<div class="question_input" id="q6">
							<label>Question 6</label>
							<input type="text" name="q6_question" placeholder="Enter a question here..." value="">
							<label>Select a Response Type</label>
							<select name="q6_input">
							  <option value="n">Numeric (1-5)</option>
							  <option value="t">Text</option>
							</select>
						</div>
						<br><br><hr><br>

						<!--Question Field-->
						<button  class="add_question_button" onclick="toggle('q7')" type="button">
	         				+ Add Question 7
	    				</button>
						<div class="question_input" id="q7">
							<label>Question 7</label>
							<input type="text" name="q7_question" placeholder="Enter a question here..." value="">
							<label>Select a Response Type</label>
							<select name="q7_input">
							  <option value="n">Numeric (1-5)</option>
							  <option value="t">Text</option>
							</select>
						</div>
						<br><br><hr><br>

						<!--Question Field-->
						<button  class="add_question_button" onclick="toggle('q8')" type="button">
	         				+ Add Question 8
	    				</button>
						<div class="question_input" id="q8">
							<label>Question 8</label>
							<input type="text" name="q8_question" placeholder="Enter a question here..." value="">
							<label>Select a Response Type</label>
							<select name="q8_input">
							  <option value="n">Numeric (1-5)</option>
							  <option value="t">Text</option>
							</select>
						</div>
						<br><br><hr><br>

						<!--Question Field-->
						<button  class="add_question_button" onclick="toggle('q9')" type="button">
	         				+ Add Question 9
	    				</button>
						<div class="question_input" id="q9">
							<label>Question 9</label>
							<input type="text" name="q9_question" placeholder="Enter a question here..." value="">
							<label>Select a Response Type</label>
							<select name="q9_input">
							  <option value="n">Numeric (1-5)</option>
							  <option value="t">Text</option>
							</select>
						</div>
						<br><br><hr><br>

						<!--Question Field-->
						<button  class="add_question_button" onclick="toggle('q10')" type="button">
	         				+ Add Question 10
	    				</button>
						<div class="question_input" id="q10">
							<label>Question 10</label>
							<input type="text" name="q10_question" placeholder="Enter a question here..." value="">
							<label>Select a Response Type</label>
							<select name="q10_input">
							  <option value="n">Numeric (1-5)</option>
							  <option value="t">Text</option>
							</select>
						</div>
						<br><br><hr><br>

						<h3>Click 'Save Assessment' to make it available for use.</h3>
						<h4>Once saved this assessment will be available to all unit coordinators.</h4>
						<br><br>

						<!-- *** stores p_id passes p_id to handler *** -->
						<input type="text" style="display:none;" name="pid" value="<?php echo $p_id;?>">
						
						<!--Save Button-->
						<input type="submit" name="save_assessment_button" value="Save Assessment">
						<br><br>

						<!--Close tab Button-->
						<button type="button" class="close_tab" onclick="window.open('', '_self', ''); window.close();">x Close Tab</button>
						
					</form>
				</div>
			</div>
			<!-- P-Module -->
		</div>
		<!--------- End of Event Module -------->
	</div>
</div>

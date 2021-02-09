<!--feedback_form_handler.php-->

<?php 

	require(dirname(__DIR__).'\classes\Logger.php');
	//Require Logger class

	$logger = new Logger();  
	//initialises a new logger object

	/*-----------------------------------------------------*/
	//Declare variables (for input validation)

	$p_id="";
	//presentation id
	$a_id="";
	//attendee id
	$ass_id="";
	//assessment id

	/*-----------------------------------------------------*/
	//function to update rows in results table
	
	function update_results_row($con, $result_id, $column, $input_data)
	{
		$update_query=mysqli_query($con, "UPDATE Results SET $column = '$input_data' WHERE r_id='$result_id'");
		//update row

		if($update_query==true)
		{
			return true;
			//query successful
		} 
		else
		{
			return false;
			//query failed
		}
	}

	/*-----------------------------------------------------*/
	
	$error_array = array();	
	//holds error messages in an array
	
	$_SESSION['user_alert']=array();
    //create an array to store user alerts in the session variable


	//process data
	if(isset($_POST['submit_feedback_button']))
	//new feedback form submitted
	{

		$p_id=$_POST['p_id'];
		//get presentation id

		$a_id=$_POST['a_id'];
		//get attendee id

		$ass_id=$_POST['ass_id'];
		//get assessment id

		$logger->write("STARTED > feedback_form_handler.php for a_id $a_id, p_id $p_id");
		//send activity log


		/*-----------------------------------------------------*/
		//check if attendee signed up for the presentation

		$check_query=mysqli_query($con_mysqli, "SELECT* FROM Attendees WHERE a_presentation_id='$p_id' AND a_id='$a_id'");

		if(mysqli_num_rows($check_query)>0)
		//rows exist
		{
			$logger->write("SUCCESS > Attendee match (true) a_id $a_id, p_id $p_id");
			//send activity log

			$ass_obj = new Ass($con_mysqli);
			//create new assessment object

			$column_array=$ass_obj->get_column_array($ass_id);
			//stores an array of columns in which input must be updated

			/*-----------------------------------------------------*/
			//Create a new 'blank' row in Results table

			$result_query=mysqli_query($con_mysqli, "INSERT INTO Results VALUES (NULL, '$p_id', '$a_id', '$ass_id', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none')");
			//insert into results table 

			$result_id=$con_mysqli->insert_id;
			//get result id 

			if($result_query==true)
			//insert into results table successful 
			{
				$logger->write("SUCCESS > Insert into Results table a_id $a_id, p_id $p_id");
				//send activity lo

				$status=true;
				//update status

				$input_data="";
				//input_data

				$col=1;
				//column position

				$num_cols=sizeof($column_array)-1;
				//number of collumns in the array (note the first element of the array is blank)
				
				/*-----------------------------------------------------*/
				//Update table with form input

				for ($i=0; $i < $num_cols; $i++)
				//from first to the last column in column array
				{
					$col_name=array_pop($column_array);
					//get column name

					if(isset($_POST[$col_name]))
					{
						$input_data=$_POST[$col_name];
						//get input data from feedback form

						$result=update_results_row($con_mysqli, $result_id, $col_name, $input_data);
						//update column in result row

						if($result==false)
						//update error
						{
							$status=false;
							//set status
						}
					}	
				}


				if($status==true)
				//no update errors found
				{
					//successful update

					/*-----------------------------*/
					//* Update attendee stats

					$update_query=mysqli_query($con_mysqli, "UPDATE Attendees SET a_feedback = 'yes' WHERE a_id='$a_id'");
					//update attenddess table a_feedback=yes

					/*-----------------------------*/
					//* Update Presentation Stats

					$get_num = mysqli_query($con_mysqli, "SELECT p_num_responses FROM Presentations WHERE p_id='$p_id'");
					//query data base for attendee count
					$row = mysqli_fetch_array($get_num);
					//push results into an array and assign to l.h. variable
					$num_responses= $row['p_num_responses'];
					//assign likes to l.h. variable
					$num_responses ++;
					//increment value
					$query = mysqli_query($con_mysqli, "UPDATE Presentations SET p_num_responses = '$num_responses' WHERE p_id='$p_id'");

					/*-----------------------------*/

					$logger->write("SUCCESS > Update Results table r_id: $result_id");
					//send activity log

					array_push($_SESSION['user_alert'],"<div class='form_alert'> ✓ Thank you! Your feedback has been saved.</div>");
					//* push alert message

					/*-----------------------------*/
					//redirect to main site

					header("Location: index_main.php");
					//redirect the user to login portal
					exit();
					//close script

				}
				else
			   //update errors found
				{
					$delete_query=mysqli_query($con_mysqli, "DELETE FROM Results where r_id = '$result_id'");
					//error on update - perform rollback
					$logger->write("FAILURE > Update Results table r_id: $result_id (row deleted)");
					//send activity log
					array_push($error_array,"<div class='form_alert'> ! Feedback unsuccessful. Contact Support.</div>");
					//push error msg into array
				}
			}
			else
			{
				//query error

				$logger->write("FAILURE > Insert into Results table a_id $a_id, p_id $p_id");
				//send activity log
				array_push($error_array,"<div class='form_alert'> ! Feedback unsuccessful. Contact Support.</div>");
				//push error msg into array
			}
		}
		else
		{

			$logger->write("FAILURE > Attendee match (false) a_id $a_id, p_id $p_id");
			//send activity log
			array_push($_SESSION['user_alert'],"<div class='form_alert'> ! You did not sign up for this presenation!</div>");
			//* push alert message
			header("Location: index_main.php");
			//redirect the user to main page
		}

	}

?>
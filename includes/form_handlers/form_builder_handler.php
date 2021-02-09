<!--form_builder_handler.php-->


<?php

	require(dirname(__DIR__).'\classes\Assessment.php');
	//Require Logger class
	require(dirname(__DIR__).'\classes\Logger.php');
	//Require Logger class

	$logger = new Logger();  
	//initialises a new logger object


	/*-----------------------------------------------------*/
	//Declare variables (for input validation)

	$ass_title="";
	$ass_desc="";
	$question="";
	$input="";
	$username="";
	$acctype="";

	if(isset($_SESSION['username']))
	{
		$username=$_SESSION['username'];
		$acctype=$_SESSION['acctype'];
	}

	/*-----------------------------------------------------*/

	$error_array = array();	//holds error messages in an array


	/*-----------------------------------------------------*/


	if(isset($_POST['save_assessment_button']))
	//save assessment button is clicked

	{
		$logger->write("STARTED > form_builder_handler.php for $acctype $username");
		//send activity log
		$ass = new Ass($con_mysqli);
		//new assessment object
		$file = $ass->get_new_file();
		//create  new file path
		$n_count=1;
		//counter var
		$t_count=1;
		//counter var
		$met_minimum=false;
		//counter var

		/*-----------------------------------------------------*/
		//Get assessment name and description

		$ass_title = strip_tags($_POST['ass_name']);
		//remove any html tags and store form values in the l.h. value
		$ass_desc = strip_tags($_POST['ass_desc']);
		//remove any html tags and store form values in the l.h. value

		/*-----------------------------------------------------*/
		//Read in question fields and write to file

		if($file!="error")
		// new file created successfully
		{

			/*START----------------------------------------------------------*/

			//* Get Question + Input (#1)
			if (isset($_POST['q1_question']) && !empty($_POST['q1_question']))
			//if question field is not empty
			{
				/*-----------------------------*/
				//* Get Question
				$question = strip_tags($_POST['q1_question']);
				//remove any html tags and store form values in the l.h. value

				if(strlen($question)>=5)
				//question is t least 5 characters long
				{
					/*-----------------------------*/
					//* Get Input
					$input = $_POST['q1_input'];
					//get input type

					if($input=="n")
					{
						$input=$input.$n_count;
						//set input string
						$n_count++;
						//increment n count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
						$met_minimum=true;
						//increment line count
					}
					else if($input=="t")
					{
						$input=$input.$t_count;
						//set input string
						$t_count++;
						//increment t_count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
						$met_minimum=true;
						//increment line count
					}
				}
			}
			/*END----------------------------------------------------------*/

			/*START----------------------------------------------------------*/

			//* Get Question + Input (#2)
			if (isset($_POST['q2_question']) && !empty($_POST['q2_question']))
			//if question field is not empty
			{
				/*-----------------------------*/
				//* Get Question
				$question = strip_tags($_POST['q2_question']);
				//remove any html tags and store form values in the l.h. value

				if(strlen($question)>=5)
				//question is t least 5 characters long
				{
					/*-----------------------------*/
					//* Get Input
					$input = $_POST['q2_input'];
					//get input type

					if($input=="n")
					{
						$input=$input.$n_count;
						//set input string
						$n_count++;
						//increment n count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
					else if($input=="t")
					{
						$input=$input.$t_count;
						//set input string
						$t_count++;
						//increment t_count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
				}
			}
			/*END----------------------------------------------------------*/

			/*START----------------------------------------------------------*/

			//* Get Question + Input (#3)
			if (isset($_POST['q3_question']) && !empty($_POST['q3_question']))
			//if question field is not empty
			{
				/*-----------------------------*/
				//* Get Question
				$question = strip_tags($_POST['q3_question']);
				//remove any html tags and store form values in the l.h. value

				if(strlen($question)>=5)
				//question is t least 5 characters long
				{
					/*-----------------------------*/
					//* Get Input
					$input = $_POST['q3_input'];
					//get input type

					if($input=="n")
					{
						$input=$input.$n_count;
						//set input string
						$n_count++;
						//increment n count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
					else if($input=="t")
					{
						$input=$input.$t_count;
						//set input string
						$t_count++;
						//increment t_count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
				}
			}
			/*END----------------------------------------------------------*/

			/*START----------------------------------------------------------*/

			//* Get Question + Input (#4)
			if (isset($_POST['q4_question']) && !empty($_POST['q4_question']))
			//if question field is not empty
			{
				/*-----------------------------*/
				//* Get Question
				$question = strip_tags($_POST['q4_question']);
				//remove any html tags and store form values in the l.h. value

				if(strlen($question)>=5)
				//question is t least 5 characters long
				{
					/*-----------------------------*/
					//* Get Input
					$input = $_POST['q4_input'];
					//get input type

					if($input=="n")
					{
						$input=$input.$n_count;
						//set input string
						$n_count++;
						//increment n count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
					else if($input=="t")
					{
						$input=$input.$t_count;
						//set input string
						$t_count++;
						//increment t_count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
				}
			}
			/*END----------------------------------------------------------*/

			/*START----------------------------------------------------------*/

			//* Get Question + Input (#5)
			if (isset($_POST['q5_question']) && !empty($_POST['q5_question']))
			//if question field is not empty
			{
				/*-----------------------------*/
				//* Get Question
				$question = strip_tags($_POST['q5_question']);
				//remove any html tags and store form values in the l.h. value

				if(strlen($question)>=5)
				//question is t least 5 characters long
				{
					/*-----------------------------*/
					//* Get Input
					$input = $_POST['q5_input'];
					//get input type

					if($input=="n")
					{
						$input=$input.$n_count;
						//set input string
						$n_count++;
						//increment n count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
					else if($input=="t")
					{
						$input=$input.$t_count;
						//set input string
						$t_count++;
						//increment t_count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
				}
			}
			/*END----------------------------------------------------------*/

			/*START----------------------------------------------------------*/

			//* Get Question + Input (#6)
			if (isset($_POST['q6_question']) && !empty($_POST['q6_question']))
			//if question field is not empty
			{
				/*-----------------------------*/
				//* Get Question
				$question = strip_tags($_POST['q6_question']);
				//remove any html tags and store form values in the l.h. value

				if(strlen($question)>=5)
				//question is t least 5 characters long
				{
					/*-----------------------------*/
					//* Get Input
					$input = $_POST['q6_input'];
					//get input type

					if($input=="n")
					{
						$input=$input.$n_count;
						//set input string
						$n_count++;
						//increment n count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
					else if($input=="t")
					{
						$input=$input.$t_count;
						//set input string
						$t_count++;
						//increment t_count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
				}
			}
			/*END----------------------------------------------------------*/

			/*START----------------------------------------------------------*/

			//* Get Question + Input (#7)
			if (isset($_POST['q7_question']) && !empty($_POST['q7_question']))
			//if question field is not empty
			{
				/*-----------------------------*/
				//* Get Question
				$question = strip_tags($_POST['q7_question']);
				//remove any html tags and store form values in the l.h. value

				if(strlen($question)>=5)
				//question is t least 5 characters long
				{
					/*-----------------------------*/
					//* Get Input
					$input = $_POST['q7_input'];
					//get input type

					if($input=="n")
					{
						$input=$input.$n_count;
						//set input string
						$n_count++;
						//increment n count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
					else if($input=="t")
					{
						$input=$input.$t_count;
						//set input string
						$t_count++;
						//increment t_count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
				}
			}
			/*END----------------------------------------------------------*/

			/*START----------------------------------------------------------*/

			//* Get Question + Input (#8)
			if (isset($_POST['q8_question']) && !empty($_POST['q8_question']))
			//if question field is not empty
			{
				/*-----------------------------*/
				//* Get Question
				$question = strip_tags($_POST['q8_question']);
				//remove any html tags and store form values in the l.h. value

				if(strlen($question)>=5)
				//question is t least 5 characters long
				{
					/*-----------------------------*/
					//* Get Input
					$input = $_POST['q8_input'];
					//get input type

					if($input=="n")
					{
						$input=$input.$n_count;
						//set input string
						$n_count++;
						//increment n count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
					else if($input=="t")
					{
						$input=$input.$t_count;
						//set input string
						$t_count++;
						//increment t_count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
				}
			}
			/*END----------------------------------------------------------*/

			/*START----------------------------------------------------------*/

			//* Get Question + Input (#9)
			if (isset($_POST['q9_question']) && !empty($_POST['q9_question']))
			//if question field is not empty
			{
				/*-----------------------------*/
				//* Get Question
				$question = strip_tags($_POST['q9_question']);
				//remove any html tags and store form values in the l.h. value

				if(strlen($question)>=5)
				//question is t least 5 characters long
				{
					/*-----------------------------*/
					//* Get Input
					$input = $_POST['q9_input'];
					//get input type

					if($input=="n")
					{
						$input=$input.$n_count;
						//set input string
						$n_count++;
						//increment n count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
					else if($input=="t")
					{
						$input=$input.$t_count;
						//set input string
						$t_count++;
						//increment t_count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
				}
			}
			/*END----------------------------------------------------------*/

			/*START----------------------------------------------------------*/

			//* Get Question + Input (#10)
			if (isset($_POST['q10_question']) && !empty($_POST['q10_question']))
			//if question field is not empty
			{
				/*-----------------------------*/
				//* Get Question
				$question = strip_tags($_POST['q10_question']);
				//remove any html tags and store form values in the l.h. value

				if(strlen($question)>=5)
				//question is t least 5 characters long
				{
					/*-----------------------------*/
					//* Get Input
					$input = $_POST['q10_input'];
					//get input type

					if($input=="n")
					{
						$input=$input.$n_count;
						//set input string
						$n_count++;
						//increment n count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
					else if($input=="t")
					{
						$input=$input.$t_count;
						//set input string
						$t_count++;
						//increment t_count
						$ass->write_to_file($file, $question, $input);
						//write question + input to file
					}
				}
			}
			/*END----------------------------------------------------------*/

			/*-----------------------------------------------------*/
			//Insert data into Assessments table

			if($met_minimum==true)
			//at least 1 question entered
			{
				$ass_query=mysqli_query($con_mysqli, "INSERT INTO Assessments VALUES (NULL,'$ass_title','$ass_desc','$file')"); 
				//insert into Assessments table 

				if($ass_query==true)
				//insert successful
				{
					//query failed
					array_push($error_array,"<div class='form_alert'> ✓ New assessment added to list!</div>");
					//push message onto error array
					$logger->write("SUCCESS > Insert into Assessments table a_title: $ass_title");
					//send activity log

				}
				else
				{
					//query failed
					array_push($error_array,"<div class='form_alert'> ! Assessment creation unsuccessful. Contact Support.</div>");
					//push message onto error array
					$logger->write("FAILURE > Insert into Assessments table a_title: $ass_title");
					//send activity log
				}

			}
			else
			{
				//too few questions
				array_push($error_array,"<div class='form_alert'> ! Too few qustions. Enter at least one question.</div>");
				//push message onto error array
				$logger->write("FAILURE > Insert into Assessments table a_title: $ass_title");
				//send activity log
			}

		}
		else
		{
			//file open error
			array_push($error_array,"<div class='form_alert'> ! Assessment creation unsuccessful. Contact Support.</div>");
			//push message onto error array
			$logger->write("FAILURE > Insert into Assessments table a_title: $ass_title");
			//send activity log
		}

	}

?>
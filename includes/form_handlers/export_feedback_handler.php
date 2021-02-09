<?php

//--export_feedback_handler.php-->

/* Query sample
SELECT r_presentation_id,a_first_name,a_last_name,a_affiliation,a_position,a_email,n1,n2,t1,t2 
FROM Results LEFT JOIN Attendees
ON Results.r_attendee_id = Attendees.a_id
WHERE r_presentation_id = 3; */

/*-----------------------------------------------------*/
//process request

if(isset($_POST['export_feedback_button']))
//export button clicked
{

	require(dirname(__DIR__).'\classes\Assessment.php');
	//Require Logger class

	$con = mysqli_connect("localhost","root","","present_app_db");
	//creates a connection variable and connects to MySQL server
	//Connects to the 'present_app_db' database

	if(mysqli_connect_errno())
	//if error number returned
	{
		echo "Failed to connect: " . mysqli_connect_errno();
	}
	//print error message if connection was unsuccessful


	$p_id = $_POST['p_id'];
	//fetch pid

	/*-----------------------------------------------------*/
	//Get data for query

	$present_query=mysqli_query($con, "SELECT * FROM Presentations WHERE p_id='$p_id'"); //ok
	//fetch presentation 	
	$present_row=mysqli_fetch_array($present_query); //ok
	//push data into an array
	$ass_id= $present_row['p_assessment_id'];
	//fetch ass id

	 $ass_obj = new Ass($con);
     //create new assessment object
     $input_col = $ass_obj->get_column_str ($ass_id);
     //get input columns for the specific assessment
     $input_arr = $ass_obj->get_column_array($ass_id);
     //get input columns for the specific assessment

     $query = "SELECT a_first_name,a_last_name,a_affiliation,a_position,a_email,$input_col FROM Results LEFT JOIN Attendees ON Results.r_attendee_id = Attendees.a_id WHERE r_presentation_id = $p_id;";
     //create query string 

	$col_array = array('First Name','Last Name','Affiliation','Position','Email');

	$num_questions = sizeof($input_arr);
	//get number of questions

	for ($pos=1; $pos<$num_questions; $pos++)
	//push input columns into col_array
	{
		array_push($col_array, "Q$pos");
	}
 	//create a column array

	/*-----------------------------------------------------*/
	//Query database for results

     $result = mysqli_query($con, $query); 
     //Query database

    /*-----------------------------------------------------*/
	//Print results to CSV

	 ob_start();
	//Turns on output buffering

	 ob_end_clean();
	 //erase/turn off output buffering

	 $output = fopen("php://output", "w");  
	//create a file point connect it to output stream and give it write function hence the 'w'.

     fputcsv($output, $col_array);
 	//Set the columns of the CSV file

     while($row = mysqli_fetch_assoc($result))  
     // while results exist output row to CSV file
     {  
          fputcsv($output, $row); 
          // Output the row to the CSV 
     }  

    fclose($output); 
    //Close file

    /*-----------------------------------------------------*/
	//call CSV export function

	header('Content-Type: text/csv; charset=utf-8'); 
	// Header function for csv
	header('Content-Disposition: attachment; filename=assessment_data.csv'); 
	// Header function for file name and the csv be downloaded instead of displayed.

	exit();
	//close script

}

?>
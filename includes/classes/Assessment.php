<?php

//--Assessment.php-->

//--- * Change Permissions as superuser(for creating new files) * ---
//2 >> navigate to present_app/includes/assessments
//3 >> glennlum$ sudo chmod 777 assessments -->

/*----------'Assessment' Class Definition----------*/
class Ass
{

	private $con;
	//stores a connection variable
	private $column_array;
	//stores array of column headers (set element 0 as */ questions start from 1)

	/*--------constructor--------*/
	public function __construct($connection)
	{
		$this->con = $connection;
	}

	/*--------display assessment options--------*/
	public function display_assessment_options()
	{
		$str=
		"
		<select  class=\"ass_selector\" id=\"sel\" name=\"assessment_type\" style=\"width:50.5%; height:4.5%;\">
		";

		$ass_query=mysqli_query($this->con, "SELECT * FROM Assessments");

		while($ass_row = mysqli_fetch_array($ass_query)) 
		{
			$option_num = $ass_row['ass_id'];
			$option_title = $ass_row['ass_title'];

			$str.="<option value=\"$option_num\">$option_title</option>";
		}

		$str.="</select>";

		echo $str;
	}


	/*--------display assessment form--------*/
	public function display_form($a_id)
	{
		$str="";
		//a html string

		/*--------------- open the file by  -------------*/

		$ass_query=mysqli_query($this->con, "SELECT * FROM Assessments WHERE ass_id=$a_id");
		//fetch assessment data by id
		$ass_row = mysqli_fetch_array($ass_query);
		//push results into an array
		$this->txt_file = $ass_row['ass_filepath'];
		//fetch file path
		$file=fopen($this->txt_file, "r") or die("Unable to open file!"); 
		//open txt file - read only - file must exist / print error message if unable to open

		
		if ($file!=NULL)
		{
			/*---------------Prepare variables for data read -------------*/

			$field="question";
			//type of value contained in the field
			$question="";
			//form question
			$input="";
			//form input type
			$num_questions = sizeof($this->get_column_array($a_id))-1;
			//get number of questions

			/*---------------Read data from file -------------*/

			for ($pos=0; $pos<$num_questions; $pos++)
			//iterate from first to last question 
			{

				$line = fgets($file);
				//read a line of text
				$row_item = strtok($line, "^");
				//tokenise first item in line of text

				while ($row_item !== false) 
				{
				    //assign row_item to correct variable
				    if($field=="question")
				    {
				    	$question=$row_item;
				    	//get question
				    	$num=$pos+1;
				    	//set question number

				    	/*--add to html string--*/

				    	$str.=
				    	"
				    	<!-----------------question module----------------->
						<div class=\"question_field\" style=\"padding:10px;\">
							<!--question-->
							<label style=\"font-size:28px;\">Q$num</label><br>
							<label style=\"font-size:19px; line-height: 1.3em;\">$question</label>
						";	

				    	/*--add to html string--*/

				    	$field="input";
				    	//toggle field value
				    }
				    else if ($field=="input")
				    {
				    	$input=$row_item;
				    	//get input type
				    	$input=$input[0].$input[1];
				    	//extract first two characters

				    	/*--add to html string--*/

				    	if($input[0]=='n')
				    	//numeric input required
				    	{
				    		$str.=
				    		"
					    		<!--input numeric-->
								<h4>Choose a response from 1 (least favourable) to 5 (most favourable):</h4>
								<select style=\"height:35px; width:75%;\"name=\"$input\" form=\"feedback\" required>
								  <option value=\"1\">1</option>
								  <option value=\"2\">2</option>
								  <option value=\"3\">3</option>
								  <option value=\"4\">4</option>
								  <option value=\"5\">5</option>
								</select>
							</div>
							<br><hr><br><br>
							";

				    	}
				    	else if($input[0]=='t')
				    	//text input required
				    	{
				    		$str.=
				    		"
					    		<!--input text-->
								<h4>Type your response below:</h4>
								<textarea class=\"description_box\" name=\"$input\" form=\"feedback\" required>Your thoughts here...</textarea>
							</div>
							<br><hr><br><br>
							";
				    	}
				    	/*--add to html string--*/

				    	$field="question";
				    	//toggle field value
				    }

				    $row_item = strtok("^");
				    //get next token

				}//END inner WHILE

			}//END outer WHILE
			/*----------------------------*/

			echo $str;
			//print html string
		}
	}


	/*--------display results--------*/
	public function display_results($p_id, $a_id)
	{
		$str="";
		//a html string

		/*--------------- open the file by  -------------*/

		$ass_query=mysqli_query($this->con, "SELECT * FROM Assessments WHERE ass_id=$a_id");
		//fetch assessment data by id
		$ass_row = mysqli_fetch_array($ass_query);
		//push results into an array
		$this->txt_file = $ass_row['ass_filepath'];
		//fetch file path
		$file=fopen($this->txt_file, "r") or die("Unable to open file!"); 
		//open txt file - read only - file must exist / print error message if unable to open

		
		if ($file!=NULL)
		{
			/*---------------Prepare variables for data read -------------*/

			$i=0;
			//counter var
			$field="question";
			//type of value contained in the field
			$question="";
			//form question
			$input="";
			//form input type
			$num_questions = sizeof($this->get_column_array($a_id))-1;
			//get number of questions

			/*---------------Read data from file -------------*/

			for ($pos=0; $pos<$num_questions; $pos++)
			//iterate from first to last question 
			{
				
				$line = fgets($file);
				//read a line of text
				$row_item = strtok($line, "^");
				//tokenise first item in line of text

				//echo "$i ";

				while ($row_item !== false) 
				{
				    //assign row_item to correct variable
				    if($field=="question")
				    {
				    	$question=$row_item;
				    	//get question
				    	$num=$pos+1;
				    	//set question number

				    	/*--add to html string--*/

				    	$str.=
				    	"
				    	<!-----------------question module----------------->
						<div class=\"question_field\" style=\"padding:10px;\">
							<!--question-->
							<label style=\"font-size:28px;\">Q$num</label><br>
							<label style=\"font-size:19px; line-height: 1.3em;\">$question</label>
						";	

				    	/*--add to html string--*/

				    	$field="input";
				    	//toggle field value
				    }
				    else if ($field=="input")
				    {
				    	$input=$row_item;
				    	//get input type
				    	$input=$input[0].$input[1]; //*******
				    	//extract first two characters

				    	/*--add to html string--*/

				    	if($input[0]=='n')
				    	//numeric input required
				    	{

				    		//get the average score
				    		$result_query=mysqli_query($this->con, "SELECT r_id, AVG($input) 'avg_score' from Results WHERE r_presentation_id='$p_id'"); 
							//fethc the average score for the given column 
							$result_row=mysqli_fetch_array($result_query); 
							//push data into an array
							$avg_score=$result_row['avg_score'];
							//get the average score
							$avg_score=round($avg_score,2);
							//round to 2 decimal place

							$result_str="";
							//create a result string

							for ($i=0; $i <floor($avg_score); $i++)
							{ 
								$result_str.="★";
							}
							for ($i=0; $i < 5-floor($avg_score); $i++)
							{ 
								$result_str.="☆";
							}

							$result_str=$result_str." (".$avg_score.")";
							//generate a result string

				    		$str.=
				    		"
					    	<!--input numeric-->
							<h3 style=\"font-size:20px; margin-left: 9%;\">Average Score: $result_str </h3>
							</div>
							<br><hr><br><br>
							";

				    	}
				    	else if($input[0]=='t')
				    	//text input required
				    	{
				    		$result_str="";
							//create a result string
				    		$result_query=mysqli_query($this->con, "SELECT * from Results WHERE r_presentation_id='$p_id'"); 
							//fethc the average score for the given column 
							while($result_row = mysqli_fetch_array($result_query))
							{
								$result_str.="'".$result_row[$input]."'\n\n";

							}  //while $result_row contains data

				    		$str.=
				    		"
					    	<!--input text-->
							<h3 style=\"margin-left:8%;\">Sample Responses:</h3>
							<textarea class=\"description_box\" style=\"margin-left:8%; overflow:scroll;\"name=\"t1\" form=\"feedback_form\" required>$result_str</textarea>
							</div>
							<br><hr><br><br>
							";
				    	}
				    	/*--add to html string--*/

				    	$field="question";
				    	//toggle field value
				    }

				    $row_item = strtok("^");
				    //get next token

				}//END inner WHILE

			}//END outer WHILE
			/*----------------------------*/

			echo $str;
			//print html string
		}
	}



	/*--------get an array of columns--------*/
	public function get_column_array($a_id)
	{
		$this->column_array=array('*');
		//initialise column array

		/*--------------- open the file by  -------------*/
		$ass_query=mysqli_query($this->con, "SELECT * FROM Assessments WHERE ass_id=$a_id");
		//fetch assessment data by id
		$ass_row = mysqli_fetch_array($ass_query);
		//push results into an array
		$this->txt_file = $ass_row['ass_filepath'];
		//fetch file path
		$file=fopen($this->txt_file, "r") or die("Unable to open file!"); 
		//open txt file - read only - file must exist / print error message if unable to open
		
		if ($file!=NULL)
		{
			/*---------------Prepare variables for data read -------------*/
	
			$field="question";
			//type of value contained in the field
			$question="";
			//form question
			$input="";
			//form input type

			/*---------------Read data from file -------------*/
			while (!feof($file)) 
			{
				$line = fgets($file);
				//read a line of text
				$row_item = strtok($line, "^");
				//tokenise first item in line of text


				while ($row_item !== false) 
				{
				    //assign row_item to correct variable
				    if($field=="question")
				    {
				    	$question=$row_item;
				    	//get question
				    	$field="input";
				    	//toggle field value
				    }
				    else if ($field=="input")
				    {
				    	$input=$row_item;
				    	//get input type
				    	$input=$input[0].$input[1]; //*******
				    	//extract first two characters
				    	array_push($this->column_array, $input);
				    	//push input type into column array (for later use)
				    	$field="question";
				    	//toggle field value
				    }
				    $row_item = strtok("^");
				    //get next token

				}//END inner WHILE

			}//END outer WHILE
			/*----------------------------*/
			return $this->column_array; //note: number of questions = length of array-1
			//return html string
		}
	}


	/*--------get an array of columns--------*/
	public function get_column_str ($a_id)
	{
		$str="";

		/*--------------- open the file by  -------------*/
		$ass_query=mysqli_query($this->con, "SELECT * FROM Assessments WHERE ass_id=$a_id");
		//fetch assessment data by id
		$ass_row = mysqli_fetch_array($ass_query);
		//push results into an array
		$this->txt_file = $ass_row['ass_filepath'];
		//fetch file path
		$file=fopen($this->txt_file, "r") or die("Unable to open file!"); 
		//open txt file - read only - file must exist / print error message if unable to open
		
		if ($file!=NULL)
		{
			/*---------------Prepare variables for data read -------------*/
	
			$field="question";
			//type of value contained in the field
			$question="";
			//form question
			$input="";
			//form input type

			/*---------------Read data from file -------------*/
			while (!feof($file)) 
			{
				$line = fgets($file);
				//read a line of text
				$row_item = strtok($line, "^");
				//tokenise first item in line of text


				while ($row_item !== false) 
				{
				    //assign row_item to correct variable
				    if($field=="question")
				    {
				    	$question=$row_item;
				    	//get question
				    	$field="input";
				    	//toggle field value
				    }
				    else if ($field=="input")
				    {
				    	$input=$row_item;
				    	//get input type
				    	$input=$input[0].$input[1]; //*******
				    	//extract first two characters
				    	$str.=$input.",";
				    	//append input column to string
				    	$field="question";
				    	//toggle field value
				    }
				    $row_item = strtok("^");
				    //get next token

				}//END inner WHILE

			}//END outer WHILE
			/*----------------------------*/
			return rtrim($str,',');
			//return html string
		}
	}

	/*--------opens a new file for writing--------*/
	public function get_new_file()
	{
		$i=1;
		//counter var
		$file = "includes/assessments/ass".$i.".txt";
		//initial file path
		$check_file_query = mysqli_query($this->con,"SELECT ass_filepath FROM Assessments WHERE ass_filepath='$file'");
		//check if file exists in database

		if($check_file_query==true)
		//lookup successful
		{
			while(mysqli_num_rows($check_file_query)!=0)
			//if file exists, increment file number
			{
				$i++; 
				//increment counter
				$file =  "includes/assessments/ass".$i.".txt";
				//update file path
				$check_file_query = mysqli_query($this->con,"SELECT ass_filepath FROM Assessments WHERE ass_filepath='$file'");
				//check if file exists in database
			}
			
			$result = fopen($file, "w+") or die("Unable to open file!");
			//create a new file in assessments folder 

			if($result!=NULL)
			{
				return $file;
				//return the new file path to caller
			}
			else
			{
				$file="error";
				//set error msg
				return $file;
				//return error message
			}
		}
		else
		//lookup not successful
		{
			$file="error";
			//set error msg
			return $file;
			//return error message
		}
	}

	/*--------write a line of text to file--------*/
	public function write_to_file($file, $question, $input)
	{
		$file = fopen($file, "a") or die("Unable to open file!");
		//open a file in assessments folder 

		if($file!=NULL)
		//file opened successfully
		{	
			$str=$question."^".$input."\n";
			//create string to be written
			fwrite($file, $str);
			//write to assemsent file
			fclose($file);
			//close assessment file
			return true;
			//write successful
		}
		else
		{
			return false;
			//write not successful
		}
	}

}

/*----------'Assessment' Class Definition----------*/

?>
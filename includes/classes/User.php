
<!--User.php-->

<?php

/*----------'User' Class Definition----------*/
class User
{
	/*--------------------*/

	private $user; 
	//stores a username
	private $con;
	//stores a connection variable

	/*--------------------*/

	//class constructor
	public function __construct($con,$user)
	{
		$this->con = $con;
		//assign connection variable to $con
		$user_details_query = mysqli_query($this->con, "SELECT * FROM Users WHERE u_username='$user'");
		//fetch user details from database
		$this->user=mysqli_fetch_array($user_details_query);
		//push user details into an array and assign to $user
	}

	/*--------------------*/


	public function getAccType()
	{
		return $this->user['u_acc_type'];
		//returns username
	}

	/*--------------------*/

	public function getUsername()
	{
		return $this->user['u_username'];
		//returns username
	}

	/*--------------------*/

		public function getID()
	{
		return $this->user['u_id'];
		//returns username
	}

	/*--------------------*/

	public function getEventID()
	{

		$rep_id=$this->getID();
		//fetch representative id
		$present_query=mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_rep_id='$rep_id'");
		//get presentation data
		$present_row = mysqli_fetch_array($present_query);
		//push event data into an array
		return $present_row['p_event_id'];
		//return the event id
	}
	/*--------------------*/

	public function getPresentationID()
	{

		$rep_id=$this->getID();
		//fetch representative id
		$present_query=mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_rep_id='$rep_id'");
		//get presentation data
		$present_row = mysqli_fetch_array($present_query);
		//push event data into an array
		return $present_row['p_id'];
		//return the event id
	}

	/*--------------------*/

	public function getFirstAndLastName()
	{
		$userID = $this->getID();
		//get username from $user and assign to $username

		$query = mysqli_query($this->con, "SELECT u_first_name, u_last_name FROM Users WHERE u_id='$userID'");
		//query database and assign results to query

		$row = mysqli_fetch_array($query);
		//push query results into an array and assign to $row

		return $row['u_first_name']." ".$row['u_last_name'];
		//return first and last name in a string
	}
	
	/*--------------------*/

	public function getEmail()
	{
		$userID = $this->getID();
		//get username from $user and assign to $username

		$query = mysqli_query($this->con, "SELECT u_email FROM Users WHERE u_id='$userID'");
		//query database and assign results to query

		$row = mysqli_fetch_array($query);
		//push query results into an array and assign to $row

		return $row['u_email'];
		//return first and last name in a string
	}

	/*--------------------*/

	public function getTel()
	{
		$userID = $this->getID();
		//get username from $user and assign to $username

		$query = mysqli_query($this->con, "SELECT u_telephone FROM Users WHERE u_id='$userID'");
		//query database and assign results to query

		$row = mysqli_fetch_array($query);
		//push query results into an array and assign to $row

		return $row['u_telephone'];
		//return first and last name in a string
	}

	/*--------------------*/

	public function getSignDate()
	{
		$userID = $this->getID();
		//get username from $user and assign to $username

		$query = mysqli_query($this->con, "SELECT u_signup_date FROM Users WHERE u_id='$userID'");
		//query database and assign results to query

		$row = mysqli_fetch_array($query);
		//push query results into an array and assign to $row

		return $row['u_signup_date'];
		//return first and last name in a string
	}
	
	/*--------------------*/

	public function getProfilePic()
	{
		$username = $this->user['u_username'];
		//get username from $user and assign to $username

		$query = mysqli_query($this->con, "SELECT u_profile_pic FROM Users WHERE u_username='$username'");
		//query database and assign results to query

		$row = mysqli_fetch_array($query);
		//push query results into an array and assign to $row

		return $row['u_profile_pic'];
		//return first and last name in a string
	}

	/*--------------------*/

	//For representative users
	public function getUCName()
	{
		$rep_id = $this->user['u_id'];
		//get rep id
		$present_query = mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_rep_id='$rep_id'");
		//query database and assign results to query
		$present_row = mysqli_fetch_array($present_query);
		//push query results into an array 
		$event_id=$present_row['p_event_id'];
		//fetch event id of presentation
		$event_query = mysqli_query($this->con, "SELECT * FROM Events WHERE e_id='$event_id'");
		//query database and assign results to query
		$event_row = mysqli_fetch_array($event_query);
		//push query results into an array 

		return $event_row['e_uc_name'];
		//return first and last name in a string
	}

	/*--------------------*/

	//For representative users
	public function getUCEmail()
	{
		$rep_id = $this->user['u_id'];
		//get rep id
		$present_query = mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_rep_id='$rep_id'");
		//query database and assign results to query
		$present_row = mysqli_fetch_array($present_query);
		//push query results into an array 
		$event_id=$present_row['p_event_id'];
		//fetch event id of presentation
		$event_query = mysqli_query($this->con, "SELECT * FROM Events WHERE e_id='$event_id'");
		//query database and assign results to query
		$event_row = mysqli_fetch_array($event_query);
		//push query results into an array 

		return $event_row['e_uc_email'];
		//return first and last name in a string
	}

	/*--------------------*/

	//For representative users
	public function getPresentStatus()
	{
		$rep_id = $this->user['u_id'];
		//get rep id
		$present_query = mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_rep_id='$rep_id'");
		//query database and assign results to query
		$present_row = mysqli_fetch_array($present_query);
		//push query results into an array 
		
		$result=$present_row['p_published'];

		if ($result=="yes")
		{
			return "Published On<br>".$present_row['p_date_published'];
		}
		else
		{
			return "Not Published";
		}
	}

	/*--------------------*/

	//For representative users
	public function getDaysToPresent()
	{
		$rep_id = $this->user['u_id'];
		//get rep id
		$present_query = mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_rep_id='$rep_id'");
		//query database and assign results to query
		$present_row = mysqli_fetch_array($present_query);
		//push query results into an array 
		$present_date=$present_row['p_date'];
		//fetch the presentation date
		$today = date("Y-m-d");
		//get today's dates

		$diff = date_diff(date_create($today), date_create($present_date));
		//get date difference in days
		$diff=$diff->format('%a');
		//formate result

		if ($diff>0)
		//0 or more days
		{
			return $diff;
		}
		else if($diff==0)
		//today
		{
			return "It's Today!";
		}
		else
		//presentation is closed
		{
			return "Already Closed";
		}
	}
	/*--------------------*/

	//For UC users
	public function getUpcomingEvents()
	{
		$uc_id = $this->user['u_id'];
		//get rep id
		$event_query = mysqli_query($this->con, "SELECT * FROM Events WHERE e_uc_id='$uc_id' AND e_event_closed='no'");
		//query database and assign results to query
		$event_count = mysqli_num_rows($event_query);
		//push query results into an array 
		return $event_count;
		//return num of upcoming events
	}

   /*--------------------*/

	//For UC users
	public function getNumPresentations()
	{
		$uc_id = $this->user['u_id'];
		//get rep id
		$event_query = mysqli_query($this->con, "SELECT * FROM Events WHERE e_uc_id='$uc_id' AND e_event_closed='no'");
		//query database and assign results to query
		
		$result=0;
		//counter

		while($event_row=mysqli_fetch_array($event_query))
		//while rows exist
		{
			$result+=$event_row['e_num_presentations'];
			//add up num of presentations in each event
		}
		return $result;
		//return result
	}

	/*--------------------*/

	//For UC users
	public function getNumPublished()
	{
		$uc_id = $this->user['u_id'];
		//get rep id
		$event_query = mysqli_query($this->con, "SELECT * FROM Events WHERE e_uc_id='$uc_id' AND e_event_closed='no'");
		//query database and assign results to query
		
		$result=0;
		//counter

		while($event_row=mysqli_fetch_array($event_query))
		//while rows exist
		{
			$e_id=$event_row['e_id'];
			//fetch event id

			$present_query = mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_event_id='$e_id' AND p_published='yes'");
			//query database for published presentations

			$result+=mysqli_num_rows($present_query);
			//add up number of published presentations
		}
		return $result;
		//return result
	}

	/*--------------------*/

	//For UC users
	public function isNewUC()
	{
		$event_count = $this->getUpcomingEvents();

		if($event_count==0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*--------------------*/

	//For REP users
	public function isNewRep()
	{
		$rep_id= $this->user['u_id'];

		$present_query = mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_rep_id='$rep_id' AND p_published='yes'");
		//query database for published presentations

		$result=mysqli_num_rows($present_query);
		//check if this rep's presentation is published

		if($result==0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
/*----------'User' Class Definition----------*/

?>
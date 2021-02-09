<!--login_handler.php-->

<?php 

require(dirname(__DIR__).'\classes\User.php');
	//Require User class 

	$logger = new Logger(); //start logger  (file already included in registration handler)
	//initialises a new logger object

/*-------------------------login_handler(UC)-------------------------*/


	if(isset($_POST['login_uc_button']))
	//if login_button variable is not NULL
	{

		$logger->write("STARTED > login_handler.php for acctype: UC");
		//send activity log

		/*-----------------------------------------------------*/
		//Prepare Email and Password for verification

		$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);
		//Sanitizes the email, ensures email is in the correct format

		$_SESSION['log_email'] = $email;
		//stores email data of the current session in SESSION variable

		$password = md5($_POST['log_password']);
		//Encrypts the password before sending it to database for verification

		/*-----------------------------------------------------*/
		//Email and Password Verification

		$check_database_query =  mysqli_query($con_mysqli, "SELECT * FROM Users WHERE u_email='$email' AND u_password='$password'");
		//checks the users table for a record with matching email and password

		$check_login_query = mysqli_num_rows($check_database_query); 
		//retrieves number of matching rows for the above email and pasword


		if($check_login_query==1) 
		//email and password matched a row in database (there can be only one)
		{
			$row = mysqli_fetch_array($check_database_query);
			//store search results above in an array and assign to $row

			$username = $row['u_username'];
			//assign the value in 'username' column to $username

			$user_closed_query = mysqli_query($con_mysqli, "SELECT * FROM Users WHERE u_email='$email' AND u_acc_closed='yes'");
			//queries database to check if email entered belongs to a closed account

			if(mysqli_num_rows($user_closed_query)==1) 
			//if a the account was previously closed
			{
				$reopen_account = mysqli_query($con_mysqli,"UPDATE Users SET u_acc_closed='no' WHERE u_email='$email'");
				//reopen the account in database
			}

			/*----------------------------------*/
			//redirect user to index page

			$_SESSION['username']=$username;
			//save $username to current $_SESSION variable
			//if this value is not NULL it means user is logged in

			$_SESSION['acctype']='uc';
			//save account type to current $_SESSION variable
			//provides information about the account type

			$logger->write("SUCCESS > user logged in: ".$username);
			//send activity log

			header("Location: index_uc.php");
			//redirect the user to index_uc.php (go to dashboard)

			exit();
			//shuts down execution of the current script
		}
		else
		{
			array_push($error_array, "Email or Password was Incorrect.<br>");
			//uses error_array from register handler / pushes message into array
			//checked by register.php

			$logger->write("FAILURE > invalid email or password");
			//send activity log
		}

		$logger->close();
		//close logger
	}

/*-------------------------login_handler(Representative)-------------------------*/
 

	if(isset($_POST['login_rep_button']))
	//if login_button variable is not NULL
	{
		$logger->write("STARTED > login_handler.php for acctype: REP");
		//send activity log

		/*-----------------------------------------------------*/
		//Prepare Email and Password for verification

		$username = strtolower($_POST['log_username']); 
		//Sanitizes the username, ensures username is in the correct format

		$_SESSION['username'] = $username;
		//stores username data of the current session in SESSION variable

		$password = md5($_POST['log_password']);
		//Encrypts the password before sending it to database for verification

		//$password = $_POST['log_password']; //temp(use above)**
		//Encrypts the password before sending it to database for verification

		/*-----------------------------------------------------*/
		//Email and Password Verification

		$check_database_query =  mysqli_query($con_mysqli, "SELECT * FROM Users WHERE u_username='$username' AND u_password='$password'");
		//checks the users table for a record with matching email and password

		$check_login_query = mysqli_num_rows($check_database_query);
		//retrieves number of matching rows for the above email and pasword


		if($check_login_query==1) 
		//email and password matched a row in database (there can be only one)
		{
			$row = mysqli_fetch_array($check_database_query);
			//store search results above in an array and assign to $row

			$username = $row['u_username'];
			//assign the value in 'username' column to $username

			$user_closed_query = mysqli_query($con_mysqli, "SELECT * FROM Users WHERE u_username='$username' AND u_acc_closed='yes'");
			//queries database to check if email entered belongs to a closed account

			if(mysqli_num_rows($user_closed_query)==1) 
			//if a the account was previously closed
			{
				$reopen_account = mysqli_query($con_mysqli,"UPDATE Users SET u_acc_closed='no' WHERE u_username='$username'");
				//reopen the account in database
			}


			/*----------------------------------*/
			//redirect user to index page

			$_SESSION['username']=$username;
			//save $username to current $_SESSION variable
			//if this value is not NULL it means user is logged in

			$_SESSION['acctype']='rep';
			//save account type to current $_SESSION variable
			//provides information about the account type

			$logger->write("SUCCESS > user logged in: ".$username);
			//send activity log

			/*----------------------------------*/
			//check user status

			$user_rep = new User ($con_mysqli,$username);
		
			header("Location: upload.php");
			//redirect the user to index_uc.php (go to dashboard)

			exit();
			//shuts down execution of the current script
		}
		else
		{
			array_push($error_array, "Username or Password was Incorrect.<br>");
			//uses error_array from register handler / pushes message into array
			//checked by register.php

			$logger->write("FAILURE > invalid email or password");
			//send activity log
		}

		$logger->close();
		//close logger
	}
?>
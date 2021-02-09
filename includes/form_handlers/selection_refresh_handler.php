<?php

	if (isset($_POST['refresh_sel_button'])) 
	//if refresh selection button is clicked
	{
		/*-----------------------------*/
		//* Get presentation date

		$present_date = date('Y-m-d', strtotime($_POST['present_date']));
		//fetch presentation dat
		$_SESSION['present_date']=$present_date;
		//stores unit code into session variable

		/*-----------------------------*/
		//* Get presentation time

		$present_time = date("H:i:s", strtotime($_POST['present_time']));
		//fetch presentation time
		$_SESSION['present_time']=$present_time;
		//stores present time into session variable

		/*-----------------------------*/
		//* Get Unit Teaching Period

		$present_room = strip_tags($_POST['present_room']);
		//remove any html tags and store form values in the l.h. value
		$_SESSION['present_room']=$present_room;
		//stores present room into session variable

		/*-----------------------------*/
		//* Get Rep First Name

		$repfname = strip_tags($_POST['rep_fname']);
		//remove any html tags and store form values in the l.h. value
		$repfname = str_replace (' ','',$repfname);
		//removes any spaces in l.h. value
		$repfname = ucfirst(strtolower($repfname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['rep_fname']=$repfname;
		//stores present room into session variable


		/*-----------------------------*/
		//* Get Rep Last Name

		$replname = strip_tags($_POST['rep_lname']);
		//remove any html tags and store form values in the l.h. value
		$replname = str_replace (' ','',$replname);
		//removes any spaces in l.h. value
		$replname = ucfirst(strtolower($replname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['rep_lname']=$replname;
		//stores present room into session variable

		$rep_fullname = $repfname." ".$replname;
		//create a full name string

		/*-----------------------------*/
		//* Get Email

		$rep_email = strip_tags($_POST['rep_email']);
		//remove any html tags and store form values in the l.h. value
		$rep_email = str_replace (' ','',$rep_email);
		//removes any spaces in l.h. value
		$rep_email = strtolower($rep_email);
		//capitalizes first letter / makes other letters lower case
		$_SESSION['rep_email']=$rep_email;
		//stores present room into session variable

	}
?>
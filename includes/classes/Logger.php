
<!--Logger.php-->

<?php

/*----------'Logger' Class Definition----------*/
class Logger
{
	private $file;

	public function __construct()
	{
		$this->file = fopen(dirname(__DIR__)."\log\log.txt", "a") or die("Unable to open file!"); 
		//opens log.txt
	}

	public function write($message)
	{
		date_default_timezone_set("Asia/Singapore");
		//sets the time zone
		$timestamp=time();
		//generates a time string
		$txt=date("F d, Y h:i:s A", $timestamp)."    ".$message."\n";
		//construct an activity log with timestamp
		fwrite($this->file, $txt);
		//write to log file
	}

	public function close()
	{
		fclose($this->file);
		//close log file
	}

}

/*----------'Logger' Class Definition----------*/

?>
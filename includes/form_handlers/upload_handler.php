
<?php

//Bella (start)


class VideoProcessor {

    private $con;
    private $sizeLimit = 1000000000;
	private $status = "pending";//pending status 
    private $allowedTypes = array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg");
	private $error_array = array();	//array contains errors

    // *** ALSO UNCOMMENT ONE OF THESE DEPENDING ON YOUR COMPUTER ***
    //private $ffprobePath = "ffmpeg/mac/regular-xampp/ffprobe"; // *** MAC (USING REGULAR XAMPP) ***
    //private $ffprobePath = "ffmpeg/mac/xampp-VM/ffprobe"; // *** MAC (USING XAMPP VM) ***
    // private $ffprobePath = "ffmpeg/linux/ffprobe"; // *** LINUX ***
    private $ffmpegPath;//to convert other type of videos into mp4 video 

    public function __construct($con) {
        $this->con = $con;
		$this->ffmpegPath = realpath($_SERVER['DOCUMENT_ROOT'].'/present_app/ffmpeg/windows/bin/ffmpeg.exe');//the path of ffmpeg.exe for window 
    }

      public function upload($videoUploadData,$logger) 
	  {
		
        $logger->write("STARTED > upload_handler.php for PresentationID: ".$videoUploadData->presentationID);		
		
        $targetDir = "assets/uploads/videos/"; //directory to store videos 
        $videoData = $videoUploadData->videoDataArray;//to get the values from videoUploadData

        $tempFilePath = $_SERVER['DOCUMENT_ROOT'].'/present_app/'. $targetDir . uniqid() . basename($videoData["name"]); // to give a temporary file path for the video which is soon to be delected
        $tempFilePath = str_replace(" ", "_", $tempFilePath);//to remove white spaces 

        $isValidData = $this->processData($videoUploadData, $logger);         
				
        if(!$isValidData) //if video isn't valid the execution will be terminated 
		{
			array_push($this->error_array, "Presentation cannot be uploaded.<br>");
			$logger->write("FAILURE > The title ".$videoUploadData->title." won't be inserted");
			$logger->write("FAILURE > The description " .$videoUploadData->description." won't be inserted ");
			$logger->write("FAILURE > The speaker note " .$videoUploadData->speakerNote." won't be inserted ");
            return false;
        }
       
	    $isVideoExist = $this->existVideo($videoData);
	    $isVideoPathExist = $this->existVideoP($videoUploadData);
		
       if($isVideoExist == true && $isVideoPathExist == true || $isVideoExist == true && $isVideoPathExist == false )
	   {
		   
        $isValidVideo = $this->processVideo($videoData, $tempFilePath, $logger);//to validate video 

        if(!$isValidVideo) //if video isn't valid the execution will be terminated 
		{
			array_push($this->error_array, "Presentation cannot be uploaded.<br>");
			$logger->write("FAILURE > The file: ".$videoData["name"]." is a invalid type");
			$logger->write("FAILURE > The file Type: " .$videoData["type"]);
            return false;
        }

        if(move_uploaded_file($videoData["tmp_name"], $tempFilePath))//to move the video into it's final file path  
		{			
            $finalFilePath = $targetDir . uniqid() . ".mp4";//to give permanent file path to the video 

            if(!$this->insertVideoData($videoUploadData, $finalFilePath, $logger)) //to insert the information of video into tables in the database
			{ 
			    array_push($this->error_array, "Presentation cannot be uploaded.<br>");
				$logger->write("FAILURE > Update query failed ");
                return false;
            }
			else 
			{
				$logger->write("SUCCESS > Update query completed ");
			}

            if(!$this->convertVideoToMp4($tempFilePath, $finalFilePath,$logger)) { //to convert the other types of video into mp4
                
				$logger->write("FAILURE > The video could not be converted to mp4");
                return false;
            }
			else 
			{
				$logger->write("SUCCESS > The video is successfully converted to mp4");
			}

            if(!$this->deleteFile($tempFilePath,$logger)) { //to delete the file on the temporary file path 
                
				$logger->write("FAILURE > Delete temporary File path failed");
                return false;
            }
			else
			{
				$logger->write("SUCCESS > Delete temporary File path success ");
			}

            return true; 

        }
		
		return true;
	
	   }
	   else if($isVideoExist == false && $isVideoPathExist == true)
	   {
            if(!$this->updateVideoData($videoUploadData,$logger)) //to insert the information of video into tables in the database
			{ 
			    array_push($this->error_array, "Presentation cannot be updated.<br>");
				$logger->write("FAILURE > Update query failed ");
                return false;
            }
			else
			{
			  array_push($this->error_array, "Presentation is updated.<br>");
			  return true;
			}
	   }
	   else 
	   {
			array_push($this->error_array, "Video must be provided.<br>");
			$logger->write("FAILURE > The video doesn't exist");
            return false;		   
	   }
	  
    }

    private function existVideoP($uploadData)
	{
		$query = $this->con->prepare("SELECT p_video FROM presentations WHERE p_id=:presentationID");//fetch the location of video from the presentation 
	    $query->bindParam(":presentationID",$uploadData->presentationID);//to bind id with placeholder of id in query
        $query->execute();//execute the query 
        $file = $query->fetch(PDO::FETCH_ASSOC);//to get data from the query 	
				
        if($file['p_video']!= $this->status)//to check the status if the video location is not pending 
		{
			return true;
		}
   		else 
		{
			return false;
		}
	}

    private function existVideo($videoData)
	{
         if($videoData["name"]!= null)
         {
			 return true;
		 }
         else 
         {
			return false;
		 }			 
	}

    private function processVideo($videoData, $filePath ,$logger) { //to validate video 
        
		$videoType = pathInfo($filePath, PATHINFO_EXTENSION);
        
        if(!$this->isValidSize($videoData)) { //to validate the size of the video 
		
		    array_push($this->error_array, "The video's size is invalid.<br>");
			$logger->write("FAILURE > File too large. Can't be more than " . $this->sizeLimit . " bytes");
            return false;
        }
        else if(!$this->isValidType($videoType)) {//to validate the type of the video 
            
			array_push($this->error_array, "The video's type is invalid.<br>");
			$logger->write("FAILURE > Invalid file type");
            return false;
        }
        else if($this->hasError($videoData)) { //to check whether the video contains any types of 

            array_push($this->error_array, $videoData["error"]);
			$logger->write("FAILURE > Error code: " . $videoData["error"]);
            return false;
        }

        return true;
    }

    private function processData($Data, $logger)
	{
		$title = $Data->title;
		$description = $Data->description;
		$speaker_note = $Data->speakerNote;
		
		if(!$this->isValidTittle($title))
		{
			array_push($this->error_array, "Title must be between 2-100 characters.<br>");
			$logger->write("FAILURE > Tittle is invalid. Can't be more than 100 characters and less than 2 characters ");
			return false;
		}
		
		if(!$this->isValidDescription($description))
		{
			array_push($this->error_array, "Description must be between 2-100 characters.<br>");
			$logger->write("FAILURE > Description is invalid. Can't be more than 1000 and less than 2 characters");
		    return false;
		}
		
		if(!$this->isValidSpeakerNote($speaker_note))
		{
			array_push($this->error_array, "Speaker note must be between 2-100 characters.<br>");
			$logger->write("FAILURE > Speaker_note is invalid. Can't be more than 1000 and less than 2 characters");
		    return false;
		}		
		
		return true;
		
	}

    private function isValidSpeakerNote($speaker_note)
	{

		$length = strlen($speaker_note);
		
		if($length >= 2 && $length <= 100)
		{
			return true;
		}
		else 
		{
			return false;
		}
		
	}
	
	private function isValidTittle($title)
	{
		$length = strlen($title);
		
		if($length >= 2 && $length <= 100)
		{
			return true;
		}
		else 
		{
			return false;
		}
		
	}

	private function isValidDescription($description)
	{
		$length = strlen($description);
		
		if($length >= 2 && $length <= 1000)
		{
			return true;
		}
		else 
		{
			return false;
		}
		
	}

    private function isValidSize($data) { //to validate the size //the size of video can not be more than sizeLimit(max size)
        return $data["size"] <= $this->sizeLimit;
    }

    private function isValidType($type) {//to validate the type of video //video must be one of the video in allowedTypes array 
        $lowercased = strtolower($type); //to lowercase the type of video 
        return in_array($lowercased, $this->allowedTypes);
    }
    
    private function hasError($data) { //to check the error 
        return $data["error"] != 0;
    }

    private function insertVideoData($uploadData, $filePath , $logger) {
        
		$query = $this->con->prepare("SELECT p_video FROM presentations WHERE p_id=:presentationID");//fetch the location of video from the presentation 
	    $query->bindParam(":presentationID",$uploadData->presentationID);//to bind id with placeholder of id in query
        $query->execute();//execute the query 
        $file = $query->fetch(PDO::FETCH_ASSOC);//to get data from the query 	
				
        if($file['p_video']!= $this->status)//to check the status if the video location is not pending 
		{			
			$oldfilePath = $_SERVER['DOCUMENT_ROOT'].'/present_app/'.$file['p_video'];//delete the old video from the folder (assets/uploads/videos/)
			if(!$this->deleteFile($oldfilePath,$logger)) {//if video can not be deleted
				$logger->write("FAILURE > Old video can not be deleted");//write it in the log 
		    }
			else //if vide is deleted 
			{
				$logger->write("FAILURE > Old video is deleted");//write it in the log
			}
		}

		$publish = "yes";
		$todayDate = date("Y-m-d");
		$presentationID = $uploadData->presentationID;
		
        $query = $this->con->prepare("UPDATE presentations SET p_title=:title, p_details=:description , p_video=:filePath , p_published = :publish , p_date_published= :todayDate , p_speaker_info=:speaker_note WHERE p_id=:presentationID"); //to upload values into the database
        $query->bindParam(":title",$uploadData->title);//to bind id with placeholder of id in query 
		$query->bindParam(":description",$uploadData->description);//to bind id with placeholder of id in query 
		$query->bindParam(":filePath",$filePath);//to bind id with placeholder of id in query 
		$query->bindParam(":publish",$publish);//to bind id with placeholder of id in query 
		$query->bindParam(":todayDate",$todayDate);//to bind id with placeholder of id in query 
		$query->bindParam(":presentationID",$uploadData->presentationID);//to bind id with placeholder of id in query 
        $query->bindParam(":speaker_note",$uploadData->speakerNote);//to bind id with placeholder of id in query 
		
        array_push($this->error_array, "Presentation is published.<br>");

        return $query->execute();
    }

    private function updateVideoData($uploadData,$logger)
	{
		$publish = "yes";
		$todayDate = date("Y-m-d");
		$presentationID = $uploadData->presentationID;
		
        $query = $this->con->prepare("UPDATE presentations SET p_title=:title, p_details=:description ,p_published = :publish , p_date_published= :todayDate , p_speaker_info=:speaker_note WHERE p_id=:presentationID"); //to upload values into the database
        $query->bindParam(":title",$uploadData->title);//to bind id with placeholder of id in query 
		$query->bindParam(":description",$uploadData->description);//to bind id with placeholder of id in query 
		$query->bindParam(":publish",$publish);//to bind id with placeholder of id in query 
		$query->bindParam(":todayDate",$todayDate);//to bind id with placeholder of id in query 
		$query->bindParam(":presentationID",$uploadData->presentationID);//to bind id with placeholder of id in query 
        $query->bindParam(":speaker_note",$uploadData->speakerNote);//to bind id with placeholder of id in query 

        return $query->execute();		
	}

    public function convertVideoToMp4($tempFilePath, $finalFilePath , $logger) { //to convert the other types of video into mp4 
	
	    $finalFilePath = $_SERVER['DOCUMENT_ROOT'].'/present_app/'. $finalFilePath;
	  
        $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";//to convert the type of video using ffmpeg //$tempFilePath is file path of original path and $finalFilePath is the new converted video's file path 

        $outputLog = array(); //to get error code if it's existed
        exec($cmd, $outputLog, $returnCode);//$returnCode - the number of error 
        
        if($returnCode != 0) { 
            //Command failed
            foreach($outputLog as $line) {
				
				$logger->write("FAILURE > ".$line);
            }
            return false;
        }

        return true;
    }

    private function deleteFile($filePath,$logger) //to delete the temporary file path 
	{		
        if(!unlink($filePath)) { //the code for deleting the temporary file path 
 
			$logger->write("FAILURE > Could not delete file");
            return false;
        }

        return true;
    }
	
	public function getErrorArray()
	{
		return $this->error_array;
	}

}

//Bella (end)
?>
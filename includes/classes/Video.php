<?php 

//Modification by Bella
//added $speakerNote

class VideoData
{
	public $videoDataArray , $title, $description, $presentationID,$speakerNote;//variables //Bella (Part - 1)
		
	public function __construct($videoDataArray , $title, $description, $presentationID,$speakerNote)//constructor //Bella (Part -2)
	{
	    $this->videoDataArray = $videoDataArray;
		$this->title = $title;
		$this->description = $description;
		$this->presentationID = $presentationID;
		$this->speakerNote = $speakerNote;//Bella (Part - 3)
	}
}


?>
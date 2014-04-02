<?php
	
	//set Slide settings
	$arrTransitions = $operations->getArrTransition();
	
	$arrSlideNames = $slider->getArrSlideNames();
	
	$slideSettings = new UniteSettingsAdvancedRev();

	//title
	$params = array("description"=>"The title of the slide, will be shown in the slides list.","class"=>"medium");
	$slideSettings->addTextBox("title","Slide","Slide Title", $params);

	//state
	$params = array("description"=>"The state of the slide. The unpublished slide will be excluded from the slider.");
	$slideSettings->addSelect("state",array("published"=>"Published","unpublished"=>"Unpublished"),"State","published",$params);
		
	//transition
	$params = array("description"=>"The appearance transition of this slide.");
	$slideSettings->addSelect("slide_transition",$arrTransitions,"Transition","random",$params);
		
	//slot amount
	$params = array("description"=>"The number of slots or boxes the slide is divided into. If you use boxfade, over 7 slots can be juggy."
		,"class"=>"small"
	);	
	$slideSettings->addTextBox("slot_amount","7","Slot Amount", $params);
	
	//rotation:
	$params = array("description"=>"Rotation (-720 -> 720, 999 = random) Only for Simple Transitions."
		,"class"=>"small"
	);
	$slideSettings->addTextBox("transition_rotation","0","Rotation", $params);
	
	//transition speed
	$params = array("description"=>"The duration of the transition (Default:300, min: 100 max 2000). "
		,"class"=>"small"
	);
	$slideSettings->addTextBox("transition_duration","300","Transition Duration", $params);		
	
	//delay	
	$params = array("description"=>"A new delay value for the Slide. If no delay defined per slide, the delay defined via Options ( $sliderDelay ms) will be used"
		,"class"=>"small"
	);
	$slideSettings->addTextBox("delay","","Delay", $params);
	
	//-----------------------
	
	//enable link
	$slideSettings->addSelect_boolean("enable_link", "Enable Link", false, "Enable","Disable");
	
	$slideSettings->startBulkControl("enable_link", UniteSettingsRev::CONTROL_TYPE_SHOW, "true");
	
		//link type
		$slideSettings->addRadio("link_type", array("regular"=>"Regular","slide"=>"To Slide"), "Link Type","regular");
		
		//link	
		$params = array("description"=>"A link on the whole slide pic");
		$slideSettings->addTextBox("link","","Slide Link", $params);
		
		//link target
		$params = array("description"=>"The target of the slide link");
		$slideSettings->addSelect("link_open_in",array("same"=>"Same Window","new"=>"New Window"),"Link Open In","same",$params);
		
		//num_slide_link
		$arrSlideLink = array("nothing"=>"-- Not Chosen --","next"=>"-- Next Slide --","prev"=>"-- Previous Slide --");		
		foreach($arrSlideNames as $slideNameID=>$slideName)
			$arrSlideLink[$slideNameID] = $slideName;
		
		$slideSettings->addSelect("slide_link", $arrSlideLink, "Link To Slide","nothing");
		
		$params = array("description"=>"The position of the link related to layers");
		$slideSettings->addRadio("link_pos", array("front"=>"Front","back"=>"Back"), "Link Position","front",$params);
		
		$slideSettings->addHr("link_sap");
		
	$slideSettings->endBulkControl();
		
		$slideSettings->addControl("link_type", "slide_link", UniteSettingsRev::CONTROL_TYPE_ENABLE, "slide");
		$slideSettings->addControl("link_type", "link", UniteSettingsRev::CONTROL_TYPE_DISABLE, "slide");
		$slideSettings->addControl("link_type", "link_open_in", UniteSettingsRev::CONTROL_TYPE_DISABLE, "slide");
		
	//-----------------------
	
	//enable video
	$params = array("description"=>"Put a full width video on the slide");
	$slideSettings->addSelect_boolean("enable_video", "Enable Full Width Video", false, "Enable","Disable");
	
	//video id	
	$params = array("description"=>"The field can take Youtube ID (example: QohUdrgbD2k) or Vidmeo ID (example: 30300114)",
					"class"=>"medium");
	$slideSettings->addTextBox("video_id","","Video ID", $params);

	//video autoplay
	$params = array("description"=>"Enable video autoplay on enter slide",
					"class"=>"medium");
	$slideSettings->addCheckbox("video_autoplay", false,"Video Autoplay");
		
	$slideSettings->addControl("enable_video", "video_id", UniteSettingsRev::CONTROL_TYPE_SHOW, "true");
	$slideSettings->addControl("enable_video", "video_autoplay", UniteSettingsRev::CONTROL_TYPE_SHOW, "true");
	
	$params = array("description"=>"Slide Thumbnail. If not set - it will be taken from the slide image.");
	$slideSettings->addImage("slide_thumb", "","Thumbnail" , $params);

	$params = array("description"=>"Apply to full width mode only. Centering vertically slide images.");
	$slideSettings->addCheckbox("fullwidth_centering", false, "Full Width Centering", $params);
	
	//add background type (hidden)
	$slideSettings->addTextBox("background_type","image","Background Type", array("hidden"=>true));
	
	//store settings
	self::storeSettings("slide_settings",$slideSettings);

?>

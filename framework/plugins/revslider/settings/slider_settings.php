<?php
	
	//set "slider_main" settings
	$sliderMainSettings = new UniteSettingsAdvancedRev();
	$sliderMainSettings->addTextBox("title", "","Slider Title",array("description"=>"The title of the slider. Example: Slider1","required"=>"true"));	
	$sliderMainSettings->addTextBox("alias", "","Slider Alias",array("description"=>"The alias that will be used for embedding the slider. Example: slider1","required"=>"true"));
	$sliderMainSettings->addTextBox("shortcode", "","Slider Short Code",array("readonly"=>true,"class"=>"code"));
	$sliderMainSettings->addHr();
	
	$sliderMainSettings->addRadio("slider_type", array("fixed"=>"Fixed","responsitive"=>"Responsive","fullwidth"=>"Full Width"),"Slider Type","fixed");
	
	$paramsSize = array("width"=>960,"height"=>350);	
	$sliderMainSettings->addCustom("slider_size", "slider_size","","Slider Size",$paramsSize);
	
	$paramsResponsitive = array("w1"=>940,"sw1"=>770,"w2"=>780,"sw2"=>500,"w3"=>510,"sw3"=>310);
	$sliderMainSettings->addCustom("responsitive_settings", "responsitive","","Responsive Sizes",$paramsResponsitive);
	$sliderMainSettings->addHr();
	
	self::storeSettings("slider_main",$sliderMainSettings);
	
	//set "slider_params" settings. 
	$sliderParamsSettings = new UniteSettingsAdvancedRev();	
	$sliderParamsSettings->loadXMLFile(self::$path_settings."/slider_settings.xml");
	
	//update transition type setting.
	$settingFirstType = $sliderParamsSettings->getSettingByName("first_transition_type");
	$operations = new RevOperations();
	$arrTransitions = $operations->getArrTransition();
	$settingFirstType["items"] = $arrTransitions;
	$sliderParamsSettings->updateArrSettingByName("first_transition_type", $settingFirstType);
		
	
	//store params
	self::storeSettings("slider_params",$sliderParamsSettings); 
	
?>
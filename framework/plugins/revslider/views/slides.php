<?php
	
	$sliderID = self::getGetVar("id");
	
	if(empty($sliderID))
		UniteFunctionsRev::throwError("Slider ID not found"); 
	
	$slider = new RevSlider();
	$slider->initByID($sliderID);
	$sliderParams = $slider->getParams();
	
	//set iframe parameters	
	$width = $sliderParams["width"];
	$height = $sliderParams["height"];
	
	$iframeWidth = $width+60;
	$iframeHeight = $height+50;
	
	$iframeStyle = "width:{$iframeWidth}px;height:{$iframeHeight}px;";
	
	$arrSlides = $slider->getSlides();
	$numSlides = count($arrSlides);
	
	$linksSliderSettings = self::getViewUrl(RevSliderAdmin::VIEW_SLIDER,"id=$sliderID");
	
	require self::getPathTemplate("slides");
	
?>
	
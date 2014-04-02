<?php

	class RevSliderFront extends UniteBaseFrontClassRev{
		
		/**
		 * 
		 * the constructor
		 */
		public function __construct($mainFilepath){
			
			parent::__construct($mainFilepath,$this);
			
			//set table names
			GlobalsRevSlider::$table_sliders = self::$table_prefix.GlobalsRevSlider::TABLE_SLIDERS_NAME;
			GlobalsRevSlider::$table_slides = self::$table_prefix.GlobalsRevSlider::TABLE_SLIDES_NAME;
		}
		
		
		/**
		 * 
		 * a must function. you can not use it, but the function must stay there!.
		 */		
		public static function onAddScripts(){
			
			self::addStyle("settings","rs-settings","rs-plugin/css");
			self::addStyle("captions","rs-captions","rs-plugin/css");

			$url_jquery = "http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js";
			self::addScriptAbsoluteUrl($url_jquery, "jquery");
			
			self::addScript("jquery.themepunch.plugins.min","rs-plugin/js","themepunch.plugins");
			self::addScript("jquery.themepunch.revolution.min","rs-plugin/js");
		}
		
	}
	

?>
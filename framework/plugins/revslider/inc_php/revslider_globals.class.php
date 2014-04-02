<?php

	class GlobalsRevSlider{
		
		const SHOW_SLIDER_TO = "admin";		//options: admin, editor, author
		
		const TABLE_SLIDERS_NAME = "revslider_sliders";
		const TABLE_SLIDES_NAME = "revslider_slides";
		const FIELDS_SLIDE = "slider_id,slide_order,params,layers";
		const FIELDS_SLIDER = "title,alias,params";
		
		public static $table_sliders;
		public static $table_slides;						
		public static $filepath_captions;
		public static $filepath_captions_original;
		public static $urlCaptionsCSS;
		public static $isNewVersion;
	}

?>
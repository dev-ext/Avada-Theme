<?php
 
class RevSlider_Widget extends WP_Widget {
	
    public function __construct(){
    	
        // widget actual processes
     	$widget_ops = array('classname' => 'widget_revslider', 'description' => __('Displays a revolution slider on the page') );
        parent::__construct('rev-slider-widget', __('Revolution Slider'), $widget_ops);
    }
 
    /**
     * 
     * the form
     */
    public function form($instance) {
	
		$slider = new RevSlider();
    	$arrSliders = $slider->getArrSlidersShort();
    	    	
		if(empty($arrSliders))
			echo __("No sliders found, Please create a slider");
		else{
			
			$field = "rev_slider";
			$fieldPages = "rev_slider_pages";
			$fieldCheck = "rev_slider_homepage";
			
	    	$sliderID = UniteFunctionsRev::getVal($instance, $field);
	    	$homepage = UniteFunctionsRev::getVal($instance, $fieldCheck);
	    	$pagesValue = UniteFunctionsRev::getVal($instance, $fieldPages);
			
			$fieldID = $this->get_field_id( $field );
			$fieldName = $this->get_field_name( $field );
			
			$select = UniteFunctionsRev::getHTMLSelect($arrSliders,$sliderID,'name="'.$fieldName.'" id="'.$fieldID.'"',true);
			
			$fieldID_check = $this->get_field_id( $fieldCheck );
			$fieldName_check = $this->get_field_name( $fieldCheck );
			$checked = "";
			if($homepage == "on")
				$checked = "checked='checked'";

			$fieldPages_ID = $this->get_field_id( $fieldPages );
			$fieldPages_Name = $this->get_field_name( $fieldPages );
			
		?>
			Choose Slider: <?php echo $select?>
			<div style="padding-top:10px;"></div>
			
			<label for="<?php echo $fieldID_check?>">Home Page Only:</label>
			<input type="checkbox" name="<?php echo $fieldName_check?>" id="<?php echo $fieldID_check?>" <?php echo $checked?> >
			<br><br>
			<label for="<?php echo $fieldPages_ID?>">Pages: (example: 2,10) </label>
			<input type="text" name="<?php echo $fieldPages_Name?>" id="<?php echo $fieldPages_ID?>" value="<?php echo $pagesValue?>">
			
			<div style="padding-top:10px;"></div>
		<?php
		}	//else
		 
    }
 
    /**
     * 
     * update
     */
    public function update($new_instance, $old_instance) {
    	
        return($new_instance);
    }

    
    /**
     * 
     * widget output
     */
    public function widget($args, $instance) {
    	
		$sliderID = UniteFunctionsRev::getVal($instance, "rev_slider");
				
		$homepageCheck = UniteFunctionsRev::getVal($instance, "rev_slider_homepage");
		$homepage = "";
		if($homepageCheck == "on")
			$homepage = "homepage";
		
		$pages = UniteFunctionsRev::getVal($instance, "rev_slider_pages");
		if(!empty($pages)){
			if(!empty($homepage))
				$homepage .= ",";
			$homepage .= $pages;
		}
				
		if(empty($sliderID))
			return(false);
						
		RevSliderOutput::putSlider($sliderID,$homepage);
    }
 
}


?>
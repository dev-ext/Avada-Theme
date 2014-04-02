	<div class="wrap settings_wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2>Edit Slides: <?php echo $slider->getTitle()?></h2>
		
		<div class="vert_sap"></div>
		<?php if($numSlides >= 5):?>
		<a class='button-primary' id="button_new_slide_top" href='javascript:void(0)' >New Slide</a>
		<?php endif?>
		
		<div class="vert_sap"></div>
		<div class="sliders_list_container">
			<?php require self::getPathTemplate("slides_list");?>
		</div>
		<div class="vert_sap_medium"></div>
		<a class='button-primary' id="button_new_slide" href='javascript:void(0)' >New Slide</a>
		<span class="hor_sap"></span>
		<a class="button_close_slide button-primary" href='<?php echo self::getViewUrl(RevSliderAdmin::VIEW_SLIDERS);?>' >Close</a>
		<span class="hor_sap"></span>
		
		<a href="<?php echo $linksSliderSettings?>" id="link_slider_settings">To Slider Settings</a>
		
	</div>

	<?php require self::getPathTemplate("dialog_preview_slide");?>
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
			
			RevSliderAdmin.initSlidesListView("<?php echo $sliderID?>");
			
		});
		
	</script>

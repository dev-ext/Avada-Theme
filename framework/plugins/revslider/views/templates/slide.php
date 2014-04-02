	
	<!--  load good font -->
	<?php
		
		if($loadGoogleFont == "true"){
			$googleFont = $slider->getParam("google_font","");
			if(!empty($googleFont))
				echo "<link href='http://fonts.googleapis.com/css?family=$googleFont' rel='stylesheet' id='google-font-css' type='text/css' media='all' />"; 
		}
		
	?>
	
	<div class="wrap settings_wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2>
			Edit Slide <?php echo $slideOrder?>, title: <?php echo $slideTitle?> 
			<a id="link_hide_options" class="link_setting_title" href="javascript:void(0)">Hide Slide Options</a>
		</h2>
		
		<div id="slide_params_holder">
			<form name="form_slide_params" id="form_slide_params">		
			<?php
				$settingsSlideOutput->draw("form_slide_params",false);
			?>
				<input type="hidden" id="image_url" name="image_url" value="<?php echo $imageUrl?>" />
			</form>
		</div>
		
		<div class="vert_sap"></div>
		<h3>Slide Image and Layers:</h3>
		<div class="vert_sap"></div>
		
		<div id="jqueryui_error_message" class="unite_error_message" style="display:none;">
				<b>Warning!!! </b>The jquery ui javascript include that is loaded by some of the plugins are custom made and not contain needed components like 'autocomplete' or 'draggable' function.
				Without those functions the editor may not work correctly. Please remove those custom jquery ui includes in order the editor will work correctly.
		 </div>
		
		<?php require self::getPathTemplate("edit_layers");?>
		<div class="slide_update_button_wrapper">
			<a href="javascript:void(0)" id="button_save_slide" class="orangebutton">Update Slide</a>
			<div id="loader_update" class="loader_round" style="display:none;">updating...</div>
			<div id="update_slide_success" class="success_message" class="display:none;"></div>
		</div>
		<a id="button_close_slide" href="<?php echo $closeUrl?>" class="button-primary">Close</a>
		
	</div>
	
	<div class="vert_sap"></div>
		
	<?php require self::getPathTemplate("dialog_preview_slide");?>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			RevSliderAdmin.initEditSlideView(<?php echo $slideID?>);
		});
	</script>
	
	

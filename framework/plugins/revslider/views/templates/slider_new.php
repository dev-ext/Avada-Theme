

	<div class="wrap settings_wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2>New Slider</h2>
					
			<div class="settings_panel">
			
				<div class="settings_panel_left">
				
					<?php $settingsSliderMain->draw("form_slider_main",true)?>
					
					<div class="vert_sap_medium"></div>
					
					<a id="button_save_slider" class='button-primary' href='javascript:void(0)' >Create Slider</a>
					
					<span class="hor_sap"></span>
					
					<a id="button_cancel_save_slider" class='button-primary' href='<?php echo self::getViewUrl("sliders") ?>' >Close</a>
					
				</div>
				<div class="settings_panel_right">
					<?php $settingsSliderParams->draw("form_slider_params",true); ?>
				</div>
				
				<div class="clear"></div>				
			</div>
			
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			
			RevSliderAdmin.initAddSliderView();
		});
	</script>
	

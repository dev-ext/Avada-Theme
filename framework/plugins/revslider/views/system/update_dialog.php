
<div id="dialog_update_plugin" class="api_wrapper" title="Update Slider Plugin" style="display:none;">
		<div class="api-caption">Update Revolution Slider Plugin:</div>
		<div class="api-desc">
			To update the slider please show the slider install package. The files will be overwriten. 
			<br> File example: revslider.zip
		</div>
		
		<br>		
		
		<form action="<?php echo UniteBaseClassRev::$url_ajax?>" enctype="multipart/form-data" method="post">
		    
		    <input type="hidden" name="action" value="revslider_ajax_action">
		    <input type="hidden" name="client_action" value="update_plugin">
		    <input type="hidden" name="sliderid" value="<?php echo $sliderID?>">
		    
		    Choose the update file:   
		    <br>
			<input type="file" name="update_file" class="input_update_slider">
			
			<input type="submit" class='button-secondary' value="Update Slider">
		</form>
				
</div>
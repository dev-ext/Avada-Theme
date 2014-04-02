
	<div id="toolbox_wrapper" class="toolbox_wrapper" style="display:none;">
	
		<div class="api-caption">Export / Import slider:</div>
		<div class="api-desc">Note, that when you importing slider, it delete all the current slider settings and slides, then replace it with the new ones.</div>
		
		<br>
		
		<a id="button_export_slider" class='button-secondary' href='javascript:void(0)' >Export Slider</a>
		
		<br><br><br>
		
		<form action="<?php echo UniteBaseClassRev::$url_ajax?>" enctype="multipart/form-data" method="post">
		    
		    <input type="hidden" name="action" value="revslider_ajax_action">
		    <input type="hidden" name="client_action" value="import_slider">
		    <input type="hidden" name="sliderid" value="<?php echo $sliderID?>">
		    
		    Choose the import file:   
		    <br>
			<input type="file" name="import_file" class="input_import_slider">
			
			<input type="submit" class='button-secondary' value="Import Slider">
		</form>		
				
	</div>
	


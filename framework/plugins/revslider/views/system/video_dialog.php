

<!-- //Youtube dialog: -->
<div id="dialog_video" class="dialog-video" title="Add Youtube Layout" style="display:none">
	
	<div class="video_left">
		
		<!-- Type chooser -->
		
		<div class="video-type-chooser">
			<div class="choose-video-type">
				Choose video type:
			</div>
			
			<label for="video_radio_youtube">Youtube</label>
			<input type="radio" checked id="video_radio_youtube" name="video_select">
			
			<label for="video_radio_vimeo">Vimeo</label>
			<input type="radio" id="video_radio_vimeo" name="video_select">
			
		</div>
		
		<!-- Vimeo block -->
		
		<div id="video_block_vimeo" class="video-select-block" style="display:none;" >
			<div class="video-title" >
				Enter Vimeo ID:
			</div>
			
			<input type="text" id="vimeo_id" value=""></input>
			&nbsp;
			<input type="button" id="button_vimeo_search" class="button-regular" value="search">
			
			<img id="vimeo_loader" src="<?php echo self::$url_plugin?>/images/loader.gif" style="display:none">
			
			<div class="video_example">
				example:  30300114
			</div>
		
		</div>
		
		<!-- Youtube block -->
		
		<div id="video_block_youtube" class="video-select-block">
		
			<div class="video-title">
				Enter Youtube ID:
			</div>
			
			<input type="text" id="youtube_id" value=""></input>
			&nbsp;
			<input type="button" id="button_youtube_search" class="button-regular" value="search">
			
			<img id="youtube_loader" src="<?php echo self::$url_plugin?>/images/loader.gif" style="display:none">
			
			<div class="video_example">
				example:  QohUdrgbD2k
			</div>
			
		</div>
		
		<!-- Video controls -->
		
		<div id="video_hidden_controls" style="display:none;">
		
			<div class="youtube-inputs-wrapper">
				Width:
				<input type="text" id="input_video_width" class="video-input-small" value="320">
				
				Height:
				<input type="text" id="input_video_height" class="video-input-small" value="240">
				
			</div>
			
			<div class="add-button-wrapper">
				<a href="javascript:void(0)" class="button-primary" id="button-video-add">Add This Video</a>
			</div>
		</div>
		
	</div>
	
	<div id="video_content" class="video_right"></div>		
	
</div>

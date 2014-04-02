	
	<div class="edit_slide_wrapper">
		
		<div class="editor_buttons_wrapper">
			<div class="editor_buttons_wrapper_top">
				<input type="radio" name="radio_bgtype" data-bgtype="image" id="radio_back_image" <?php if($bgType == "image") echo 'checked="checked"'?> >
				<label for="radio_back_image">Image BG</label>
				<a href="javascript:void(0)" id="button_change_image" class="button-primary margin_right10 <?php if($bgType != "image") echo "button-disabled" ?>" style="margin-bottom:5px">Change Image</a>
				
				<span class="hor_sap"></span>
				
				<input type="radio" name="radio_bgtype" data-bgtype="trans" id="radio_back_trans" <?php if($bgType == "trans") echo 'checked="checked"'?>>
				<label for="radio_back_trans">Trapsparent BG</label>
				
				<span class="hor_sap"></span>
				
				<input type="radio" name="radio_bgtype" data-bgtype="solid" id="radio_back_solid" <?php if($bgType == "solid") echo 'checked="solid"'?>>
				<label for="radio_back_solid">Solid BG</label>
				<input type="text" name="bg_color" id="slide_bg_color" <?php echo $bgSolidPickerProps?> value="<?php echo $slideBGColor?>">
				
				<a href="javascript:void(0)" class="button-secondary" id="button_preview_slide" title="Preview Slide">Preview Slide</a>
				
			</div>
			<div class="clear"></div>
			
			<div class="editor_buttons_wrapper_bottom">
				<a href="javascript:void(0)" id="button_add_layer" class="button-secondary margin_top2">Add Layer</a>
				<a href="javascript:void(0)" id="button_add_layer_image" class="button-secondary margin_top2 margin_left10">Add Layer: Image</a>
				<a href="javascript:void(0)" id="button_add_layer_video" class="button-secondary margin_top2 margin_left10">Add Layer: Video</a>
										
				<a href="javascript:void(0)" id="button_delete_layer" class="button-secondary margin_top2 margin_left10 button-disabled">Delete Layer</a>
				<a href="javascript:void(0)" id="button_delete_all" class="button-secondary margin_top2 margin_left10 button-disabled">Delete All Layers</a>
										
				<a href="javascript:void(0)" id="button_duplicate_layer" class="button-secondary margin_top2 margin_left10 button-disabled">Duplicate Layer</a>
			</div>
			
			<div class="clear"></div>
			
		</div>
		
		<div class="clear"></div>
		
		<div id="divLayers" class="<?php echo $divLayersClass?>" style="<?php echo $style?>"></div>
		<div class="clear"></div>
		<div class="vert_sap"></div>
		
		<div id="global_timeline" class="timeline">
			<div id="timeline_handle" class="timerdot"></div>
			<div id="layer_timeline" class="layertime"></div>
			<div class="mintime">0 ms</div>
			<div class="maxtime"><?php echo $slideDelay?> ms</div>
		</div>
		
		<div class="layer_props_wrapper">
		
		<!-----  Left Layers Form ------>
		
			<div class="edit_layers_left">
		
				<form name="form_layers" id="form_layers">
					<script type='text/javascript'>
						g_settingsObj['form_layers'] = {}
					</script>
					<div class='settings_wrapper'>					
						<div class="postbox unite-postbox">
							<h3 class='no-accordion'>
								<span>Layer Params</span>
							</h3>
							<div class="inside">
								<ul class="list_settings">
									<?php
										$s = $settingsLayerOutput;
										$s->drawSettingsByNames("layer_caption,layer_text,layer_image_link,layer_link_open_in,button_edit_video,button_change_image_source"); 
									?>
									
									<!-- LAYER POSITION -->
									<!-- 
									<li class="attribute_title">
										<span class="setting_text_2 text-disabled" original-title="">Layer Position</span>
										<hr>										
									</li>
									 -->
									<?php 
								    	$s->drawSettingsByNames("layer_left,layer_top"); 
								    ?>									
									
									<!--LAYER START ANIMATION -->
									<!-- 
									<li class="attribute_title">
										<span class="setting_text_2 text-disabled" original-title="">Start Transition</span>
										<hr>										
									</li>
									 -->
									<?php 
								    	$s->drawSettingsByNames("layer_video_autoplay,layer_animation,layer_easing,layer_speed,layer_hidden,layer_slide_link"); 
								    ?>							

									<!--LAYER END ANIMATION -->									
									<li class="attribute_title">
										<span class="setting_text_2 text-disabled" original-title="">End Transition (optional)</span>
										&nbsp;&nbsp;&nbsp;
										<a id="link_show_end_params" class="link_show_end_params" href="javascript:void(0)">Show End Params</a>
										
										<hr>										
									</li>
									<?php 
								    	$s->drawSettingsByNames("layer_endtime,layer_endspeed,layer_endanimation,layer_endeasing","hidden");
								    ?>
								</ul>
								<div class="clear"></div>
							</div>
						</div>
					</div>
					
				</form>	
			</div>
			
		<!----- End Left Layers Form ------>
			
			<div class="edit_layers_right">
				<div class="postbox unite-postbox layer_sortbox">
					<h3 class="no-accordion">
						<span>Layers Sorting</span>
						<div id="button_sort_visibility" title="Hide All Layers"></div>
						<div id="button_sort_time" class="ui-state-active ui-corner-all button_sorttype"><span>By Time</span></div>
						<div id="button_sort_depth" class="ui-state-hover ui-corner-all button_sorttype"><span>By Depth<span></div>
					</h3>			
					
					<div class="inside">
						<ul id="sortlist" class='sortlist'></ul>
					</div>
				</div>
			</div>
			
			<div class="clear"></div>
			
		</div>
	</div>
	
	<div id="dialog_edit_css" class="dialog_edit_file" title="Edit captions.css file" style="display:none;">
		<p>
			<textarea id="textarea_edit" rows="20" cols="100"></textarea>
		</p>
		<div class='unite_error_message' id="dialog_error_message" style="display:none;"></div>
		<div class='unite_success_message' id="dialog_success_message" style="display:none;"></div>
	</div> 
	
	<div id="dialog_insert_button" class="dialog_insert_button" title="Insert Button" style="display:none;">
		<p>
			<ul class="list-buttons">
			<?php foreach($arrButtonClasses as $class=>$text): ?>
					<li>
						<a href="javascript:UniteLayersRev.insertButton('<?php echo $class?>','<?php echo $text?>')" class="tp-button <?php echo $class?> small"><?php echo $text?></a>
					</li>
			<?php endforeach;?> 
			</ul>
		</p>
	</div>
	
	<script type="text/javascript">
		
		jQuery(document).ready(function() {
			<?php if(!empty($jsonLayers)):?>
				//set init layers object
				UniteLayersRev.setInitLayersJson(<?php echo $jsonLayers?>);
			<?php endif?>

			<?php if(!empty($jsonCaptions)):?>
			UniteLayersRev.setInitCaptionClasses(<?php echo $jsonCaptions?>);
			<?php endif?>
			
			UniteLayersRev.setCssCaptionsUrl('<?php echo $urlCaptionsCSS?>'); 
			UniteLayersRev.init("<?php echo $slideDelay?>");
			
		});
	
	</script>

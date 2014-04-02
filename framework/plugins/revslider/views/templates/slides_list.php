
	<div class="postbox box-slideslist">
		<h3>
			<span class='slideslist-title'>Slides List</span>
			<span id="saving_indicator" class='slideslist-loading'>Saving Order...</span>
		</h3>
		<div class="inside">
			<?php if(empty($arrSlides)):?>
			No Slides Found
			<?php endif?>
			
			
			<ul id="list_slides" class="list_slides ui-sortable">
			
				<?php foreach($arrSlides as $slide):
					
					$bgType = $slide->getParam("background_type","image");
					
					$order = $slide->getOrder();
					
					$imageFilepath = $slide->getImageFilepath();									
					$imageUrl = $slide->getImageUrl();
					
					if(!empty($imageFilepath))	//show php resized image:
						$urlImageForView = self::getImageUrl($imageFilepath,200,100,true);
					else
						$urlImageForView = $imageUrl;

					$slideTitle = $slide->getParam("title","Slide");
					$title = $slideTitle;
					$filename = $slide->getImageFilename();
					
					$imageAlt = stripslashes($slideTitle);
					if(empty($imageAlt))
						$imageAlt = "slide";
					
					if($bgType == "image")
						$title .= " ({$filename})";
					
					$slideid = $slide->getID();
					
					$urlEditSlide = self::getViewUrl(RevSliderAdmin::VIEW_SLIDE,"id=$slideid");
					$linkEdit = UniteFunctionsRev::getHtmlLink($urlEditSlide, $title);
					
					$state = $slide->getParam("state","published");
					
				?>
					<li id="slidelist_item_<?php echo $slideid?>" class="ui-state-default">
					
						<span class="slide-col col-order">
							<span class="order-text"><?php echo $order?></span>
							<div class="state_loader" style="display:none;"></div>
							<?php if($state == "published"):?>
							<div class="icon_state state_published" data-slideid="<?php echo $slideid?>" title="Unpublish Slide"></div>
							<?php else:?>
							<div class="icon_state state_unpublished" data-slideid="<?php echo $slideid?>" title="Publish Slide"></div>
							<?php endif?>
							
							<div class="icon_slide_preview" title="Preview Slide" data-slideid="<?php echo $slideid?>"></div>
							
						</span>
						
						<span class="slide-col col-name">
							<?php echo $linkEdit?>
							<a class='button_edit_slide greenbutton' href='<?php echo $urlEditSlide?>'>Edit Slide</a>
						</span>
						<span class="slide-col col-image">
							<?php switch($bgType):
									default:
									case "image":
										?>
										<img id="slide_image_<?php echo $slideid?>" width="200" height="100" src="<?php echo $urlImageForView?>" class="slide_image" title="Slide Image - Click to change" alt="<?php echo $imageAlt?>">										
										<?php 
									break;
									case "solid":
										$bgColor = $slide->getParam("slide_bg_color","#d0d0d0");
										?>
										<div class="slide_color_preview" style="background-color:<?php echo $bgColor?>"></div>
										<?php 
									break;
									case "trans":
										?>
										<div class="slide_color_preview_trans"></div>
										<?php 
									break;
									endswitch;  ?>
						</span>
						
						<span class="slide-col col-operations">
							<a id="button_delete_slide_<?php echo $slideid?>" class='button-secondary button_delete_slide' href='javascript:void(0)'>Delete</a>
							<a id="button_duplicate_slide_<?php echo $slideid?>" class='button-secondary button_duplicate_slide' href='javascript:void(0)'>Duplicate</a>
						</span>
						
						<span class="slide-col col-handle">
							<div class="col-handle-inside">
								<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
							</div>
						</span>	
						<div class="clear"></div>
					</li>
				<?php endforeach;?>
			</ul>
			
		</div>
	</div>
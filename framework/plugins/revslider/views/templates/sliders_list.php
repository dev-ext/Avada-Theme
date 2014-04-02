
	<table class='wp-list-table widefat fixed unite_table_items'>
		<thead>
			<tr>
				<th width='5%'>ID</th>
				<th width='30%'>Name</th>
				<th width='10%'>N. Slides</th>						
				<th width=''>Actions</th>
				<th width='15%'>Shortcode</th>
				<th width='60'>Preview</th>						
			</tr>
		</thead>
		<tbody>
			<?php foreach($arrSliders as $slider):
				
				$id = $slider->getID();
				$showTitle = $slider->getShowTitle();
				$title = $slider->getTitle();
				$alias = $slider->getAlias();
				$shortCode = $slider->getShortcode();
				$numSlides = $slider->getNumSlides();
				
				$editLink = self::getViewUrl(RevSliderAdmin::VIEW_SLIDER,"id=$id");
				$editSlidesLink = self::getViewUrl(RevSliderAdmin::VIEW_SLIDES,"id=$id");
				
				$showTitle = UniteFunctionsRev::getHtmlLink($editLink, $showTitle);
				
			?>
				<tr>
					<td><?php echo $id?><span id="slider_title_<?php echo $id?>" class="hidden"><?php echo $title?></span></td>								
					<td><?php echo $showTitle?></td>
					<td><?php echo $numSlides?></td>
					<td>
						<a class="greenbutton newlineheight" href='<?php echo $editSlidesLink ?>'>Edit Slides</a>
						<div class="clearme"></div>						
						<a id="button_delete_<?php echo $id?>" href='javascript:void(0)' class="button-secondary button_delete_slider changemargin newlineheight">Delete</a>
						<div class="clearme"></div>
						<a id="button_duplicate_<?php echo $id?>" href='javascript:void(0)' class="button-secondary button_duplicate_slider changemargin2 newlineheight">Duplicate</a>
					</td>
					<td><?php echo $shortCode?></td>
					<td>
						<div id="button_preview_<?php echo $id?>" class="button_slider_preview" title="Preview <?php echo $title?>"></div>
					</td>
				</tr>							
			<?php endforeach;?>
			
		</tbody>		 
	</table>

	<?php require self::getPathTemplate("dialog_preview_slider");?>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			RevSliderAdmin.initSlidersListView();
		});
	</script>

	
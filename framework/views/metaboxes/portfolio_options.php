<div class='pyre_metabox'>
<?php
$this->select(	'width',
				'Width (Content Columns)',
				array('full' => 'Full Width', 'half' => 'Half Width'),
				''
			);
?>
<?php
$this->select(	'page_title',
				'Page Title',
				array('yes' => 'Show', 'no' => 'Hide'),
				''
			);
?>
<?php
$this->select(	'page_title_text',
				'Page Title Bar Text',
				array('yes' => 'Show', 'no' => 'Hide'),
				''
			);
?>
<?php
$this->textarea(	'video',
				'Video Embed Code'
			);
?>
<?php
$this->text(	'video_url',
				'Youtube/Vimeo Video URL for Lightbox',
				''
			);
?>
<?php
$this->text(	'project_url',
				'Project URL',
				''
			);
?>
<?php
$this->text(	'project_url_text',
				'Project URL Text',
				''
			);
?>
<?php
$this->text(	'copy_url',
				'Copyright URL',
				''
			);
?>
<?php
$this->text(	'copy_url_text',
				'Copyright URL Text',
				''
			);
?>
<?php
$this->text(	'fimg_width',
				'Featured Image Width',
				'(in pixels or percentage, e.g.: 100% or 100px.  Or Use "auto" for automatic resizing if you added either width or height)'
			);
?>
<?php
$this->text(	'fimg_height',
				'Featured Image Height',
				'(in pixels or percentage, e.g.: 100% or 100px.  Or Use "auto" for automatic resizing if you added either width or height)'
			);
?>
<?php
$this->select(	'image_rollover_icons',
				'Image Rollover Icons',
				array('linkzoom' => 'Link + Zoom', 'link' => 'Link', 'zoom' => 'Zoom', 'no' => 'No Icons'),
				''
			);
?>
<?php
$this->text(	'link_icon_url',
				'Link Icon URL',
				'Leave blank for post URL'
			);
?>
<?php
$this->select(	'related_posts',
				'Show Related Posts',
				array('yes' => 'Show', 'no' => 'Hide'),
				''
			);
?>
</div>
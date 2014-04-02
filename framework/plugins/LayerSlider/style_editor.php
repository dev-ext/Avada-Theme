<?php

	// Get uploads dir
	$upload_dir = wp_upload_dir();
	$upload_dir = $upload_dir['basedir'];

	// Get css file
	$file = $upload_dir . '/layerslider.custom.css';

	// Get contents
	$contents = file_exists($file) ? file_get_contents($file) : '';
?>

<div class="wrap">

	<!-- Page title -->
	<div class="ls-icon-layers"></div>
	<h2>
		<?php _e('LayerSlider Custom Styles Editor', 'LayerSlider') ?>
		<a href="?page=layerslider" class="add-new-h2"><?php _e('Back to the list', 'LayerSlider') ?></a>
	</h2>

	<?php if(isset($_GET['edited'])) : ?>
	<div class="updated"><?php _e('Your changes has been saved!', 'LayerSlider') ?></div>
	<?php  endif; ?>

	<!-- Editor box -->
	<div class="ls-box ls-skin-editor-box">
		<h3 class="header"><?php _e('Contents of your custom CSS file', 'LayerSlider') ?></h3>
		<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="inner">
			<input type="hidden" name="posted_ls_styles_editor" value="1">
			<textarea rows="10" cols="50" name="contents" id="editor"><?php echo stripslashes($contents) ?></textarea>
			<p>
				<?php if(!is_writable($upload_dir)) { ?>
				<?php _e('You need to make your uploads folder writable before you can save your changes. See the <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">Codex</a> for more information.', 'LayerSlider') ?>
				<?php } else { ?>
				<button class="button-primary"><?php _e('Save changes', 'LayerSlider') ?></button>
				<?php } ?>
			</p>
		</form>
	</div>
</div>
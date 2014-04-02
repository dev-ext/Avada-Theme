<?php

	// Get WPDB Object
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . "layerslider";

	// Get sliders
	$sliders = $wpdb->get_results( "SELECT * FROM $table_name
										WHERE flag_hidden = '0' AND flag_deleted = '0'
										ORDER BY id ASC LIMIT 200" );

	// Custom capability
	$custom_capability = get_option('layerslider_custom_capability', 'manage_options');

	// Auto-updates
	$code = get_option('layerslider-purchase-code', '');
	$validity = get_option('layerslider-validated', '0');
?>
<div class="wrap">
	<div class="ls-icon-layers"></div>
	<h2>
		<?php _e('LayerSlider sliders', 'LayerSlider') ?>
		<a href="?page=layerslider_add_new" class="add-new-h2"><?php _e('Add New', 'LayerSlider') ?></a>
		<a href="?page=layerslider&action=import_sample" class="add-new-h2"><?php _e('Import sample sliders', 'LayerSlider') ?></a>
	</h2>

	<div class="ls-box ls-slider-list">
		<table>
			<thead>
				<tr>
					<td>ID</td>
					<td><?php _e('Name', 'LayerSlider') ?></td>
					<td><?php _e('Shortcode', 'LayerSlider') ?></td>
					<td><?php _e('Actions', 'LayerSlider') ?></td>
					<td><?php _e('Created', 'LayerSlider') ?></td>
					<td><?php _e('Modified', 'LayerSlider') ?></td>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($sliders)) : ?>
				<?php foreach($sliders as $key => $item) : ?>
				<?php $name = empty($item->name) ? 'Unnamed' : $item->name; ?>
				<tr>
					<td><?php echo $item->id ?></td>
					<td><a href="?page=layerslider&action=edit&id=<?php echo $item->id ?>"><?php echo $name ?></a></td>
					<td>[layerslider id="<?php echo $item->id ?>"]</td>
					<td>
						<a href="?page=layerslider&action=edit&id=<?php echo $item->id ?>"><?php _e('Edit', 'LayerSlider') ?></a> |
						<a href="?page=layerslider&action=duplicate&id=<?php echo $item->id ?>"><?php _e('Duplicate', 'LayerSlider') ?></a> |
						<a href="?page=layerslider&action=remove&id=<?php echo $item->id ?>" class="remove"><?php _e('Remove', 'LayerSlider') ?></a>
					</td>
					<td><?php echo date('M. d. Y.', $item->date_c) ?></td>
					<td><?php echo date('M. d. Y.', $item->date_m) ?></td>
				</tr>
				<?php endforeach; ?>
				<?php endif; ?>
				<?php if(empty($sliders)) : ?>
				<tr>
					<td colspan="6"><?php _e("You didn't create a slider yet.", "LayerSlider") ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<?php if($GLOBALS['lsAutoUpdateBox'] == true) : ?>
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="ls-box ls-settings ls-auto-update">
		<input type="hidden" name="action" value="layerslider_verify_purchase_code">
		<h3 class="header"><?php _e('Auto-updates', 'LayerSlider') ?></h3>
		<table>
			<tbody>
				<tr>
					<td><?php _e('Purchase code', 'LayerSlider') ?></td>
					<td class="desc">
						<input type="texT" name="purchase_code" value="<?php echo $code ?>" placeholder="bc8e2b24-3f8c-4b21-8b4b-90d57a38e3c7"><br>
						<?php _e('To receive auto-updates, you need to enter your item purchase code. You can find it on your CodeCanyon downloads page, just click on the "Licence Certificate" button of the corresponding item. This will download a text file which contains your purchase code.', 'LayerSlider') ?>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2">
						<button class="button"><?php _e('Save changes', 'LayerSlider') ?></button>
						<span style="<?php echo ($validity == '0' && $code != '') ? 'color: #c33219;' : 'color: #4b982f'?>">
							<?php
								if($validity == '1') {
									_e('Thank you for purchasing LayerSlider WP. You successfully validated your purchase code for auto-updates.', 'LayerSlider');
								} else if($code != '') {
									_e("Your purchase code doesn't appear to be valid. Please make sure that you entered your purchase code correctly.", "LayerSlider");
								}
							?>
						</span>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
	<?php endif; ?>

	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="ls-box ls-settings">
		<input type="hidden" name="posted_ls_global" value="1">
		<h3 class="header"><?php _e('Permissions', 'LayerSlider') ?></h3>
		<table>
			<tbody>
				<tr>
					<td><?php _e('Custom capability', 'LayerSlider') ?></td>
					<td><input type="text" name="custom_capability" value="<?php echo $custom_capability ?>"></td>
					<td class="desc"><?php _e('If you want to give access for other users than admins to this page, you can specify a custom capability. You can find all the available capabilities on', 'LayerSlider') ?> <a href="http://codex.wordpress.org/Roles_and_Capabilities#Capabilities" target="_blank"><?php _e('this page', 'LayerSlider') ?></a>.</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3">
						<button class="button"><?php _e('Save changes', 'LayerSlider') ?></button>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>

	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="ls-box ls-import-box">
		<h3 class="header"><?php _e('Import sliders', 'LayerSlider') ?></h3>
		<div class="inner">
			<textarea name="import" rows="10" cols="50"></textarea>
			<button class="button"><?php _e('Import', 'LayerSlider') ?></button>
		</div>
	</form>



	<?php

		// Array for export sliders data
		$export = array();

		// Get sliders data
		foreach($sliders as $item) {
			$export[] = json_decode($item->data, true);
		}
	?>
	<div class="ls-box ls-import-box">
		<h3 class="header"><?php _e('Export sliders', 'LayerSlider') ?></h3>
		<div class="inner">
			<textarea rows="10" cols="50" readonly="readonly"><?php echo base64_encode(json_encode($export)) ?></textarea>
			<p><?php _e('Place this export code into the import text field in your new site and press "Import".', 'LayerSlider') ?></p>
		</div>
	</div>
</div>

<!-- Help menu WP Pointer -->
<?php

// Get users data
global $current_user;
get_currentuserinfo();

if(get_user_meta($current_user->ID, 'layerslider_help_wp_pointer', true) != '1') {
add_user_meta($current_user->ID, 'layerslider_help_wp_pointer', '1'); ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#contextual-help-link-wrap').pointer({
			pointerClass : 'ls-help-pointer',
			pointerWidth : 320,
			content: '<h3><?php _e('The documentation is here', 'LayerSlider') ?></h3><div class="inner"><?php _e('This is a WordPress contextual help menu, we use it to give you fast access to our documentation. Please keep in mind that because this menu is contextual, it only shows the relevant information to the page that you are currently viewing. So if you search something, you should visit the corresponding page first and then open this help menu.', 'LayerSlider') ?></div>',
			position: {
				edge : 'top',
				align : 'right'
			}
		}).pointer('open');
	});
</script>
<?php } ?>
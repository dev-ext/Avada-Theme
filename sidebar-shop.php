<div id="sidebar">
	<?php
	wp_reset_query();
	if(is_product()) {
		generated_dynamic_sidebar();
	} else {
		$shop_page_id = get_option('woocommerce_shop_page_id');
		$name = get_post_meta($shop_page_id, 'sbg_selected_sidebar_replacement', true);
		if($name) {
			generated_dynamic_sidebar($name[0]);
		}	
	}
	?>
</div>
<?php get_header(); ?>
	<div id="content" class="full-width">
		<div id="post-<?php the_ID(); ?>" class="post">
			<div class="post-content">
				<div class="title">
					<h2><?php echo __('Oops, This Page Could Not Be Found!', 'Avada'); ?></h2><div class="title-sep-container"><div class="title-sep"></div></div>
				</div>
				<div class="error_page">
					<div class="one_third">
						<div class="error-image"></div>
					</div>
					<div class="one_third useful_links">
						<h3><?php echo __('Here are some useful links:', 'Avada'); ?></h3>
						<?php wp_nav_menu(array('theme_location' => '404_pages', 'depth' => 1, 'container' => false, 'menu_id' => '', 'menu_class' => 'arrow')); ?>
					</div>
					<div class="one_third last">
						<h3><?php echo __('Search Our Website', 'Avada'); ?></a></h3>
						<p><?php echo __('Can\'t find what you need? Take a moment and do a search below!', 'Avada'); ?></p>
						<?php get_search_form(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>
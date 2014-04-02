<?php get_header(); ?>
	<?php
	if($data['blog_full_width']) {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
	} elseif($data['blog_sidebar_position'] == 'Left') {
		$content_css = 'float:right;';
		$sidebar_css = 'float:left;';
	} elseif($data['blog_sidebar_position'] == 'Right') {
		$content_css = 'float:left;';
		$sidebar_css = 'float:right;';
	}
	?>
	<div id="content" style="<?php echo $content_css; ?>">
		<?php if (have_posts()) : ?>
		<?php while(have_posts()): the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
			<?php
			if('page' != $post->post_type && !$data['search_featured_images']):
			if($data['legacy_posts_slideshow']) {
				get_template_part('legacy-slideshow');
			} else {
				get_template_part('new-slideshow');
			}
			endif;
			?>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php if(!$data['search_excerpt']): ?>
			<div class="post-content">
				<?php
				$stripped_content = tf_content( $data['excerpt_length_blog'], $data['strip_html_excerpt'] );
				echo $stripped_content; 
				?>
			</div>
			<?php endif; ?>
			<div class="meta-info">
				<div class="alignleft">
					<?php echo __('By', 'Avada'); ?> <?php the_author_posts_link(); ?><span class="sep">|</span><?php the_time($data['date_format']); ?><span class="sep">|</span><?php the_category(', '); ?><span class="sep">|</span><?php comments_popup_link(__('0 Comments', 'Avada'), __('1 Comment', 'Avada'), '% '.__('Comments', 'Avada')); ?>
				</div>
				<div class="alignright">
					<a href="<?php the_permalink(); ?>" class="read-more"><?php echo __('Read More', 'Avada'); ?></a>
				</div>
			</div>
		</div>
		<?php endwhile; ?>
		<?php themefusion_pagination($pages = '', $range = 2); ?>
		<?php else: ?>
		<div class="error_page">
			<div class="one_third">
				<h1><?php echo __('Oops!', 'Avada'); ?><br></h1>
			<h2><?php echo __('Couldn\'t find what you\'re looking for!', 'Avada'); ?></h2>
			</div>
			<div class="one_third useful_links">
				<h3><?php echo __('Here are some useful links:', 'Avada'); ?></h3>
				<?php wp_nav_menu(array('theme_location' => '404_pages', 'depth' => 1, 'container' => false, 'menu_id' => '', 'menu_class' => 'arrow')); ?>
			</div>
			<div class="one_third last">
				<h3><?php echo __('Try again!', 'Avada'); ?></a></h3>
				<p><?php echo __('If you want to rephrase your query, here is your chance:', 'Avada'); ?></p>
				<?php get_search_form(); ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<div id="sidebar" style="<?php echo $sidebar_css; ?>">
		<?php
		if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Blog Sidebar')): 
		endif;
		?>
	</div>
<?php get_footer(); ?>
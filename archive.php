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
			if($data['featured_images']):
			if($data['legacy_posts_slideshow']) {
				get_template_part('legacy-slideshow');
			} else {
				get_template_part('new-slideshow');
			}
			endif;
			?>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="post-content">
				<?php
				if($data['content_length'] == 'Excerpt') {
					$stripped_content = tf_content( $data['excerpt_length_blog'], $data['strip_html_excerpt'] );
					echo $stripped_content; 
				} else {
					the_content('');
				}
				?>
			</div>
			<div style="clear:both;"></div>
			<?php if($data['post_meta']): ?>
			<div class="meta-info">
				<div class="alignleft">
					<?php echo __('By', 'Avada'); ?> <?php the_author_posts_link(); ?><span class="sep">|</span><?php the_time($data['date_format']); ?><span class="sep">|</span><?php the_category(', '); ?><span class="sep">|</span><?php comments_popup_link(__('0 Comments', 'Avada'), __('1 Comment', 'Avada'), '% '.__('Comments', 'Avada')); ?>
				</div>
				<div class="alignright">
					<a href="<?php the_permalink(); ?>" class="read-more"><?php echo __('Read More', 'Avada'); ?></a>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php endwhile; ?>
		<?php themefusion_pagination($pages = '', $range = 2); ?>
		<?php else: ?>
		<?php endif; ?>
	</div>
	<div id="sidebar" style="<?php echo $sidebar_css; ?>">
		<?php
		if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Blog Sidebar')): 
		endif;
		?>
	</div>
<?php get_footer(); ?>
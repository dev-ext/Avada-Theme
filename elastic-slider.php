<?php
global $data;
$args = array(
	'post_type' => 'themefusion_elastic',
	'posts_per_page' => -1,
	'suppress_filters' => 0
);
$args['tax_query'][] = array(
	'taxonomy' => 'themefusion_es_groups',
	'field' => 'slug',
	'terms' => get_post_meta($slider_page_id, 'pyre_elasticslider', true)
);
$query = new WP_Query($args);
if($query->have_posts()):
?>
<div id="ei-slider" class="ei-slider">
	<ul class="ei-slider-large">
		<?php while($query->have_posts()): $query->the_post(); ?>
		<li>
			<?php the_post_thumbnail('full', array('title' => '', 'alt' => '')); ?>
			<div class="ei-title">
				<?php if(get_post_meta(get_the_ID(), 'pyre_caption_1', true)): ?>
				<h2><?php echo get_post_meta(get_the_ID(), 'pyre_caption_1', true); ?></h2>
				<?php endif; ?>
				<?php if(get_post_meta(get_the_ID(), 'pyre_caption_2', true)): ?>
				<h3><?php echo get_post_meta(get_the_ID(), 'pyre_caption_2', true); ?></h3>
				<?php endif; ?>
			</div>
		</li>
		<?php endwhile; ?>
	</ul>
	<ul class="ei-slider-thumbs">
		<li class="ei-slider-element">Current</li>
		<?php while($query->have_posts()): $query->the_post(); ?>
		<li>
			<a href="#"><?php the_title(); ?></a>
			<?php the_post_thumbnail('full', array('title' => '', 'alt' => '')); ?>
		</li>
		<?php endwhile; ?>
	</ul>
</div>
<?php endif; ?>
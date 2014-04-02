			<?php global $data; ?>
			<?php if($data['blog_layout'] != 'Grid' && $data['blog_layout'] != 'Timeline'): ?>
			<style type="text/css">
			<?php if(get_post_meta($post->ID, 'pyre_fimg_width', true) && get_post_meta($post->ID, 'pyre_fimg_width', true) != 'auto'): ?>
			#post-<?php echo $post->ID; ?> .post-slideshow,
			#post-<?php echo $post->ID; ?> .floated-post-slideshow,
			#post-<?php echo $post->ID; ?> .post-slideshow .image > img,
			#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > img,
			#post-<?php echo $post->ID; ?> .post-slideshow .image > a > img,
			#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > a > img
			{width:<?php echo get_post_meta($post->ID, 'pyre_fimg_width', true); ?> !important;}
			<?php endif; ?>

			<?php if(get_post_meta($post->ID, 'pyre_fimg_height', true) && get_post_meta($post->ID, 'pyre_fimg_height', true) != 'auto'): ?>
			#post-<?php echo $post->ID; ?> .post-slideshow,
			#post-<?php echo $post->ID; ?> .floated-post-slideshow,
			#post-<?php echo $post->ID; ?> .post-slideshow .image > img,
			#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > img,
			#post-<?php echo $post->ID; ?> .post-slideshow .image > a > img,
			#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > a > img
			{height:<?php echo get_post_meta($post->ID, 'pyre_fimg_height', true); ?> !important;}
			<?php endif; ?>

			<?php
			if(
				get_post_meta($post->ID, 'pyre_fimg_height', true) && get_post_meta($post->ID, 'pyre_fimg_width', true) &&
				get_post_meta($post->ID, 'pyre_fimg_height', true) != 'auto' && get_post_meta($post->ID, 'pyre_fimg_width', true) != 'auto'
			) { ?>
			@media only screen and (max-width: 479px){
				#post-<?php echo $post->ID; ?> .post-slideshow,
				#post-<?php echo $post->ID; ?> .floated-post-slideshow,
				#post-<?php echo $post->ID; ?> .post-slideshow .image > img,
				#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > img,
				#post-<?php echo $post->ID; ?> .post-slideshow .image > a > img,
				#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > a > img{
					width:auto !important;
					height:auto !important;
				}
			}
			<?php }
			?>
			</style>
			<?php endif; ?>

			<?php
			if(get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'link') {
				$link_icon_css = '';
				$zoom_icon_css = 'display:none;';
			} elseif(get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'zoom') {
				$link_icon_css = 'display:none;';
				$zoom_icon_css = '';
			} elseif(get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'no') {
				$link_icon_css = 'display:none;';
				$zoom_icon_css = 'display:none;';
			} else {
				$link_icon_css = '';
				$zoom_icon_css = '';
			}

			$icon_url_check = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true); if(!empty($icon_url_check)) {
				$permalink = get_post_meta($post->ID, 'pyre_link_icon_url', true);
			} else {
				$permalink = get_permalink($post->ID);
			}
			?>

			<?php
			if($data['blog_full_width']) {
				$size = 'full';
			} else {
				$size = 'blog-large';
			}

			if($data['blog_layout'] == 'Medium' || $data['blog_layout'] == 'Medium Alternate') {
				$size = 'blog-medium';
			}

			if(
				get_post_meta($post->ID, 'pyre_fimg_height', true) && get_post_meta($post->ID, 'pyre_fimg_width', true) &&
				get_post_meta($post->ID, 'pyre_fimg_height', true) != 'auto' && get_post_meta($post->ID, 'pyre_fimg_width', true) != 'auto'
			) {
				$size = 'full';
			}

			if($data['blog_layout'] == 'Grid' || $data['blog_layout'] == 'Timeline') {
				$size = 'full';
			}
			?>
			
			<?php if($data['blog_layout'] == 'Large' || $data['blog_layout'] == 'Large Alternate' || $data['blog_layout'] == 'Grid' || $data['blog_layout'] == 'Timeline'): ?>
			<?php
			$args = array(
			    'post_type' => 'attachment',
			    'numberposts' => $data['posts_slideshow_number']-1,
			    'post_status' => null,
			    'post_parent' => $post->ID,
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'post_mime_type' => 'image',
				'exclude' => get_post_thumbnail_id()
			);
			$attachments = get_posts($args);
			if($attachments || has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)):
			?>
			<div class="flexslider post-slideshow">
				<ul class="slides">
					<?php if(get_post_meta(get_the_ID(), 'pyre_video', true)): ?>
					<li class="full-video">
						<?php echo get_post_meta(get_the_ID(), 'pyre_video', true); ?>
					</li>
					<?php endif; ?>
					<?php if(has_post_thumbnail()): ?>
					<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id()); ?>
					<li>
						<div class="image">
								<?php if($data['image_rollover']): ?>
								<?php the_post_thumbnail($size); ?>
								<?php else: ?>
								<a href="<?php echo $permalink(); ?>"><?php the_post_thumbnail($size); ?></a>
								<?php endif; ?>
								<div class="image-extras">
									<div class="image-extras-content">
										<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
										<a style="<?php echo $link_icon_css; ?>" class="icon link-icon" href="<?php the_permalink(); ?>">Permalink</a>
										<?php
										if(get_post_meta($post->ID, 'pyre_video_url', true)) {
											$full_image[0] = get_post_meta($post->ID, 'pyre_video_url', true);
										}
										?>
										<a style="<?php echo $zoom_icon_css; ?>" class="icon gallery-icon" href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery<?php echo $post->ID; ?>]" title="<?php echo get_post_field('post_content', get_post_thumbnail_id()); ?>"><img style="display:none;" alt="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id()); ?>" />Gallery</a>
										<h3><?php the_title(); ?></h3>
									</div>
								</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if($data['posts_slideshow']): ?>
					<?php foreach($attachments as $attachment): ?>
					<?php $attachment_image = wp_get_attachment_image_src($attachment->ID, $size); ?>
					<?php $full_image = wp_get_attachment_image_src($attachment->ID, 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata($attachment->ID); ?>
					<li>
						<div class="image">
								<a href="<?php the_permalink(); ?>"><img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo $attachment->post_title; ?>" /></a>
								<a style="display:none;" href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery<?php echo $post->ID; ?>]" alt="<?php echo get_post_field('post_excerpt', $attachment->ID); ?>" title="<?php echo get_post_field('post_content', $attachment->ID); ?>"><img style="display:none;" alt="<?php echo get_post_field('post_excerpt', $attachment->ID); ?>" /></a>
						</div>
					</li>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>
			<?php endif; ?>
			
			<?php if($data['blog_layout'] == 'Medium' || $data['blog_layout'] == 'Medium Alternate'): ?>
			<?php
			$args = array(
			    'post_type' => 'attachment',
			    'numberposts' => $data['posts_slideshow_number']-1,
			    'post_status' => null,
			    'post_parent' => $post->ID,
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'post_mime_type' => 'image',
				'exclude' => get_post_thumbnail_id()
			);
			$attachments = get_posts($args);
			if($attachments || has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)):
			?>
			<div class="flexslider blog-medium-image floated-post-slideshow">
				<ul class="slides">
					<?php if(get_post_meta(get_the_ID(), 'pyre_video', true)): ?>
					<li class="full-video">
						<?php echo get_post_meta(get_the_ID(), 'pyre_video', true); ?>
					</li>
					<?php endif; ?>
					<?php if(has_post_thumbnail()): ?>
					<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id()); ?>
					<li>
						<div class="image">
								<?php if($data['image_rollover']): ?>
								<?php the_post_thumbnail($size); ?>
								<?php else: ?>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail($size); ?></a>
								<?php endif; ?>
								<div class="image-extras">
									<div class="image-extras-content">
										<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
										<a style="<?php echo $link_icon_css; ?>" class="icon link-icon" href="<?php echo $permalink; ?>">Permalink</a>
										<?php
										if(get_post_meta($post->ID, 'pyre_video_url', true)) {
											$full_image[0] = get_post_meta($post->ID, 'pyre_video_url', true);
										}
										?>
										<a style="<?php echo $zoom_icon_css; ?>" class="icon gallery-icon" href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery<?php echo $post->ID; ?>]" title="<?php echo get_post_field('post_content', get_post_thumbnail_id()); ?>"><img style="display:none;" alt="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id()); ?>" />Gallery</a>
										<h3><?php the_title(); ?></h3>
									</div>
								</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if($data['posts_slideshow']): ?>
					<?php foreach($attachments as $attachment): ?>
					<?php $attachment_image = wp_get_attachment_image_src($attachment->ID, 'blog-medium'); ?>
					<?php $full_image = wp_get_attachment_image_src($attachment->ID, 'full'); ?>
					<?php $attachment_data = wp_get_attachment_metadata($attachment->ID); ?>
					<li>
						<div class="image">
								<a href="<?php the_permalink(); ?>"><img src="<?php echo $attachment_image[0]; ?>" alt="<?php echo $attachment->post_title; ?>" /></a>
								<a style="display:none;" href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery<?php echo $post->ID; ?>]" alt="<?php echo get_post_field('post_excerpt', $attachment->ID); ?>" title="<?php echo get_post_field('post_content', $attachment->ID); ?>"><img style="display:none;" alt="<?php echo get_post_field('post_excerpt', $attachment->ID); ?>" /></a>
						</div>
					</li>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>
			<?php endif; ?>
<?php
add_action('widgets_init', 'pyre_tabs_load_widgets');

function pyre_tabs_load_widgets()
{
	register_widget('Pyre_Tabs_Widget');
}

class Pyre_Tabs_Widget extends WP_Widget {
	
	function Pyre_Tabs_Widget()
	{
		$widget_ops = array('classname' => 'pyre_tabs', 'description' => 'Popular posts, recent post and comments.');

		$control_ops = array('id_base' => 'pyre_tabs-widget');

		$this->WP_Widget('pyre_tabs-widget', 'Avada: Tabs', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		global $data, $post;
		
		extract($args);
		
		$posts = $instance['posts'];
		$comments = $instance['comments'];
		$tags_count = $instance['tags'];
		$show_popular_posts = isset($instance['show_popular_posts']) ? 'true' : 'false';
		$show_recent_posts = isset($instance['show_recent_posts']) ? 'true' : 'false';
		$show_comments = isset($instance['show_comments']) ? 'true' : 'false';
		$show_tags = isset($instance['show_tags']) ? 'true' : 'false';
		
		echo $before_widget;
		?>
		<div class="tab-holder">
			<div class="tab-hold tabs-wrapper">
				<ul id="tabs" class="tabset tabs">
					<?php if($show_popular_posts == 'true'): ?>
					<li><a href="#tab1"><?php echo __('Popular', 'Avada'); ?></a></li>
					<?php endif; ?>
					<?php if($show_recent_posts == 'true'): ?>
					<li><a href="#tab2"><?php echo __('Recent', 'Avada'); ?></a></li>
					<?php endif; ?>
					<?php if($show_comments == 'true'): ?>
					<li><a href="#tab3"><span class="chat-icon"></span></a></li>
					<?php endif; ?>
				</ul>
				<div class="tab-box tabs-container">
					<?php if($show_popular_posts == 'true'): ?>
					<div id="tab1" class="tab tab_content" style="display: none;">
						<?php
						$popular_posts = new WP_Query('showposts='.$posts.'&orderby=comment_count&order=DESC');
						if($popular_posts->have_posts()): ?>
						<ul class="news-list">
							<?php while($popular_posts->have_posts()): $popular_posts->the_post(); ?>
							<li>
								<?php if(has_post_thumbnail()): ?>
								<div class="image">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('tabs-img'); ?>
									</a>
								</div>
								<?php endif; ?>
								<div class="post-holder">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									<div class="meta">
										<?php the_time($data['date_format']); ?>
									</div>
								</div>
							</li>
							<?php endwhile; ?>
						</ul>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<?php if($show_recent_posts == 'true'): ?>
					<div id="tab2" class="tab tab_content" style="display: none;">
						<?php
						$recent_posts = new WP_Query('showposts='.$posts);
						if($recent_posts->have_posts()):
						?>
						<ul class="news-list">
							<?php while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
							<li>
								<?php if(has_post_thumbnail()): ?>
								<div class="image">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('tabs-img'); ?>
									</a>
								</div>
								<?php endif; ?>
								<div class="post-holder">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									<div class="meta">
										<?php the_time($data['date_format']); ?>
									</div>
								</div>
							</li>
							<?php endwhile; ?>
						</ul>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<?php if($show_comments == 'true'): ?>
					<div id="tab3" class="tab tab_content" style="display: none;">
						<ul class="news-list">
							<?php
							$number = $instance['comments'];
							global $wpdb;
							$recent_comments = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, comment_author_url, SUBSTRING(comment_content,1,110) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $number";
							$the_comments = $wpdb->get_results($recent_comments);
							foreach($the_comments as $comment) { ?>
							<li>
								<div class="image">
									<a>
										<?php echo get_avatar($comment, '52'); ?>
									</a>
								</div>
								<div class="post-holder">
									<p><?php echo strip_tags($comment->comment_author); ?> says:</p>
									<div class="meta">
										<a class="comment-text-side" href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php echo strip_tags($comment->comment_author); ?> on <?php echo $comment->post_title; ?>"><?php echo string_limit_words(strip_tags($comment->com_excerpt), 12); ?>...</a>
									</div>
								</div>
							</li>
							<?php } ?>
						</ul>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['posts'] = $new_instance['posts'];
		$instance['comments'] = $new_instance['comments'];
		$instance['tags'] = $new_instance['tags'];
		$instance['show_popular_posts'] = $new_instance['show_popular_posts'];
		$instance['show_recent_posts'] = $new_instance['show_recent_posts'];
		$instance['show_comments'] = $new_instance['show_comments'];
		$instance['show_tags'] = $new_instance['show_tags'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('posts' => 3, 'comments' => '3', 'tags' => 20, 'show_popular_posts' => 'on', 'show_recent_posts' => 'on', 'show_comments' => 'on', 'show_tags' =>  'on');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('posts'); ?>">Number of popular posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" value="<?php echo $instance['posts']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('tags'); ?>">Number of recent posts:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" value="<?php echo $instance['tags']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('comments'); ?>">Number of comments:</label>
			<input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('comments'); ?>" name="<?php echo $this->get_field_name('comments'); ?>" value="<?php echo $instance['comments']; ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_popular_posts'], 'on'); ?> id="<?php echo $this->get_field_id('show_popular_posts'); ?>" name="<?php echo $this->get_field_name('show_popular_posts'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_popular_posts'); ?>">Show popular posts</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_recent_posts'], 'on'); ?> id="<?php echo $this->get_field_id('show_recent_posts'); ?>" name="<?php echo $this->get_field_name('show_recent_posts'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_recent_posts'); ?>">Show recent posts</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_comments'], 'on'); ?> id="<?php echo $this->get_field_id('show_comments'); ?>" name="<?php echo $this->get_field_name('show_comments'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_comments'); ?>">Show comments</label>
		</p>
	<?php }
}
?>
<?php
add_action('widgets_init', 'contact_info_load_widgets');

function contact_info_load_widgets()
{
	register_widget('Contact_Info_Widget');
}

class Contact_Info_Widget extends WP_Widget {
	
	function Contact_Info_Widget()
	{
		$widget_ops = array('classname' => 'contact_info', 'description' => '');

		$control_ops = array('id_base' => 'contact_info-widget');

		$this->WP_Widget('contact_info-widget', 'Avada: Contact Info', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}
		?>
		<?php if($instance['address']): ?>
		<p class="address"><?php echo $instance['address']; ?></p>
		<?php endif; ?>

		<?php if($instance['phone']): ?>
		<p class="phone"><?php _e('Phone:', 'Avada'); ?> <?php echo $instance['phone']; ?></p>
		<?php endif; ?>

		<?php if($instance['fax']): ?>
		<p class="fax"><?php _e('Fax:', 'Avada'); ?> <?php echo $instance['fax']; ?></p>
		<?php endif; ?>

		<?php if($instance['email']): ?>
		<p class="email"><?php _e('Email:', 'Avada'); ?> <a href="mailto:<?php echo $instance['email']; ?>"><?php if($instance['emailtxt']) { echo $instance['emailtxt']; } else { echo $instance['email']; } ?></a></p>
		<?php endif; ?>

		<?php if($instance['web']): ?>
		<p class="web"><?php _e('Web:', 'Avada'); ?> <a href="<?php echo $instance['web']; ?>"><?php if($instance['webtxt']) { echo $instance['webtxt']; } else { echo $instance['web']; } ?></a></p>
		<?php endif; ?>
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];
		$instance['address'] = $new_instance['address'];
		$instance['phone'] = $new_instance['phone'];
		$instance['fax'] = $new_instance['fax'];
		$instance['email'] = $new_instance['email'];
		$instance['emailtxt'] = $new_instance['emailtxt'];
		$instance['web'] = $new_instance['web'];
		$instance['webtxt'] = $new_instance['webtxt'];

		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Contact Info');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('address'); ?>">Address:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" value="<?php echo $instance['address']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('phone'); ?>">Phone:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" value="<?php echo $instance['phone']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('fax'); ?>">Fax:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>" value="<?php echo $instance['fax']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('email'); ?>">Email:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" value="<?php echo $instance['email']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('emailtxt'); ?>">Email Link Text:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('emailtxt'); ?>" name="<?php echo $this->get_field_name('emailtxt'); ?>" value="<?php echo $instance['emailtxt']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('web'); ?>">Website URL (with HTTP):</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('web'); ?>" name="<?php echo $this->get_field_name('web'); ?>" value="<?php echo $instance['web']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('webtxt'); ?>">Website URL Text:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('webtxt'); ?>" name="<?php echo $this->get_field_name('webtxt'); ?>" value="<?php echo $instance['webtxt']; ?>" />
		</p>
	<?php
	}
}
?>
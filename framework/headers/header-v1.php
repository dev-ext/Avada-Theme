<?php global $data; ?>
<header id="header">
	<div class="avada-row" style="margin-top:<?php echo $data['margin_header_top']; ?>;margin-bottom:<?php echo $data['margin_header_bottom']; ?>;">
		<div class="logo" style="margin-right:<?php echo $data['margin_logo_right']; ?>;margin-top:<?php echo $data['margin_logo_top']; ?>;margin-left:<?php echo $data['margin_logo_left']; ?>;margin-bottom:<?php echo $data['margin_logo_bottom']; ?>;"><a href="<?php bloginfo('url'); ?>"><img src="<?php echo $data['logo']; ?>" alt="<?php bloginfo('name'); ?>" /></a></div>
		<?php if($data['ubermenu']): ?>
		<nav id="nav-uber">
		<?php else: ?>
		<nav id="nav" class="nav-holder">
		<?php endif; ?>
			<?php wp_nav_menu(array('theme_location' => 'main_navigation', 'depth' => 4, 'container' => false, 'menu_id' => 'nav')); ?>
		</nav>
	</div>
</header>
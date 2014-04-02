<style type="text/css">
.demo_bg{
	width: 100%;
	position: absolute;
	top: 0;
	left: 0;
	z-index:-1;
	height: 100%;
}
#style_selector{
	background:#fff;
	width:193px;
	position:fixed;
	right:0;
	top:100px;
	z-index:100;
}
#style_selector_container{
	-webkit-box-shadow: 0 2px 9px 2px rgba(0,0,0,0.14);
	-moz-box-shadow: 0 2px 9px 2px rgba(0,0,0,0.14);
	box-shadow: 0 2px 9px 2px rgba(0,0,0,0.14);
	border:1px solid rgba(23,24,26,0.15);
	-webkit-border-top-left-radius: 2px;
	-webkit-border-bottom-left-radius: 2px;
	-moz-border-radius-topleft: 2px;
	-moz-border-radius-bottomleft: 2px;
	border-top-left-radius: 2px;
	border-bottom-left-radius: 2px;
}
.style-main-title{
	color:#000000;
	font-size:15px;
	height:44px;
	line-height:44px;
	text-align:center;
	border-bottom:1px solid rgba(23,24,26,0.15);

	background-image: linear-gradient(top, #FFFFFF 0%, #F7F4F4 100%);
	background-image: -o-linear-gradient(top, #FFFFFF 0%, #F7F4F4 100%);
	background-image: -moz-linear-gradient(top, #FFFFFF 0%, #F7F4F4 100%);
	background-image: -webkit-linear-gradient(top, #FFFFFF 0%, #F7F4F4 100%);
	background-image: -ms-linear-gradient(top, #FFFFFF 0%, #F7F4F4 100%);

	background-image: -webkit-gradient(
		linear,
		left top,
		left bottom,
		color-stop(0, #FFFFFF),
		color-stop(1, #F7F4F4)
	);
}
.box-title{
	font-size:12px;
	height:41px;
	line-height:41px;
	text-align:center;
	border-bottom:1px solid rgba(23,24,26,0.15);
}
.input-box{
	padding:10px;
	padding-left:40px;
	border-bottom:1px solid rgba(23,24,26,0.15);
}
.input-box input[type=text]{
	background:#f7f7f7;
	width:60px;
	border:1px solid rgba(23,24,26,0.15);
	font-size:11px;
	color:#000000;
	padding:3px;
	margin-left:10px;
}
.input-box select{
	background:#f7f7f7;
	width:120px;
	border:1px solid rgba(23,24,26,0.15);
	font-size:11px;
	color:#000000;
}
#style_selector .style-toggle{
	width:35px;
	height:43px;
	background:url(<?php bloginfo('template_directory'); ?>/images/colorpicker/style_arrow.png);
	cursor:pointer;
}
#style_selector .close{
	background-position:top left;
	position:absolute;
	top:45px;
	left:-35px;
	width:35px;
}
#style_selector .open{
	background-position:bottom left;
	position:absolute;
	top:45px;
	right:0;
	width:35px;
}
#style_selector .images{
	width:165px;
	padding-left:25px;
	margin-top:15px;
	border-bottom:1px solid rgba(23,24,26,0.15);
	padding-bottom:10px;
	position:relative;
	z-index:1000000;
}
#style_selector .images img{
	width:25px;
	height:24px;
	margin-right:7px;
	margin-bottom:7px;
	z-index:1000;
}
#style_selector .images img.active{
	border:0px solid #ccc;
	opacity:0.5;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#style_selector select[name=layout]').change(function() {
		var current = jQuery(this).find('option:selected').val();
		
		if(current == 'Boxed') {
			var html = 'body{background-color:#d7d6d6;background-image:url("http://isharis.dnsalias.com/wp-content/themes/Avada/images/patterns/pattern1.png");background-repeat:repeat;}#wrapper{background:#fff;width:1000px;margin:0 auto;}';
			jQuery('style#ss').append(html);
		} else {
			jQuery('style#ss').empty();
			jQuery('body').attr('style', '');
		}

	});
	jQuery('#style_selector select[name=color_skin]').change(function() {
		var current = jQuery(this).find('option:selected').val();
		
		if(current == 'Light') {
			window.location = 'http://theme-fusion.com/avada/';
		} else {
			window.location = 'http://theme-fusion.com/avadadark/';
		}

	});
	jQuery('#style_selector .close').click(function(e) {
		e.preventDefault();
		
		jQuery('#style_selector_container').hide();
		
		jQuery(this).hide();
		jQuery('#style_selector .open').show();
	});
	

	jQuery('#style_selector .open').click(function(e) {
		e.preventDefault();
		
		jQuery('#style_selector_container').show();
		
		jQuery(this).hide();
		jQuery('#style_selector .close').show();
	});

	jQuery('.patterns a').click(function(e) {
		e.preventDefault();

		var current = jQuery('#style_selector select[name=layout]').find('option:selected').val();

		if(current == 'Boxed') {
			jQuery(this).parent().find('img').removeClass('active');
			jQuery(this).find('img').addClass('active');

			var name = jQuery(this).attr('name');
			
			if(jQuery(this).hasClass('bkgd')) {
				jQuery('body').css('background', 'url(<?php bloginfo("template_directory"); ?>/images/patterns/'+name+'.jpg) no-repeat center center fixed');
				jQuery('body').css('background-size', 'cover');
			} else {
				jQuery('body').css('background', 'url(<?php bloginfo("template_directory"); ?>/images/patterns/'+name+'.png) repeat center center scroll');
				jQuery('body').css('background-size', 'auto');
			}
		} else {
		alert('Select boxed layout');
		}
	});

	jQuery('.predefined a').click(function(e) {
		e.preventDefault();

		jQuery(this).parent().find('img').removeClass('active');
		jQuery(this).find('img').addClass('active');

		var name = jQuery(this).attr('name');

		if(name == 'green') {
			jQuery('#style_selector_ss').attr('href', '');
		} else {
			jQuery('#style_selector_ss').attr('href', '<?php bloginfo("template_directory"); ?>/css/skins/'+name+'.css');
		}

		if(jQuery('.header-v2').length >= 1) {
			jQuery('.header-social').attr('style', 'background-color: white !important; border-bottom: 1px solid #E1E1E1 !important;')
		} else {
			jQuery('.header-social').attr('style', 'border-bottom: 1px solid #E1E1E1 !important;')
		}
	});
});
</script>
<div id="style_selector">
	<div id="style_selector_container">
	<div class="style-main-title">Style Selector</div>
	<div class="box-title">Choose Your Layout Style</div>
	<div class="input-box">
		<div class="input">
			<select name="layout">
				<option>Wide</option>
				<option>Boxed</option>
			</select>
		</div>
	</div>
	<div class="box-title">Choose Your Color Skin</div>
	<div class="input-box">
		<div class="input">
			<select name="color_skin">
				<option>Light</option>
				<option>Dark</option>
			</select>
		</div>
	</div>
	<div class="box-title">Patterns for Boxed Version</div>
	<div class="images patterns">
		<a href="#" name="pattern1"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/pattern1.png" alt="" class="active" /></a>
		<a href="#" name="pattern2"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/pattern2.png" alt="" /></a>
		<a href="#" name="pattern3"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/pattern3.png" alt="" /></a>
		<a href="#" name="pattern4"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/pattern4.png" alt="" /></a>
		<a href="#" name="pattern5"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/pattern5.png" alt="" /></a>
		<a href="#" name="pattern6"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/pattern6.png" alt="" /></a>
		<a href="#" name="pattern7"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/pattern7.png" alt="" /></a>
		<a href="#" name="pattern8"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/pattern8.png" alt="" /></a>
		<a href="#" name="pattern9"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/pattern9.png" alt="" /></a>
		<a href="#" name="pattern10"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/pattern10.png" alt="" /></a>
	</div>
    <div class="box-title">Images for Boxed Version</div>
	<div class="images patterns">
		<a href="#" class="bkgd" name="bkgd1"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/bkgd1_thumbnail.png" alt="" /></a>
		<a href="#" class="bkgd" name="bkgd2"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/bkgd2_thumbnail.png" alt="" /></a>
		<a href="#" class="bkgd" name="bkgd3"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/bkgd3_thumbnail.png" alt="" /></a>
		<a href="#" class="bkgd" name="bkgd4"><img src="<?php bloginfo('template_directory'); ?>/images/patterns/bkgd4_thumbnail.png" alt="" /></a>
	</div>
	<div class="box-title">10 Predefined Color Schemes</div>
	<div class="images predefined">
		<a href="#" name="green"><img src="<?php bloginfo('template_directory'); ?>/images/colorpicker/green.png" alt="" class="active" /></a>
		<a href="#" name="darkgreen"><img src="<?php bloginfo('template_directory'); ?>/images/colorpicker/darkgreen.png" alt="" /></a>
		<a href="#" name="yellow"><img src="<?php bloginfo('template_directory'); ?>/images/colorpicker/yellow.png" alt="" /></a>
		<a href="#" name="lightblue"><img src="<?php bloginfo('template_directory'); ?>/images/colorpicker/lightblue.png" alt="" /></a>
		<a href="#" name="lightred"><img src="<?php bloginfo('template_directory'); ?>/images/colorpicker/lightred.png" alt="" /></a>
		<a href="#" name="pink"><img src="<?php bloginfo('template_directory'); ?>/images/colorpicker/pink.png" alt="" /></a>
		<a href="#" name="lightgrey"><img src="<?php bloginfo('template_directory'); ?>/images/colorpicker/lightgrey.png" alt="" /></a>
		<a href="#" name="brown"><img src="<?php bloginfo('template_directory'); ?>/images/colorpicker/brown.png" alt="" /></a>
		<a href="#" name="red"><img src="<?php bloginfo('template_directory'); ?>/images/colorpicker/red.png" alt="" /></a>
		<a href="#" name="blue"><img src="<?php bloginfo('template_directory'); ?>/images/colorpicker/blue.png" alt="" /></a>
		<p style="margin:0;line-height:normal;margin-left:-10px;display:none;"><small>These are just examples and you can build your own color scheme in the backend.</small></p>
	</div>
	</div>
	<div class="style-toggle close"></div>
	<div class="style-toggle open" style="display:none;"></div>
</div>
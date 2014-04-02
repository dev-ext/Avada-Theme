//////////////////////////////////////////////////////////////////
// Add ThemeFusion Button
//////////////////////////////////////////////////////////////////
(function() {  
    tinymce.create('tinymce.plugins.tfbutton', {
        init : function(ed, url) {  
            avada_shortcode_url = url;
        },    
        createControl : function(n, cm) {
            switch(n) {
                case 'tfbutton':
                    var c = cm.createSplitButton('tfbutton', {
                        title : 'ThemeFusion Shortcodes',
                        image : avada_shortcode_url+'/tfbutton.png'
                    }); 

                    c.onRenderMenu.add(function(c, m) {
                        m.add({
                            title : 'Shortcodes',
                            'class' : 'mceMenuItemTitle'
                        }).setDisabled(1);

                        m.add({
                            title : 'Alert',
                            icon: 'avada_alert',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[alert type="e.g. general, error, success, notice"]Your Message Goes Here.[/alert]');
                            }
                        });

                        m.add({
                            title : 'Buttons',
                            icon: 'avada_button',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[button color="e.g. green, darkgreen, orange, blue, red, pink, darkgray, lightgray or leave blank" size="large or small" link="" target=""]Text here[/button]');
                            }
                        });

                        m.add({
                            title : 'Blog',
                            icon: 'avada_blog',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[blog posts_per_page="5" author="" author_name="" category_name="" category=""  order="DESC" orderby="date" post_status="publish" post_type="post" tag="" nopaging="false" blog_layout="large"][/blog]');
                            }
                        });

                        m.add({
                            title : 'Checklist',
                            icon: 'avada_checklist',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[checklist icon="check, star, arrow, asterik, cross, plus" iconcolor="" circle="yes or no"]<ul>\r<li>Item #1</li>\r<li>Item #2</li>\r<li>Item #3</li>\r</ul>[/checklist]');
                            }
                        });

                        m.add({
                            title : 'Client Slider',
                            icon: 'avada_client_slider',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[clients][client link="" linktarget="" image=""][client link="" linktarget="" image=""][client link="" linktarget="" image=""][client link="" linktarget="" image=""][client link="" linktarget="" image=""][/clients]');
                            }
                        });

                        m.add({
                            title : 'Content Boxes',
                            icon: 'avada_content_boxes',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[content_boxes layout="icon-with-title, icon-on-top, icon-on-side, icon-boxed" iconcolor="" circlecolor="" circlebordercolor="" backgroundcolor=""]<br />[content_box title="Responsive Design" icon="tablet" image="" link="http://themeforest.net/user/ThemeFusion" linktarget="" linktext="Learn More"]Avada is fully responsive and can adapt to any screen size. Try resizing your browser window to see the adaptation.[/content_box]<br />[content_box title="Awesome Sliders" icon="thumbs-up" image="" link="http://themeforest.net/user/ThemeFusion" linktarget="" linktext="Learn More"]Avada includes the awesome Layer Parallax Slider as well as the popular FlexSlider2. Both are super easy to use![/content_box]<br />[content_box title="Unlimited Colors"  icon="magic" image="" link="http://themeforest.net/user/ThemeFusion" linktarget="" linktext="Learn More"]We included a backend color picker for unlimited color options. Anything can be changed, including the gradients![/content_box]<br />[content_box last="yes" title="500+ Google Fonts" icon="beaker" image="" link="http://themeforest.net/user/ThemeFusion" linktarget="" linktext="Learn More"]Avada loves fonts, choose from over 500+ Google Fonts. You can change all headings and body copy with ease![/content_box]<br />[/content_boxes]');
                            }
                        });

                        m.add({
                            title : 'Counters Circle',
                            icon: 'avada_counters_circle',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[counters_circle][counter_circle filledcolor="" unfilledcolor="" value="75"]75%[/counter_circle][counter_circle filledcolor="" unfilledcolor="" value="30"][fontawesome icon="adjust" circle="no" size="large"][/counter_circle][counter_circle filledcolor="" unfilledcolor="" value="70"]7/10[/counter_circle][counter_circle filledcolor="" unfilledcolor="" value="50"]Title[/counter_circle][/counters_circle]');
                            }
                        });

                        m.add({
                            title : 'Counters Box',
                            icon: 'avada_counters_box',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[counters_box][counter_box value="75"]Counter Title Goes Here[/counter_box][counter_box value="55"]Counter Title Goes Here[/counter_box][counter_box value="65"]Counter Title Goes Here[/counter_box][counter_box value="85"]Counter Title Goes Here[/counter_box][/counters_box]');
                            }
                        });

                        m.add({
                            title : 'Dropcap',
                            icon: 'avada_dropcap',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[dropcap]...[/dropcap]');
                            }
                        });

                        m.add({
                            title : 'Full Width',
                            icon: 'avada_full',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[fullwidth backgroundcolor="" backgroundimage="" backgroundrepeat="no-repeat" backgroundposition="top left" backgroundattachment="scroll" bordersize="1px" bordercolor="" paddingTop="20px" paddingBottom="20"]...[/fullwidth]');
                            }
                        });

                        m.add({
                            title : 'Flexslider',
                            icon: 'avada_flexslider',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[flexslider layout="posts, posts-with-excerpt, attachments" excerpt="25" category="" limit="3" id=""][/flexslider]');
                            }
                        });

                        m.add({
                            title : 'FontAwesome',
                            icon: 'avada_fontawesome',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[fontawesome icon="adjust" circle="yes or no" size="large medium or small" iconcolor="" circlecolor="" circlebordercolor=""]');
                            }
                        });

                        m.add({
                            title : 'Google Map',
                            icon: 'avada_map',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[map address="" type="roadmap, satellite, hybrid, terrain" width="100%" height="300px" zoom="14" scrollwheel="true" scale="true" zoom_pancontrol="true"][/map]');
                            }
                        });

                        m.add({
                            title : 'Highlight',
                            icon: 'avada_highlight',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[highlight color="eg. yellow, black or #333333"]...[/highlight]');
                            }
                        });

                        m.add({
                            title : 'Image Frames',
                            icon: 'avada_img_frames',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[imageframe style="border,glow,dropshadow,bottomshadow" bordercolor="" bordersize="4px" stylecolor="" align=""]<img src="Image Link" alt="Image Description" />[/imageframe]');
                            }
                        });

                        m.add({
                            title : 'Images Carousel',
                            icon: 'avada_img_carousel',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[images][image link="" linktarget="" image=""][image link="" linktarget="" image=""][image link="" linktarget="" image=""][image link="" linktarget="" image=""][image link="" linktarget="" image=""][/images]');
                            }
                        });

                        m.add({
                            title : 'Lightbox',
                            icon: 'avada_lightbox',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('<a title="lightbox description" href="full image link" rel="prettyPhoto"><img alt="lightbox title" src="thumbnail image link" /></a>');
                            }
                        });

                        m.add({
                            title : 'Progress Bar',
                            icon: 'avada_progress_bar',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[progress percentage="60" filledcolor="" unfilledcolor=""]Web Design[/progress]');
                            }
                        });

                        m.add({
                            title : 'Person',
                            icon: 'avada_person',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[person name="John Doe" picture="" title="Developer" facebook="http://facebook.com" twitter="http://twitter.com" linkedin="http://linkedin.com" dribbble="http://dribbble.com" linktarget=""]Redantium, totam rem aperiam, eaque ipsa qu ab illo inventore veritatis et quasi architectos beatae vitae dicta sunt explicabo. Nemo enim.[/person]');
                            }
                        });

                        m.add({
                            title : 'Pricing Table',
                            icon: 'avada_pricing_table',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[pricing_table type="e.g. 1 or 2" backgroundcolor="" bordercolor="" dividercolor=""][pricing_column title="Standard"][pricing_price currency="$" price="15.55" time="monthly"][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column][/pricing_table]');
                            }
                        });

                        m.add({
                            title : 'Recent Works',
                            icon: 'avada_recent_works',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[recent_works layout="carousel, grid, grid-with-excerpts" filters="true or false" columns="1-4" cat_slug="" number_posts="10" excerpt_words="15"][/recent_works]');
                            }
                        });

                        m.add({
                            title : 'Recent Posts',
                            icon: 'avada_recent_posts',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[recent_posts layout="default, thumbnails-on-side, date-on-side" columns="1-4" number_posts="4" cat_slug="" thumbnail="yes" title="yes" meta="yes" excerpt="yes" excerpt_words="15" strip_html="true"][/recent_posts]');
                            }
                        });

                        m.add({
                            title : 'SoundCloud',
                            icon: 'avada_soundcloud',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[soundcloud url="http://api.soundcloud.com/tracks/15565763" comments="true" auto_play="false" color="ff7700" width="100%" height="81"]');
                            }
                        });

                        m.add({
                            title : 'Slider',
                            icon: 'avada_slider',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[slider width="100%" height="100%"][slide type="video"][vimeo id="10145153" width="600" height="350"][/slide][slide link="" linktarget=""]image link here[/slide][/slider]');
                            }
                        });

                        m.add({
                            title : 'Social Links',
                            icon: 'avada_social_links',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[social_links colorscheme="" linktarget="" rss="" facebook="" twitter="" dribbble="" google="" linkedin="" blogger="" tumblr="" reddit="" yahoo="" deviantart="" vimeo="" youtube="" pinterest="" digg="" flickr="" forrst="" myspace="" skype=""]');
                            }
                        });

                        m.add({
                            title : 'Separator',
                            icon: 'avada_separator',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[separator top="40" style="none, single, double, dashed, dotted, shadow"]');
                            }
                        });

                        m.add({
                            title : 'Sharing Box',
                            icon: 'avada_sharing_box',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[sharing tagline="Share This Story, Choose Your Platform!" title="Title To Share" link="http://google.com" description="A small description of the page" backgroundcolor=""][/sharing]');
                            }
                        });

                        m.add({
                            title : 'Tabs',
                            icon: 'avada_tabs',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[tabs tab1=\"Tab 1\" tab2=\"Tab 2\" tab3=\"Tab 3\" layout="horizontal or vertical" backgroundcolor="" inactivecolor=""]<br /><br />[tab id=1]Tab content 1[/tab]<br />[tab id=2]Tab content 2[/tab]<br />[tab id=3]Tab content 3[/tab]<br /><br />[/tabs]');
                            }
                        });

                        m.add({
                            title : 'Toggles',
                            icon: 'avada_toggle',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[accordian][toggle title="Title" open="yes"]...[/toggle][toggle title="Title" open="no"]...[/toggle][toggle title="Title" open="no"]...[/toggle][toggle title="Title" open="no"]...[/toggle][toggle title="Title" open="no"]...[/toggle][/accordian]');
                            }
                        });

                        m.add({
                            title : 'Testimonial',
                            icon: 'avada_testimonial',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[testimonials backgroundcolor="" textcolor=""]<br />[testimonial name="John Doe" gender="male or female" company="My Company" link="" target=""]"Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consec tetur, adipisci velit, sed quia non numquam eius modi tempora incidunt utis labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minimas veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur slores amet untras vel illum qui."[/testimonial]<br />[testimonial name="Doe John" gender="male or female" company="My Company" link="" target=""]"Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consec tetur, adipisci velit, sed quia non numquam eius modi tempora incidunt utis labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minimas veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur slores amet untras vel illum qui."[/testimonial]<br />[/testimonials]');
                            }
                        });

                        m.add({
                            title : 'Title',
                            icon: 'avada_title',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[title size="1 to 6"]Title[/title]');
                            }
                        });

                        m.add({
                            title : 'Tagline Box',
                            icon: 'avada_tagline',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[tagline_box backgroundcolor="" shadow="no" border="1px" bordercolor="" highlightposition="right, left, top or bottom" link="http://themeforest.net/user/ThemeFusion" linktarget="" button="Purchase Now" title="Avada is incredibly responsive, with a refreshingly clean design" description="And it has some awesome features, premium sliders, unlimited colors, advanced theme options and so much more!"][/tagline_box]');
                            }
                        });

                        m.add({
                            title : 'Tooltip',
                            icon: 'avada_tooltip',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[tooltip title="Text Tooltip"]Hover over this text for Tooltip[/tooltip]');
                            }
                        });

                        m.add({
                            title : 'Table',
                            icon: 'avada_table',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('<div class="table-1"> \
<table width="100%"> \
<thead> \
<tr> \
<th>Column 1</th> \
<th>Column 2</th> \
<th>Column 3</th> \
<th>Column 4</th> \
</tr> \
</thead> \
<tbody> \
<tr> \
<td>Item #1</td> \
<td>Description</td> \
<td>Subtotal:</td> \
<td>$1.00</td> \
</tr> \
<tr> \
<td>Item #2</td> \
<td>Description</td> \
<td>Discount:</td> \
<td>$2.00</td> \
</tr> \
<tr> \
<td>Item #3</td> \
<td>Description</td> \
<td>Shipping:</td> \
<td>$3.00</td> \
</tr> \
<tr> \
<td>Item #4</td> \
<td>Description</td> \
<td>Tax:</td> \
<td>$4.00</td> \
</tr> \
<tr> \
<td><strong>All Items</strong></td> \
<td><strong>Description</strong></td> \
<td><strong>Your Total:</strong></td> \
<td><strong>$10.00</strong></td> \
</tr> \
</tbody> \
</table>');
                            }
                        });

                        m.add({
                            title : 'Vimeo',
                            icon: 'avada_vimeo',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[vimeo id="Enter video ID (eg. 10145153)" width="600" height="350"]');
                            }
                        });


                        m.add({
                            title : 'Youtube',
                            icon: 'avada_youtube',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[youtube id="Enter video ID (eg. Wq4Y7ztznKc)" width="600" height="350"]');
                            }
                        });


                        m.add({
                            title : '1/2',
                            icon: 'avada_half',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[one_half last="no"]...[/one_half]');
                            }
                        });

                        m.add({
                            title : '1/3',
                            icon: 'avada_third',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[one_third last="no"]...[/one_third]');
                            }
                        });

                        m.add({
                            title : '2/3',
                            icon: 'avada_two_third',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[two_third last="no"]...[/two_third]');
                            }
                        });

                        m.add({
                            title : '1/4',
                            icon: 'avada_fourth',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[one_fourth last="no"]...[/one_fourth]');
                            }
                        });

                        m.add({
                            title : '3/4',
                            icon: 'avada_three_fourth',
                            onclick : function() {
                                tinyMCE.activeEditor.selection.setContent('[three_fourth last="no"]...[/three_fourth]');
                            }
                        });
                    });

                  // Return the new menubutton instance
                  return c;
            }

            return null;
        },  
    });  
    tinymce.PluginManager.add('tfbutton', tinymce.plugins.tfbutton);  
})();
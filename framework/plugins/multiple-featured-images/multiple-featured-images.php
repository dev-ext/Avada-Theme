<?php
/*
Plugin Name: Multiple Featured Images
Description: Enables multiple featured images for posts and pages. If you like my plugin, feel free to give me reward ;) <a href="http://www.amazon.de/registry/wishlist/16KTW9ZG027C8" title="Amazon Wishlist" target="_blank">Amazon Wishlist</a>
Version: 0.3
Author: Marcus Kober
Author URI: http://www.koeln-dialog.de/
*/

/*  Copyright 2012 Marcus Kober (m.kober@koeln-dialog.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if( !class_exists( 'kdMultipleFeaturedImages' ) ) {
    
    class kdMultipleFeaturedImages {
        
        private $id                 = '';
        private $post_type          = '';
        private $labels             = array();
        
        private $metabox_id         = '';
        
        private $post_meta_key      = '';
        
        private $nonce              = '';

        private $default_labels     = array(
            'name'      => 'Featured Image 2',
            'set'       => 'Set featured image 2',
            'remove'    => 'Remove featured image 2',
            'use'       => 'Use as featured image 2',
        );
        
        private $default_args       = array(
            'id'        => 'featured-image-2',
            'post_type' => 'page',
        );
        
        /**
         * Initialize the plugin
         * 
         * @param array $args 
         * @return void
         */
        public function __construct( $args ) {
            $this->labels       = wp_parse_args( $args['labels'], $this->default_labels );
            unset( $args['labels'] );
            $args               = wp_parse_args( $args, $this->default_args );
            $this->id           = $args['id'];
            $this->post_type    = $args['post_type'];
            
            $this->metabox_id   = $this->id.'_'.$this->post_type;
            
            $this->post_meta_key= 'kd_'.$this->id.'_'.$this->post_type.'_id';
            
            $this->nonce        = 'mfi-'.$this->id.$this->post_type;
            
            if( !current_theme_supports( 'post-thumbnails' ) ) {
                add_theme_support( 'post-thumbnails' );
            }

            add_action( 'admin_init', array( &$this, 'kd_admin_init' ) );
            add_action( 'add_meta_boxes', array( &$this, 'kd_add_meta_box' ) );
            add_filter( 'attachment_fields_to_edit', array( &$this, 'kd_add_attachment_field' ), 11, 2 );

            add_action( 'wp_ajax_set-MuFeaImg-'.$this->id.'-'.$this->post_type, array( &$this, 'kd_ajax_set_image' ) );
            
            add_action( 'delete_attachment', array( &$this, 'kd_delete_attachment' ) );

        }        
        
        /**
         * Add admin-Javascript
         * 
         * @return void 
         */
        public function kd_admin_init() {
            if( strstr($_SERVER['REQUEST_URI'], 'wp-admin/post-new.php') || strstr($_SERVER['REQUEST_URI'], 'wp-admin/post.php') || strstr($_SERVER['REQUEST_URI'], 'wp-admin/media-upload.php')) {
                wp_enqueue_script(
                        'kd-multiple-featured-images',
                        get_bloginfo('template_directory').'/framework/plugins/multiple-featured-images/js/kd-admin.js',
                        'jquery'
                );
            }
        }
 
        /**
         * Add admin metabox for choosing additional featured images
         * 
         * @return void 
         */
        public function kd_add_meta_box() {
            add_meta_box(
                    $this->metabox_id,
                    $this->labels['name'],
                    array( $this, 'kd_meta_box_content' ),
                    $this->post_type,
                    'side',
                    'low'
            );
        }

        /**
         * Output the metabox content
         * 
         * @global object $post 
         * @return void
         */
        public function kd_meta_box_content() {
            global $post;
            
            $image_id = get_post_meta( 
                    $post->ID,
                    $this->post_meta_key,
                    true
            );
            
           echo $this->kd_meta_box_output( $image_id );
        }

        /**
         * Generate the metabox content
         * 
         * @global int $post_ID
         * @param int $image_id
         * @return string 
         */
        public function kd_meta_box_output( $image_id = NULL ) {
            global $post_ID;
            
            $output = '';
            
            $setImageLink = sprintf(
                    '<p class="hide-if-no-js"><a title="%2$s" href="%1$s" id="kd_%3$s" class="thickbox">%%s</a></p>',
                    get_upload_iframe_src( 'image' ),
                    $this->labels['set'],
                    $this->id
            );
            
            if( $image_id && get_post( $image_id ) ) {
                $nonce_field = wp_create_nonce( $this->nonce.$post_ID );
                
                $thumbnail = wp_get_attachment_image( $image_id, array( 266, 266 ) );
                $output.= sprintf( $setImageLink, $thumbnail );
                $output.= '<p class="hide-if-no-js">';
                $output.= sprintf(
                        '<a href="#" id="remove-%1$s-image" onclick="kdMuFeaImgRemove( \'%1$s\', \'%2$s\', \'%3$s\' ); return false;">',
                        $this->id,
                        $this->post_type,
                        $nonce_field
                );
                $output.= $this->labels['remove'];
                $output.= '</a>';
                $output.= '</p>';
                
                return $output;
            }
            else {
                return sprintf( $setImageLink, $this->labels['set'] );
            }
                
        }
        
        /**
         * Create a new field in the image upload form
         * 
         * @param string $form_fields
         * @param object $post
         * @return string 
         */
        public function kd_add_attachment_field( $form_fields, $post ) {
            $calling_id = 0;
            if( isset( $_GET['post_id'] ) ) {
                $calling_id = absint( $_GET['post_id'] );
            }
            elseif( isset( $_POST ) && count( $_POST ) ) {
                $calling_id = $post->post_parent;
            }
            
            $calling_post = get_post( $calling_id );
            
            if( is_null( $calling_post ) || $calling_post->post_type != $this->post_type ) {
                return $form_fields;
            }
            
            $nonce_field = wp_create_nonce( $this->nonce.$calling_id );

            $output = sprintf(
                    '<a href="#" id="%1$s-featuredimage" onclick="kdMuFeaImgSet( %3$s, \'%1$s\', \'%2$s\', \'%6$s\' ); return false;">%5$s</a>',
                    $this->id,
                    $this->post_type,
                    $post->ID,
                    $this->labels['name'],
                    $this->labels['use'],
                    $nonce_field
            );
            
            $form_fields['MuFeaImg-'.$this->id.'-'.$this->post_type] = array(
                'label' => $this->labels['name'],
                'input' => 'html',
                'html'  => $output
            );
            
            return $form_fields;            
        }
        
        /**
         * Ajax function: set and delete featured image
         * 
         * @global int $post_ID 
         * @return void
         */
        public function kd_ajax_set_image() {
            global $post_ID;
            
            $post_ID = intval( $_POST['post_id'] );
            
            if( !current_user_can( 'edit_post', $post_ID ) ) {
                die( '-1' );
            }
            
            $thumb_id = intval( $_POST['thumbnail_id'] );
            
            check_ajax_referer( $this->nonce.$post_ID );
            
            if( $thumb_id == '-1' ) {
                delete_post_meta( $post_ID, $this->post_meta_key );
                
                die( $this->kd_meta_box_output( NULL ) );
            }
            
            if( $thumb_id && get_post( $thumb_id) ) {
                $thumb_html = wp_get_attachment_image( $thumb_id, 'thumbnail' );
                
                if( !empty( $thumb_html ) ) {
                    update_post_meta( $post_ID, $this->post_meta_key, $thumb_id );
                    
                    die( $this->kd_meta_box_output( $thumb_id ) );
                }
            }
            
            die( '0' );
            
        }
        
        /**
         * Delete custom featured image if attachmet is deleted
         * 
         * @global object $wpdb
         * @param int $post_id 
         * @return void
         */
        public function kd_delete_attachment( $post_id ) {
            global $wpdb;

            $wpdb->query( 
                    $wpdb->prepare( 
                            "DELETE FROM $wpdb->postmeta WHERE meta_key = '%s' AND meta_value = %d", 
                            $this->post_meta_key, 
                            $post_id 
                    )
            );
        }
        
        /**
         * Retrieve the id of the featured image
         * 
         * @global object $post
         * @param string $image_id
         * @param string $post_type
         * @param int $post_id
         * @return int 
         */
        public static function get_featured_image_id( $image_id, $post_type, $post_id = NULL) {
            global $post;
            
            if( is_null( $post_id ) ) {
                $post_id = get_the_ID();
            }
            
            return get_post_meta( $post_id, "kd_{$image_id}_{$post_type}_id", true);
        }

        /**
         * Return the featured image url
         * 
         * @param string $image_id
         * @param string $post_type
         * @param int $post_id
         * @return string 
         */
        public static function get_featured_image_url( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
            $id = self::get_featured_image_id( $image_id, $post_type, $post_id);
            
            if( $size != 'full' ) {
            	$url = wp_get_attachment_image_src( $id, $size );
            	$url = $url[0];
            }
            else {
            	$url = wp_get_attachment_url( $id );
            }

            return $url;
        }
        
        /**
         * Return the featured image html output
         * 
         * @param string $image_id
         * @param string $post_type
         * @param string $size
         * @param int $post_id
         * @return string
         */
        public static function get_the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
            $id = self::get_featured_image_id( $image_id, $post_type, $post_id);

            $output = '';
            
            if( $id ) {
                $output = wp_get_attachment_image(
                        $id,
                        $size,
                        false
                );
            }
            
            return $output;
        }
        
        /**
         * Output the featured image html output
         * 
         * @param string $image_id
         * @param string $post_type
         * @param string $size
         * @param int $post_id 
         * @return void
         */
        public static function the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
            echo self::get_the_featured_image( $image_id, $post_type, $size, $post_id );
        }
    }
}

function kd_mfi_get_featured_image_id( $image_id, $post_type, $post_id = NULL ) {
    return kdMultipleFeaturedImages::get_featured_image_id( $image_id, $post_type, $post_id );
}

function kd_mfi_get_featured_image_url( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
    return kdMultipleFeaturedImages::get_featured_image_url( $image_id, $post_type, $size, $post_id );
}

function kd_mfi_get_the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
    return kdMultipleFeaturedImages::get_the_featured_image( $image_id, $post_type, $size, $post_id );
}

function kd_mfi_the_featured_image( $image_id, $post_type, $size = 'full', $post_id = NULL ) {
    return kdMultipleFeaturedImages::the_featured_image( $image_id, $post_type, $size, $post_id );
}

?>

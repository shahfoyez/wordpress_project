<?php
/*
Plugin Name: WPLMS Gift Course
Plugin URI: http://www.Vibethemes.com
Description: Gift Courses within WPLMS framework to users.
Version: 1.0
Author: VibeThemes (H.K)
Author URI: http://www.vibethemes.com
Text Domain: wplms-gift
*/
/*
Copyright 2017  VibeThemes  (email : vibethemes@gmail.com)
*/

include_once 'classes/class.config.php';
include_once 'classes/class.updater.php';
include_once 'classes/class.gift_course.php';
include_once 'classes/class.actions.php';
include_once 'classes/class.filters.php';

// Add text domain
add_action('plugins_loaded','wplms_gift_translations');
function wplms_gift_translations(){
    $locale = apply_filters("plugin_locale", get_locale(), 'wplms-gift');
    $lang_dir = dirname( __FILE__ ) . '/languages/';
    $mofile        = sprintf( '%1$s-%2$s.mo', 'wplms-gift', $locale );
    $mofile_local  = $lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;

    if ( file_exists( $mofile_global ) ) {
        load_textdomain( 'wplms-gift', $mofile_global );
    } else {
        load_textdomain( 'wplms-gift', $mofile_local );
    }  
}

if(class_exists('WPLMS_Gift_Course_Class'))
{	
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WPLMS_Gift_Course_Class', 'activate'));
    register_deactivation_hook(__FILE__, array('WPLMS_Gift_Course_Class', 'deactivate'));

    // instantiate the plugin class
 	$init=WPLMS_Gift_Course_Class::instance_gift_class();
}

// Enqueue script
add_action('wp_enqueue_scripts','wplms_gift_course_custom_cssjs');
function wplms_gift_course_custom_cssjs(){

    global $post;
    if(isset($post) && $post->post_type == 'course'){
        wp_enqueue_script( 'wplms-gift-course-js', plugins_url( 'js/wplms_gift_course.js' , __FILE__ ));
        //Add translation for js
        $translation_array = array( 
            'email_missing' => _x( 'Please enter the valid email IDs.','displayed to user when gift course is clicked.','wplms-gift' ),
            'gift_button_label' => _x( 'Send As Gift','displayed to user when gift variation popup is opened','wplms-gift' ),
        );

        if(is_user_logged_in()){
            $current_user = wp_get_current_user();
            $translation_array['user_email'] = $current_user->user_email;
        }
        wp_localize_script( 'wplms-gift-course-js', 'wplms_gift_course_js', $translation_array );
    }
}



function Wplms_Gift_Course_Plugin_updater() {
    $license_key = trim( get_option( 'wplms_gift_course_license_key' ) );
    $edd_updater = new Wplms_Gift_Course_Plugin_Updater( 'http://vibethemes.com', __FILE__, array(
            'version'   => '1.1',               
            'license'   => $license_key,        
            'item_name' => 'WPLMS GIFT COURSE',    
            'author'    => 'VibeThemes' 
        )
    );
}
add_action( 'admin_init', 'Wplms_Gift_Course_Plugin_updater', 0 );
<?php
/**
 *
 * @class       Wplms_Gift_Course_Filters
 * @author      VibeThemes (H.K.Latiyan)
 * @category    Admin
 * @package     WPLMS-Gift-Course/classes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wplms_Gift_Course_Filters{

	public static $instance;
	
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Wplms_Gift_Course_Filters();
        return self::$instance;
    }

	private function __construct(){

        //Add Gift data in woocommerce item meta and display it on cart page
        
		add_filter('woocommerce_add_cart_item',array($this,'add_gift_data'),10,2);
		add_filter('woocommerce_get_cart_item_from_session',array($this,'get_cart_items_from_session'), 10, 3 );
        add_filter('woocommerce_cart_item_name',array($this,'display_gift_in_cart'),10,2);

        //LMS Setting for gift course
        add_filter('lms_general_settings',array($this,'wplms_gift_course_restriction_switch'));

        //Restrict user enrollement for gift course
        add_filter('bp_course_order_complete_item_subscribe_user',array($this,'gift_course_restrict_item'),10,2);

        //Thankyou page for gift courses
        add_filter('wplms_course_purchased',array($this,'check_wplms_gift_course_purchased'),10,5);

        add_filter('woocommerce_order_items_meta_display',array($this,'beautify_gift_labels_in_thankyou_page'));

        //Change course students in case of max seats for gifts
        add_filter('bp_course_count_students_pursuing',array($this,'modify_students_pursuing_for_gifts'),10,2);
	} // END public function __construct


    function beautify_gift_labels_in_thankyou_page($output){
        $output = str_replace('_', ' ', $output);
        return $output;
    }

    function gift_course_restrict_item($flag , $item_id){
        // Set flag for gift course
        $gc_actions = Wplms_Gift_Course_Actions::init();
        if(!empty($gc_actions->restrict_item_ids)){
            if(in_array($item_id,$gc_actions->restrict_item_ids)){
                return false;
            }
        }
        return $flag;
    }

    function check_wplms_gift_course_purchased($html,$course_id,$item_id,$item,$order){

        //Get gift email from item_meta
        $gift_email = wc_get_order_item_meta($item_id,'gift_email',true);

        if(empty($gift_email)){
            return $html;
        }

        //Check order status
        if($order->status == 'completed' || $order->status == 'complete'){
          $ostatus = sprintf(__('Gifted to %s','wplms-gift'),$gift_email);
        }else if($order->status == 'pending'){
          do_action('wplms_force_woocommerce_order_complete',$order);
          $ostatus = __('WAITING FOR ORDER CONFIRMATION TO GIFT COURSE','wplms-gift');
        }else{
          $ostatus = __('WAITING FOR ADMIN APPROVAL','wplms-gift');
        }
        
        //Modify Thank you page HTML for gifted course
        $html = '<li>
                <a href="'.get_permalink($course_id).'" class="course_name gift_course">'.get_post_field('post_title',$course_id).'</a>
                <span style="padding-left:10px;">
                '.$ostatus.'</span>
                </li>';
        echo '<style>.gift_course:before {font-family:\'fontawesome\';content:"\f06b";margin-right:5px;font-size:16px;}.variation-gift.from{margin-top:20px !important;position:relative;}
.variation-gift{font-size:13px;padding:0;margin:-1px 0 !important;background:#eee;padding:10px !important;border:none !important;font-weight:400;text-transform:uppercase;}
dt.variation-gift{width:20%;min-width:200px;}dd.variation-gift{width:80%;}
dd.variation-gift.from:after{content:"\f06b";font-family:fontawesome;font-size:120px;position:absolute;right:20px;top:0px;line-height:1;opacity:0.2}</style>';

        return $html;
    }

	function add_gift_data($cart_item_data,$cart_item_key){

        // Add Gift Course Data in cart
        if ( isset($_REQUEST['gift_email']) && isset($_REQUEST['gift_from']) ){

            $gift_from = $_REQUEST['gift_from'];
            $gift_email = $_REQUEST['gift_email'];
            $gift_message = $_REQUEST['gift_message'];

            // Validate Emails
            preg_match("/^([a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}$)/", $gift_from, $gift_from_check);
            preg_match("/^([a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}$)/", $gift_email, $gift_email_check);

            $non_giftable = apply_filters('wplms_gift_course_non_giftable',0);
            if(empty($gift_from_check) || empty($gift_email_check) || $non_giftable){

                wc_add_notice(__('The email adress you entered is not a valid email, Therefore this course cannot be gifted','wplms-woo'),'error');
                echo '<style>.woocommerce .woocommerce-message {display:none;}</style>';

                //Remove product from cart item data
                $this->key = $cart_item_key;
                add_action( 'woocommerce_after_calculate_totals',array($this,'remove_product_from_cart'));
                
                return $cart_item_data;
            }

            //Check gift user's activity if she already accepted this gift before
            $user_id = email_exists($gift_email);
            if(!empty($user_id)){

                $courses = get_post_meta($cart_item_data['product_id'],'vibe_courses',true);
                if(!empty($courses)){

                    global $wpdb,$bp;
                    foreach($courses as $course_id){

                        //Check if user is already enrolled
                        $check = get_user_meta($user_id, $course_id, true);
                        if(isset($check) && $check && is_numeric($check)){
                            
                            wc_add_notice(__('The user is already enrolled in this course, Therefore this course cannot be gifted','wplms-woo'),'error');
                            echo '<style>.woocommerce .woocommerce-message {display:none;}</style>';

                            //Remove product from cart item data
                            $this->key = $cart_item_key;
                            add_action( 'woocommerce_after_calculate_totals',array($this,'remove_product_from_cart'));
                            
                            return $cart_item_data;
                        }

                        //Check if user has already accepted the gift
                        $activity = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$bp->activity->table_name} WHERE user_id = %d AND item_id = %d AND type = %s", $user_id, $course_id, 'wplms_gift_accepted' ) );

                        if(!empty($activity)){
                           
                           wc_add_notice(__('You have already gifted this course to the user, Therefore this course cannot be gifted','wplms-woo'),'error');
                            echo '<style>.woocommerce .woocommerce-message {display:none;}</style>';

                            //Remove product from cart item data
                            $this->key = $cart_item_key;
                            add_action( 'woocommerce_after_calculate_totals',array($this,'remove_product_from_cart'));
                            
                            return $cart_item_data;
                        }
                    }
                }
            }

            //Add gift data in item data
            $new_value = array(
                'gift_from' => $gift_from,
                'gift_email' => $gift_email,
                'gift_message' => $gift_message
                );

            return array_merge($cart_item_data,$new_value);
        }

        return $cart_item_data;
    }

    function remove_product_from_cart(){
        WC()->cart->remove_cart_item( $this->key );
    }

    function get_cart_items_from_session($item,$values,$key){

        // Get the gift data from the woocommerce session
        /*global $woocommerce;
        $woocommerce->cart->remove_cart_item( $key );*/

        if (array_key_exists( 'gift_from', $values ) ){
            $item['gift_from'] = $values['gift_from'];
        }
        if (array_key_exists( 'gift_email', $values ) ){
            $item['gift_email'] = $values['gift_email'];
        }
        if (array_key_exists( 'gift_message', $values ) ){
            $item['gift_message'] = $values['gift_message'];
        }

        return $item;
    }

    function display_gift_in_cart($product_name, $cart_item_key ){

        // Display gift email in cart
        if(isset($cart_item_key['gift_email'])){

            echo '<span class="display_gift_email" style="display:block;">';
            echo $cart_item_key['gift_email'];
            echo '</span>';
            echo '<style>.display_gift_email:before {font-family:\'fontawesome\';content:"\f06b";margin-right:5px;font-size:16px;}</style>';
        }

        return $product_name;
    }

    function wplms_gift_course_restriction_switch($settings){
        
        // Create LMS settings switches
        $settings[] = array(
            'label'=>__('WPLMS Gift Course Settings','wplms-gift' ),
            'type'=> 'heading',
        );
        $settings[] = array(
                'label' => __('Enable non logged in users to send gift', 'wplms-batches'),
                'name' => 'enable_gift_button_for_non_loggedin_users',
                'desc' => __('Enabling this setting will allow non logged in users to send gifts.', 'wplms-gift'),
                'type' => 'checkbox',
            );
        $settings[] = array(
                'label' => __('Send free courses as gift', 'wplms-batches'),
                'name' => 'enable_gift_button_for_free_courses',
                'desc' => __('Enabling this setting will allow users to gift free courses also.', 'wplms-gift'),
                'type' => 'checkbox',
            );

        return $settings;
    }

    function modify_students_pursuing_for_gifts($number,$course_id){

        //Get total number of gifts present in the course
        $gift_tokens = get_post_meta($course_id,'wplms_gift_tokens',true);
        if(!empty($gift_tokens)){
            //Get total number of students pursuing course
            global $wpdb;
            $number = $wpdb->get_var( $wpdb->prepare("select count(user_id) as number from {$wpdb->usermeta} where meta_key = %s",'course_status'.$course_id));

            $gifts = count($gift_tokens);
            $number = $number + $gifts;
        }

        return $number;
    }

} // END class Wplms_Gift_Course_Filters

Wplms_Gift_Course_Filters::init();

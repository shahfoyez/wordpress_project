<?php
/**
 *
 * @class       Wplms_Gift_Course_Actions
 * @author      VibeThemes (H.K.Latiyan)
 * @category    Admin
 * @package     WPLMS-Gift-Course/classes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wplms_Gift_Course_Actions{

    public $restrict_item_ids = array();

	public static $instance;
	
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Wplms_Gift_Course_Actions();
        return self::$instance;
    }

	private function __construct(){

        //Add gift meta in order item meta
		add_action('woocommerce_add_order_item_meta',array($this,'add_gift_meta_to_order_item_meta'),1,2);
        //Send gift by email
  		add_action('woocommerce_order_status_completed',array($this,'send_gift_course_via_email'),5);

        //Remove gift meta if product is removed from cart
  		add_action('woocommerce_before_cart_item_quantity_zero',array($this,'remove_gift_meta_from_cart'),1,1);
        //Add users to course when they come through the gift email
        add_action('template_redirect',array($this,'add_gifted_course_to_user'),1);

        //Ajax call for free courses.
        add_action('wp_ajax_nopriv_send_free_gift_email',array($this,'send_free_gift_email'));
        add_action('wp_ajax_send_free_gift_email',array($this,'send_free_gift_email'));

        //Handle order cancelled or refunded
        add_action('woocommerce_order_status_cancelled',array($this,'gift_course_disable_access'),10,1);
        add_action('woocommerce_order_status_refunded',array($this,'gift_course_disable_access'),10,1);

        //Show gifts in profile dropdown menu
        add_action('wplms_logged_in_top_menu',array($this,'gift_course_loggedin_menu'));

        add_action('wp_footer',array($this,'footer_css'));

	} // END public function __construct

	function add_gift_meta_to_order_item_meta($item_id, $values){

        $gift_from = $values['gift_from'];
        $gift_email = $values['gift_email'];
        $gift_message = $values['gift_message'];

        //Return if email is empty
        if(empty($gift_email) || empty($gift_from)){
            return;
        }

        // Adding gift data to order item meta
        wc_add_order_item_meta($item_id,'gift_from',$gift_from);
        wc_add_order_item_meta($item_id,'gift_email',$gift_email);

        if(!empty($gift_message)){
            wc_add_order_item_meta($item_id,'gift_message',$gift_message);  
        }
        
  	}

  	function send_gift_course_via_email($order_id){	
		
        $order = new WC_Order( $order_id );
        $items = $order->get_items();

        foreach($items as $item_id => $item){

    		// Get data from order item meta
            $gift_email = wc_get_order_item_meta($item_id,'gift_email',true);
            if(empty($gift_email))
                return;
            
            $courses = get_post_meta($item['product_id'],'vibe_courses',true);
            if(!empty($courses)){
                // Set flag for gift course
                $this->restrict_item_ids[] = $item_id;

                $gift_from = wc_get_order_item_meta($item_id,'gift_from',true);
                $gift_message = wc_get_order_item_meta($item_id,'gift_message',true);
                
                //Check if user exists
                $check = email_exists($gift_from);
                if($check){
                    $user = get_user_by('email',$gift_from);
                    $gift_sender = $user->display_name;
                }else{
                    $gift_sender = $gift_from;
                }
                
                foreach($courses as $course_id){

                    $course_title = get_the_title($course_id);

                    $gift_tokens = get_post_meta($course_id,'wplms_gift_tokens',true);
                    if(empty($gift_tokens)){
                        $gift_tokens = array($gift_email);
                    }else{
                        $gift_tokens[] = $gift_email;
                    }
                    update_post_meta($course_id,'wplms_gift_tokens',$gift_tokens);

                    //Record gift sent activity
                    $activity_id = $this->record_gift_sent_activity($course_id,$gift_email,$gift_from);

                    // Send email
                    bp_course_wp_mail($gift_email,$subject,$gift_message,array('action'=>'wplms_gift_mail','tokens'=>array('gift_sender.name'=>$gift_sender,'gift_course.name'=>$course_title,'gift_course.giftlink'=>'<a href="'.get_permalink($course_id).'?gift='.$activity_id.'&email='.$gift_email.'&from='.$gift_from.'">'.$course_title.'</a>')));

                    do_action('wplms_gift_sent_via_email',$course_id,$gift_email,$gift_from);
                }

            }//End gift email check in Item loop
        }//end item;
        return;
	}

    function add_gifted_course_to_user(){

        global $wpdb,$bp,$post;
        if($post->post_type != 'course'  || !isset($_GET['gift'])){
            return;
        }else{
            if($_GET['gift'] == 'accepted'){
                add_action('wplms_course_before_front_main',function(){
                     echo '<div id="message" class="notice"><p>'._x('Congratulations ! you\'ve successfully accepted the gift and you\'re now enrolled in the course.','','wplms-gift').'</p></div>';
                });
            }
        } 

        if(!isset($_GET['email']) || !isset($_GET['from'])){
            return;
        }

        //Define variables
        $course_id = $post->ID;
        $course_title = get_the_title($course_id);
        $activity_id = $_GET['gift'];
        $user_email = $_GET['email'];
        $from_email = $_GET['from'];

        //Check gift
        $gift = bp_activity_get_meta($activity_id,'wplms_gift_sent',true);
        $gift_tokens = get_post_meta($course_id,'wplms_gift_tokens',true);
        $pos = array_search( $user_email, $gift_tokens );

        //Check security
        if(empty($gift_tokens) || !$pos || $gift['course_id'] != $course_id || $gift['gift_email'] != $user_email || $gift['gift_from'] != $from_email){

            _e('Security check Failed. Contact Administrator.','wplms-gift');
            die();
        }

        //Delete the gift from the tokens
        unset($gift_tokens[$pos]);
        update_post_meta($course_id,'wplms_gift_tokens',$gift_tokens);

        // Check if user already exists
        $user_id = email_exists($user_email);

        if(empty($user_id)){
            $password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            $user_id = wp_create_user($user_email,$password,$user_email);
        }
        
        // Add user to course
        $product_id = get_post_meta($course_id,'vibe_product',true);
        if(!empty($product_id)){

            $subscribed = get_post_meta($product_id,'vibe_subscription',true);
            if(vibe_validate($subscribed) ){

                $duration = get_post_meta($product_id,'vibe_duration',true);
                $product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400,$product_id);
                $total_duration = $duration * $product_duration_parameter;
                bp_course_add_user_to_course($user_id,$course_id,$total_duration);

            }else{
                bp_course_add_user_to_course($user_id,$course_id);
            }
        }else{
            bp_course_add_user_to_course($user_id,$course_id);
        }


        //Send email to gift sender
        bp_course_wp_mail($from_email,$subject,$message,array('action'=>'wplms_gift_received_mail','tokens'=>array('gift_receiver.email'=>$user_email,'gift_receiver.coursename'=>'<a href="'.get_permalink($course_id).'">'.$course_title.'</a>')));
        
        //Record gift accepted activity
        $this->record_gift_accepted_activity($course_id,$from_email,$user_id);

        do_action('wplms_gift_accepted_by_user',$course_id,$user_email,$from_email);

        // Login user
        $user = get_user_by('email', $user_email );
        $user = apply_filters( 'authenticate', $user, '', '' );
        if(!is_wp_error($user)){
            wp_set_current_user( $user->ID, $user->user_login );
            wp_set_auth_cookie( $user->ID,$remember );
            do_action( 'wp_login', $user->user_login );
            //Reload page
            wp_redirect(get_permalink($course_id).'?gift="accepted"');
        }
    }

    function remove_gift_meta_from_cart($cart_item_key){

        global $woocommerce;
        $cart = $woocommerce->cart->get_cart();
        // Remove gift data from the cart if product is removed from cart
        foreach( $cart as $key => $values){
            if ( $values['gift_from'] == $cart_item_key ){
                unset( $woocommerce->cart->cart_contents[ $key ] );
            }
        	if ( $values['gift_email'] == $cart_item_key ){
            	unset( $woocommerce->cart->cart_contents[ $key ] );
        	}
        	if ( $values['gift_message'] == $cart_item_key ){
            	unset( $woocommerce->cart->cart_contents[ $key ] );
        	}
        }
    }

    function send_free_gift_email(){

        if(!isset($_POST['course']) || !isset($_POST['gift_from']) || !isset($_POST['gift_email'])){
            _e('Security check Failed. Contact Administrator.','wplms-gift');
             die();
        }

        // Define variables
        $course_id = $_POST['course'];
        $gift_from = $_POST['gift_from'];
        $gift_email = $_POST['gift_email'];
        if($_POST['gift_message'] != 'undefined'){
            $gift_message = $_POST['gift_message'];
        }

        //Check for Free course
        $free = get_post_meta($course_id,'vibe_course_free',true);
        if(function_exists('vibe_validate') && !vibe_validate($free)){
            _e('Security check Failed. Course is not free.','wplms-gift');
             die();
        }
        
        //Check is user exists
        $check = email_exists($gift_from);
        if($check){
            $user = get_user_by('email',$gift_from);
            $gift_sender = $user->display_name;
        }else{
            $gift_sender = $gift_from;
        }
        
        $course_title = get_the_title($course_id);

        $gift_tokens = get_post_meta($course_id,'wplms_gift_tokens',true);
        if(empty($gift_tokens)){
            $gift_tokens = array($gift_email);
        }else{
            $gift_tokens[] = $gift_email;
        }
        update_post_meta($course_id,'wplms_gift_tokens',$gift_tokens);

        $settings    = bp_email_get_appearance_settings();

        //Record gift sent activity
        $activity_id = $this->record_gift_sent_activity($course_id,$gift_email,$gift_from);

        // Send email
        bp_course_wp_mail($gift_email,$subject,$gift_message,array('action'=>'wplms_gift_mail','tokens'=>array('gift_sender.name'=>$gift_sender,'gift_course.name'=>$course_title,'gift_course.giftlink'=>'<a href="'.get_permalink($course_id).'?gift='.$activity_id.'&email='.$gift_email.'&from='.$gift_from.'" style="padding:15px 30px;border-radius:10px;background:'.$settings['highlight_color'].';color:#fff;">'.$course_title.'</a>')));

        do_action('wplms_gift_sent_via_email',$course_id,$gift_email,$gift_from);


        // Create json
        $json_array = array(
            'success_message'=> __('Congratulations Free Gift Sent','wplms-gift')
             );
        //Send json
        print_r(json_encode($json_array));

        die();
    }

    //Order cancelled or refunded
    function gift_course_disable_access($order_id){

        $order = new WC_Order( $order_id );
        $items = $order->get_items();
        $user_id = $order->user_id;

        foreach($items as $item_id => $item){

            $product_id = $item['product_id'];
            $courses = get_post_meta($product_id,'vibe_courses',true);
            $gift_email = wc_get_order_item_meta($item_id,'gift_email',true);

            if(empty($gift_email))
                return;

            // Check if user already exists
            $user_id = email_exists($gift_email);

            if(isset($courses) && is_array($courses)){
                foreach($courses as $course){

                    //Delete the gift from the tokens
                    $gift_tokens = get_post_meta($course,'wplms_gift_tokens',true);
                    $pos = array_search( $gift_email, $gift_tokens );
                    unset($gift_tokens[$pos]);
                    update_post_meta($course,'wplms_gift_tokens',$gift_tokens);

                    if(!empty($user_id)){

                        $check = bp_course_is_member($course,$user_id);
                        if($check){
                            bp_course_remove_user_from_course($user_id,$course);
                            
                            do_action('bp_crouse_gift_removed_course_user_unsubscribed',$user_id,$course);
                        }
                    }

                    $this->record_gift_refund_activity($course,$gift_email);
                }
            }
        } 
    }

    function gift_course_loggedin_menu($menu){

        //Add gift link in user profile menu
        $menu['gifts']=array(
                          'icon' => 'fa fa-gift',
                          'label' => __('Gifts','wplms-gift'),
                          'link' => bp_loggedin_user_domain().BP_WPLMS_GIFT_SLUG,
                          );
        return $menu;
    }

    function footer_css(){

        if(is_singular('course')){
            ?>
            <style>
            #gift_course_button.active:before {content:"\f00d";font-family:'fontawesome';margin-right:10px;}
            </style>
            <?php
        }
    }

    function record_gift_sent_activity($course_id,$gift_email,$gift_from){

        global $bp;

        if ( !function_exists( 'bp_activity_add' ) )
            return 0;

        $activity_id = $user_id = 0;

        $gift_user = email_exists($gift_from);
        if(is_numeric($gift_user)){
            $user_id = $gift_user;
        }else if(is_user_logged_in()){
            $user_id = $bp->loggedin_user->id;
        }

        
        if(!empty($user_id)){
            //Define args
            $args = array(
                'user_id' => $user_id,
                'action' => 'Gift Sent',
                'content' => '',
                'primary_link' => '',
                'component' => 'course',
                'type' => 'wplms_gift_sent',
                'item_id' => $course_id,
                'secondary_item_id' => false,
                'recorded_time' => gmdate( "Y-m-d H:i:s" ),
                'hide_sitewide' => true
            );

            //Add/insert activity
            $args = apply_filters('wplms_record_gift_sent_activity_args',$args);
            $activity_id = bp_activity_add($args);

            if ( function_exists( 'bp_activity_update_meta' ) ){
                $gift  = array('course_id' => $course_id, 'gift_email' => $gift_email, 'gift_from' => $gift_from);
                //Add gift in activity meta
                bp_activity_update_meta($activity_id,'wplms_gift_sent',$gift);
            }
        }
        
        return $activity_id;
    }

    function record_gift_accepted_activity($course_id,$from_email,$user_id){

        global $bp;

        if ( !function_exists( 'bp_activity_add' ) )
            return;
        //Define args
        $args = array(
            'user_id' => $user_id,
            'action' => 'Gift Accepted',
            'content' => '',
            'primary_link' => '',
            'component' => 'course',
            'type' => 'wplms_gift_accepted',
            'item_id' => $course_id,
            'secondary_item_id' => false,
            'recorded_time' => gmdate( "Y-m-d H:i:s" ),
            'hide_sitewide' => true
        );

        //Add/insert activity
        $args = apply_filters('wplms_record_gift_accepted_activity_args',$args);
        $activity_id = bp_activity_add($args);

        if ( function_exists( 'bp_activity_update_meta' ) ){
            //Add from email in activity meta
            bp_activity_update_meta($activity_id,'wplms_gift_accepted',$from_email);
        }
    }

    function record_gift_refund_activity($course_id,$gift_email){

        global $bp;

        if ( !function_exists( 'bp_activity_add' ) )
            return;
        //Define args
        $args = array(
            'user_id' => $bp->loggedin_user->id,
            'action' => 'Gift Refund',
            'content' => $gift_email,
            'primary_link' => '',
            'component' => 'course',
            'type' => 'wplms_gift_refund',
            'item_id' => $course_id,
            'secondary_item_id' => false,
            'recorded_time' => gmdate( "Y-m-d H:i:s" ),
            'hide_sitewide' => true
        );

        //Add/insert activity
        $args = apply_filters('wplms_record_gift_refund_activity_args',$args);
        bp_activity_add($args);
    }

} // END class Wplms_Gift_Course_Actions

Wplms_Gift_Course_Actions::init();

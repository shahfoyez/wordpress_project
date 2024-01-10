<?php
/**
 *
 * @class       WPLMS_Gift_Course_Class
 * @author      VibeThemes (H.K.Latiyan)
 * @category    Admin
 * @package     WPLMS-Gift-Course/classes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPLMS_Gift_Course_Class{  
	public static $instance;
	public static function instance_gift_class(){

	    if ( is_null( self::$instance ) )
	        self::$instance = new WPLMS_Gift_Course_Class();
	    return self::$instance;
	}

	private function __construct(){

		//Create gift course email
		add_filter('bp_course_all_mails',array($this,'add_gift_course_email'));
		//Add gift button in course details widget.
		add_filter('wplms_course_details_widget',array($this,'display_gift_course_button'),99);

		//Add gift course in profile menu
		add_action( 'bp_setup_nav', array($this,'add_wplms_gift'), 100 );

	} // END public function __construct

	public function activate(){

	}

	public function deactivate(){
		
	}
	        
	function display_gift_course_button($course_details){

		// Check LMS setting for non logged in users
		if(class_exists('WPLMS_tips')){
			$tips = WPLMS_tips::init();
			if(isset($tips) && isset($tips->settings) && !isset($tips->settings['enable_gift_button_for_non_loggedin_users'])){
				if(!is_user_logged_in()){
					return $course_details;
				}
			}
		}
		// Check if product is connected to the course
		global $post;
		if($post->post_type != 'course'){
            return $course_details;
        }

		$course_id = $post->ID;
		$product_id = get_post_meta($course_id,'vibe_product',true);
		if(empty($product_id) || !is_numeric($product_id)){

			// Check if course is free
			$free = get_post_meta($course_id,'vibe_course_free',true);
			if(function_exists('vibe_validate') && vibe_validate($free)){
				
				// Check LMS settings for free courses
				if(class_exists('WPLMS_tips')){
					$tips = WPLMS_tips::init();
					if(isset($tips) && isset($tips->settings) && !isset($tips->settings['enable_gift_button_for_free_courses'])){
						
						return $course_details;
					}
				}
				// Display Gift Course button for free course
				$course_details['gift_course'] = '<input type="hidden" id="gift_course_id" value="'.$course_id.'"/><a id="gift_course_button"  class="full button" data-from="'.__('Enter your email','wplms-gift').'" data-to="'.__('Enter gift email','wplms-gift').'" data-message="'.__('Enter Recipient Name & Message','wplms-gift').'" data-free="1" data-button="'.__('Send as Gift','wplms-gift').'">'.__('Gift this course','wplms-gift').'</a>';

				return $course_details;
			}

			return $course_details;
		}

		global $woocommerce;
    	$cart_url = $woocommerce->cart->get_cart_url();
		echo '<div id="move_field_c"><input type="hidden" name="add-to-cart" value="'.$product_id.'" /></div>';

		// Check if product is variable
		$product = wc_get_product($product_id);
		if( empty($product) ){
			return $course_details;
		}

		if( $product->is_type( 'variable' )){
			// Display Gift Course button for variable product
			$course_details['gift_course'] = '<input type="hidden" id="gift_course_cart_url" value="'.$cart_url.'"/>
		<a id="gift_course_button"  class="full button" data-from="'.__('Enter your email','wplms-gift').'" data-to="'.__('Enter gift email','wplms-gift').'" data-message="'.__('Enter Recipient Name & Message','wplms-gift').'" data-variation="'.__('Select Variation','wplms-gift').'" data-button="'.__('Send as Gift','wplms-gift').'">'.__('Gift this course','wplms-gift').'</a>';

			return $course_details;
		}

		// Display Gift Course button for normal product
		$course_details['gift_course'] = '<input type="hidden" id="gift_course_cart_url" value="'.$cart_url.'"/>
		<a id="gift_course_button"  class="full button" data-from="'.__('Enter your email','wplms-gift').'" data-to="'.__('Enter gift email','wplms-gift').'" data-message="'.__('Enter Recipient Name & Message','wplms-gift').'" data-button="'.__('Send as Gift','wplms-gift').'">'.__('Gift this course','wplms-gift').'</a>';

		return $course_details;
	}

	function add_gift_course_email($bp_course_mails){

		// Gift Course email and tokens added
		$bp_course_mails['wplms_gift_mail'] = array(
            'description'=> __('This course is a gift ','wplms-gift'),
            'subject' =>  sprintf(__('A gift from %s ','wplms-gift'),'{{gift_sender.name}}'),
            'message' =>  sprintf(__('The user %s has sent you a gift. To redeem this gift, click on the link below :  %s','wplms-gift'),'{{gift_course.name}}','{{{gift_course.giftlink}}}')

        );

		$bp_course_mails['wplms_gift_received_mail'] = array(
			'description'=> __('The gifted course has been recieved by the user ','wplms-gift'),
            'subject' =>  sprintf(__('Gift course recevied by %s ','wplms-gift'),'{{gift_receiver.email}}'),
            'message' =>  sprintf(__('The %s course gifted to %s has been received','wplms-gift'),'{{gift_receiver.coursename}}','{{{gift_receiver.email}}}')
		);

		return $bp_course_mails;
	}

	function add_wplms_gift(){

		global $bp;
		//Add gift tab in profile menu
	    bp_core_new_nav_item( array( 
	        'name' => __('Gifts','wplms-gift'),
	        'slug' => BP_WPLMS_GIFT_SLUG, 
	        'item_css_id' => 'gift',
	        'screen_function' => array($this,'show_screen'),
	        'default_subnav_slug' => 'home', 
	        'position' => 50
	    ) );

	    //Add gifts sent submenu in gift tab
	    bp_core_new_subnav_item( array(
				'name' 		  => __( 'Gifts Sent', 'wplms-gift' ),
				'slug' 		  => 'home',
				'parent_slug' => BP_WPLMS_GIFT_SLUG,
	        	'parent_url' => $bp->displayed_user->domain.BP_WPLMS_GIFT_SLUG.'/',
				'screen_function' => array($this,'show_gifts_sent'),
			) );

	    //Add gifts accepted submenu in gift tab
	    bp_core_new_subnav_item( array(
				'name' 		  => __( 'Gifts Accepted', 'wplms-gift' ),
				'slug' 		  => BP_WPLMS_GIFT_ACCEPTED_SLUG,
				'parent_slug' => BP_WPLMS_GIFT_SLUG,
	        	'parent_url' => $bp->displayed_user->domain.BP_WPLMS_GIFT_SLUG.'/',
				'screen_function' => array($this,'show_gifts_accepted'),
			) );
	}

	function show_gifts_sent(){

		//Show gifts sent content
		add_action( 'bp_template_content', array($this,'gifts_sent_content'));
    	bp_core_load_template( 'members/single/plugins');
	}

	function show_gifts_accepted(){

		//Show gifts accepted content
		add_action( 'bp_template_content', array($this,'gifts_accepted_content'));
    	bp_core_load_template( 'members/single/plugins');
	}

	function gifts_sent_content(){
		
		global $wpdb, $bp;
		$user_id = get_current_user_id();

		// total no of gifts to be displayed on per page
		$limit = vibe_get_option('loop_number');
        if(!isset($limit)) $limit = 5;
	    $paged = (get_query_var('p')) ? get_query_var('p') : 1;
	    
	    if( $paged == 1 ){
	      $offset = 0;  
	    }else{
	       $offset = ( $paged - 1 ) * $limit;
	    }

		//Get user's gift sent activity
		$activities = $wpdb->get_results( $wpdb->prepare( "
			SELECT SQL_CALC_FOUND_ROWS id,item_id 
			FROM {$bp->activity->table_name} 
			WHERE user_id = %d AND type = %s 
			LIMIT %d OFFSET %d", $user_id, 'wplms_gift_sent', $limit, $offset ) );

		//Check if activity is empty
		if(empty($activities)){
			echo '<div class="message">';
			_e('No Gifts Sent.','wplms-gift');
			echo '</div>';

			return;
		}else{
			$total_num = $wpdb->get_var('SELECT FOUND_ROWS();');
		}

		?>
		<!-- Create table for gifts sent -->
		<table class="gift_table">
			<tr>
				<th><?php echo __('Gift','wplms-gift'); ?></th>
				<th><?php echo __('Gift User','wplms-gift'); ?></th>
				<th><?php echo __('Status','wplms-gift'); ?></th>
			</tr>

			<?php
			foreach ($activities as $activity){

				//Get gift email for each gifts sent
				$gift = bp_activity_get_meta($activity->id,'wplms_gift_sent',true);

				//Check if meta exists
				if(!empty($gift)){
					//Add course name with its link in the table
					$course = '<a href="'.get_permalink($activity->item_id).'">'.get_the_title($activity->item_id).'</a>';

					//Check if user exists
					$user_id = email_exists($gift['gift_email']);
	                if(!empty($user_id)){
	                    $name = bp_core_get_userlink($user_id).'('.$gift['gift_email'].')';

	                    //Get user's accepted activity
	                    $check_status = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM {$bp->activity->table_name} WHERE user_id = %d AND item_id = %d AND type = %s", $user_id, $activity->item_id, 'wplms_gift_accepted' ) );

	                    //Show user's gift status
	                    if(empty($check_status)){
	                    	$status = '<span class="gift_pending">'.__('PENDING','wplms-gift').'</span>';
	                    }else{
	                    	$status = '<span class="gift_accepted">'.__('Accepted','wplms-gift').'</span>';
	                    }

	                }else{
	                    $name = $gift['gift_email'];
	                    $status = '<span class="gift_pending">'.__('PENDING','wplms-gift').'</span>';
	                }

					?>
					<tr>
						<td><?php echo $course; ?></td>
						<td><?php echo $name; ?></td>
						<td><?php echo $status; ?></td>
					</tr>
					<?php
				}
			}
			?>

		</table>

		<style>
			.gift_table {width:100%;margin-left:15px;}
			.gift_table tr th {text-align:initial;width: 200px;}
			.gift_table tr {line-height:3em;}
			.gift_table tr td a {text-decoration:none;color:#444;}
			.gift_table tr th:nth-child(3),.gift_table tr td:nth-child(3) {text-align:center;}
			.gift_table tr td span.gift_pending {padding-right:5px;}

			.gift_table tr>td:nth-child(3)>span.gift_accepted:before {
			    content: '\f110';
			    font-size: 15px;
			    color: #46ad23;
			    background: #46ad23;
			    border-radius: 50%;
			    padding: 0 0 0 6px;
			    margin-right: 5px;
			}
			.gift_table tr>td:nth-child(3)>span.gift_pending:before {
			    content: '\f110';
			    font-size: 15px;
			    color: #e1eade;
			    background: #e1eade;
			    border-radius: 50%;
			    padding: 0 0 0 6px;
			    margin-right: 5px;
			}
		</style>
		<?php

		//Pagination
        $total_pages = ceil($total_num/$limit);

        $query_string = $_SERVER['QUERY_STRING'];
		$base = $bp->displayed_user->domain.BP_WPLMS_GIFT_SLUG.'/?'.remove_query_arg('p', $query_string).'%_%';

		echo '<div class="pagination">';
		echo paginate_links(array(  
			'base' => $base,
			'format' => '&p=%#%',
			'current' => $paged,
			'total' => $total_pages,
			'prev_text' => __('&lsaquo; Previous','wplms-woo'),
			'next_text' => __('Next &rsaquo;','wplms-woo'),
		));
		echo '</div>';
	}

	function gifts_accepted_content(){
		
		global $wpdb, $bp;
		$user_id = get_current_user_id();

		// total no of gifts to be displayed on per page
		$limit = vibe_get_option('loop_number');
        if(!isset($limit)) $limit = 5;
	    $paged = (get_query_var('p')) ? get_query_var('p') : 1;
	    
	    if( $paged == 1 ){
	      $offset = 0;  
	    }else{
	       $offset = ( $paged - 1 ) * $limit;
	    }

		//Get user's gift accepted activity
		$activities = $wpdb->get_results( $wpdb->prepare( "
			SELECT SQL_CALC_FOUND_ROWS id,item_id 
			FROM {$bp->activity->table_name} 
			WHERE user_id = %d AND type = %s
			LIMIT %d OFFSET %d", $user_id, 'wplms_gift_accepted', $limit, $offset ) );

		//Check if activity is empty
		if(empty($activities)){
			echo '<div class="message">';
			_e('No Gifts Accepted.','wplms-gift');
			echo '</div>';

			return;
		}else{
			$total_num = $wpdb->get_var('SELECT FOUND_ROWS();');
		}

		?>
		<!-- Create table for gifts accepted -->
		<table class="gift_table">
			<tr>
				<th><?php echo __('Gift','wplms-gift'); ?></th>
				<th><?php echo __('Gift From','wplms-gift'); ?></th>
			</tr>

			<?php
			foreach ($activities as $activity){

				//Get user's email who sent the gift
				$email = bp_activity_get_meta($activity->id,'wplms_gift_accepted',true);

				if(!empty($email)){
					//Add course name with its link in the table
					$course = '<a href="'.get_permalink($activity->item_id).'">'.get_the_title($activity->item_id).'</a>';

					//Check if user exists
					$user_id = email_exists($email);
		            if(!empty($user_id)){
		                $name = bp_core_get_userlink($user_id).'('.$email.')';
		            }else{
		                $name = $email;
		            }

					?>
					<tr>
						<td><?php echo $course; ?></td>
						<td><?php echo $name; ?></td>
					</tr>
					<?php
				}
			}
			?>

		</table>
		<style>
			.gift_table {width:100%;margin-left:15px;}
			.gift_table tr th {text-align:initial;}
			.gift_table tr {line-height:3em;}
			.gift_table tr td a {text-decoration:none;color:#444;}
			.gift_table tr th:nth-child(2),.gift_table tr td:nth-child(2) {text-align:center;}
		</style>
		<?php

		//Pagination
        $total_pages = ceil($total_num/$limit);

        $query_string = $_SERVER['QUERY_STRING'];
		$base = $bp->displayed_user->domain.BP_WPLMS_GIFT_SLUG.'/'.BP_WPLMS_GIFT_ACCEPTED_SLUG.'/?'.remove_query_arg('p', $query_string).'%_%';

		echo '<div class="pagination">';
		echo paginate_links(array(  
			'base' => $base,
			'format' => '&p=%#%',
			'current' => $paged,
			'total' => $total_pages,
			'prev_text' => __('&lsaquo; Previous','wplms-woo'),
			'next_text' => __('Next &rsaquo;','wplms-woo'),
		));
		echo '</div>';
	}

} // END class WPLMS_Gift_Course_Class

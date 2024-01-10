<?php 
	function wplms_child_enqueue_styles() {
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
		// wp_enqueue_style( "singleCourse-css", get_theme_file_uri("/assets/css/singleCourse.css" ), null, "1.5" );
		wp_enqueue_style('all-course', get_theme_file_uri('/assets/css/all-course.css'), false, time(), 'all');
	} 
	add_action( 'wp_enqueue_scripts', 'wplms_child_enqueue_styles' );
	add_action( 'wp_enqueue_scripts', 'add_ajax_url' );

	function add_ajax_url() {
		wp_localize_script( 'jquery', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
	}
	
	function timer_function( $atts ){
		




		$args = array(
			'post_type' => 'activity',
			'post_status' => 'publish',
			'posts_per_page' => 10,
			'user_id' => 666, 
			'per_page' => 10,
		);
		
		$activities = bp_activity_get($args);
		// dd($activities);
		
		if (!empty($activities['activities'])) {
			foreach ($activities['activities'] as $activity) {
		
				$activity_id = $activity->id;
				$activity_content = $activity->content;
				// Retrieve additional activity data as needed
		
				$word_count = str_word_count(strip_tags($activity_content));
		
				if ($word_count > 20) {
					// Output or manipulate the data as desired
					echo 'Activity ID: ' . $activity_id . '<br>';
					echo 'Activity Content: ' . $activity_content . '<br>';
				}
			}
		} else {
			// No activities found
			echo 'No activities found.';
		}
		// Restore original query
		wp_reset_query();

	
		
		
		
		






		$id = $atts['id'];
		?>
		
		<div class="foy-countdown">
			<div class="foy-countdown-items"> 
				<div class="foy-days">
					<p class="foy-time <?php echo $id.'days'?>" id="<?php echo $id.'days'?>"></p>
					<p class="foy-time-label"> Days</p>
				</div>
				<div class="foy-divider foy-days-divider">
					<p class="foy-dot">:</p>
					<p class="foy-time-label"></p>
				</div>
				<div class="foy-hours">
					<p class="foy-time <?php echo $id.'hours'?>" id="<?php echo $id.'hours'?>"></p>
					<p class="foy-time-label"> Hours</p>
				</div>
				<div class="foy-divider">
					<p class="foy-dot">:</p>
					<p class="foy-time-label"></p>
				</div>
				<div class="foy-minutes">
					<p class="foy-time <?php echo $id.'mins'?>" id="<?php echo $id.'mins'?>"></p>
					<p class="foy-time-label"> Minutes</p>
				</div>
				<div class="foy-divider">
					<p class="foy-dot">:</p>
					<p class="foy-time-label"></p>
				</div>
				<div class="foy-seconds">
					<p class="foy-time <?php echo $id.'secs'?>" id="<?php echo $id.'secs'?>"></p>
					<p class="foy-time-label"> Seconds</p>
				</div>
			</div>
		</div>
		<?php	 
	}
	add_shortcode( 'timer-html', 'timer_function' );
	
	
	function timer_function1( $atts ){
		$interval = $atts['interval'];
		$id = $atts['id'];
		global $wpdb;
		date_default_timezone_set("Asia/Dhaka");
		// echo date_default_timezone_get();
		$current_date = date('Y-m-d H:i:s');
		$results = $wpdb->get_results("SELECT * FROM wp_timer WHERE id = $id");
		// $wpdb->insert('wp_timer', array('id' => 1, 'end_time' =>  )); 
		if($id!= "" && !$results){
			$ent = date('Y-m-d H:i:s', strtotime($current_date. ' + '.$interval.' hours'));
			$wpdb->insert('wp_timer', array('id' => $id, 'end_time' => $ent)); 
			echo "New timer has created";
		}else{
			foreach ($results as $time){
				$end_time =  $time->end_time;
			} 
			
			if ( $current_date <  $end_time ) { ?>
				<script>
				// Set the date we're counting down to
				var database_time = <?php echo json_encode($end_time, JSON_HEX_TAG); ?>;
				var id = <?php echo json_encode($id, JSON_HEX_TAG); ?>;
				var countDownDate = new Date(database_time).getTime();
				// function to change default timezone
				function changeTimezone(date, ianatz) {
					var invdate = new Date(date.toLocaleString('en-US', {
						timeZone: ianatz
					}));
					var diff = date.getTime() - invdate.getTime();
					return new Date(date.getTime() - diff);
				}
				// Update the count down every 1 second
				var x = setInterval(function() {
					// Get today's date and time
					// var now = new Date().getTime();
					var here = new Date();
					// change default timezone
					var now = changeTimezone(here, "Asia/Dhaka").getTime();
					console.log("Here"+here);
					console.log("Now"+changeTimezone(here, "Asia/Dhaka"));
	
	
					// Find the distance between now and the count down date
					var distance = countDownDate - now;
						
					// Time calculations for days, hours, minutes and seconds
					var days = Math.floor(distance / (1000 * 60 * 60 * 24));
					var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
					var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
					var seconds = Math.floor((distance % (1000 * 60)) / 1000);
						
					// Result is output to the specific element
					days = ("0" + days).slice(-2)
					var elems = document.getElementsByClassName(id+"days");
					for(var i = 0; i < elems.length; i++) {
						elems[i].innerHTML = days;
					}
					
					hours = ("0" + hours).slice(-2)
					var elems = document.getElementsByClassName(id+"hours");
					for(var i = 0; i < elems.length; i++) {
						elems[i].innerHTML = hours ;
					}
	
					minutes = ("0" + minutes).slice(-2)
					var elems = document.getElementsByClassName(id+"mins");
					for(var i = 0; i < elems.length; i++) {
						elems[i].innerHTML = minutes ;
					}
					
					seconds = ("0" + seconds).slice(-2)
					var elems = document.getElementsByClassName(id+"secs");
					for(var i = 0; i < elems.length; i++) {
						elems[i].innerHTML = seconds ;
					}
					
					// If the count down is over
					if (distance < 0) {
						clearInterval(x);
						var elems = document.getElementsByClassName(id+"days");
						for(var i = 0; i < elems.length; i++) {
							elems[i].innerHTML = '00';
						}
						var elems = document.getElementsByClassName(id+"hours");
						for(var i = 0; i < elems.length; i++) {
							elems[i].innerHTML = '00';
						}
						var elems = document.getElementsByClassName(id+"mins");
						for(var i = 0; i < elems.length; i++) {
							elems[i].innerHTML = '00';
						}
						var elems = document.getElementsByClassName(id+"secs");
						for(var i = 0; i < elems.length; i++) {
							elems[i].innerHTML = '00';
						}
					}
				}, 1000);
				</script>
			<?php
			}else{
				$date = $end_time;
				$new_time = date('Y-m-d H:i:s', strtotime($date. ' + '.$interval.' hours'));
				$execute = $wpdb->query
				("
				UPDATE `wp_timer` 
				SET `end_time` = '$new_time'
				WHERE `wp_timer`.`id` = $id
				");
			}
		} 
	}
	add_shortcode( 'foy-timer', 'timer_function1' );

// Certificate Courses
function certificate_data_fetch(){
	$cat_id = $_REQUEST['cat_id'];
	return $cat_id;
	global $wpdb;
	$courses = $wpdb->get_results(
		$wpdb->prepare("
			SELECT *, t.name as parent_name, meta.meta_value as student_assigned
			FROM wp_posts p
			INNER JOIN wp_term_relationships tr ON (p.ID = tr.object_id)
			INNER JOIN wp_term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
			INNER JOIN wp_terms t ON (tt.term_id = t.term_id)
			LEFT JOIN wp_postmeta meta ON (p.ID = meta.post_id AND meta.meta_key = 'vibe_students')
			WHERE p.post_type = 'course'
			AND p.post_status = 'publish'
			AND tt.taxonomy = 'course-cat'
			AND tt.term_id = $cat_id
			ORDER BY meta.meta_value DESC
		")
	);
	return $courses;
}
add_action('wp_ajax_certificate_data_fetch', 'certificate_data_fetch');
add_action('wp_ajax_nopriv_certificate_data_fetch', 'certificate_data_fetch');

function custom_api_init() {
	// https://yourdomain.com/wp-json/custom/v1/data/
    register_rest_route( 'foy-post/', '/data/', array(
        'methods' => 'POST',
        'callback' => 'foy_api_data_insert'
    ) );
}
add_action( 'rest_api_init', 'custom_api_init' );

function foy_api_data_insert( $request ) {
	$data = $request->get_params();
	return rest_ensure_response( $data );
    $data = array(
        'message' => 'Hello, World!'
    );
    return rest_ensure_response( $data );
}

// $args = array(
// 	// 'post_status' => 'publish',
// 	'post_type' => 'course',
// 	'meta_query' => array(
// 		array(
// 			// 'key' => 'average_rating',
// 			'key' => 'vibe_students',
// 		),
// 		array(
// 			'key' => 'vibe_product',
// 			'value'   => array(''),
// 			'compare' => 'NOT IN'
// 		)
// 	),
// 	'tax_query' => array(
//         array(
//             'taxonomy' => 'course-cat',
//             'terms' => 47
//         )
//     ),
// 	'order' => 'DESC',
// 	'posts_per_page' => 10,
// );

// $the_query = new WP_Query($args);
// $courseCats = array('47');

global $wpdb;
 

// dd($courses);

// Certificate Courses
function data_fetch(){
    $cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 0;
	global $wpdb;
	$courses = $wpdb->get_results(
		$wpdb->prepare("
			SELECT p.ID, p.name, p.post_content, p.slug, p.post_name, p.post_title, t.name as parent_name, meta.meta_value as student_assigned, pm.meta_value as post_thumbnail
			FROM wp_posts p
			INNER JOIN wp_term_relationships tr ON (p.ID = tr.object_id)
			INNER JOIN wp_term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
			INNER JOIN wp_terms t ON (tt.term_id = t.term_id)
			LEFT JOIN wp_postmeta meta ON (p.ID = meta.post_id AND meta.meta_key = 'vibe_students')
			LEFT JOIN wp_postmeta pm ON (p.ID = pm.post_id AND pm.meta_key = '_thumbnail_id')
			WHERE p.post_type = 'course'
			AND p.post_status = 'publish'
			AND tt.taxonomy = 'course-cat'
			AND tt.term_id = $cat_id
			ORDER BY meta.meta_value DESC
            LIMIT 7
		")
	);
    // Return the course data in JSON format
    wp_send_json($courses);
    // Don't forget to exit after sending the JSON response
    wp_die();
}
add_action('wp_ajax_data_fetch', 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch', 'data_fetch');

 
add_action('bp_member_options_nav','custom_link123');

function custom_link123(){

  if(is_user_logged_in())
    echo '<li id="custom_link"><a href="custom-link">Custom</a></li>';
}

// function register_custom_taxonomy() {
//     $labels = array(
//         'name' => __( 'Level', 'textdomain' ),
//         'singular_name' => __( 'Level', 'textdomain' ),
//         'menu_name' => __( 'Level', 'textdomain' ),
//         // add more labels as needed
//     );

//     $args = array(
//         'labels' => $labels,
//         'public' => true,
//         'hierarchical' => false,
//         'show_ui' => true,
//         'show_admin_column' => true,
//         'query_var' => true,
//         'rewrite' => array( 'slug' => 'level' ),
//     );

//     register_taxonomy( 'level', array( 'post' ), $args );
// }
// add_action( 'init', 'register_custom_taxonomy', 0 );
 
// add_action( 'init', 'add_custom_taxonomy_terms', 1 );
// function add_course_level_meta_box() {
//     add_meta_box(
//         'course_level_meta_box', // ID
//         'Level', // Title
//         'course_level_meta_box_callback', // Callback function
//         'course', // Post type
//         'side', // Context
//         'default' // Priority
//     );
// }
// add_action( 'add_meta_boxes', 'add_course_level_meta_box' );

// $cat_id = 47;
// global $wpdb;
// $courses = $wpdb->get_results(
// 	$wpdb->prepare("
// 		SELECT ID, name, post_content, slug, post_name, post_title, t.name as parent_name, meta.meta_value as student_assigned, pm.meta_value as post_thumbnail, (SELECT guid FROM wp_posts WHERE ID = pm.meta_value) as thumbnail_url
// 		FROM wp_posts p
// 		INNER JOIN wp_term_relationships tr ON (p.ID = tr.object_id)
// 		INNER JOIN wp_term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
// 		INNER JOIN wp_terms t ON (tt.term_id = t.term_id)
// 		LEFT JOIN wp_postmeta meta ON (p.ID = meta.post_id AND meta.meta_key = 'vibe_students')
// 		LEFT JOIN wp_postmeta pm ON (p.ID = pm.post_id AND pm.meta_key = '_thumbnail_id')
// 		WHERE p.post_type = 'course'
// 		AND p.post_status = 'publish'
// 		AND tt.taxonomy = 'course-cat'
// 		AND tt.term_id = 47
// 		ORDER BY meta.meta_value DESC
// 		LIMIT 7
// 	")
// );

// foreach ($courses as $course) {
//     // Get the thumbnail URL
//     $thumbnail_id = $course->post_thumbnail;
//     $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'thumbnail')[0];
    
//     // Do something with the thumbnail URL, e.g. display the image
//     echo '<img src="' . $thumbnail_url . '">';
// }

// $courses = $wpdb->get_results(
// 	$wpdb->prepare("
// 		SELECT *, t.name as parent_name, meta.meta_value as student_assigned
// 		FROM wp_posts p
// 		INNER JOIN wp_term_relationships tr ON (p.ID = tr.object_id)
// 		INNER JOIN wp_term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
// 		INNER JOIN wp_terms t ON (tt.term_id = t.term_id)
// 		LEFT JOIN wp_postmeta meta ON (p.ID = meta.post_id AND meta.meta_key = 'vibe_students')
// 		WHERE p.post_type = 'course'
// 		AND p.post_status = 'publish'
// 		AND tt.taxonomy = 'course-cat'
// 		AND tt.term_id = 47
// 		ORDER BY meta.meta_value DESC
// 	")
// );
// dd($courses);
function foy_register_session()
{
	if (!session_id()) {
		session_start();
	}
	// destroying session after 30 minute
	$currentTime = time();
	if ($currentTime > $_SESSION['expire']) {
		unset($_SESSION['coupon']);
		unset($_SESSION['start']);
		unset($_SESSION['expire']);
	}
}
add_action('init', 'foy_register_session');

//saving Ajax Data
function foy_save_enquiry_form_action()
{
	unset($_SESSION['coupon']);
	if (isset($_REQUEST['coupon'])) {
		$coupon = $_REQUEST['coupon'];
		$_SESSION['coupon'] = $coupon;
		echo "Request: " . $_REQUEST['coupon'];
		echo " Session: " . $_SESSION['coupon'];
		// Destroying session after 30 minute
		$_SESSION['start'] = time();
		$_SESSION['expire'] = time() + (30 * 60);
	}
	die();


}
add_action('wp_ajax_save_post_details_form', 'foy_save_enquiry_form_action');
add_action('wp_ajax_nopriv_save_post_details_form', 'foy_save_enquiry_form_action');

// to automatically apply coupon cart page
function foy_apply_coupon()
{
	if ($_SESSION['coupon']) {
		$coupon_code = $_SESSION['coupon'];
		if (WC()->cart->has_discount($coupon_code)) {
			return;
		}
		WC()->cart->apply_coupon($coupon_code);
	}
}
add_action('woocommerce_before_cart', 'foy_apply_coupon');
// to automatically apply coupon to checkout page
function foy_apply_coupon_checkout() {
	if ($_SESSION['coupon']) {
		$coupon_code = $_SESSION['coupon'];
		if (WC()->cart->has_discount($coupon_code)) {
			return;
		}
		WC()->cart->apply_coupon($coupon_code);
	}
}
add_action('woocommerce_before_checkout_form', 'foy_apply_coupon_checkout');

// unset session when coupon is removed
function coupon_removed_action($coupon_code)
{
	unset($_SESSION['coupon']);
	unset($_SESSION['start']);
	unset($_SESSION['expire']);
}
add_filter("woocommerce_removed_coupon", 'coupon_removed_action');

function foy_custom_footer_script() {
	
	if (isset($_GET['foy_auto_coupon'])) { 
		?>
		<script>
			function foyFunction() {
				console.log("kdafbg");
				const coupon = "FOY100";
				jQuery(document).ready(function(){
					jQuery.ajax({
						// url: ajaxurl,
						url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
						type: 'get',
						data: {
							'coupon': coupon,
							'action': 'save_post_details_form' 
						},
						success: function(data) {
							console.log("gagaet");
							window.location.replace("http://localhost/MyProject/cart/?add-to-cart=79"); 
						}
					});
				}); 
			}
			foyFunction();
		</script>
	<?php } 
}
add_action( 'wp_footer', 'foy_custom_footer_script' );

function my_custom_cart_text() {
	echo $_SESSION['coupon'];
}
add_action( 'woocommerce_cart_contents', 'my_custom_cart_text' );




function session_ceck(){
     if (!session_id()) {
         session_start();
     }
}
$referer = wp_get_referer();
$page =  $_SERVER['REQUEST_URI'];
// set session
if ( strpos( $referer, 'blog' ) !== false ) {

    session_ceck();
    $_SESSION['from_blog'] = true;
}

if ( strpos( $referer, 'register' ) !== false ) {
    // Set no-cache headers
    header( 'Cache-Control: no-cache, no-store, must-revalidate' );
    header( 'Pragma: no-cache' );
    header( 'Expires: 0' );
}

//unset($_SESSION['from_blog']);
function custom_login_redirect( $user_login, $user  ) {
    session_ceck();
    if ( $_SESSION['from_blog'] ) {
        // To unset the session
        session_ceck();
        unset($_SESSION['from_blog']);
        // Get the user data
        $user = get_userdata( $user_login );
        // Log the user in
        wp_set_current_user( $user_login, $user->user_login );
        wp_set_auth_cookie( $user_login );
        do_action( 'wp_login', $user->user_login, $user );
        // Insert the user meta with the key 'from_blog' and value 'id'
        add_user_meta( $user_login, 'from_blog', true );

        // redirect to the desire page
        wp_redirect( home_url('/blog') );
        exit;
    }
    wp_redirect( home_url('/about') );
    exit;
}
add_action( 'user_register', 'custom_login_redirect', 10, 2 );


if (!session_id()) {
    session_start();
}

//global $wpdb;
//$query = $wpdb->prepare(
//    "SELECT user_id
//    FROM {$wpdb->usermeta}
//    WHERE meta_key = %s",
//    'from_blog'
//);
//
//$user_ids = $wpdb->get_col($query);
//
//if ($user_ids) {
//    foreach ($user_ids as $user_id) {
//        $user_data = get_userdata( $user_id );
//        $user_name = $user_data->display_name;
//        echo "Username: ".$user_name."<br>";
//    }
//} else {
//    echo 'No user IDs found.';
//}




//// Get the current user's ID
//$user_id = get_current_user_id();
//
//// Replace 'from_blog' with the actual user meta key
//$meta_key = 'from_blog';
//
//// Get the user meta value
//$meta_value = get_user_meta( $user_id, $meta_key, true );


//function custom_login_redirect( $user_login, $user ) {
//    $referer = wp_get_referer(); //$_SERVER['HTTP_REFERER']
//    if ( strpos( $referer, '/all-courses' ) !== false ) {
//        wp_redirect( home_url('/contact-us/') );
//        exit;
//    }
//    wp_redirect( home_url() );
//    exit;
//}
//add_action( 'wp_login', 'custom_login_redirect', 10, 2 );

function custom_content_filter($content)
{
    $post_type = get_post_type();
    if (is_singular('post') && $post_type == 'post') {
        if (!is_user_logged_in()) {
            global $post;
            $excerpt_length = 10;
            // Adjust the number of words in the excerpt as needed
            $raw_content = $post->post_content;
            $content_parts = get_extended($raw_content);
            $content = $content_parts['main'];
//            $excerpt_more = '... <a href="#login" rel="nofollow" class="vbplogin">read more</a>';
            $excerpt_more = '... <a href="#login" rel="nofollow" class="vbplogin custom-read-more">read more</a>';
            $words = preg_split("/[\n\r\t ]+/", $content, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
            if (count($words) > $excerpt_length) {
                array_pop($words);
                $content = implode(' ', $words);
                $content = $content . $excerpt_more;
            } else {
                $content = implode(' ', $words);
            }
            return $content;
        }
    }
    return $content;
}
add_filter('the_content', 'custom_content_filter', 10);


/**
 * course directory course loop section modift by filter hook,
 * because its not a template so not possible to override course loop section without filter hook,
 * its wplms theme official instruction wplms theme support forum
 */
add_filter('bp_course_single_item_view', callback: function ($x) {
	global $post;

	$course_id = get_the_ID();
	$product_id = get_post_meta($course_id, 'vibe_product', true);
	$vibe_students = get_post_meta($course_id, 'vibe_students', true);
	$average_rating = get_post_meta($course_id, 'average_rating', true);
	$course_classes = apply_filters('bp_course_single_item', 'course_single_item course_id_' . $post->ID . ' course_status_' . $post->post_status . ' course_author_' . $post->post_author, get_the_ID());
	$count = get_post_meta(get_the_ID(), 'rating_count', true);
	$units=bp_course_get_curriculum_units($course_id);

	$duration = $total_duration = 0;
    if($units != ""){
	    foreach($units as $unit){
		    $duration = get_post_meta($unit,'vibe_duration',true);
		    $total_duration =  $total_duration + $duration;
	    }
	    $hours = floor($total_duration / 60);
	    $minutes = $total_duration % 60;
	    if ($hours > 0) {
		    $hours_text = $hours .'hr';
	    }
	    if ($minutes > 0) {
		    $minutes_text = $minutes .'m';
	    }
    }
	?>
    <li class="<?php echo $course_classes; ?>">
        <div class="a2n_course-card">
            <div class="courses_container">
                <div id="courses_content">
                    <?php
                        bp_course_avatar();
                    ?>
                    <div class="courses_items">
                        <h4 class="courses_title">
                            <a href="<?php echo get_the_permalink($course_id) ?>">
			                    <?php echo get_the_title(); ?>
                            </a>
                        </h4>
                        <div class="inner_items">
                            <p>
                                <img src="https://coursecloud.org/wp-content/uploads/2023/11/Frame-83.svg" alt="" />
	                            <?php echo $vibe_students." Students"; ?>
                            </p>
                            <p>
                                <img src="https://coursecloud.org/wp-content/uploads/2023/11/Group-42.svg" alt=""/>
                                <?php echo $hours_text." ".$minutes_text?>
                            </p>
                        </div>
	                    <?php if (!empty($average_rating)) { ?>
                            <h5 class="courses_ratings">
                                <span><i class="fa fa-star" aria-hidden="true"></i> <?php echo $average_rating; ?></span>
                                <?php
                                    if ($count > 999) {
                                        echo "(".($count / 1000) . 'k'.")";
                                    } else {
                                        echo "(".$count.")";
                                    }
                                ?>
                            </h5>
                        <?php } ?>
                        <div class="courses_end">
                            <div class="price-div">
		                        <?php
		                        if (!bp_is_my_profile()) {
			                        bp_course_credits();
		                        } else {
			                        the_course_button($course_id);
		                        }
		                        ?>
                            </div>
                            <div class="btn-div">
		                        <?php
		                        if (!bp_is_my_profile()) {
			                        ?>
<!--                                    <a href="--><?php //echo get_the_permalink($course_id) ?><!--" class="view-btn">-->
<!--                                        View Course-->
<!--                                    </a>-->
                                    <a href="?add-to-cart=<?php echo $product_id; ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart  add-to-cart-btn main_button" data-product_id="<?php echo $product_id; ?>" data-product_sku="" aria-label="Add" rel="nofollow">
                                       Buy Now
                                    </a>
			                        <?php
		                        }
		                        ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="courses_bottom-shape"></div>
            </div>
        </div>
    </li>
	<?php
	return 1;
});


function custom_course_card_shortcode($atts) {
	global $post;
	// Extract shortcode attributes
	$atts = shortcode_atts(
		array(
			'course_id' => 0, // Default course ID
		),
		$atts,
		'course_card'
	);

	// Get the course ID from the shortcode attributes
	$course_id = intval($atts['course_id']);

	// Check if the course ID is valid
	if ($course_id <= 0) {
		return 'Invalid course ID';
	}

	// Rest of your existing code
	$product_id = get_post_meta($course_id, 'vibe_product', true);
	$vibe_students = get_post_meta($course_id, 'vibe_students', true);
	$average_rating = get_post_meta($course_id, 'average_rating', true);
	$course_classes = apply_filters('bp_course_single_item', 'course_single_item course_id_' . $post->ID . ' course_status_' . $post->post_status . ' course_author_' . $post->post_author, $course_id);
	$count = get_post_meta($course_id, 'rating_count', true);
	$units = bp_course_get_curriculum_units($course_id);
	$duration = $total_duration = 0;

	foreach ($units as $unit) {
		$duration = get_post_meta($unit, 'vibe_duration', true);
		$total_duration = $total_duration + $duration;
	}

	$hours = floor($total_duration / 60);
	$minutes = $total_duration % 60;
	$hours_text = $minutes_text = '';

	if ($hours > 0) {
		$hours_text = $hours . 'hr';
	}

	if ($minutes > 0) {
		$minutes_text = $minutes . 'm';
	}

	ob_start(); // Start output buffering
	?>
    <li class="<?php echo esc_attr($course_classes); ?>">
        <div class="a2n_course-card">
            <div class="courses_container">
                <div id="courses_content">
					<?php bp_course_avatar(); ?>
                    <div class="courses_items">
                        <h4 class="courses_title">
                            <a href="<?php echo esc_url(get_the_permalink($course_id)); ?>">
								<?php echo esc_html(get_the_title($course_id)); ?>
                            </a>
                        </h4>
                        <div class="inner_items">
                            <p>
                                <img src="https://coursecloud.org/wp-content/uploads/2023/11/Frame-83.svg" alt=""/>
								<?php echo esc_html($vibe_students) . " Students"; ?>
                            </p>
                            <p>
                                <img src="https://coursecloud.org/wp-content/uploads/2023/11/Group-42.svg" alt=""/>
								<?php echo esc_html($hours_text . " " . $minutes_text); ?>
                            </p>
                        </div>
						<?php if (!empty($average_rating)) { ?>
                            <h5 class="courses_ratings">
                                <span><i class="fa fa-star" aria-hidden="true"></i> <?php echo esc_html($average_rating); ?></span>
								<?php
								if ($count > 999) {
									echo "(".(esc_html($count) / 1000) . 'k'.")";
								} else {
									echo "(".esc_html($count).")";
								}
								?>
                            </h5>
						<?php } ?>
                        <div class="courses_end">
                            <div class="price-div">
								<?php
								if (!bp_is_my_profile()) {
									bp_course_credits();
								} else {
									the_course_button($course_id);
								}
								?>
                            </div>
                            <div class="btn-div">
								<?php
								if (!bp_is_my_profile()) {
									?>
                                    <a href="?add-to-cart=<?php echo esc_attr($product_id); ?>"
                                       data-quantity="1"
                                       class="button product_type_simple add_to_cart_button ajax_add_to_cart  add-to-cart-btn main_button"
                                       data-product_id="<?php echo esc_attr($product_id); ?>" data-product_sku=""
                                       aria-label="Add" rel="nofollow">
                                        Buy Now
                                    </a>
									<?php
								}
								?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="courses_bottom-shape"></div>
            </div>
        </div>
    </li>
	<?php
	return ob_get_clean(); // Return the buffered content
}

// Register the shortcode
add_shortcode('course_card', 'custom_course_card_shortcode');


$user_id = get_current_user_id();
$pageposts = $wpdb->get_results($wpdb->prepare("
    SELECT posts.ID as id, IF(meta.meta_value > %d,'active','expired') as status, product_meta.meta_value as associated_product
    FROM {$wpdb->posts} AS posts
    LEFT JOIN {$wpdb->usermeta} AS meta ON posts.ID = meta.meta_key
    LEFT JOIN {$wpdb->postmeta} AS product_meta ON posts.ID = product_meta.post_id AND product_meta.meta_key = 'vibe_product'
    WHERE   posts.post_type   = %s
    AND   posts.post_status   = %s
    AND   meta.user_id   = %d
", time(), 'course', 'publish', $user_id));
//echo "<pre>";
//var_dump($pageposts);
//echo "</pre>";




// added by shah fayez ali
// Add A custom widget under widget section that fetch the categories
class Custom_Course_Categories_Widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct(
			'custom_course_categories_widget',
			'Foy Custom Course Categories',
			array('description' => 'Custom widget for displaying course categories')
		);
	}
	public function widget($args, $instance)
	{
		$excluded_categories = !empty($instance['excluded_categories']) ? array_map('intval', explode(',', $instance['excluded_categories'])) : array();

		$course_categories = get_terms(array(
			'taxonomy' => 'course-cat',
			'hide_empty' => true,
			'exclude' => $excluded_categories,
			'orderby' => 'count',
			'order' => 'DESC',
		));

		echo $args['before_widget'];

		// Widget title (if set)
		$title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
		if ($title) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// Display course categories
		if (!empty($course_categories) && !is_wp_error($course_categories)) {
			global $wp;
			$current_url = home_url($wp->request);
			$path = parse_url($current_url, PHP_URL_PATH);
			$lastString = basename($path);
			echo '<ul>';

			echo '<li class="foy-cat-top">';
			echo '<a class="foy-cat-title" href="">All Courses</a>';
			echo '</li>';

			echo '<li class="' . ($lastString == 'all-courses ' ? 'foy-current-cat foy-cat-li' : 'foy-cat-li') . '">';
			echo '<input type="checkbox" class="foy-cat-checkbox" ' . ($lastString == 'all-courses' ? 'checked="checked"' : '') . '>';
			echo '<a class="foy-cat-title" href="' . site_url('all-courses') . '">All Courses</a>';
			echo '</li>';

			foreach ($course_categories as $category) {
				$cat_slug = $category->slug;
				$course_count = $category->count;

				echo '<li class="' . ($lastString == $cat_slug ? 'foy-current-cat foy-cat-li' : 'foy-cat-li') . '">';
				echo '<input type="checkbox" class="foy-cat-checkbox" ' . ($lastString == $cat_slug ? 'checked="checked"' : '') . '>';
				echo '<a class="foy-cat-title" href="' . get_term_link($category) . '">' . esc_html($category->name) . ' ('.$course_count.')</a>';
				echo '</li>';
			}
			echo '</ul>';
		} else {
			echo '<p>No course categories found.</p>';
		}

		echo $args['after_widget'];
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['excluded_categories'] = sanitize_text_field($new_instance['excluded_categories']);
		$instance['title'] = sanitize_text_field($new_instance['title']); // Add title field if needed
		return $instance;
	}

	public function form($instance)
	{
		$excluded_categories = !empty($instance['excluded_categories']) ? esc_attr($instance['excluded_categories']) : '';
		$title = !empty($instance['title']) ? esc_attr($instance['title']) : ''; // Add title field if needed

		// Add title field if needed
		echo '<p>';
		echo '<label for="' . $this->get_field_id('title') . '">Widget Title:</label>';
		echo '<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" />';
		echo '</p>';

		echo '<p>';
		echo '<label for="' . $this->get_field_id('excluded_categories') . '">Exclude Category IDs (comma-separated):</label>';
		echo '<input class="widefat" id="' . $this->get_field_id('excluded_categories') . '" name="' . $this->get_field_name('excluded_categories') . '" type="text" value="' . $excluded_categories . '" />';
		echo '</p>';
	}
}

// Register the widget
function register_custom_course_categories_widget()
{
	register_widget('Custom_Course_Categories_Widget');
}
add_action('widgets_init', 'register_custom_course_categories_widget');


function custom_taxonomy_course_type() {
	$labels = array(
		'name'              => _x( 'Course Types', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Course Type', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Course Types', 'textdomain' ),
		'all_items'         => __( 'All Course Types', 'textdomain' ),
		'parent_item'       => __( 'Parent Course Type', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Course Type:', 'textdomain' ),
		'edit_item'         => __( 'Edit Course Type', 'textdomain' ),
		'update_item'       => __( 'Update Course Type', 'textdomain' ),
		'add_new_item'      => __( 'Add New Course Type', 'textdomain' ),
		'new_item_name'     => __( 'New Course Type Name', 'textdomain' ),
		'menu_name'         => __( 'Course Type', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true, // Set to true if you want it to behave like categories, false for tags
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'course-type' ),
		'show_in_menu'      => true,
	);

	// Replace 'course' with the name of your custom post type
	register_taxonomy( 'course-type', 'course', $args );
}

add_action( 'init', 'custom_taxonomy_course_type' );




// Add this code to your theme's functions.php file or in a custom plugin

function custom_taxonomy_course_language() {
	$labels = array(
		'name'              => _x( 'Course Languages', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Course Language', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Course Languages', 'textdomain' ),
		'all_items'         => __( 'All Course Languages', 'textdomain' ),
		'parent_item'       => __( 'Parent Course Language', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Course Language:', 'textdomain' ),
		'edit_item'         => __( 'Edit Course Language', 'textdomain' ),
		'update_item'       => __( 'Update Course Language', 'textdomain' ),
		'add_new_item'      => __( 'Add New Course Language', 'textdomain' ),
		'new_item_name'     => __( 'New Course Language Name', 'textdomain' ),
		'menu_name'         => __( 'Course Language', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true, // Set to true if you want it to behave like categories, false for tags
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'course-language' ),
		'show_in_menu'      => true,
	);

	// Replace 'course' with the name of your custom post type
	register_taxonomy( 'course-language', 'course', $args );
}

add_action( 'init', 'custom_taxonomy_course_language' );
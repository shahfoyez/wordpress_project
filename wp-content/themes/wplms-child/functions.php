<?php 
	function wplms_child_enqueue_styles() {
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); 
		// wp_enqueue_style( "singleCourse-css", get_theme_file_uri("/assets/css/singleCourse.css" ), null, "1.5" );
	} 
	add_action( 'wp_enqueue_scripts', 'wplms_child_enqueue_styles' );
	add_action( 'wp_enqueue_scripts', 'add_ajax_url' );

	function add_ajax_url() {
		wp_localize_script( 'jquery', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
	}
	
	function timer_function( $atts ){
		


	 
		global $wpdb;
		
		// Query the database to retrieve user-wise course count
		$query = "
			SELECT COUNT(DISTINCT p.ID) AS course_count, u.ID AS user_id
			FROM {$wpdb->prefix}posts AS p
			INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
			INNER JOIN {$wpdb->prefix}users AS u ON pm.meta_value = u.ID
			WHERE p.post_type = 'course'
			AND p.post_status = 'publish'
			GROUP BY u.ID
		";
		
		$results = $wpdb->get_results($query);
		dd($results);
		if (!empty($results)) {
			foreach ($results as $result) {
				$user_id = $result->user_id;
				$course_count = $result->course_count;
		
				// Output the user ID and course count
				echo "User ID: $user_id | Course Count: $course_count <br>";
			}
		} else {
			echo "No results found.";
		}

	
		
		
		
		






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
	echo "ekfgb";
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
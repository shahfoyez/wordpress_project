<?php
	$filepath= realpath(dirname(__FILE__));
	// include_once ($filepath.'/Format.php');
?>
<?php
class OrderCertificate {
    public function getCourses(){
        $user_id = get_current_user_id();
        $course_ids = bp_course_get_user_courses($user_id);
        // echo "<pre>";
        // var_dump($user_certificates);
        // echo "</pre>";

        if( $course_ids ){
            // get user certificates(string)
            $user_certificates = get_user_meta( $user_id, 'certificates', true );
            // get the distinct course ids
            $distinct_course_ids = array_unique($course_ids);

            // dd($distinct_course_ids);
            if($user_certificates != NULL){
                // Get the common elements between
                $common_elements = array_intersect( $user_certificates, $distinct_course_ids);
                $different_elements = array_diff($distinct_course_ids, $user_certificates);
            }else{
                $common_elements = array('');
                $different_elements =  $distinct_course_ids;
            }
            
            // Create the two separate arrays
            $claimed = count( $common_elements )>0 ? $common_elements : array('');
            $pending = count( $different_elements )>0 ? $different_elements : array('');
            

            $default = array(  
                'post_type' => 'course',
                'post_status' => 'publish',
                'posts_per_page' => -1, 
                'orderby' => 'date', 
                'order' => 'ASC', 
                'meta_query' => array(
                    array(
                        'key' => 'vibe_certificate_template',
                        'compare' => '!=',
                        'value' => ''
                    ),
                    // array(
                    //     'key' => 'vibe_product',
                    //     'value'   => array(''),
                    //     'compare' => 'NOT IN'
                    // ),
                ),
            );
            $claimed = array(  
                'post__in' =>  $claimed,
            );
            $pending = array(  
                'post__in' =>  $pending,
            );
            $claimed_merged_args = array_merge($default, $claimed);
            $pending_merged_args = array_merge($default, $pending);
            // dd($claimed_merged_args);

            $claimed_cources = new WP_Query( $claimed_merged_args );
            $pending_cources = new WP_Query( $pending_merged_args );
            // dd($claimed_cources);

            $data = array(
                'claimed' => $claimed_cources->posts,
                'pending' => $pending_cources->posts
            ); 
        }else{
            $data = [];
        }
        return $data;  
    }
    public function getAllCourses(){
        $args = array(
            'post_type' => 'course',
            'post_status' => 'publish',
            'posts_per_page' => 50, 
            'orderby' => 'date', 
            'order' => 'DESC', 
            'meta_query' => array(
                array(
                    // 'key' => 'average_rating',
                    'key' => 'vibe_students',
                ),
                // array(
                //     'key' => 'vibe_product',
                //     'value'   => array(''),
                //     'compare' => 'NOT IN'
                // ),
                array(
                    'key' => 'vibe_certificate_template',
                    'compare' => '!=',
                    'value' => ''
                )
            ),
        );
        $the_query = new WP_Query($args);
        return $the_query->posts;
    }
    public function courseCount(){

        $user_id = get_current_user_id();
        // var_dump( $user_id);
        $taken_cources = 0 ;
        $claimed_cources = 0;
        if($user_id){
            $user_certificates = get_user_meta( $user_id, 'certificates', true );
            $course_ids = bp_course_get_user_courses($user_id);
    
         
            if( count($course_ids) > 0 ){
                $distinct_course_ids = count(array_unique($course_ids)) > 0 ? array_unique($course_ids) : array('');
                $claimed_cources = count(array_intersect( $user_certificates, $distinct_course_ids)) ?? 0;
    
                // get courses that has certificate
                $taken = array(  
                    'post_type' => 'course',
                    'post_status' => 'publish',
                    'posts_per_page' => -1, 
                    'orderby' => 'date', 
                    'order' => 'ASC', 
                    'meta_query' => array(
                        array(
                            'key' => 'vibe_certificate_template',
                            'compare' => '!=',
                            'value' => ''
                        ),
                        // array(
                        //     'key' => 'vibe_product',
                        //     'value'   => array(''),
                        //     'compare' => 'NOT IN'
                        // ),
                    ),
                    'post__in' =>  $distinct_course_ids,
                    'fields' => 'ids'
                );
                $taken_cources = count( get_posts( $taken ) );
            }
        }
         
        

        // All courses
        $allCourses = array(
            'post_type' => 'course',
            'post_status' => 'publish',
            'posts_per_page' => -1, 
            'meta_query' => array(
                // array(
                //     'key' => 'vibe_product',
                //     'value'   => array(''),
                //     'compare' => 'NOT IN'
                // ),
                array(
                    'key' => 'vibe_certificate_template',
                    'compare' => '!=',
                    'value' => ''
                )
            ),
            'fields' => 'ids'
        );
        
        $all_courses = count( get_posts( $allCourses ) );
        $data = array(
            'all' => $all_courses,
            'taken' =>  $taken_cources,
            'claimed' => $claimed_cources,
        );
        return $data; 
    }
    public function courseLinkIdExtract($data){
         // Get the course link
         $course_link = $data['course-link'];

         // Extract the path from the URL
         $path = parse_url($course_link, PHP_URL_PATH);

         // Get the last segment of the path, which should be the course slug
         $segments = explode('/', rtrim($path, '/'));
         $course_slug = end($segments);

         // Query the database to find the course ID
         global $wpdb;
         $course_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type = 'course' AND post_name = '$course_slug'");
        //  dd($course_id);
         if( $course_id != NULL){
            $category = has_term( 'qls', 'course-cat', $course_id );
            $course_type = $category == true ? 'qls' : '';
            echo "<script>window.location = 'https://uk.hfonline.org/new-certificate-2023-test/?course-id=".$course_id."&course-type=".$course_type."';</script>";
         }else{
            $msg = "<span class='foy-error'>Sorry! Course not found!</span>";
            return $msg;
            // echo "<script>window.location = '?error=".$msg."';</script>";
         }
        
    }
}
?>
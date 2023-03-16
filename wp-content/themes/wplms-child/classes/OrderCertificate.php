<?php
	$filepath= realpath(dirname(__FILE__));
	// include_once ($filepath.'/Format.php');
?>
<?php
class OrderCertificate {
    public function getCourses(){
        $user_id = get_current_user_id();
        $user_certificates = get_user_meta( $user_id, 'certificates', true );
        $course_ids = bp_course_get_user_courses($user_id);
    
        if( $course_ids ){
            $distinct_course_ids = array_unique($course_ids);
            // dd($distinct_course_ids);
            
            // Get the common elements between $main and $array2
            $common_elements = array_intersect( $user_certificates, $distinct_course_ids);

            // Get the elements of $array2 that are not in $main
            $different_elements = array_diff($distinct_course_ids, $user_certificates);
            
            // Create the two separate arrays
            $claimed = $common_elements;
            $pending = $different_elements;



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
                'post__in' =>   $claimed,
            );
            $pending = array(  
                'post__in' =>  $pending,
            );
            $claimed_merged_args = array_merge($default, $claimed);
            $pending_merged_args = array_merge($default, $pending);
            // dd($claimed_merged_args);

            $claimed_cources = new WP_Query( $claimed_merged_args );
            $pending_cources = new WP_Query( $pending_merged_args );
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
        $user_certificates = get_user_meta( $user_id, 'certificates', true );
        $course_ids = bp_course_get_user_courses($user_id);
        
        if( $course_ids ){
            $distinct_course_ids = array_unique($course_ids);
        
            // Get the common elements, meaning certificate claimed
            $claimed_cources = count(array_intersect( $user_certificates, $distinct_course_ids));
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
        // dd($all_courses->post_count);
        $data = array(
            'all' => $all_courses,
            'taken' =>  $taken_cources,
            'claimed' => $claimed_cources,
        );
        return $data; 
    }
}
?>
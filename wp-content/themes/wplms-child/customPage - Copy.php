<?php /* Template Name: Foy Custom */ ?>
<?php
    require_once get_stylesheet_directory() . '/classes/OrderCertificate.php';
    $order_obj = new OrderCertificate();
    // get user's courses that has an associated certificate
    $allType = $order_obj->getCourses();
    if (  $allType ) {
        foreach($allType as $key => $courseType){
            echo $key;
            if ( $courseType->posts ) {
                foreach($courseType->posts as $course) {
                    echo $course->ID."<br>";
                    echo $course->post_title."<br>";
                    $certificate_template_id = get_post_meta( $course->ID, 'vibe_certificate_template', true );
                    $certificate_template = get_post( $certificate_template_id );
                    $certificate_template_background_image = wp_get_attachment_image_src( get_post_thumbnail_id( $certificate_template_id ), 'full' );
                }
            }
        }
    }else{
        echo "No courses found";
    }
?>
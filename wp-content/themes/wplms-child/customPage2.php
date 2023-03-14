<?php
/* Template Name: Custom Page 2*/
get_header(vibe_get_header());
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.proto.min.js" integrity="sha512-jVHjpoNvP6ZKjpsZxTFVEDexeLNdWtBLVcbc7y3fNPLHnldVylGNRFYOc7uc+pfS+8W6Vo2DDdCHdDG/Uv460Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php
    require_once get_stylesheet_directory() . '/classes/OrderCertificate.php';
    $order_obj = new OrderCertificate();
    // get user's courses that has an associated certificate
    $allType = $order_obj->getCourses();
?>
<?php
    if(isset($_GET['type'])){
        $type = $_GET['type'];
    }
    if( $type == 'enrolled'){
        $selectCourses =  $allType;
    }else{
        $selectCourses = $order_obj->getAllCourses();
    }
?>
<section>
    <?php do_action('wplms_before_title'); ?>

    <div class="container" id="cert-container-merit-page">
        <div class="foy-certificate-top-section">
            <h1 class="select-course-title">select your course for certificate</h1>
            <select id="course-for-certificate">
                <option selected>Search for Your Desired Courses</option>
                <?php
                    if ($selectCourses) {
                        foreach($selectCourses as $selectCourse){
                            if ($selectCourse->posts) {
                                foreach ($selectCourse->posts as $course) {?>
                                    <option value=" "><?php echo $course->post_title; ?></option>
                                <?php }
                            }
                        } 
                    } 
                ?>
            </select>
            <button type="submit" disabled>Submit</button>
        </div>
         
        <div class="foy-certificate-bottom-section">
        <?php
            if ($allType) {
                foreach ($allType as $key => $courseType) { ?>
                <div class="foy-heading">
                    <p class="foy-type-heading"><?php echo ucfirst($key)?></p>
                </div>
                    <?php
                    if (count($courseType->posts) > 0) {
                        foreach ($courseType->posts as $course) {
                            // dd( $course);
                            $category = has_term( 'qls', 'course-cat', $course->ID );
                            $cats = get_the_terms( 77, 'course-cat' );

                            // dd( $category );
                            $title = $course->post_title . "<br>";
                        ?>
                            <div class="certificate-merit-card">
                                <img src="<?php echo get_theme_file_uri(); ?>/assets/img/certificate.webp" alt="">
                                <h1 class="certificate-course-title"><?php echo $title ?></h1>
                                <div class="certificate-status <?php echo $key == 'claimed' ? 'foy-claimed' : 'foy-pending' ?>">
                                    <?php echo ucfirst($key)?>
                                </div>

                                <?php
                                    $courseCertLink = bp_get_course_certificate(array(
                                        'course_id' => $course->ID, 
                                        'user_id' => get_current_user_id()
                                    ));
                                    if($key == 'claimed'){?>
                                        <a href="<?php echo $courseCertLink ?>" class="redirect-url-btn">Print</a> 
                                    <?php
                                    }else{ ?>
                                        <a href="https://uk.hfonline.org/new-certificate-2023-test/?course-name=<?php echo $course->post_title ?><?php echo $category == true? '&course-type=qls' : ''?> " class="redirect-url-btn">Claim Now</a> 
                                    <?php }
                                ?> 
                            </div>
                        <?php
                        }
                    } else{?>
                        <h4>No Course Found</h4>
                    <?php }
                }
            } else {
                echo "No courses found";
            }
        ?>

        </div>
    </div>
</section>
<script>
    jQuery("#course-for-certificate").chosen({
        no_results_text: "Oops, nothing found!",
        width: "592px",
        // height: "56px",
    });
</script>
<?php
get_footer(vibe_get_footer());

<?php
/* Template Name: UKHF-Certification-Merit-Page*/
get_header(vibe_get_header());
?>
<?php

require_once get_stylesheet_directory() . '/classes/OrderCertificate.php';
$order_obj = new OrderCertificate();
// get user's courses that has an associated certificate
$allType = $order_obj->getCourses();

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.proto.min.js"
    integrity="sha512-jVHjpoNvP6ZKjpsZxTFVEDexeLNdWtBLVcbc7y3fNPLHnldVylGNRFYOc7uc+pfS+8W6Vo2DDdCHdDG/Uv460Q=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"
    integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"
    integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<section>
    <?php do_action('wplms_before_title'); ?>






    <?php

if (  $allType ) {
    foreach($allType as $key => $courseType){
        echo $key;
        if ( $courseType->posts ) {
            foreach($courseType->posts as $course) {
                $title = $course->post_title."<br>";
            ?>
    <?php
            <div class="certificate-merit-card">
                <img src="<?php echo get_theme_file_uri(); ?>/assets/img/hf_online_certificate4.png" alt="">
    <h1 class="certificate-course-title">master herbalist diploma course</h1>
    <div class="certificate-status-claimed">
        Claimed
    </div>
    <a href="#" class="redirect-url-btn">Print</a>
    </div>
    }
    }
    }
    }else{
    echo "No courses found";
    }
    ?>








    <div class="container" id="cert-container-merit-page">
        <h1 class="select-course-title">select your course for certificate</h1>
        <select id="course-for-certificate">
            <option selected>Search for Your Desired Courses</option>
            <option value="Nutrition Masterclass Diploma">Nutrition Masterclass Diploma</option>
            <option value="Master Herbalist Diploma Course">Master Herbalist Diploma Course</option>
        </select>
        <div class="certificate-merit-card">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/hf_online_certificate4.png" alt="">
            <h1 class="certificate-course-title">master herbalist diploma course</h1>
            <div class="certificate-status-claimed">
                Claimed
            </div>
            <a href="#" class="redirect-url-btn">Print</a>
        </div>

        <div class="certificate-merit-card">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/hf_online_certificate4.png" alt="">
            <h1 class="certificate-course-title">Nutrition Masterclass Diploma</h1>
            <div class="certificate-status-pending">
                Pending
            </div>
            <a href="#" class="redirect-url-btn">claim now</a>
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
<?php
/* Template Name: Custom Page 3*/
?>
<?php
    get_header();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.proto.min.js" integrity="sha512-jVHjpoNvP6ZKjpsZxTFVEDexeLNdWtBLVcbc7y3fNPLHnldVylGNRFYOc7uc+pfS+8W6Vo2DDdCHdDG/Uv460Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php
    require_once get_stylesheet_directory() . '/classes/OrderCertificate.php';
    $order_obj = new OrderCertificate();
    // get user's courses that has an associated certificate
    $allType = $order_obj->getCourses();
    $selectCourses =  $allType;
?>
<section>
    <?php do_action('wplms_before_title'); ?>

    <div class="container" id="cert-container-merit-page">
        <div class="foy-certificate-top-section">
            <h1 class="select-course-title">select your course for certificate</h1>
            <form id="foy-select" action="https://uk.hfonline.org/new-certificate-2023-test/" method="GET">
                <select name="course-id" id="course-for-certificate" onclick="foyFunction(this)">
                    <option selected>Search for Your Desired Courses</option>
                    <?php
                        if ( count($allType) > 0) {
                            foreach ($allType as $key => $courseType){
                                if($key == 'pending'){
                                    continue;
                                }
                                foreach ($courseType->posts as $course) {
                                    $category = has_term( 'qls', 'course-cat', $course->ID );
                                    ?>  
                                    <option 
                                        value="<?php echo $course->ID ?>" 
                                        data-content="<?php echo $category == true ? 'qls' : ''?>"
                                    >
                                    <?php echo $course->post_title; ?>
                                    </option>
                                <?php }
                            } 
                        } 
                    ?>
                </select>
                <input name="course-type" class="foy-input" type="text" hidden>
                <button type="submit">Submit</button>
            </form>
            <script>
                const form = document.querySelector('#foy-select');
                const input = document.querySelector('.foy-input');
                const select = document.querySelector('#course-for-certificate');

                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const selectedOption = select.options[select.selectedIndex];
                    const dataContent = selectedOption.dataset.content;
                    input.value = dataContent;
                    form.submit();
                });
            </script>
        </div>
        <div class="foy-certificate-bottom-section">
            <!-- Claimed Courses -->
            <?php
                if ($allType['claimed']) { ?>
                    <div class="foy-heading">
                        <p class="foy-type-heading">Claimed</p>
                    </div>
                    <?php
                    $claimedCourses = $allType['claimed']->posts;
                    if (count( $claimedCourses) > 0 ) {
                        foreach ( $claimedCourses as $course) {
                            // dd( $course);
                            $category = has_term( 'qls', 'course-cat', $course->ID );
                            // dd( $category );
                            $title = $course->post_title . "<br>";
                            $courseCertLink = bp_get_course_certificate(array(
                                'course_id' => $course->ID, 
                                'user_id' => get_current_user_id()
                            ));
                        ?>
                            <div class="certificate-merit-card">
                                <img src="<?php echo get_theme_file_uri(); ?>/assets/img/certificate.webp" alt="">
                                <h1 class="certificate-course-title"><?php echo $title ?></h1>
                                <div class="certificate-status foy-claimed">
                                    Claimed
                                </div>
                                <a href="<?php echo $courseCertLink ?>" class="redirect-url-btn">Print</a> 
                            </div>
                        <?php }
                    }else{ ?>
                        <h4>No Course Found</h4>
                    <?php }
                } 
            ?>
            <!-- Pending Courses -->
            <?php
                if ($allType['pending']) { ?>
                    <div class="foy-heading">
                        <p class="foy-type-heading">Pending</p>
                    </div>
                    <?php
                    $total_pages = $allType['pending']->max_num_pages; 
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $pendingCourses = $allType['pending']->posts;
                    if (count( $pendingCourses) > 0 ) {
                        foreach ( $pendingCourses as $course) {
                            // dd( $course);
                            $category = has_term( 'qls', 'course-cat', $course->ID );
                            // dd( $category );
                            $title = $course->post_title . "<br>";
                            $courseCertLink = bp_get_course_certificate(array(
                                'course_id' => $course->ID, 
                                'user_id' => get_current_user_id()
                            ));
                            ?>
                            <div class="certificate-merit-card">
                                <img src="<?php echo get_theme_file_uri(); ?>/assets/img/certificate.webp" alt="">
                                <h1 class="certificate-course-title"><?php echo $title ?></h1>
                                <div class="certificate-status foy-pending">
                                    Pending
                                </div>
                                <a href="https://uk.hfonline.org/new-certificate-2023-test/?course-id=<?php echo $course->ID ?><?php echo $category == true ? '&course-type=qls' : ''?> " class="redirect-url-btn">Claim Now</a> 
                            </div>
                        <?php } ?> 
                        
                        <!-- pagination -->
                        <div class="pagination">
                            <?php
                                echo paginate_links(array(
                                    'total' => $total_pages,
                                    'current' => $paged,
                                    'prev_text' => __('« prev'),
                                    'next_text' => __('next »'),
                                ));
                            ?>
                        </div>
                        <?php
                    }else{ ?>
                        <h4>No Course Found</h4>
                    <?php }
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

<?php
/* Template Name: Custom Page 3*/
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
    // dd($allType);
    $selectCourses =  $allType;
?>
<section>
    <?php do_action('wplms_before_title'); ?>

    <div class="container" id="cert-container-merit-page">
        <div class="foy-certificate-top-section">
            <h1 class="select-course-title">select your course for certificate</h1>
            <form id="certificate-form" action="https://uk.hfonline.org/new-certificate-2023-test/" method="GET">
            <select id="course-for-certificate" name="course">
                <!-- <option selected disabled>Search for Your Desired Courses</option> -->
                <?php
                if (count($allType) > 0) {
                    foreach ($allType as $key => $courseType) {
                    foreach ($courseType as $course) {
                        $category = has_term('qls', 'course-cat', $course->ID);
                ?>
                <option value="<?php echo $course->ID ?>" data-type="<?php echo $category == true ? 'qls' : '' ?>"><?php echo $course->post_title ?></option>
                <?php
                    }
                    }
                }
                ?>
            </select>
            <button id="foy-submit" name="foy-submit" type="submit" disabled>Submit</button>
            </form>

            <script>
            var select = document.getElementById("course-for-certificate").addEventListener("change", addActivityItem, false);
            console.log(select);
            var button = document.getElementById("foy-submit");
            // document.getElementById("activitySelector").addEventListener("change", addActivityItem, false);
            function addActivityItem()
            select.addEventListener("click", function() {
                console.log("jsfhvysgfgausyfyuasd");
                button.disabled = false;
                var selectedOption = select.options[select.selectedIndex];
                var courseID = selectedOption.value;
                var courseType = selectedOption.dataset.type;
                var actionURL = "https://uk.hfonline.org/new-certificate-2023-test/?course-id=" + courseID;
                if (courseType) {
                actionURL += "&course-type=" + courseType;
                }
                document.getElementById("certificate-form").action = actionURL;
            });
            </script>
        </div>

 



         
        <div class="foy-certificate-bottom-section">
        <?php
            if ($allType) {
                foreach ($allType as $key => $courseType) { ?>
                <div class="foy-heading">
                    <p class="foy-type-heading"><?php echo ucfirst($key)?></p>
                </div>
                    <?php
                    if (count($courseType) > 0) {
                        foreach ($courseType as $course) {
                            // dd( $course);
                            $category = has_term( 'qls', 'course-cat', $course->ID );
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
                                        <a href="https://uk.hfonline.org/new-certificate-2023-test/?course-id=<?php echo $course->ID ?><?php echo $category == true ? '&course-type=qls' : ''?> " class="redirect-url-btn">Claim Now</a> 
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

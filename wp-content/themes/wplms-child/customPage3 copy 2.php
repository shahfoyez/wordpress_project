<?php
/* Template Name: customPage4 */
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
$courseCount = $order_obj->courseCount();
// get all courses
$selectCourses = $order_obj->getAllCourses();

?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST);
    if (isset($_POST['foy-link-ap'])) {
        var_dump($_POST);
        $linkRedirect = $order_obj->clickedOption($_POST);
    } else {
        var_dump("dkg");
        $linkRedirect = $order_obj->clickedOption($_POST);
    }
}
?>

<section class="new-certificate-parent-container">
    <?php do_action('wplms_before_title'); ?>
    <div class="container">
        <div class="foy-progress">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/pr_2.png" alt="">
        </div>

        <div class="foy-certificate-top-section">
            <h1 class="select-course-title">select The course You want to Gift Certificate for</h1>
            <form id="foy-select" action="" method="POST">
            <div class="gift-recipient-info">
                <h1 class="select-course-title">Gift certificate to your closed ones</h1>
                <form id="ceritificate-gift-form" action="">
                    <label for="cert-recipient-name">Name of Certificate Recipient:</label><br>
                    <input type="text" id="cert-recipient-name" name="cert-recipient-name"><br>
                    <label for="cert-recipient-email">Email of Certificate Recipient:</label><br>
                    <input type="email" id="cert-recipient-email" name="cert-recipient-email">
                </form>
            </div>

            <div class="cert-gift-option">
                <h1 class="select-course-title">Options</h1>
                <span>
                    <p>
                        <input value="" class="cert-gift-readio" type="radio" id="gift-with-no-course" name="radio-group-cert-gift" checked>
                        <label for="gift-with-no-course">With Course</label>
                    </p>
                    <p>
                        <input value="" class="cert-gift-readio" type="radio" id="gift-with-course" name="radio-group-cert-gift">
                        <label for="gift-with-course">Without Course</label>
                    </p>
                </span>
            </div>





                <!-- first input -->
                <div style="display: flex; width: 100%;">
                    <select name="course-id" id="course-for-certificate-gift">
                        <option selected value="">Search for Your Desired Courses</option>
                        <?php
                        if (count($selectCourses) > 0) {
                            foreach ($selectCourses as $course) {
                                $category = has_term('qls', 'course-cat', $course->ID);
                        ?>
                                <option value="<?php echo $course->ID ?>" data-content="<?php echo $category == true ? 'qls' : '' ?>">
                                    <?php echo $course->post_title; ?>
                                </option>
                        <?php }
                        }
                        ?>
                    </select>
                    <input name="course-type" class="foy-input" type="text" hidden>
                    <button id="proceed-button" name="proceed-button" type="submit">Proceed</button>
                </div>

                <!-- second input -->
                <h1 class="select-course-title">or paste your course link here</h1>
                <?php
                if (isset($linkRedirect)) {
                    echo "<span class='foy-error'>Sorry! Course not found!</span>";
                }
                ?>
                <input class="course-link" type="text" name="course-link" id="course-id-input">
                <input class="course-type" type="text" name="course-type" value="" hidden>
                <button id="foy-link-ap" name="foy-link-ap" type="submit" disabled>Apply</button>
            </form>

        </div>
        <!-- <div class="foy-certificate-middle-section">
            <h1 class="select-course-title">or paste your course link here</h1>
            <?php
            if (isset($linkRedirect)) {
                //echo "<span class='foy-error'>Sorry! Course not found!</span>";
            }
            ?>
            <form id="foy-link-select" action="" method="POST">
                <input class="course-link" type="text" name="course-link" id="course-id-input">
                <input class="course-type" type="text" name="course-type" value="" hidden>

                <button id="foy-link-ap" name="foy-link-ap" type="submit" disabled>Apply</button>
            </form>
        </div> -->


    </div>
</section>


<script>
    const form = document.querySelector('#foy-select');
    const input = document.querySelector('.foy-input');
    const select = document.querySelector('#course-for-certificate-gift');

    const proceedButton = document.querySelector('#proceed-button');
    // proceedButton.disabled = true;
    // Enable the button when an option is selected
    // select.addEventListener('change', () => {
    //     if (select.value == '') {
    //         proceedButton.disabled = true;
    //     } else {
    //         proceedButton.disabled = false;
    //     }
    // });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const selectedOption = select.options[select.selectedIndex];
        const dataContent = selectedOption.dataset.content;
        input.value = dataContent;
        form.submit();
    });
</script>

<script>
    const courseIdInput = document.getElementById('course-id-input');
    const applyButton = document.getElementById('foy-link-ap');
    courseIdInput.addEventListener('input', function() {

        var courseLink = courseIdInput.value;
        if (courseLink.length > 0) {
            applyButton.removeAttribute('disabled');
        } else {
            applyButton.setAttribute('disabled', '');
        }
    });
</script>
<script>
    jQuery("#course-for-certificate-gift").chosen({
        no_results_text: "Oops, nothing found!",
        width: "100%",
        // height: "56px",
    });
</script>

<?php
get_footer(vibe_get_footer());

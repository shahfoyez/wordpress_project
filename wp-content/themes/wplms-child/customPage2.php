<?php
/* Template Name: Custom Page 2*/
get_header(vibe_get_header());
?>
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
        if (isset($_POST['foy-link-ap'])) {
            // Get the course link
            $course_link = $_POST['course-link'];

            // Extract the path from the URL
            $path = parse_url($course_link, PHP_URL_PATH);

            // Get the last segment of the path, which should be the course slug
            $segments = explode('/', rtrim($path, '/'));
            $course_slug = end($segments);

            // Query the database to find the course ID
            global $wpdb;
            $course_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type = 'course' AND post_name = '$course_slug'");
            $category = has_term( 'qls', 'course-cat', $course_id );
            
        }
    }
?>
<section>
    <?php do_action('wplms_before_title'); ?>
    <div class="container" id="cert-container-page1">
        
        <div class="foy-certificate-top-section">
            <h1 class="select-course-title">Pre-order your certificate</h1>
            <form id="foy-select" action="https://uk.hfonline.org/new-certificate-2023-test/" method="GET">
                <select name="course-id" id="course-for-certificate">
                    <!-- <option selected>Search for Your Desired Courses</option> -->
                    <?php
                        if (count($selectCourses) > 0) {
                            foreach($selectCourses as $course){ 
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
                    ?>
                </select>
                <input name="course-type" class="foy-input" type="text" hidden>
                <button id="proceed-button" name="proceed-button" type="submit">Proceed</button>
            </form>
             
        </div>

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
         

        <div class="foy-certificate-middle-section">
            <h1 class="select-course-title">or paste your course link here</h1>
            <!-- <form id="foy-link-select" action="https://uk.hfonline.org/new-certificate-2023-test/" method="GET">
                <input class="course-link" type="text" name="course-id">
                <input class="course-type" type="text" name="course-type">

                <button name="apply-button" type="submit">Apply</button>
            </form> -->

            <form id="foy-link-select" action="#" method="POST">
                <input class="course-link" type="text" name="course-link" id="course-id-input">
                <input class="course-type" type="text" name="course-type" value="" hidden>

                <button id="foy-link-ap" name="foy-link-ap" type="submit" disabled>Apply</button>
            </form>



            <script>
                const courseIdInput = document.getElementById('course-id-input');
                console.log(courseIdInput);
                const applyButton = document.getElementById('foy-link-ap');
                courseIdInput.addEventListener('input', function() {
                    
                    var courseLink = courseIdInput.value;
                    console.log(courseLink);
                    if (courseLink.length > 0) {
                        applyButton.removeAttribute('disabled');
                    } else {
                        applyButton.setAttribute('disabled', '');
                    }
                });
             
                // Get the course ID from the URL and set it as the value of the course ID input field
                // const urlParams = new URLSearchParams(window.location.search);
                // const courseId = urlParams.get('course-id');
                // document.getElementById('course-id-input').value = courseId;

                // // Set the value of the course type input field to "good"
                // document.querySelector('input[name="course-type"]').value = 'good';

                // // Disable the apply button until the course ID field is filled
                // const applyButton = document.getElementById('apply-button');
                // const courseIdInput = document.getElementById('course-id-input');

                // courseIdInput.addEventListener('input', function() {
                //     if (courseIdInput.value.length > 0) {
                //         applyButton.removeAttribute('disabled');
                //     } else {
                //         applyButton.setAttribute('disabled', '');
                //     }
                // });
            </script>
        
        </div>

        <?php

            // // Get the course link
            // $course_link = 'http://localhost/MyProject/course/software-training/';

            // // Extract the path from the URL
            // $path = parse_url($course_link, PHP_URL_PATH);

            // // Get the last segment of the path, which should be the course slug
            // $segments = explode('/', rtrim($path, '/'));
            // $course_slug = end($segments);

            // // Query the database to find the course ID
            // global $wpdb;
            // $course_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type = 'course' AND post_name = '$course_slug'");

            // // Output the course ID
            // // dd($course_id);
            ?>



        <div class="foy-certificate-bottom-section">
            <h1 class="select-course-title">or select a Course from our popular categories</h1>

        </div>
        
    </div>
</section>

<?php
get_footer(vibe_get_footer());

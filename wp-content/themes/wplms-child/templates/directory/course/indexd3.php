<?php
if (!defined('ABSPATH')) exit;
$id = vibe_get_bp_page_id('course');
require_once get_stylesheet_directory().'/classes/AllCourses.php';
$all_courses = new AllCourses();
$course_categories = $all_courses->GetCategories();
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
    /*category style*/
    .foy-course-categories {
        display: flex;
        justify-content: left;
        flex-wrap: wrap;
        align-items: center;
        gap: 20px;
        margin-bottom: 10px;
    }
    .foy-course-categories li {
        border: 2px solid #E6F0F7;
        padding: 10px 20px;
        border-radius: 10px;
    }
    .foy-course-categories li a {
        color: #2D3748;
        font-family: Inter, serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        margin-left: 10px;
    }
    .foy-course-categories img {
        height: 20px;
        width: 20px;
    }
    @media only screen and (min-width: 1440px){
        .page-id-0 .container {
            width: 1170px;
        }
    }
    @media only screen and (max-width: 600px){
        .foy-course-categories li a {
            font-size: 12px;
        }
        .foy-course-categories img {
            height: 15px;
            width: 15px;
        }
        .foy-course-categories li {
            padding: 10px 15px;
            border-radius: 8px;
        }
        .foy-course-categories li a {
            margin-left: 5px;
        }
        .foy-course-categories {
            gap: 12px;
        }
    }
    #course-order-select #course-order-by {
        height: 32px;
        border-radius: 5px;
        width: 200px;
        background-image: url('http://localhost/MyProject/wp-content/uploads/2023/11/Frame-1000003821.svg') !important;
        background-size: contain;
        border: 1px solid rgba(171, 206, 228, 0.30);
        background-color: #ffffff;
    }
    .pagination .page-numbers.current, .pagination span.current {
        background: #16A5E1;
        border-color: #16A5E1;
        color: #FFF;
        padding: 4px 8px;
        border-radius: 3px;
        font-family: Inter, serif;
        font-size: 12px;
        font-style: normal;
        font-weight: 600;
    }
    .pagination a, .pagination a.page-numbers {
        margin-right: 0;
        margin-left: 5px;
        color: #444;
        background: none;
        border: 1px solid #2D3748;
        text-transform: uppercase;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 3px;
        font-family: Inter, serif;
        font-size: 12px;
        font-style: normal;
    }
    #buddypress .item-list-tabs#subnav {
        padding: 0px !important;
    }
    #buddypress div.pagination .pag-count {
        display: none;
    }
    #buddypress ul.item-list {
        border-top: 1px solid #D5DCE4;
        padding-top: 20px !important;
    }
    .foy-margin-control {
        margin-top: -80px;
    }
    @media only screen and (min-width: 768px){
        #buddypress .item-list-tabs#subnav ul li.last {
            float: left;
            position: absolute;
        }
    }
    input.foy-cat-checkbox {
        pointer-events: none;
        cursor: not-allowed;
        margin: 0;
    }
    li.foy-cat-header {
        background: #FCC;
        text-align: center;
    }
    li.foy-cat-header a{
        color: #2B354E;
        font-variant-numeric: lining-nums tabular-nums;
        font-family: Plus Jakarta Sans, serif;
        font-size: 12px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
</style>
<style>
    /*card style*/
    .a2n_course-card #courses_content {
        /*width: 340px;*/
        padding: 18px 15px;
        border-radius: 12px;
        background: #fff;
        box-shadow: 4.20869px 3.36695px 20.20169px 0px rgba(0, 0, 0, 0.1);
        z-index: 10;
        transition: 0.1s ease-in;
        margin: 0 auto !important;
        overflow: hidden;
        position: relative;
    }
    .a2n_course-card #courses_content .wp-post-image {
        border-radius: 10px;
    }
    .a2n_course-card #courses_content .inner_items img {
        margin-bottom: 4px;
        height: 14px;
    }
    .a2n_course-card #courses_content img.courses_img {
        width: 100%;
    }
    /*.a2n_course-card #courses_content .courses_items {*/
    /*    padding-right: 10px;*/
    /*    padding-bottom: 20px;*/
    /*}*/
    .a2n_course-card .courses_container {
        position: relative;

        /*width: 368px;*/
    }
    .a2n_course-card .courses_container .courses_bottom-shape {
        position: absolute;
        width: 90%;
        height: 67px;
        bottom: -10px;
        left: 50%;
        border-radius: 17px;
        background: #fff;
        box-shadow: 0px 4px 42.08686px 4.20869px rgba(0, 0, 0, 0.15);
        z-index: 1;
        transition: 0.1s ease-in;
        transform: translateX(-50%);
    }
    .a2n_course-card #courses_content .courses_title {
        color: #264e9a;
        font-family: Inter, serif;
        font-size: 19px;
        font-weight: 600;
        margin-bottom: 15px;
        padding-top: 10px;
        text-align: left;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 28px;
    }
    .a2n_course-card #courses_content .inner_items {
        display: flex;
        column-gap: 25px;
        align-items: center;
        margin-bottom: 20px;
    }
    .a2n_course-card #courses_content .inner_items p {
        margin: 0 !important;
        color: #6C778A !important;
        font-family: Inter, serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
    }
    .a2n_course-card #courses_content .courses_ratings {
        padding-bottom: 15px;
        margin-bottom: 15px;
        border-bottom: 1px solid #D9D9D9;
        text-align: left;
        color: #2D3748;
        font-family: Inter, serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
    }
    .a2n_course-card #courses_content .courses_ratings > span {
        color: #ffb800;
        font-family: Inter, serif;
        font-weight: 900;
        padding-right: 3px;
        font-size: 14px;
        font-style: normal;
    }
    .a2n_course-card #courses_content .courses_end > p {
        color: #2d3748;
        font-family: Inter, serif;
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 0 !important;
        width: 50%;
    }
    .a2n_course-card #courses_content .courses_end > p > del {
        color: #6c778a;
        font-family: Inter, serif;
        font-size: 24px;
        font-weight: 400;
        padding-right: 4px;
        opacity: 1;
    }
    .a2n_course-card #courses_content .courses_end {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .a2n_course-card #courses_content .main_button {
        padding: 15px 0px;
        width: 100%;
        text-align: center;
        border-radius: 30px;
        background: linear-gradient(90deg, #004680 0%, #4484ba 100%);
        border: none;
        color: #fff;
        text-decoration: none;
        font-family: Inter, serif;
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }
    .a2n_course-card #courses_content .main_button {
        padding: 15px 0px;
        width: 100%;
        text-align: center;
        border-radius: 30px;
        background: linear-gradient(90deg, #004680 0%, #4484ba 100%);
        border: none;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        margin: 0;
        font-family: Inter, serif;
        font-size: 12px;
        font-style: normal;
    }
    .a2n_course-card #courses_content:hover,
    .a2n_course-card #courses_content:hover + .courses_bottom-shape {
        background: #041C4A;
    }
    .a2n_course-card #courses_content:hover,
    .a2n_course-card #courses_content:hover .courses_title,
    .a2n_course-card #courses_content:hover .inner_items p,
    .a2n_course-card #courses_content:hover .courses_ratings,
    .a2n_course-card #courses_content:hover .courses_end > p,
    .a2n_course-card #courses_content:hover .courses_end > p > del {
        color: #fff;
        opacity: 1;
    }
    .a2n_course-card #courses_content:hover .main_button {
        background:  linear-gradient(90deg, #00C6FB 0%, #005BEA 100%);
    }
    .price-div, .btn-div {
        width: 50%;
    }
    .price-div span.subs {
        display: none;
    }
    del span.woocommerce-Price-amount.amount {
        color: #6C778A;
        font-family: Inter, serif;
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
    }
    ins span.woocommerce-Price-amount.amount {
        color: #2D3748;
        font-family: Inter, serif;
        font-size: 24px;
        font-style: normal;
        font-weight: 600;
    @media (min-width: 768px) and (max-width: 1024px) {
        .a2n_course-card .elementor-main-swiper {
            width: calc(100% - 100px) !important;
        }
        .a2n_course-card #courses_content {
            padding: 8px;
            max-width: 100%;
            margin: 0 auto !important;
        }
        .a2n_course-card .courses_container {
            max-width: 100%;
            margin: 0 auto;
        }
        .a2n_course-card .courses_container .courses_bottom-shape {
            position: absolute;
            width: 85%;
            height: 27px;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }
        .a2n_course-card #courses_content .courses_title {
            font-size: 15px;
            padding-bottom: 8px;
        }
    }
    @media (max-width: 767px){
        .a2n_course-card .main_button{
            font-size:14px;
            padding: 10px 30px;
        }
        .a2n_course-card #courses_content {
            padding: 8px;
            margin: 0 auto !important;
        }
        .a2n_course-card .courses_container {
            position: relative !important;
            width: 100%;
        }
        .a2n_course-card #courses_content #courses_content img {
            margin: 8px auto;
        }
        .a2n_course-card #courses_content .courses_items {
            padding-right: 6px;
            padding-bottom: 8px;
        }
        .a2n_course-card .courses_container .courses_bottom-shape {
            position: absolute;
            height: 27px;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }
        .a2n_course-card #courses_content .courses_title {
            font-size: 15px;
            padding-bottom: 8px;
        }
    }
    @media (max-width: 385px){
        .a2n_course-card #courses_content {
            padding: 8px;
            max-width: 100%;
            margin: 0 auto !important;
        }
        .a2n_course-card .courses_container {
            position: relative !important;
            width: 100%;
        }
        .a2n_course-card #courses_content #courses_content img {
            margin: 8px auto;
        }
        .a2n_course-card #courses_content .courses_items {
            padding-right: 6px;
            padding-bottom: 8px;
        }
        .a2n_course-card .courses_container .courses_bottom-shape {
            position: absolute;
            width: 85%;
            height: 27px;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }
        .a2n_course-card #courses_content .courses_title {
            font-size: 15px;
            padding-bottom: 8px;
        }
        .a2n_course-card .elementor-swiper-button {
            display: none !important;
        }
    }

    @media (max-width:544px){
        .a2n_course-card #courses_content .courses_end > p {
            font-size: 22px;
        }
        .a2n_course-card #courses_content .main_button {
            padding: 10px 0px;
            font-size: 12px;
        }
        .a2n_course-card #courses_content .courses_ratings {
            font-size: 14px;
        }
        .a2n_course-card #courses_content .inner_items p {
            font-size: 14px;
        }
    }
    /* course slider css End*/
</style>
<style>
    .minimal.d3.directory #buddypress div.item-list-tabs#subnav, .minimal.d4.directory #buddypress div.item-list-tabs#subnav {
        border-bottom: none;
    }
    #buddypress .item-list-tabs#subnav {
        background: transparent !important;
    }
    #buddypress .dir-list {
        background: transparent !important;
        padding: 0;
    }
    .bp-user.course #buddypress .item-list.grid, .directory #buddypress .item-list.grid {
        margin: 0;
        padding-top: 15px;
    }
    ul#course-list {
        display: grid !important;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
        padding-top: 0px !important;
    }
    @media only screen and (max-width: 1200px){
        ul#course-list {
            grid-template-columns: 1fr 1fr;
        }
    }
    @media only screen and (max-width: 450px){
        ul#course-list {
            grid-template-columns: 1fr;
        }
    }
    #course-dir-list #course-list .course_single_item {
        width: 100% !important;
        margin: 0px !important;
        padding: 0px !important;
        margin-bottom: 10px !important;
        border: 0px;
    }
    #buddypress ul.item-list.grid li {
        margin-bottom: 0px;
    }
    #content #buddypress {
        padding-top: 30px;
        margin: 0px;
        background: linear-gradient(359deg, #E3F3FF 7.48%, #E3F3FF 38.14%, #F1F9FF 68.32%, #FCFEFF 99.45%);
    }
    .foy-instructor-btn .create-group-button {
        max-width: 300px;
        margin: 0;
        padding: 15px 0px;
        floar: left;
        margin-bottom: 20px;
    }
    .foy-category-div {
        background: #fff;
    }
    .foy-category-div .container {
        padding-top: 50px;
        padding-bottom: 50px;
    }
    #buddypress .item-list-tabs#subnav {
        border: none;
    }
</style>
<style>
    /*header style*/
    #foy-course-header {
        background-image: url('http://localhost/MyProject/wp-content/themes/wplms-child/img/course_top_bg.png');
        padding: 40px 0px 66px 0px;
    }
    #foy-course-header .container{
        max-width: 550px;
        margin: 0 auto;
    }
    .foy-count-section {
        display: flex;
        justify-content: space-between;
        max-width: 482px;
        margin: 0 auto;
        margin-bottom: 20px;
    }
    .foy-dflex{
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .foy-search-main h4{
        text-align: center;
        color: #173E88;
        font-family: Work Sans, serif;
        font-size: 60px;
        font-style: normal;
        font-weight: 700;
    }
    .foy-search-main p{
        color: #2D3748;
        font-family: Inter, serif;
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        margin: 0;
    }
    .foy-search-main form {
        display: flex;
        background: #fff;
        padding: 8px;
        border: 2px solid rgba(171, 206, 228, 0.30);
        border-radius: 10px;
    }
    .foy-search-box input#s {
        margin: 0;
        border: none;
        background: none;
        padding: 0px;
        padding-left: 10px;
        width: 70%;
        height: 45px;
    }
    button.foy-srch-icon {
        border-radius: 6px;
        background: linear-gradient(90deg, #004680 0%, #4484BA 100%);
        color: #FFF;
        font-family: Inter, serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 600;
        width: 30%;
        border: none;
    }
</style>
<style>
    /* Style for the checkbox */
    input.foy-cat-checkbox {
        pointer-events: none;
        cursor: not-allowed;
    }
    .foy-cat-checkbox {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        border: 1px solid #ED4275;
        border-radius: 2px;
        position: relative;
        cursor: pointer;
    }

    /* Style for the checkbox when checked */
    .foy-cat-checkbox:checked {
        background-color: transparent;
        border-color: #ED4275;
    }

    /* Style for the checkmark inside the checkbox */
    .foy-cat-checkbox:checked::before {
        content: '\2713';
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #ED4275;
    }
    li.foy-cat-li {
        display: flex !important;
        align-items: center;
        gap: 10px;
    }
    #buddypress .widget ul li {
        border-bottom: none;
    }
    .foy-cat-top{
        background: #FCC;
        text-align: center;
        margin-bottom: 10px;
    }
    .foy-cat-top a{
        color: #2B354E;
        font-family: Plus Jakarta Sans, serif;
        font-size: 12px;
        font-weight: 500;
    }
    #buddypress .widget ul li {
        padding: 10px 0;
    }
    a.foy-cat-title {
        color: #2B354E;
        font-family: Plus Jakarta Sans, serif;
        font-size: 12px;
        font-weight: 500;
        letter-spacing: 0.24px;
    }

</style>
<section id="title"  style="display: none">
	<?php do_action('wplms_before_title'); ?>
    <div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
            <div class="col-md-12">
                <div class="pagetitle">
					<?php
					if (is_tax()) {
						echo '<h1>';
						single_cat_title();
						echo '</h1>';
						echo do_shortcode(category_description());
					} else {
						echo '<h1>' . vibe_get_title($id) . '</h1>';
						the_sub_title($id);
					}
					?>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="foy-course-header">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="foy-search-main">
                    <h4>
                        All Courses
                    </h4>
                    <div class="foy-count-section">
                        <div class="foy-dflex">
                            <img src="http://localhost/MyProject/wp-content/uploads/2023/11/ic_baseline-people.svg"/>
                            <p>12000+ Students</p>
                        </div>
                        <div class="foy-dflex">
                            <img src="http://localhost/MyProject/wp-content/uploads/2023/11/ion_book-sharp.svg"/>
                            <p>3000+ Courses</p>
                        </div>
                    </div>
                    <div>
                        <form action="<?php echo home_url(); ?>" class="foy-search-box foy-display-desk" autocomplete="off">
                            <input name="post_type" class="foy-input-search" placeholder="Search for your courses..." type="hidden" value="course">
                            <input type="text" name="s" id="s" value="" placeholder="Search for your courses...">
                            <!-- <div id="foy-loading" class="spinner-border" role="status">-->
                            <!-- <img src="<?php echo get_stylesheet_directory_uri('/img/loader.gif')?>" alt="search loader">-->
                            <!-- </div>-->
                            <button type="submit" class="foy-srch-icon" >
                                Find Courses
                            </button>
                        </form>
                        <!-- <div class="foy-suggestion-box" id="foy-suggestion-box" style="display: none;"> </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end custom header section-->
<section>
    <div class="foy-category-div">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <div class="foy-instructor-btn">
                        <?php
                        do_action('wplms_be_instructor_button');
                        ?>
                    </div>
                </div>
                <div class="col-md-9 col-sm-12">
					<?php
					if ($course_categories) {
						$current_url = home_url($wp->request);
						$path = parse_url($current_url, PHP_URL_PATH);
						$lastString = basename($path);
						?>
                        <ul class="foy-course-categories">
<!--                            <li class="--><?php //echo $lastString === 'courses' ? 'foy-current-cat' : ''; ?><!--">-->
<!--                                <img class="foy-cat-img" src="--><?php //echo get_stylesheet_directory_uri().'/img/course_cat.png'; ?><!--" alt="Default Image">-->
<!--                                <a class="foy-cat-title" href="--><?php //echo site_url('courses'); ?><!--">All Courses</a>-->
<!--                            </li>-->
							<?php
							foreach ($course_categories as $category) {
								$cat_slug = $category->slug;
								$thumbnail_url = get_term_meta($category->term_id, 'course_cat_thumbnail_id', true);
								if ($thumbnail_url) {
									$thumbnail_url = wp_get_attachment_image_url($thumbnail_url, 'small');
								}
								?>
                                <li class="<?php echo $lastString == $cat_slug ? 'foy-current-cat' : ''; ?>">
									<?php
									if (!empty($thumbnail_url)) { ?>
                                        <img class="foy-cat-img" src="<?php echo esc_url($thumbnail_url) ?>" alt="<?php echo esc_attr($category->name)?>">
									<?php } else { ?>
                                        <img class="foy-cat-img" src="<?php echo get_stylesheet_directory_uri() .'/img/course_cat.png'; ?>" alt="<?php esc_attr($category->name)?>" alt="Default Image">
									<?php } ?>
                                    <a class="foy-cat-title" href="<?php echo get_term_link($category); ?>">
										<?php echo esc_html($category->name); ?>
                                    </a>
                                </li>
							<?php } ?>
                        </ul>
						<?php
					}else{
						echo "No Category Found";
					}
					?>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="content">
    <div id="buddypress">
        <div class="<?php echo vibe_get_container(); ?>">
			<?php do_action('bp_before_directory_course_page'); ?>
            <div class="padder">
				<?php do_action('bp_before_directory_course'); ?>
                <div class="row">
                    <div class="col-md-9 col-sm-9 col-md-push-3 col-sm-push-3 foy-margin-control">
                        <form action="" method="post" id="course-directory-form" class="dir-form">
							<?php do_action('bp_before_directory_course_content'); ?>
							<?php do_action('template_notices'); ?>
                            <div class="item-list-tabs" id="subnav" role="navigation">
                                <ul>
									<?php do_action('bp_course_directory_course_types'); ?>
                                    <li>
                                        <div class="dir-search" role="search" style="display:none;">
											<?php bp_directory_course_search_form(); ?>
                                        </div>
                                    </li>
                                    <!--                                    <li class="switch_view">-->
                                    <!--                                        <div class="grid_list_wrapper">-->
                                    <!--                                            <a id="list_view"><i class="icon-list-1"></i></a> -->
                                    <!--                                            <a id="grid_view" class="active"><i class="icon-grid"></i></a>-->
                                    <!--                                        </div>-->
                                    <!--                                    </li>-->
                                    <li id="course-order-select" class="last filter">
                                        <label for="course-order-by"><?php _e('Order By:', 'vibe'); ?></label>
                                        <select id="course-order-by">
											<?php
											?>
                                            <option value=""><?php _e('Select Order', 'vibe'); ?></option>
                                            <option value="newest"><?php _e('Newly Published', 'vibe'); ?></option>
                                            <option value="alphabetical"><?php _e('Alphabetical', 'vibe'); ?></option>
                                            <option value="popular"><?php _e('Most Members', 'vibe'); ?></option>
                                            <option value="rated"><?php _e('Highest Rated', 'vibe'); ?></option>
                                            <option value="start_date"><?php _e('Start Date', 'vibe'); ?></option>
											<?php do_action('bp_course_directory_order_options'); ?>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                            <div id="foy-courses-grid">
                                <div id="course-dir-list" class="course dir-list">
									<?php locate_template(array('course/course-loop.php'), true); ?>
                                </div>
                            </div>
							<?php do_action('bp_directory_course_content'); ?>
							<?php wp_nonce_field('directory_course', '_wpnonce-course-filter'); ?>
							<?php do_action('bp_after_directory_course_content'); ?>
                        </form>
                    </div>
                    <div class="col-md-3 col-sm-3  col-md-pull-9 col-sm-pull-9">
						<?php
						$sidebar = apply_filters('wplms_sidebar', 'buddypress', $id);
						if (!function_exists('dynamic_sidebar') || !dynamic_sidebar($sidebar)) : ?>
						<?php endif; ?>
                    </div>
                </div>
				<?php do_action('bp_after_directory_course'); ?>
            </div>
			<?php do_action('bp_after_directory_course_page'); ?>
        </div>
    </div>
</section>
<!--custom footer section -->
<section id="content">
	<?php echo do_shortcode('[elementor-template id="707"]'); ?>
</section>
<!-- end custom footer section-->
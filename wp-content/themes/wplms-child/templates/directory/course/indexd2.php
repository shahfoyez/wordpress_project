<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$id= vibe_get_bp_page_id('course');
?>
<style>
    .foy-course-left-top {
        display: flex;
    }
    .grid .foy-course-left-top {
        display: block;
    }
    .grid .foy-course-title h2{
        text-align: left;
    }
    .grid p.foy-meta-title {
        text-align: left;
    }
    .foy-course-left {
        padding: 10px;
        border-right: 1px solid #ebebeb;
    }
    .grid .foy-course-left {
        padding: 0px;
        border-right: none;
    }
    .foy-course-contents {
        padding: 0px 15px;
    }
</style>
<style>
    .foy-subtitle{
        color: #50617B;
        /*font-family: Work Sans;*/
        font-family: 'Nunito', sans-serif;
        font-size: 13px;
        font-style: normal;
        font-weight: 400;
    }
    .price-div {
        padding: 0px !important;
    }
    .course-dynamic-tags ul li{
        text-align: center;
        font-family: Poppins;
        font-size: 14px !important;
        font-style: normal;
        font-weight: 500;
        border-radius: 4px;
        padding: 2px 12px !important;
    }
    .grid .course-dynamic-tags ul li {
        font-size: 12px !important;
        padding: 0px 7px !important;
        width: auto !important;
        /*margin: 0px 3px !important;*/
    }
    /*.grid .course-dynamic-tags {*/
    /*    display: flex;*/
    /*    justify-content: end;*/
    /*    position: absolute !important;*/
    /*    top: -50px !important;*/
    /*    right: 5px;*/
    /*}*/

    .foy-featured{
        color: #32899C !important;
        background: #F1FBFC;
    }
    .foy-highly-rated{
        color: #CC3838 !important;
        background: #FDEAEA;
    }
    .foy-popular{
        color: #927C3F !important;
        background: #FFF8E6;
    }
    .foy-trending{
        color: #32853E !important;
        background: #EEF8ED;
    }
    .new-all-course-single-des {
        margin: 0 15px;
        position: relative;
    }
    .vertical-border {
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        border-right: 1px solid black; /* Change the color or style as needed */
    }
    .coure-dynamic-rating-and-students ul li {
        display: inline-block !important;
        margin-right: 20px !important;
        color: #50617b;
        line-height: 21px;
        font-size: 14px !important;
        font-weight: 400;
        padding: 0 !important;
    }
    .ks-star-rating {
        width: 70px;
    }
    .ks-star-rating .rating__background {
        fill: #bfc3ca;
        stroke: red;
        stroke-width: 1;
        height: 100%;
        width: 100%;
    }
    .ks-star-rating .rating__value {
        fill: #ffb94b;
        height: 100%;
    }
    #buddypress .coure-dynamic-rating-and-students ul li{
        border-bottom: none !important;
    }

    #course-list li:hover {
        background: white;
        box-shadow: none !important;
        border-radius: 2px;
    }
    @media only screen and (max-width: 991px) {
        .foy-course-excerpt {
            display: none;
        }
    }
    @media only screen and (max-width: 1200px) {

    }


    .grid .foy-course-excerpt{
        display: none;
    }




    /*all courses*/
    .containers-manual {
        max-width: 1027px;
        margin: 0 auto;
        border: 1px solid #efefef;
        border-radius: 10px;

    }
    .new-all-courses-single-block {
        align-items: center;
        background: #fff;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        /*display: grid;*/
        /*grid-template-columns: 1fr 3fr 0.6fr;*/
    }

    .new-all-courses-single-block .the-actual-img img {
        /* transform: scale(0.6, 0.9); */
        border-radius: 10px;
        max-width: 207px;
        height: 140px;
        object-fit: cover;
    }

    /*.the-actual-img {*/
    /*    margin-left: 8px;*/
    /*}*/

    .coure-dynamic-rating-and-students ul li {
        display: inline-block !important;
        margin-right: 20px !important;
        color: #50617b;
        line-height: 21px;
        font-size: 14px !important;
        font-weight: 400;
        padding: 0 !important;
    }

    .new-all-course-price-btns {
        padding: 20px;
        display: block !important;
        flex-direction: column;
        justify-content: space-between;
        align-items: baseline;
        align-content: center;
    }
    .grid .new-all-course-price-btns {
        padding: 10px !important;
    }

    .price-div {
        display: flex;
        flex-direction: column;
        width: 100% !important;
        text-align: right;
    }
    .btn-div {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        align-content: center;
        margin: 0px !important;
    }

    .btn-div {
        margin: 0px !important;
    }

    .course-dynamic-title h2 {
        font-weight: 600;
        line-height: 37px;
        font-size: 27px;
        padding: 0;
        margin: 0;
    }

    .course-dynamic-tags ul li {
        font-size: 14px !important;
        line-height: 21px;
        font-weight: 500;
        padding: 6px !important;
        color: #313b3d;
        display: inline-block !important;
        margin-right: 15px !important;
    }

    li.if-featured-or-highlited {
        background: #f3f4f6;
    }

    li.only-for-specific-offers {
        background: #222222;
        color: #fff !important;
        /* border: 2px dotted #c6b5c9 !important; */
        font-weight: 600 !IMPORTANT;
    }
    .course-dynamic-des p {
        font-size: 14px;
        line-height: 22px;
        font-weight: 400;
        color: #50617b;
    }

    .new-all-course-price-btns .price-div del {
        font-size: 23px;
        font-weight: 500;
        line-height: 25px;
        color: #febdcb;
    }
    .new-all-course-price-btns .price-div span {
        line-height: 28px;
        font-size: 23px;
    }
    .new-all-course-price-btns .price-div span {
        color: #7BA631;
        line-height: 35px;
        font-weight: 700;
        font-size: 34px;
    }


    .new-all-course-price-btns .btn-div a {
        padding: 12px 0px;
        border: 1px solid rgba(123, 166, 49, 0.45);;
        border-radius: 4px;
        margin: 10px 0;
        font-weight: 600;
        font-size: 14px;
        min-width: 164px;
        text-align: center;
        height: 45px;
        line-height: 20px;
        letter-spacing: 0;
    }

    a.view-btn {
        color: #7BA631;
    }

    a.add-to-cart-btn {
        color: #fff;
        background: #f84874;
    }

    .new-all-course-single-des {
        margin: 0 10px;
    }

    .course-dynamic-des p {
        font-size: 14px;
        line-height: 22px;
        font-weight: 400;
        color: #50617b;
        padding-right: 30px;
    }

    .new-all-course-single-des {
        margin: 0 10px;
        margin-right: 30px;
        border-radius: 1px solid;
        border-right: 1px solid #efefef;
    }

    .course-dynamic-tags {
        margin-top: 10px;
    }

    body.directory.course .pagetitle {
        padding: 0 !important;
    }

    body.directory.course div#subnav {
        padding: 0 !important;
        border: none !important;
    }

    body.directory.course ul#course-list {
        border: none !important;
    }

    body.directory.course div#pag-top {
        padding: 0 !important;
    }
    @media only screen and (max-width: 600px){
        .new-all-course-price-btns {
            padding: 10px !important;
        }
    }
    @media only screen and (min-width: 768px) and (max-width: 1023px) {
        .course-dynamic-title h2 {
            line-height: 28px;
            font-size: 18px;
        }

        .course-dynamic-des p {
            font-size: 12px;
            line-height: 20px;
            font-weight: 400;
            color: #50617b;
            padding-right: 10px;
        }

        .new-all-course-single-des {
            margin-right: 30px;
        }

        .new-all-course-price-btns .price-div del {
            font-size: 18px;
            line-height: 18px;
        }


        .new-all-course-price-btns .price-div del {
            font-size: 16px;
            line-height: 16px;
        }

        .new-all-courses-single-block {
            grid-template-columns: 0.5fr 2fr 1fr;
        }

        .new-all-courses-single-block .the-actual-img img {
            max-width: 200px;
            height: 150px;
        }

        .course-dynamic-tags ul li {
            font-size: 12px !important;
        }
    }

    @media only screen and (min-width: 320px) and (max-width: 1200px) {
        .course-dynamic-title h2 {
            line-height: 25px;
            font-size: 14px;
        }

        .course-dynamic-tags ul li {
            font-size: 10px !important;
            line-height: 17px;
            padding: 5px;
            margin-right: 0px;
        }

        .new-all-courses-single-block {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-content: center;
            align-items: center;
        }

        .the-actual-img {
            margin-left: 0;
        }

        .new-all-course-single-des {
            margin: 0 auto;
            border-bottom: 1px solid #efefef;
            border-right: 0;
            width: 100%;
        }

        .course-dynamic-tags ul {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }

        .course-dynamic-title h2 {
            margin-left: 15px;
        }

        .coure-dynamic-rating-and-students ul {
            display: flex;
            flex-direction: row;
            align-items: center;
            align-content: center;
            justify-content: space-between;
        }

        .course-dynamic-des p {
            padding-right: 0;
            text-align: center;
        }

        .price-div {
            display: flex;
            align-items: baseline;
            margin-top: 10px;
            flex-direction: row;
        }

        .new-all-course-price-btns {
            flex-direction: row;
        }

        .new-all-course-price-btns {
            flex-direction: column;
            /* align-items: center; */
        }

        .new-all-courses-single-block .the-actual-img img {
            max-width: 100%;
            height: 100%;
        }
        .btn-div {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            align-content: center;
            width: 100%;
            margin: 0;
        }
        .new-all-course-price-btns {
            width: 100%;
        }

        .new-all-course-price-btns .price-div del {
            margin-left: 10px;
            margin-right: 10px;
            color: #bfc3ca;
        }

        .course-dynamic-des {
            display: none;
        }

        .new-all-courses-single-block {
            position: relative;
        }

        li.if-featured-or-highlited {
            position: absolute;
            top: 20px;
            padding: 5px 10px !important;
        }

        .new-all-courses-single-block {
            position: relative;
        }

        li.if-featured-or-highlited {
            position: absolute !important;
            top: 20px;
            padding: 5px 10px !important;
        }

        li.only-for-specific-offers {
            margin-left: auto !important;
            margin-right: 0px !important;
            margin-top: -25px !important;
        }

        .coure-dynamic-rating-and-students ul li {
            margin: 0 10px;
        }

        .new-all-course-price-btns .btn-div a {
            margin: 10px;
        }

        .course-dynamic-title h2 {
            line-height: 26px;
            font-size: 18px;
            display: block;
            display: -webkit-box;
            width: 100%;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .new-all-course-price-btns .price-div a {
            width: 200px;
            margin: 0 !important;
            font-size: 25px;
            line-height: 21px;
            padding-left: 15px;
        }

        .price-div a {
            width: 175px;
            margin: 0 !important;
            font-size: 35px;
            line-height: 21px;
        }

        .coure-dynamic-rating-and-students {
            padding: 0 10px;
            margin-top: 20px;
        }

        .course-dynamic-tags ul li {
            margin-right: 0 !important;
        }

        /* .new-all-course-price-btns .btn-div a {
          margin-left: auto !important;
        } */

        .new-all-course-price-btns .btn-div {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            align-content: center;
            width: 100%;
            margin: 0 auto !important;
        }
        .new-all-course-price-btns .price-div strong span {
            padding: 0 3px !important;
        }

        .new-all-course-price-btns .btn-div a {
            min-width: 145px;
        }
    }

    @media only screen and (min-width: 992px) and (max-width: 1199px) {
        .item-list.grid .new-all-course-price-btns .btn-div a {
            font-size: 11px;
            min-width: 92px !important;
            letter-spacing: 0px;
        }
    }

    /* Sakib */

    body.directory.course #buddypress ul.item-list li {
        border: navajowhite;
        margin: 0;
        padding: 0px;
        margin-bottom: 12px;
    }

    .ks-star-rating {
        width: 70px;
        margin-left: auto;
    }
    .students {
        text-align: end;
    }
    /* .ks-star-rating svg {
    margin-bottom: 2em;
    } */

    .ks-star-rating .rating__background {
        fill: #bfc3ca;
        stroke: red;
        stroke-width: 1;
        height: 100%;
        width: 100%;
    }

    .ks-star-rating .rating__value {
        fill: #ffb94b;
        height: 100%;
    }

    .price-div a strong {
        font-size: 12px;
        line-height: 16px;
        /* margin-top: 2px !important; */
    }

    .price-div a strong span {
        font-size: 16px !important;
        line-height: 16px;
        margin-left: 5px !important;
        margin-bottom: 5px;
    }

    /* for grid view */
    .item-list.grid .course-dynamic-title h2 {
        line-height: 25px;
        font-size: 14px;
    }

    .item-list.grid .course-dynamic-tags ul li {
        font-size: 10px !important;
        line-height: 17px;
        padding: 5px;
        margin-right: 0px;
    }

    .item-list.grid .new-all-courses-single-block {
        display: block;
        /*justify-content: center;*/
        /*flex-direction: column;*/
        /*align-content: center;*/
        /*align-items: center;*/
    }

    .item-list.grid .the-actual-img {
        margin-left: 0;
    }

    .item-list.grid .new-all-course-single-des {
        margin: 0 auto;
        /*border-bottom: 1px solid #efefef;*/
        border-right: 0;
    }
    .item-list.grid .new-all-course-single-des{
        border-bottom: none !important;
    }

    .item-list.grid .course-dynamic-tags ul {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .item-list.grid .course-dynamic-title h2 {
        margin-left: 15px;
    }

    .item-list.grid .coure-dynamic-rating-and-students ul {
        display: flex;
        flex-direction: row;
        align-items: center;
        align-content: center;
        justify-content: space-between;
    }

    .item-list.grid .course-dynamic-des p {
        padding-right: 0;
        text-align: center;
    }

    .item-list.grid .price-div {
        display: grid;
        grid-template-columns: 0.8fr 0.8fr;
        align-items: baseline;
        margin-top: 10px;
    }

    .item-list.grid .new-all-course-price-btns {
        flex-direction: row;
    }

    .item-list.grid .new-all-course-price-btns {
        flex-direction: column;
        /* align-items: center; */
    }

    .item-list.grid .new-all-courses-single-block .the-actual-img img {
        max-width: 100%;
        height: 100%;
        min-height: 200px;
        max-height: 200px;
    }
    .item-list.grid .btn-div {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        align-content: center;
        width: 100%;
        margin: 0 auto !important;
    }
    .item-list.grid .new-all-course-price-btns {
        width: 100%;
        min-height: 167px;
    }

    .item-list.grid .new-all-course-price-btns .price-div del {
        margin-left: 10px;
        margin-right: 10px;
        color: #bfc3ca;
    }

    .item-list.grid .course-dynamic-des {
        display: none;
    }

    .item-list.grid .new-all-courses-single-block {
        position: relative;
    }

    .item-list.grid li.if-featured-or-highlited {
        position: absolute;
        top: 20px;
        padding: 5px 10px !important;
    }

    .item-list.grid .new-all-courses-single-block {
        position: relative;
    }

    .item-list.grid li.if-featured-or-highlited {
        position: absolute;
        top: 20px;
        padding: 5px 10px !important;
    }

    .item-list.grid li.only-for-specific-offers {
        margin-left: auto;
        margin-right: 0px !important;
        margin-top: -25px;
    }

    .item-list.grid .coure-dynamic-rating-and-students ul li {
        margin: 0 10px;
    }

    .item-list.grid .new-all-course-price-btns .btn-div a {
        margin: 0px;
    }

    .item-list.grid .course-dynamic-title h2 {
        line-height: 26px;
        font-size: 18px;
        display: block;
        display: -webkit-box;
        width: 100%;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Pagination  */
    div#course-dir-pag-bottom a,
    div#course-dir-pag-bottom span {
        height: 40px !important;
        display: inline-block;
        width: 40px;
        text-align: center;
    }

    div#course-dir-count-bottom {
        display: none;
    }

    div#course-dir-pag-bottom {
        clear: both;
        margin: 0 auto;
        text-align: center;
    }

    /* Shoive bhai */
    .item-list.grid .item-list.grid .course-dynamic-title h2 {
        margin-left: 0;
    }

    .item-list.grid .new-all-course-price-btns .btn-div a {
        min-width: 120px;
    }

    .item-list.grid .course-dynamic-tags ul li {
        text-align: left !important;
        margin-bottom: 0 !important;
    }

    .item-list.grid .course-dynamic-tags ul li {
        padding: 5px !important;
        margin-right: 0px !important;
    }

    .item-list.grid li.only-for-specific-offers {
        margin-left: auto !important;
        margin-right: 0px !important;
        margin-top: -25px !important;
    }

    .item-list.grid li.if-featured-or-highlited {
        position: absolute !important;
        top: 20px;
        /* padding: 5px 10px !important; */
    }

    .item-list.grid li.only-for-specific-offers {
        width: 130px !important;
    }

    .item-list.grid .new-all-courses-single-section {
        margin: 0 5px;
    }

    .item-list.grid .new-all-course-single-des {
        width: 100%;
    }

    .item-list.grid .course-dynamic-title h2 {
        text-align: left;
        margin: 0 !important;
        padding: 0 10px;
    }

    .item-list.grid .coure-dynamic-rating-and-students {
        padding: 0 10px;
        margin-top: 20px;
    }

    .item-list.grid .price-div a {
        width: 180px;
        margin: 0 !important;
        font-size: 35px;
        line-height: 15px;
    }

    .new-all-course-price-btns .price-div strong del span {
        font-size: 25px !important;
    }

    .item-list.grid .price-div {
        display: block !important;
        align-items: center !important;
        margin-top: 10px;
        align-content: center;
    }

    .item-list.grid .course-dynamic-title {
        min-height: 55px;
    }

    .new-all-course-price-btns .btn-div .add-to-cart-btn {
        background: #7BA631 !important;
        color: #fff !important;
        border-color: transparent !important;
    }

    .item-list.grid .price-div {
        display: flex !important;
        flex-direction: row;
        width: 100%;
        text-align: justify;
    }

    .item-list.grid .price-div a strong {
        font-size: 18px;
        line-height: 18px;
        /* margin-top: 2px !important; */
    }
</style>
<section id="title">
	<?php do_action('wplms_before_title'); ?>
    <div class="<?php echo vibe_get_container(); ?>">
        <div class="row">
             <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                	<?php 
                		if(is_tax()){
                			echo '<h1>';
                			single_cat_title();
                			echo '</h1>';
                			echo do_shortcode(category_description());
                		}else{
	                		echo '<h1>'.vibe_get_title($id).'</h1>';
	                		the_sub_title($id);
                		} 
                	?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
            	<?php 
            		do_action('wplms_be_instructor_button');	
				?>
            </div>
        </div>
    </div>
</section>
<section id="content">
	<div id="buddypress">
    <div class="<?php echo vibe_get_container(); ?>">

	<?php do_action( 'bp_before_directory_course_page' ); ?>

		<div class="padder">

		<?php do_action( 'bp_before_directory_course' ); ?>
		<div class="row">

			<div class="col-md-9 col-sm-8  col-md-push-3 col-sm-push-4">
				<form action="" method="post" id="course-directory-form" class="dir-form">

					<?php do_action( 'bp_before_directory_course_content' ); ?>

					<?php do_action( 'template_notices' ); ?>

					<div class="item-list-tabs" role="navigation">
						<ul>
							<li class="selected" id="course-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_course_root_slug() ); ?>"><?php printf( __( 'All Courses <span>%s</span>', 'vibe' ), bp_course_get_total_course_count( ) ); ?></a></li>

							<?php if ( is_user_logged_in() ) : ?>

								<li id="course-personal"><a href="<?php echo trailingslashit( bp_loggedin_user_domain() . bp_get_course_slug() ); ?>"><?php printf( __( 'My Courses <span>%s</span>', 'vibe' ), bp_course_get_total_course_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>

								<?php if(is_user_instructor()): ?>
									<li id="course-instructor"><a href="<?php echo trailingslashit( bp_loggedin_user_domain() . bp_get_course_slug()  ); ?>"><?php printf( __( 'Instructing Courses <span>%s</span>', 'vibe' ), bp_course_get_instructor_course_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>
								<?php endif; ?>		
							<?php endif; ?>
							<?php do_action( 'bp_course_directory_filter' ); ?>
						</ul>
					</div><!-- .item-list-tabs -->
					<div class="item-list-tabs" id="subnav" role="navigation">
						<ul>
							<?php do_action( 'bp_course_directory_course_types' ); ?>
							<li>
								<div class="dir-search" role="search">
									<?php bp_directory_course_search_form(); ?>
								</div><!-- #group-dir-search -->
							</li>
							<li class="switch_view">
								<div class="grid_list_wrapper">
									<a id="list_view" class="active"><i class="icon-list-1"></i></a>
									<a id="grid_view"><i class="icon-grid"></i></a>
								</div>
							</li>
							<li id="course-order-select" class="last filter">

								<label for="course-order-by"><?php _e( 'Order By:', 'vibe' ); ?></label>
								<select id="course-order-by">
									<?php
									?>
									<option value=""><?php _e( 'Select Order', 'vibe' ); ?></option>
									<option value="newest"><?php _e( 'Newly Published', 'vibe' ); ?></option>
									<option value="alphabetical"><?php _e( 'Alphabetical', 'vibe' ); ?></option>
									<option value="popular"><?php _e( 'Most Members', 'vibe' ); ?></option>
									<option value="rated"><?php _e( 'Highest Rated', 'vibe' ); ?></option>
									<option value="start_date"><?php _e( 'Start Date', 'vibe' ); ?></option>
									<?php do_action( 'bp_course_directory_order_options' ); ?>
								</select>
							</li>
						</ul>
					</div>
					<div id="course-dir-list" class="course dir-list">
						<?php locate_template( array( 'course/course-loop.php' ), true ); ?>

					</div><!-- #courses-dir-list -->

					<?php do_action( 'bp_directory_course_content' ); ?>

					<?php wp_nonce_field( 'directory_course', '_wpnonce-course-filter' ); ?>

					<?php do_action( 'bp_after_directory_course_content' ); ?>

				</form><!-- #course-directory-form -->
			</div>	
			<div class="col-md-3 col-sm-4  col-md-pull-9 col-sm-pull-8">
				<?php
					$sidebar = apply_filters('wplms_sidebar','buddypress',$id);
	                if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
               	<?php endif; ?>
			</div>
		</div>	
		<?php do_action( 'bp_after_directory_course' ); ?>

		</div><!-- .padder -->
	
	<?php do_action( 'bp_after_directory_course_page' ); ?>
</div><!-- #content -->
</div>
</section>
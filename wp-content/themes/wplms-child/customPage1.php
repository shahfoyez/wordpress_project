<?php
/* Template Name: Custom Page 1*/
get_header(vibe_get_header());
?>
<?php
    require_once get_stylesheet_directory() . '/classes/OrderCertificate.php';
    $order_obj = new OrderCertificate();
    // get user's course count(claimed and purchased and total courses)
    $courseCount = $order_obj->courseCount();
?>
<section>
    <?php do_action('wplms_before_title'); ?>
    <div class="container" id="cert-container-page1">
        <div class="foy-progress">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/pr_1.png" alt="">
        </div>
        <div class="certificate-order-card">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/cert_template.png" alt="">
            <div class="cert-type-title">
                <h1>certification that you merit</h1>
                <h4>certificate section for your enrolled courses</h4>
            </div>
            <div class="progress-container">
                <h4>you earned <?php echo $courseCount['claimed'] ?> out of <?php echo $courseCount['taken'] ?></h4>
                <div class="cert-progress">
                    <div class="cert-bar"style="width:<?php echo ($courseCount['claimed'] * 100) / $courseCount['taken'].'%'; ?>">
                    </div>
                </div>
            </div>
            <a href="<?php echo home_url(); ?>/custom-page-3" class="redirect-url-btn">claim your certificate</a>
        </div>

        <div class="certificate-order-card">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/cert_template.png" alt="">
            <div class="cert-type-title">
                <h1>Pre-order section</h1>
                <h4>Pre-order for any course</h4>
            </div>
            <div class="progress-container">
                <h4>you earned <?php echo $courseCount['claimed'] ?> out of <?php echo $courseCount['all'] ?></h4>
                <div class="cert-progress">
                    <div class="cert-bar" style="width:<?php echo ($courseCount['claimed'] * 100) / $courseCount['all'].'%'; ?>">
                    </div>
                </div>
            </div>
            <a href="<?php echo home_url(); ?>/custom-page-2" class="redirect-url-btn">claim your certificate</a>
        </div>

        <div class="certificate-order-card">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/cert_gift.png" alt="">
            <div class="cert-type-title">
                <h1>Gift Certificate</h1>
                <h4>Gift any certificate to your close ones</h4>
            </div>
            <div class="progress-container">
                <h3>
                    “Share knowledge to create <br> learning processes”
                </h3>
            </div>
            <a href="#" class="redirect-url-btn">claim your certificate</a>
        </div>
    </div>
</section>

<?php
get_footer(vibe_get_footer());

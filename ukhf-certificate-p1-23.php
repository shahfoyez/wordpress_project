<?php
/* Template Name: UKHF-Certificate-P1-23*/
get_header(vibe_get_header());
?>


<section>
    <?php do_action('wplms_before_title'); ?>


    <div class="container" id="cert-container-page1">
        <div class="certificate-order-card">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/survey1.png" alt="">
            <div class="cert-type-title">
                <h1>certification that you merit</h1>
                <h4>certificate section for your enrolled courses</h4>
            </div>
            <div class="progress-container">
                <h4>you earned 2 out of 5</h4>
                <div class="cert-progress">
                    <div class="cert-bar" style="width:75%">
                    </div>
                </div>
            </div>
            <a href="#" class="redirect-url-btn">claim your certificate</a>
        </div>

        <div class="certificate-order-card">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/survey1.png" alt="">
            <div class="cert-type-title">
                <h1>Pre-order section</h1>
                <h4>Pre-order for any course</h4>
            </div>
            <div class="progress-container">
                <h4>you earned 2 out of 3000</h4>
                <div class="cert-progress">
                    <div class="cert-bar" style="width:75%">
                    </div>
                </div>
            </div>
            <a href="#" class="redirect-url-btn">claim your certificate</a>
        </div>

        <div class="certificate-order-card">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/cert-gift-1.png" alt="">
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

        <div class="certificate-order-card">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/img/cert-claim-1.png" alt="">
            <div class="cert-type-title">
                <h1>Claim your certificate</h1>
                <h4>claim your pre-ordered certificate</h4>
            </div>
            <div class="progress-container"></div>
            <a href="#" class="redirect-url-btn">claim your certificate</a>
        </div>
    </div>
</section>

<?php
get_footer(vibe_get_footer());

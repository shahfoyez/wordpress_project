<?php
/* Template Name: Api Test*/
get_header(vibe_get_header());
?>
<?php
    require_once get_stylesheet_directory() . '/classes/ApiTest.php';
    $api_obj = new ApiTest();
    
 
?>
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $insert = $api_obj->insert();
    }
?>
<section>
    <?php do_action('wplms_before_title'); ?>
    <div class="container" id="cert-container-page1">
        <H1>Helloo There</H1>
        <form action="" method="POST">
            <button type="submit">Submit</button>
        </form>
        
    </div>
</section>

<?php
get_footer(vibe_get_footer());

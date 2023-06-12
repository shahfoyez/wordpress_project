<?php
    /* Template Name: copy*/
?>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<section>
   <?php
        if (isset($_GET['foy_auto_coupon'])) { 
            ?>
            <script>
                function foyFunction() {
                    console.log("kdafbg");
                    const coupon = "ALPHAGIFT";
                    jQuery(document).ready(function(){
                        jQuery.ajax({
                            // url: ajaxurl,
                            url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                            type: 'get',
                            data: {
                                'code': coupon,
                                'action': 'save_post_details_form' 
                            },
                            success: function(data) {
                                console.log("gagaet");
                                window.location.replace("https://www.janets.org.uk/cart/?add-to-cart=447602"); 
                            }
                        });
                    }); 
                }
                foyFunction();
            </script>
        <?php }
    ?>
</section>
    
<?php


 
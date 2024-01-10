<?php
//Header File
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
	wp_head();
	?>
</head>
<style>
    .mooc .topmenu>li>a, .mooc nav>.menu>li>a, .sleek .topmenu>li>a, .sleek nav>.menu>li>a {
        color: #000000 !important;
    }
    .sleek .container {
        width: 1615px;
    }
    .home-page header.sleek.fix {
        background: transparent;
    }
    /*search*/
    .foy-header-left {
        display: grid;
        grid-template-columns: .6fr .8fr 2fr;
        gap: 10px;
        align-items: center;
    }
    form.foy-search-box-1 {
        border-radius: 8px;
        border: 1px solid #1F4DBE;
        background: #F6F7F9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .foy-search-box-1 input#s {
        border: none;
        background: transparent;
        margin: 0;
        padding: 10px 15px;
    }
    .foy-search-box-1 button.foy-srch-icon-1 {
        background: transparent;
        color: #5a5a5a;
        border: none;
        padding: 5px 10px;
    }
</style>
<body <?php body_class(); ?>>
<div id="global" class="global">
	<?php
	get_template_part('mobile','sidebar');
	?>
    <div class="pusher">
		<?php
		$fix=vibe_get_option('header_fix');
		?>
        <header class="sleek <?php if(isset($fix) && $fix){echo 'fix';} ?>">
            <div class="<?php echo vibe_get_container(); ?>">
                <div class="row">
                    <div class="col-md-10 col-sm-4 col-xs-4 foy-header-left">
						<?php

						if(is_front_page()){
							echo '<h1 id="logo">';
						}else{
							echo '<h2 id="logo">';
						}
						$url = apply_filters('wplms_logo_url',VIBE_URL.'/assets/images/logo.png','header');
						if(!empty($url)){
							?>
                            <a href="<?php echo vibe_site_url('','logo'); ?>"><img src="<?php  echo vibe_sanitizer($url,'url'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
							<?php
						}
						if(is_front_page()){
							echo '</h1>';
						}else{
							echo '</h2>';
						}
                        ?>
                        <div>
                            <form action="<?php echo home_url(); ?>" class="foy-search-box-1" autocomplete="off">
                                <input name="post_type" class="foy-input-search-1" placeholder="Search for your courses..." type="hidden" value="course">
                                <input type="text" name="s" id="s" value="" placeholder="Search for your courses...">
                                <!-- <div id="foy-loading" class="spinner-border" role="status">-->
                                <!-- <img src="<?php echo get_stylesheet_directory_uri('/img/loader.gif')?>" alt="search loader">-->
                                <!-- </div>-->
                                <button type="submit" class="foy-srch-icon-1" >
                                    <i class="fa fa-search"></i>
                                </button>
                            </form>
                            <!-- <div class="foy-suggestion-box" id="foy-suggestion-box" style="display: none;"> </div>-->
                        </div>

                        <?php

						$args = apply_filters('wplms-main-menu',array(
							'theme_location'  => 'main-menu',
							'container'       => 'nav',
							'menu_class'      => 'menu',
							'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s<li><a id="new_searchicon"><i class="fa fa-search"></i></a></li></ul>',
							'walker'          => new vibe_walker,
							'fallback_cb'     => 'vibe_set_menu'
						));
						wp_nav_menu( $args );
						?>
                    </div>
                    <div class="col-md-2 col-sm-8 col-xs-8">
                        <ul class="topmenu">
							<?php
							if ( function_exists('bp_loggedin_user_link') && is_user_logged_in() ) :
								?>
                                <li>
                                    <a href="<?php bp_loggedin_user_link(); ?>" class="smallimg vbplogin"><?php $n=vbp_current_user_notification_count(); echo ((isset($n) && $n)?'<em></em>':''); bp_loggedin_user_avatar( 'type=full' ); ?><span><?php bp_loggedin_user_fullname(); ?></span>
                                    </a></li>
							<?php
							else :
								?>
                                <li><a href="#login" rel="nofollow" class=" vbplogin"><span><?php _e('LOGIN','vibe'); ?></span></a></li>
							<?php
							endif;

							if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )  || (function_exists('vibe_check_plugin_installed') && vibe_check_plugin_installed( 'woocommerce/woocommerce.php'))) { global $woocommerce;
								?>
                                <li>
                                    <a class="vbpcart"><span class="fa fa-shopping-basket">
                                            <?php echo (($woocommerce->cart->cart_contents_count)?'<em>'.$woocommerce->cart->cart_contents_count.'</em>':''); ?>
                                        </span>
                                    </a>
                                    <div class="woocart"><div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div></div>
                                </li>
								<?php
							}
							?>
                        </ul>
						<?php
						$style = vibe_get_login_style();
						if(empty($style)){
							$style='default_login';
						}
						?>
                        <div id="vibe_bp_login" class="<?php echo vibe_sanitizer($style,'text'); ?>">
							<?php
							vibe_include_template("login/$style.php");
							?>
                        </div>
                    </div>
                    <a id="trigger">
                        <span class="lines"></span>
                    </a>
                </div>
            </div>
        </header>

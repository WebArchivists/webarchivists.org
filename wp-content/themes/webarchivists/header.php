<?php
/**
 * Header.
 *
 * @package WordPress
 * @subpackage Webarchivists
 */
?><!DOCTYPE html>
<!--[if IE 7]><html class="ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 8]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<head>
    <meta charset="utf-8">

    <link rel="shortcut icon" href="img/favicon.png" /> 
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
    
    <title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'webarchivists' ), max( $paged, $page ) );

	?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    <?php $theme_root = get_bloginfo('template_directory'); ?>

    <!--[if lt IE 9]>
        <script src="<?php echo $theme_root ?>/js/respond.min.js"></script>
        <![if lte IE 6]>
        <script src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
        <script> window.attachEvent( "onload", CFInstall.check );</script>
        <![endif]>
    <![endif]-->
    <script src="<?php echo $theme_root ?>/js/libs/modernizr.custom.js"></script>

    <?php
    	/* We add some JavaScript to pages with the comment form
    	 * to support sites with threaded comments (when in use).
    	 */
    	if ( is_singular() && get_option( 'thread_comments' ) )
    		wp_enqueue_script( 'comment-reply' );

    	/* Always have wp_head() just before the closing </head>
    	 * tag of your theme, or you will break many plugins, which
    	 * generally use this hook to add elements to <head> such
    	 * as styles, scripts, and meta tags.
    	 */
    	wp_head();
    ?>
</head>

<body <?php body_class(); ?>>
    <header role="banner" id="header">
        <?php
            $title = esc_attr( get_bloginfo( 'name', 'display' ) );
        ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php echo $title ?>">
            <img class="logo" src="<?php echo $theme_root ?>/img/logo.png" alt="<?php echo $title; ?>">
            <h1>
                <img src="<?php echo $theme_root ?>/img/webarchivists_org.png" alt="<?php echo $title; ?>" />
            </h1>
        </a>
        <ul class="social">
            <li><a title="RSS Feed" class="rss" href="<?php bloginfo('rss2_url'); ?>"><?php echo __('RSS Feed') ?></a></li>
            <li><a title="<?php echo __('Contact us by mail') ?>" class="mail" href="/contact">Mail</a></li>
            <li><a title="<?php echo __('WebArchivists on Twitter') ?>" class="twitter" href="http://twitter.com/webarchivists">Twitter</a></li>
            <li><a title="<?php echo __('WebArchivists on Facebook') ?>" class="facebook" href="http://www.facebook.com/webarchivists">Facebook</a></li>
        </ul>
        <span class="baseline"><?php bloginfo( 'description' ); ?></span>
        <nav role="navigation">
        <?php
            wp_nav_menu(
                array (
                    'menu'            => 'main-menu',
                    'container'       => false,
                    'container_id'    => false,
                    'menu_class'      => false,
                    'menu_id'         => false,
                    'depth'           => 1,
                    'walker'          => new Webarchivists_Walker
                )
            );
        ?>
        </nav>
        <ul class="languages">
            <li><a href="#en" class="active">en</a></li>
            <li><a href="#fr">fr</a></li>
            <li><a href="#de">de</a></li>
            <li><a href="#es">es</a></li>
        </ul>
        <a href="#" class="help-translation"><?php echo __('Help us to translate !') ?></a>
    </header>
    <div id="first-time">
        <div class="message">
            <h2><?php echo __('Oh hello there ! Welcome on webarchivists.org') ?></h2>
            <p><?php echo __('This is your first time on our website, do you want to learn
               more about <strong>web-archives</strong> and why they&rsquo;re good for you?') ?></p>
            <ul class="options">
                <li><a href="#"><?php echo __('<strong>Yes</strong>, please.') ?></a></li>
                <li><a class="nothanks" href="#"><?php echo __('<strong>No</strong>, thank you.') ?></a></li>
            </ul>
        </div>
    </div>

	<?php // get_search_form(); ?>
	<section id="content" role="main">
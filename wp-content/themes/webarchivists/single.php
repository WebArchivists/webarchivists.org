<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Webarchivists
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php /* ?>
    <span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'webarchivists' ) ); ?></span>
    <span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'webarchivists' ) ); ?></span>
    */ ?>

    <?php get_template_part( 'content', 'single' ); ?>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
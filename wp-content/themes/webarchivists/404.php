<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Webarchivists
 */

get_header(); ?>

<article id="post-0" class="error404">
	<header class="entry-header">
		<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'webarchivists' ); ?></h1>
	</header>

	<div class="entry-content">
		<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'webarchivists' ); ?></p>

	</div><!-- .entry-content -->
</article><!-- #post-0 -->

<?php get_footer(); ?>
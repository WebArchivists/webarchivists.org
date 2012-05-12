<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Webarchivists
 */

get_header(); ?>

    <section id="archives" class="posts">

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title">
				<?php if ( is_day() ) : ?>
					<?php printf( __( 'Daily Archives: %s', 'webarchivists' ), '<span>' . get_the_date() . '</span>' ); ?>
				<?php elseif ( is_month() ) : ?>
					<?php printf( __( 'Monthly Archives: %s', 'webarchivists' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'webarchivists' ) ) . '</span>' ); ?>
				<?php elseif ( is_year() ) : ?>
					<?php printf( __( 'Yearly Archives: %s', 'webarchivists' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'webarchivists' ) ) . '</span>' ); ?>
				<?php else : ?>
					<?php _e( 'Blog Archives', 'webarchivists' ); ?>
				<?php endif; ?>
			</h1>
		</header>

		<!--
		<?php while ( have_posts() ) : ?>
			--><?php the_post(); get_template_part( 'content', 'preview' ); ?><!--
		<?php endwhile; ?>
		-->

		<?php webarchivists_content_nav( 'nav-below' ); ?>


	<?php else : ?>

		<article id="post-0" class="post no-results not-found">
			<header class="entry-header">
				<h1 class="entry-title"><?php _e( 'Nothing Found', 'webarchivists' ); ?></h1>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'webarchivists' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .entry-content -->
		</article><!-- #post-0 -->

	<?php endif; ?>
	
	</section>

<?php get_footer(); ?>
<?php
/**
 * The template used to display Tag Archive pages
 *
 * @package WordPress
 * @subpackage Webarchivists
 */

get_header(); ?>

    <section id="tags" class="posts">
    	<?php if ( have_posts() ) : ?>

    		<header class="page-header">
    			<h1 class="page-title"><?php
    				printf( __( 'Tag Archives: %s', 'webarchivists' ), '<span>' . single_tag_title( '', false ) . '</span>' );
    			?></h1>

    			<?php
    				$tag_description = tag_description();
    				if ( ! empty( $tag_description ) )
    					echo apply_filters( 'tag_archive_meta', '<div class="tag-archive-meta">' . $tag_description . '</div>' );
    			?>
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
    				<?php // get_search_form(); ?>
    			</div><!-- .entry-content -->
    		</article><!-- #post-0 -->

    	<?php endif; ?>
    </section>

<?php get_footer(); ?>

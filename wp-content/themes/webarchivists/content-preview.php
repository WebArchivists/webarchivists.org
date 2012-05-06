<?php
/**
 * The template for displaying posts in the Image Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package WordPress
 * @subpackage Webarchivists
 */
?><article id="post-<?php the_ID(); ?>" <?php post_class( 'indexed' ); ?>>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'webarchivists' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
    <div class="entry-content">
    	<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'webarchivists' ) ); ?>
    </div>
    <div class="entry-meta">
    	<?php
    		printf( __( '<a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s" pubdate>%3$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%4$s" title="%5$s" rel="author">%6$s</a></span></span>', 'webarchivists' ),
    			esc_url( get_permalink() ),
    			get_the_date( 'c' ),
    			get_the_date(),
    			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
    			esc_attr( sprintf( __( 'View all posts by %s', 'webarchivists' ), get_the_author() ) ),
    			get_the_author()
    		);
    	?>
    </div>
</article>
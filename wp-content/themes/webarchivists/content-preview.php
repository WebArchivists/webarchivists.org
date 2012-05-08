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
    <header>
        <!-- <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'webarchivists' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2> -->
        <div class="meta">
            <a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark" class="date">
                <time pubdate="<?php echo get_the_date( 'c' ) ?>" datetime=""><?php
                    $dateformat = __( 'F j<\s\u\p>S</\s\u\p>', 'webarchivists' );
        		    echo date_i18n( $dateformat, strtotime( get_the_date( 'c' ) ) );
                ?></time>
            </a>
            <?php webarchivists_comments_info() ?>
            <span class="country de">Germany</span>
        </div>
        <h2>
            <a href="<?php echo esc_url( get_permalink() ) ?>">
                <?php the_title() ?>
            </a>
        </h2>
    </header>
    <p>
        <?php echo get_the_excerpt() ?>
    	<a href="<?php echo esc_url( get_permalink() ) ?>#join-the-discussion" class="more"><?php echo __( 'discuss', 'webarchivists' ) ?></a>
    </p>
</article>
<?php
/**
 * The template for displaying posts on index, tags and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package WordPress
 * @subpackage Webarchivists
 */
?><article id="post-<?php the_ID(); ?>" <?php post_class( 'indexed' ); ?>>
    <header>
        <a class="thumbnail"><?php the_post_thumbnail('category-thumb') ?></a>
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
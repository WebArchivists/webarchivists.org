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
        <?php
            if( get_post_type() == 'link' ) $permalink = esc_url( get_post_meta($post->ID, "link_url", true) );
            else $permalink = esc_url( get_permalink() );
        ?>
        <?php if (has_post_thumbnail( get_the_id() ) ):
        $thumbnail_info = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_id() ), 'category-thumb', false );    
        ?>
        <a href="<?php echo $permalink ?>" class="thumbnail" style="background-image:url(<?php echo $thumbnail_info[0]; ?>)"></a>
        <?php endif;  ?>
        <div class="meta">
            <a href="<?php echo $permalink  ?>" rel="bookmark" class="date">
                <time pubdate="<?php echo get_the_date( 'c' ) ?>" datetime=""><?php
                    $dateformat = __( 'F j<\s\u\p>S</\s\u\p>', 'webarchivists' );
        		    echo date_i18n( $dateformat, strtotime( get_the_date( 'c' ) ) ) . (get_the_date('Y') != date('Y') ? ' '.get_the_date('Y') : '');
                ?></time>
            </a>
            <?php if( get_post_type() !== 'link' ) webarchivists_comments_info() ?>
            <?php /* 
            <span class="country de">Germany</span>
            */ ?>
        </div>
        <h2>
            <a href="<?php echo $permalink ?>">
                <?php the_title() ?>
            </a>
        </h2>
    </header>
    <?php if( get_post_type() === 'link' ) : ?>
        <p><?php echo get_the_content() ?></p>
    <?php else : ?>
    <p>
        <?php echo get_the_excerpt() ?>
    	<a href="<?php echo esc_url( get_permalink() ) ?>#join-the-discussion" class="more"><?php echo __( 'discuss', 'webarchivists' ) ?></a>
    </p>
    <?php endif; ?>
</article>
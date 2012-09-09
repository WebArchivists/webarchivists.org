<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Webarchivists
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="entry-meta">
            <time pubdate="<?php echo get_the_date( 'c' ) ?>" datetime=""><?php
                $dateformat = __( 'F j<\s\u\p>S</\s\u\p>', 'webarchivists' );
    		    echo date_i18n( $dateformat, strtotime( get_the_date( 'c' ) ) );
            ?></time><?php /* - <span class="type">News</span> - <span class="country fr">France</span> */ ?>
            <?php webarchivists_comments_info() ?>
		</div>
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		<?php endif; ?>
	</header><!-- .entry-header -->

    <aside>
        <?php the_post_thumbnail() ?>
        <dl>
            <dt>Published:</dt>
            <dd><time datetime="<?php echo get_the_date( 'c' ) ?>" pubdate><?php the_date() ?></time><?php if (get_the_date('c') !== get_the_modified_date('c')): ?> <time datetime="<?php echo get_the_modified_date( 'c' ) ?>" class="lastrev">(last revision: <?php echo the_modified_date() ?>)</time><?php endif ?></dd>

            <dt>Author(s):</dt>
            <dd><?php the_author() ?></dd>

            <?php /*
            <dt>Organization:</dt>
            <dd>Webarchivists (FR)</dd>
            */ ?>
            
            <?php if(get_the_tags()): ?>
            <dt>Tags:</dt>
            <dd>
                <?php the_tags('<ul class="tags"><li>','</li><li>','</li></ul>'); ?>
            </dd>
            <?php endif ?>
        </dl>
    </aside>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'webarchivists' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div>

    <?php comments_template( '', true ); ?>

</article><!-- #post-<?php the_ID(); ?> -->

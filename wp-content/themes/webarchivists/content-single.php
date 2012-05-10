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
			<?php webarchivists_posted_on(); ?>
            <span class="type">News</span>
            <span class="country fr">France</span>
            <a href="#comments" title="xx comments" class="nb-comments none"><span>No comments</span></a>
		</div>
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		<?php endif; ?>
	</header><!-- .entry-header -->

    <aside>
        <!-- <img src="img/article/thumb.jpg" alt="Image de l’article" /> -->
        <dl>
            <dt>Published:</dt>
            <dd><time datetime="<?php echo get_the_date( 'c' ) ?>" pubdate><?php the_date() ?></time> <time datetime="<?php echo get_the_modified_date( 'c' ) ?>" class="lastrev">(last revision: <?php echo the_modified_date() ?>)</time></dd>

            <dt>Author(s):</dt>
            <dd><?php the_author() ?></dd>

            <dt>Organization:</dt>
            <dd>Webarchivists (FR)</dd>

            <dt>Tags:</dt>
            <dd>
                <?php the_tags('<ul class="tags"><li>','</li><li>','</li></ul>'); ?>
            </dd>
        </dl>
    </aside>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'webarchivists' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div>



    <?php /*
	<footer class="entry-meta">
		<?php
			/* translators: used between list items, there is a space after the comma * /
			$categories_list = get_the_category_list( __( ', ', 'webarchivists' ) );

			/* translators: used between list items, there is a space after the comma * /
			$tag_list = get_the_tag_list( '', __( ', ', 'webarchivists' ) );
			if ( '' != $tag_list ) {
				$utility_text = __( 'This entry was posted in %1$s and tagged %2$s by <a href="%6$s">%5$s</a>. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'webarchivists' );
			} elseif ( '' != $categories_list ) {
				$utility_text = __( 'This entry was posted in %1$s by <a href="%6$s">%5$s</a>. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'webarchivists' );
			} else {
				$utility_text = __( 'This entry was posted by <a href="%6$s">%5$s</a>. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'webarchivists' );
			}

			printf(
				$utility_text,
				$categories_list,
				$tag_list,
				esc_url( get_permalink() ),
				the_title_attribute( 'echo=0' ),
				get_the_author(),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
			);
		?>
		<?php edit_post_link( __( 'Edit', 'webarchivists' ), '<span class="edit-link">', '</span>' ); ?>

		<?php if ( get_the_author_meta( 'description' ) && ( ! function_exists( 'is_multi_author' ) || is_multi_author() ) ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries ?>
		<div id="author-info">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'webarchivists_author_bio_avatar_size', 68 ) ); ?>
			</div><!-- #author-avatar -->
			<div id="author-description">
				<h2><?php printf( __( 'About %s', 'webarchivists' ), get_the_author() ); ?></h2>
				<?php the_author_meta( 'description' ); ?>
				<div id="author-link">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
						<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'webarchivists' ), get_the_author() ); ?>
					</a>
				</div><!-- #author-link	-->
			</div><!-- #author-description -->
		</div><!-- #entry-author-info -->
		<?php endif; ?>
	</footer><!-- .entry-meta -->
	*/ ?>

    <?php comments_template( '', true ); ?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php

get_header(); ?>

    <section id="news" class="posts">

	<?php

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    query_posts( array(
		'post_type' => array( 'post', 'link' ),
		'paged' => $paged ) // for paging links to work
	);

	if ( have_posts() ) : ?>

		<!--
		<?php while ( have_posts() ) : ?>
			--><?php the_post(); get_template_part( 'content', 'preview' ); ?><!--
		<?php endwhile; ?>
		-->

		<?php webarchivists_content_nav( 'nav-below' ); ?>

	<?php else : ?>

		<article class="post no-results not-found">
			<h1><?php _e( 'Nothing Found', 'webarchivists' ); ?></h1>
		</article>

	<?php endif; ?>
	</section>

<?php get_footer(); ?>
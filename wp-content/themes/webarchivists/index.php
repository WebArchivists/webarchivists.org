<?php

get_header(); ?>

    <section id="news" class="posts">

	<?php

    global $wp_query;
    $args = array_merge( $wp_query->query_vars, array( 'post_type' => array( 'post', 'link' ) ) );
    query_posts( $args );

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
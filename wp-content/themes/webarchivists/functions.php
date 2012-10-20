<?php

/*
 * Initialisation du thème
 */
add_action( 'after_setup_theme', 'webarchivists_setup' );

function webarchivists_setup() {

    // traductions
	load_theme_textdomain( 'webarchivists', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'post-thumbnails' );
}

/**
 * Liens Précédent / Suivant
 */

// Ajout des attributs class, title, rel
add_filter('next_posts_link_attributes', 'get_next_posts_link_attributes');
add_filter('previous_posts_link_attributes', 'get_previous_posts_link_attributes');

if (!function_exists('get_next_posts_link_attributes')){
	function get_next_posts_link_attributes($attr){
		$attr = 'rel="next" class="next" title="' . __( 'Next news', 'webarchivists' ) . '"';
		return $attr;
	}
}
if (!function_exists('get_previous_posts_link_attributes')){
	function get_previous_posts_link_attributes($attr){
		$attr = 'rel="prev" class="prev" title="' . __( 'Previous news', 'webarchivists' ) . '"';
		return $attr;
	}
}
// Affichage de la navigation
function webarchivists_content_nav( ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
        <ul class="navigation">
            <?php if ( get_previous_posts_link() ): ?><li>
                <?php previous_posts_link( '&larr; ' . __( 'Previous news', 'webarchivists' ) ); ?>
            </li><?php endif ?>
            <?php if ( get_next_posts_link() ): ?><li>
                <?php next_posts_link( __( 'Next news', 'webarchivists' ) . ' &rarr;' ); ?>
            </li><?php endif ?>
        </ul>
	<?php endif;
}

function webarchivists_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'webarchivists' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'webarchivists' ), get_the_author() ) ),
		get_the_author()
	);
}

/*
 * Informations sur les commentaire d’un contenu
 */

function webarchivists_comments_info() {
    $nb_comments = get_comments_number(); // get_comments_number returns only a numeric value
	if ( $nb_comments == 0 ): ?>
	    <a href="<?php echo esc_url( get_permalink() ) ?>#comments" title="<?php echo __( 'No comments' , 'webarchivists' ) ?>" class="nb-comments none"><span><?php echo __( 'No comments' , 'webarchivists' ) ?></span></a>
	<?php else : ?>
        <a href="<?php echo esc_url( get_permalink() ) ?>#comments" title="<?php echo $nb_comments . ' ' . ( $nb_comments > 1 ? __( 'comments' , 'webarchivists' ) : __( 'comment' , 'webarchivists' ) ) ?>" class="nb-comments<?php if ($nb_comments > 10) echo ' hot' ?>"><?php echo $nb_comments ?><span><?php echo $nb_comments > 1 ? __( 'comments' , 'webarchivists' ) : __( 'comment' , 'webarchivists' ) ?></span></a>
    <?php endif;
}

/*
 * Template des commentaires
 */
function webarchivists_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<article class="post pingback">
		<p><?php _e( 'Pingback:', 'webarchivists' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'webarchivists' ), '<span class="edit-link">', '</span>' ); ?></p>
	</article>
	<?php
			break;
		default :
	?>
	<article id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<footer class="meta">
			<div class="comment-author vcard">
				<?php
				$dateformat = __( 'd/m/Y - g:iA', 'webarchivists' );
    		    $date_i18n = date_i18n( $dateformat, strtotime( get_comment_time( 'c' ) ) );
				printf( '<a class="date" href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
				        esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						$date_i18n,
						esc_url( get_comment_link( $comment->comment_ID ) )
				    );
                ?>
			    <?php echo get_comment_author() ?>
                <?php comment_author_url_link(); ?>
			</div>

			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'webarchivists' ); ?></em>
				<br />
			<?php endif; ?>

		</footer>

		<div class="body">
		    <?php comment_text(); ?>
			<?php edit_comment_link( __( '[Edit]', 'webarchivists' ), '<span class="edit-link">', '</span>' ); ?>
		</div>

	</article>

	<?php
			break;
	endswitch;
}

/*
 * Formulaire de commentaire
 */
function webarchivists_comments_form($args) {
    $args['author'] = '<label for="author">'. __('Name') .'</label><input type="text" id="author" name="author" required aria-required="true" />';
    $args['url'] = '<label for="url">'. __('URL') .'</label><input placeholder="' . __('http://www.address.tld') . '" type="url" id="url" name="url"  />';
    $args['email'] = '<label for="email">'. __('Email') .'</label><input placeholder="' . __('email@domain.tld') . '" type="email" id="email" name="email" required aria-required="true" />';
    return $args;
}
add_filter('comment_form_default_fields','webarchivists_comments_form'); 

/*
 * Support des thumbnails
 */ 
add_theme_support( 'post-thumbnails' ); 

/*
 * Modification des informations de contact d’un utilisateur
 * (Admin et page « about »)
 */
function webarchivists_contactmethods( $contactmethods ) {
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);
    $contactmethods['twitter'] = 'Twitter';
    return $contactmethods;
}
add_filter('user_contactmethods', 'webarchivists_contactmethods');

/*
 * « Walker » du menu de navigation
 */

class Webarchivists_Walker extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="'. esc_attr( $class_names ) . '"';

        $output .= $indent . '<li id="item-'. $item->ID . '"' . $value . $class_names .'>';
//        $output .= $indent . '<li>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
        $item_output .= $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

register_nav_menus( array( 'main-menu' ) );

/**
 * This is a modification of image_downsize() function in wp-includes/media.php
 * we will remove all the width and height references, therefore the img tag 
 * will not add width and height attributes to the image sent to the editor.
 * 
 * @link http://wordpress.stackexchange.com/questions/29881/stop-wordpress-from-hardcoding-img-width-and-height-attributes
 * @param bool false No height and width references.
 * @param int $id Attachment ID for image.
 * @param array|string $size Optional, default is 'medium'. Size of image, either array or string.
 * @return bool|array False on failure, array on success.
 */
function webarchivists_image_downsize( $value = false, $id, $size ) {
    if ( !wp_attachment_is_image($id) )
        return false;

    $img_url = wp_get_attachment_url($id);
    $is_intermediate = false;
    $img_url_basename = wp_basename($img_url);

    // try for a new style intermediate size
    if ( $intermediate = image_get_intermediate_size($id, $size) ) {
        $img_url = str_replace($img_url_basename, $intermediate['file'], $img_url);
        $is_intermediate = true;
    }
    elseif ( $size == 'thumbnail' ) {
        // Fall back to the old thumbnail
        if ( ($thumb_file = wp_get_attachment_thumb_file($id)) && $info = getimagesize($thumb_file) ) {
            $img_url = str_replace($img_url_basename, wp_basename($thumb_file), $img_url);
            $is_intermediate = true;
        }
    }

    // We have the actual image size, but might need to further constrain it if content_width is narrower
    if ( $img_url) {
        return array( $img_url, 0, 0, $is_intermediate );
    }
    return false;
}
add_filter( 'image_downsize', 'webarchivists_image_downsize', 1, 3 );

/*
 * Taille des images sur les listings de posts
 */

add_image_size( 'category-thumb', 225, 9999 );


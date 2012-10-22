<?php
/*
Plugin Name: Post Type Liens
Version: 1.0.0
Author: Denis Hovart
*/

add_action('init', 'create_links_post_type' );
function create_links_post_type()
{
	register_post_type('link',
		array(
			'labels' => array(
			'name' => __('Liens externes'),
			'singular_name' => __('Lien'),
			'add_new_item' => __('Ajouter un lien'),
			'edit_item' => __('Modifier le lien'),
			'new_item' => __('Nouveau lien'),
			'all_items' => __('Tous les liens'),
			'view_item' => __('Consulter les liens'),
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array("slug" => "links"),
			'query_var' => "links",
			'supports' => array('title', 'editor', 'thumbnail'),
		),
		'public' => true,
		'menu_position' => 20
		)
	);
	add_theme_support( 'post-thumbnails' ); 
	add_post_type_support('link', 'thumbnail' );
	add_action("admin_init", "admin_link_init");
	add_action('save_post', 'save_link_custom');
}

function admin_link_init(){ 
	add_meta_box("link_url", __('Url du lien'), "link_url", "link", "normal", "high");
}

function link_url(){     
	global $post;
	$custom = get_post_custom($post->ID); 
	$link_url = $custom["link_url"][0];
	?>
	<input size="101" type="text" value="<?php echo $link_url;?>" name="link_url"/>
	<?php
}

function save_link_custom(){
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
	return $postID;

	update_post_meta($post->ID, "link_url", $_POST["link_url"]); 
}

?>
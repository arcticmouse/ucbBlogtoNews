<?php 
/*
Plugin Name:  UCB News Blog Item Post Type
Plugin URI: http://news.berkeley.edu
Description: Creates Blog Item post type for imported blog items
Author: Public Affairs
Version: 1.0
*/
 
//****************************************************************//
/*		create item type                                          */
//****************************************************************//
function create_blog_item_post_type() {
	$b_labels = array(
		'name'               => _x( 'Blog Items', 'post type general name' ),
		'singular_name'      => _x( 'Blog Item', 'post type singular name' ),
		'menu_name'          => _x( 'Blog items', 'admin menu' ),
		'name_admin_bar'     => _x( 'Blog item', 'add new on admin bar' ),
		'add_new'            => _x( 'Add new', 'blog item' ),
		'add_new_item'       => __( 'Add New Blog Item' ),
		'new_item'           => __( 'New Blog Item' ),
		'edit_item'          => __( 'Edit Blog Item' ),
		'view_item'          => __( 'View Blog Item' ),
		'all_items'          => __( 'All Blog Items' ),
		'search_items'       => __( 'Search Blog Items' ),
		'parent_item_colon'  => __( 'Parent Blog Items:' ),
		'not_found'          => __( 'No blog items found.' ),
		'not_found_in_trash' => __( 'No blog item found in trash.' )
	);

	$b_args = array(
		'labels'             => $b_labels,
        'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'berkeley_blog' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'supports'           => array( 'title', 'editor', 'custom-fields' ),
		'taxonomies'			 => array('category', 'post_tag'),
		'menu_position'		 => 10,
		'menu_icon'			 => 'dashicons-format-aside'
	);

	register_post_type( 'blog_item', $b_args );

}

add_action( 'init', 'create_blog_item_post_type' );



//****************************************************************//
/*		add template                                              */
//****************************************************************//
function my_custom_blog_item_template($template) {
    global $post;

	if ($post->post_type == 'blog_item'){
		if(file_exists(plugin_dir_path(__FILE__) . '/blog-item-template.php'))
			return plugin_dir_path(__FILE__) . '/blog-item-template.php';
	}
	
	return $template;
}

add_filter('single_template', 'my_custom_blog_item_template');
?>

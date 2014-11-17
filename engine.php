<?php 

class FredGoogleMap {

	function __construct() {
			// Hook into the 'init' action
			add_action( 'init', array($this, 'fb_googlemap_post_type', 0 ));
	}
	
	
	
	// Register Custom Post Type
	function fb_googlemap_post_type() {
	
			$labels = array(
			'name'                => _x( 'Shop Locations', 'Post Type General Name', 'emilyfruitcrisps' ),
			'singular_name'       => _x( 'Shop Location', 'Post Type Singular Name', 'emilyfruitcrisps' ),
			'menu_name'           => __( 'Locations', 'emilyfruitcrisps' ),
			'parent_item_colon'   => __( 'Parent Item:', 'emilyfruitcrisps' ),
			'all_items'           => __( 'All Locations', 'emilyfruitcrisps' ),
			'view_item'           => __( 'View Location', 'emilyfruitcrisps' ),
			'add_new_item'        => __( 'Add New Location', 'emilyfruitcrisps' ),
			'add_new'             => __( 'Add New', 'emilyfruitcrisps' ),
			'edit_item'           => __( 'Edit Location', 'emilyfruitcrisps' ),
			'update_item'         => __( 'Update Location', 'emilyfruitcrisps' ),
			'search_items'        => __( 'Search Locations', 'emilyfruitcrisps' ),
			'not_found'           => __( 'Not found', 'emilyfruitcrisps' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'emilyfruitcrisps' ),
		);
		$args = array(
			'label'               => __( 'wheretobuy', 'emilyfruitcrisps' ),
			'description'         => __( 'Where to buy Emily Fruit Crisps', 'emilyfruitcrisps' ),
			'labels'              => $labels,
			'supports'            => array( 'title', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'capability_type'     => 'page',
		);
		register_post_type( 'wheretobuy', $args );
	
	}
	
}
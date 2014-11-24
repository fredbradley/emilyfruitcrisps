<?php 
define("GOOGLE_API_API_KEY", "AIzaSyB7OLfDZEpLy13XGgbn6jaxmQXmY81wpZY");
if ( ! defined( 'RC_TC_BASE_FILE' ) )
    define( 'RC_TC_BASE_FILE', __FILE__ );
if ( ! defined( 'RC_TC_BASE_DIR' ) )
    define( 'RC_TC_BASE_DIR', dirname( RC_TC_BASE_FILE ) );
if ( ! defined( 'RC_TC_PLUGIN_URL' ) )
    define( 'RC_TC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
class FredGoogleMap {

	function __construct() {
			// Hook into the 'init' action
			add_action( 'init', array($this, 'fb_googlemap_post_type'));
			add_filter( 'rwmb_meta_boxes', array($this, 'custom_meta_boxes'));
			add_action('wp_enqueue_scripts', array($this,'enqueue_scripts'));
			add_filter('wp_head', array($this, 'custom_head'));
			add_filter( 'template_include', array($this,'rc_tc_template_chooser'));
			add_filter( 'template_include', array($this,'rc_tc_template_chooser' ));
			add_action('after_setup_theme', array($this, 'add_thumbnail_image'));
	}
	
	function add_thumbnail_image() {
		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Returns template file
	 *
	 * @since 1.0
	 */
	 
	function rc_tc_template_chooser( $template ) {
	 
	    // Post ID
	    $post_id = get_the_ID();
	 
	    // For all other CPT
	    if ( get_post_type( $post_id ) != 'wheretobuy' ) {
	        return $template;
	    } else {
		    return $this->rc_tc_get_template_hierarchy('xml');
	    }
	 
	}
	/**
	 * Get the custom template if is set
	 *
	 * @since 1.0
	 */
	 
	function rc_tc_get_template_hierarchy( $template ) {
	 
	    // Get the template slug
	    $template_slug = rtrim( $template, '.php' );
	    $template = $template_slug . '.php';
	 
	    // Check if a custom template exists in the theme folder, if not, load the plugin template file
	    if ( $theme_file = locate_template( array( 'plugin_template/' . $template ) ) ) {
	        $file = $theme_file;
	    }
	    else {
	        $file = RC_TC_BASE_DIR . '/templates/' . $template;
	    }
	 
	    return apply_filters( 'rc_repl_template_' . $template, $file );
	}
	 
	
	
	function custom_head() {
		?>
		 <style type="text/css">
			#map_canvas, #new_map, #map-canvas { height: 100%; margin: 0; padding: 0; position: initial !important; display:block;transform: none !important;}
			.mrk.USA {display:none;}
			
			
		 </style>
		 <script>
var map;
function initialize() {
  var mapOptions = {
    zoom: 8,
    center: new google.maps.LatLng(-34.397, 150.644)
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
		<?
			
	}
	function enqueue_scripts() {
		//wp_enqueue_script('google-jsapi', 'https://maps.googleapis.com/maps/api/js?key='.GOOGLE_API_API_KEY, array('jquery'), '1.0.0', false);
		wp_enqueue_style( 'store-locator', plugins_url('css/storelocator.css', __FILE__) );
		wp_enqueue_script('handlebars', plugins_url('javascripts/libs/handlebars.min.js', __FILE__), array('jquery'), time(), true);
		wp_enqueue_script( 'mapsapi', '//maps.google.com/maps/api/js?sensor=false', array('jquery'), '3.0.0', true );
		wp_enqueue_script('store-locator', plugins_url('javascripts/plugins/storeLocator/jquery.storelocator.js', __FILE__), array('jquery'), time(), true);
		wp_enqueue_script('fred-script', plugins_url('javascripts/scripts.js', __FILE__), array('jquery'), '1.0.0', false);

	}
	
	
	function custom_meta_boxes($meta_boxes) {
		$prefix = "emily_meta_";
		
		$meta_boxes[] = array(
			'id' => "shop_display_address",
			'title' => "Display Box",
			'pages' => array('wheretobuy'),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name' => "Address Line 1", 
					'id' => $prefix . "address_one",
					'type' => 'text', 
					'placeholder' => __('Eg: 100 High Street', 'fredbradley')
				),
				array(
					'name' => "Address Line 2", 
					'id' => $prefix . "address_two",
					'type' => 'text', 
					'placeholder' => __('(sometimes this is left blank!)', 'fredbradley')
				),
				array(
					'name' => "City", 
					'id' => $prefix . "city",
					'type' => 'text', 
					'placeholder' => __('Eg: London', 'fredbradley')
				),
				array(
					'name' => "County", 
					'id' => $prefix . "state",
					'type' => 'text', 
					'placeholder' => __('Eg: Surrey', 'fredbradley')
				),
				array(
					'name' => "Phone Number", 
					'id' => $prefix . "phone",
					'type' => 'text', 
					'placeholder' => __('Write this as you wish it to be displayed', 'fredbradley')
				),
				array(
					'name' => "Web URL", 
					'id' => $prefix . "website",
					'type' => 'url', 
					'placeholder' => __('The full URL', 'fredbradley')
				),
			)
		);


/*{
        "id":"19",
        "name":"Chipotle Chanhassen",
        "lat":"44.858736",
        "lng":"-93.533661",
        "address":"560 W 79th",
        "address2":"",
        "city":"Chanhassen",
        "state":"MN",
        "postal":"55317",
        "phone":"952-294-0301",
        "web":"http:\/\/www.chipotle.com",
        "hours1":"Mon-Sun 11am-10pm",
        "hours2":"",
        "hours3":""
    },*/

		
		return $meta_boxes;
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
			'supports'            => array( 'title','thumbnail' ),
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
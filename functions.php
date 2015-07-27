<?php

/**
 * Set oEmbed sizes
 */
if ( ! isset( $content_width ) ) $content_width = 785;


add_action( 'genesis_setup','xchildthemex_theme_setup', 15 );
function xchildthemex_theme_setup() {
	define( 'CHILD_THEME_VERSION', '1.0.0' );

	/**
	 * Prevent checking for updates to this theme
	 */
	add_filter( 'http_request_args', 'xchildthemex_prevent_updates', 5, 2 );


	/**
	 * Unregister unneeded parts of Genesis and WordPress
	 */

	// Unenqueue the default style.css file.
	remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

	// Remove widgets my clients never use
	unregister_widget( 'Genesis_Featured_Page' );
	unregister_widget( 'Genesis_Featured_Post' );
	unregister_widget( 'Genesis_User_Profile_Widget' );

	// Remove header right widget area
	unregister_sidebar( 'header-right' );

	// Unregister secondary sidebar
	unregister_sidebar( 'sidebar-alt' );

	// Remove unused page layouts
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );

	remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

	// Remove Genesis in-post SEO settings
	remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );

	// Remove the Genesis 'Scripts' meta box
	remove_post_type_support( 'post', 'genesis-scripts' );
	remove_post_type_support( 'page', 'genesis-scripts' );

	// Remove the Genesis Archive and Blog page templates
	add_filter( 'theme_page_templates', 'xchildthemex_remove_genesis_page_templates' );

	// Remove inline Edit link on pages
	add_filter( 'genesis_edit_post_link', '__return_false' );

	// Hide admin screen columns added by Yoast SEO
	add_action( 'init', 'xchildthemex_maybe_disable_yoast_page_analysis' );

	// Remove Genesis related settings on User Profile
	add_action( 'init', 'xchildthemex_remove_genesis_user_settings' );

	// Remove unnecessary Dashboard widgets
	add_action( 'wp_dashboard_setup', 'xchildthemex_remove_dashboard_widgets' );

	// Remove gallery styling WP inserts
	add_filter( 'use_default_gallery_style', '__return_false' );




	/**
	 * Reposition Genesis elements
	 */

	// Move primary nav into header
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	add_action( 'genesis_header', 'genesis_do_nav', 10 );

	// Move featured image above the post title
	remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
	add_action( 'genesis_entry_header', 'genesis_do_post_image', 8 );

	// Move the comment form above the comments
	remove_action( 'genesis_comment_form', 'genesis_do_comment_form' );
    add_action( 'genesis_comments', 'genesis_do_comment_form', 5 );



    /**
     * Filter content
     */

    // Filter post info meta
    add_filter( 'genesis_post_info', 'xchildthemex_post_info_filter' );

    // Filter copyright text
    add_filter( 'genesis_footer_creds_text', 'xchildthemex_creds_filter' );

    // Filter next page link
    add_filter ( 'genesis_next_link_text' , 'xchildthemex_next_page_link' );

    // Filter previous page link
    add_filter ( 'genesis_prev_link_text' , 'xchildthemex_prev_page_link' );



    /**
     * Modify comments
     */

    // Remove the website field from the comment form
    add_filter( 'genesis_comment_form_args', 'xchildthemex_remove_website_field_from_comments' );
    add_filter( 'comment_form_default_fields', 'xchildthemex_remove_website_field_from_comments' );

    // Modify the comments title text
    add_filter( 'genesis_title_comments', 'xchildthemex_comments_area_title_text' );

    // Modify the text displayed for the comment form
    add_filter( 'comment_form_defaults', 'xchildthemex_comment_form_defaults' );

    // Remove 'author says' from comments
    add_filter( 'comment_author_says_text', 'xchildthemex_comment_author_says_text' );



    /**
     * Change the default Gravatar
     */

    // Change the default gravatar image
    // add_filter( 'avatar_defaults', 'xchildthemex_gravatar' );

    // Change the size of gravatars
    // add_filter( 'genesis_comment_list_args', 'xchildthemex_gravatar_size' );



	/**
	 * Add theme support
	 */

	// Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	// Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );

	// Add support for 3-column footer widgets
	add_theme_support( 'genesis-footer-widgets', 3 );



	/**
	 * Add custom image sizes
	 */

	// add_image_size( 'xchildthemex-featured', 520, 280, TRUE );



	/**
	 * Enqeue scripts and stylesheets
	 */

	// Enqueue header scripts like fonts
	add_action( 'wp_enqueue_scripts', 'xchildthemex_add_header_scripts' );

	// Load scripts in footer like the theme's js file
	add_action( 'genesis_after_footer', 'xchildthemex_add_footer_scripts' );

	// Editor Styles
	add_editor_style( 'assets/css/editor-style.css' );

	// Load fonts in editor
	add_action( 'after_setup_theme', 'xchildthemex_add_editor_styles' );



	/**
	 * Add the page slug as a class on the body tag
	 */
	add_filter( 'body_class', 'xchildthemex_add_body_class' );


	/**
	 * Change the oEmbed width for full-width pages
	 */
	add_action( 'get_header', 'xchildthemex_content_width' );



	/**
	 * Use a custom favicon instead of favicon.ico
	 */
	add_filter( 'genesis_pre_load_favicon', 'xchildthemex_custom_favicon' );



	/**
	 * Create new widget areas
	 */
	$xchildthemex_widget_areas = array (
	    'home-featured-boxes'  => 'Home - Featured Boxes',
	);

	xchildthemex_create_widget_areas( $xchildthemex_widget_areas );




	/**
	 * Execute Shortcodes In Text Widgets
	 */
	// add_filter( 'widget_text', 'do_shortcode' );



	/**
	 * Settings only active during development
	 */

	// Hide the admin bar on the front end
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		add_filter( 'show_admin_bar', '__return_false' );
	}



	/**
	 * Make the site more user friendly
	 */

	// Style the login screen
	add_action( 'login_head', 'xchildthemex_custom_login_style' );

	// Customize the footer text in the admin
	add_filter( 'admin_footer_text', 'xchildthemex_admin_footer' );



	/**
	 * Insert content areas into the theme
	 */

	// Add featured image above the title on single post page
	add_action( 'genesis_entry_header', 'xchildthemex_post_featured_image', 5 );

}





/**
 *
 * Functions called by the theme setup function
 *
 */





function xchildthemex_prevent_updates( $r, $url ) {
    if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) )
        return $r; // Not a theme update request. Bail immediately.

    $themes = unserialize( $r['body']['themes'] );
    unset( $themes[ get_option( 'template' ) ] );
    unset( $themes[ get_option( 'stylesheet' ) ] );
    $r['body']['themes'] = serialize( $themes );
    return $r;
}




function xchildthemex_content_width() {
	if ( 'full-width-content' === genesis_site_layout() ) {
		global $content_width;
        $content_width = 1200;
	}
}



/**
 * Take an array of widget areas and create new widget areas (sidebars)
 * @param  array $widget_array Expecting [id => name, id => name, ...]
 */
function xchildthemex_create_widget_areas( $widget_array ) {
	foreach ( $widget_array as $id => $name ){
	    genesis_register_sidebar( array(
	        'id'   => $id,
	        'name' => $name,
	    ) );
	}
}



/**
 * Create new widget areas
 */

/**
 * Remove the Genesis Archive and Blog page templates
 * @param array $page_templates
 * @return array
 */
function xchildthemex_remove_genesis_page_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}





/**
 * Enqueue header scripts like fonts
 */
function xchildthemex_add_header_scripts() {
	$minornot = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_style( 'xchildthemex-style', CHILD_URL . '/assets/css/style' . $minornot . '.css' );
	wp_enqueue_style( 'xchildthemex-google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );
}


/**
 * Load Google fonts in post editor
 */

function xchildthemex_add_editor_styles() {
    $font_url = str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Lato:300,400,700' );
    add_editor_style( $font_url );
}


/**
 * Load scripts in footer like the theme's js file
 */
function xchildthemex_add_footer_scripts() {
	$minornot = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'xchildthemex-script', CHILD_URL . '/assets/js/theme' . $minornot . '.js', 'jquery' );
}





/**
 * Remove Genesis related settings on User Profile
 */
function xchildthemex_remove_genesis_user_settings() {
	remove_action( 'show_user_profile', 'genesis_user_options_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
	remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );
}





/**
 * Helper function to determine if we're on the right edit screen.
 *
 * @global  $pagenow
 * @param   $post_types array() optional post types we want to check.
 * @return  bool
 */
function xchildthemex_is_edit_screen( $post_types = '' ) {
	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		return false;
	}
	global $pagenow;
	// Return true if we're on any admin edit screen.
	if ( ! $post_types && 'edit.php' === $pagenow ) {
		return true;
	}
	elseif ( $post_types ) {
		// Grab the current post type from the query string and set 'post' as a fallback.
		$current_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'post';
		foreach ( $post_types as $post_type ) {
			// Return true if we're on the edit screen of any user-defined post types.
			if ( 'edit.php' === $pagenow && $post_type === sanitize_key( $current_type ) ) {
				return true;
			}
		}
	}
	return false;
}


/**
 * Conditionally disable the Yoast Page Analysis on admin edit screens.
 *
 * @uses   prefix_is_edit_screen
 * @return NULL if we're not on the right admin screen
 * @author Robert Neu <http://wpbacon.com>
 * @link   http://auditwp.com/wordpress-seo-admin-columns/
 */
function xchildthemex_maybe_disable_yoast_page_analysis() {
	if ( ! xchildthemex_is_edit_screen() ) {
		return;
	}
	// Disable Yoast admin columns.
	add_filter( 'wpseo_use_page_analysis', '__return_false' );
}





/**
 * Remove unnecceasry Dashboard widgets
 */
function xchildthemex_remove_dashboard_widgets() {
	global $wp_meta_boxes;

	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
}





/**
 * Filter post entry meta
 */
function xchildthemex_post_info_filter($post_info) {
	$post_info = '[post_date]';
	return $post_info;
}





/**
 * Filter the credits text
 */
function xchildthemex_creds_filter( $creds ) {
	return '&copy; ' . date( 'Y' ) . '. All Rights Reserved.';
}





/**
 * Filter next page link
 */
function xchildthemex_next_page_link( $text ) {
	return 'Next >';
}

/**
 * Filter prev page link
 */
function xchildthemex_prev_page_link( $text ) {
	return '< Previous';
}





/**
 * Modify text displayed for comment form
 */
function xchildthemex_comment_form_defaults( $defaults ) {
	// Remove email privacy notice
	$defaults['comment_notes_before'] = '';

	// Change Leave a Reply text
	$defaults['title_reply'] = __( 'Leave a Comment' );

	// Change submit button text
	$defaults['label_submit'] = __( 'Post It', 'custom' );

	// Hide allowed tags text
	$defaults['comment_notes_after'] = '';

	return $defaults;
}


/**
 * Modify the comments title text
 */
function xchildthemex_comments_area_title_text() {
	 return __(comments_number( '<h3>No Comments</h3>', '<h3>1 Comment</h3>', '<h3>% Comments</h3>' ), 'genesis' );
}


/**
 * Remove website field from comment form
 */
function xchildthemex_remove_website_field_from_comments( $fields ) {
	if ( isset( $fields['url'] ) ) {
		unset( $fields['url'] );
	}

	if ( isset( $fields['fields']['url'] ) ) {
		unset( $fields['fields']['url'] );
	}

	return $fields;
}


/**
 * Remove "author says" from comments
 */
function xchildthemex_comment_author_says_text() {
	return '';
}




/**
 * Change the default Gravatar
 */

function xchildthemex_gravatar ($avatar) {
	$custom_avatar =  get_stylesheet_directory_uri() . '/assets/img/njc_gravatar.png';
	$avatar[$custom_avatar] = "xchildthemex Gravatar";
	return $avatar;
}


/**
 * Change the size of Gravatars
 */

function xchildthemex_gravatar_size( $args ) {
	$args['avatar_size'] = 65;
	return $args;
}




/**
 * Add the page slug as a class on the body tag
 */

function xchildthemex_add_body_class( $classes ) {
    global $post;
    if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}




/**
* Use a custom favicon instead of favicon.ico
*/
function xchildthemex_custom_favicon( $favicon_url ) {
	return CHILD_URL . '/assets/img/favicon.png';
}





/**
 * Style the login page
 */

function xchildthemex_custom_login_style() {
    ?>
    <style type="text/css">
    body {
    	background: #fff !important;
    }
    h1 a {
    	background: url(<?php echo CHILD_URL .'/assets/img/logo.png'; ?>) 50% 50% no-repeat !important;
    	width: 439px !important;
    	background-size: contain !important;
    	max-width: 100%;
    	color: #20386C;
    }
    .login #nav a, .login #backtoblog a {
    	color: #DA2228;
    	font-size: 15px;
    }
    .login #nav a:hover, .login #backtoblog a:hover {
    	color: #8995b1;
    }
    .login label {
    	color: #20386C;
    }
    .wp-core-ui .button-primary {
    	background: #DA2228 !important;
    	border: 0 !important;
    }
	</style>
	<?php
}





/**
 * Customize footer text in the admin
 */

function xchildthemex_admin_footer() {
	echo '<i>Site design by <u><a href="http://maliandfriends.com" target="_blank">Mali and Friends</a></u></i>';
}





/**
 * Show featured image above post title on individual post page
 */

function xchildthemex_post_featured_image() {
	if ( ! is_singular( 'post' ) )  return;
	the_post_thumbnail( 'xchildthemex-blog-featured' );
}
<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'twentytwentyfive-style','twentytwentyfive-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION

// Copyright Shortcode so that I can display the copyright info
function my_custom_copyright_shortcode() {
	$current_year = date( 'Y' );
	return '&copy; 1997-' . $current_year . ' ON RECORDS';
}
add_shortcode( 'copyright', 'my_custom_copyright_shortcode' );

// CUSTOM POST TYPE FOR MY RELEASES
function releases()
{
	$args = array(
		
		'labels' => array(
			'name' => 'Releases',
			'singular_name' => 'Release',
			'add_new_item' => 'Add New Release',
		),
		'show_in_rest'		=> true,
		'show_ui'			=> true,
		'hierarchical'		=> true,
		'show_in_menu'		=> true,
        'show_in_nav_menus'	=> true,
        'show_in_admin_bar'	=> true,
		'public'			=> true,
		'has_archive'		=> true,
		'menu_icon'			=> 'dashicons-album',
		'supports'			=> array('title', 'editor', 'thumbnail', 'custom-fields', 'revisions'),
		'taxonomies'		=> array( 'albums' ),

	);
	
	register_post_type('music', $args);
}
add_action ('init', 'releases');

// TAXONOMYS FOR MY RELEASES
function release_taxonomys()
{
	
	$albums = array(
		
		'labels' => array(
			'name'			=> 'Albums',
			'singular_name'	=> 'Album',
			'search_items'	=> 'Search Albums',
			'all_items'		=> 'All Albums',
			'edit_item'		=> 'Edit Album',
			'add_new_item'	=> 'Add New Album Type',
			'new_item_name'	=> 'New Album Type',
			
		),
		'show_ui'			=> true,
		'show_in_rest'		=> true,
		'show_admin_column'	=> true,
		'rewrite'			=> array('slug' => 'albums'),
		'public'			=> true,
		'hierarchical'		=> true,
		
	);
	
	register_taxonomy('albums', array('music'), $albums);
}
add_action('init', 'release_taxonomys');

// CREATE BLOCKS FOR STREAMING SERVICES & Lyrics Modal & Filtering Music Releases

add_action('init', 'register_acf_streamedmusic');
function register_acf_streamedmusic() {
	register_block_type( __DIR__ . '/blocks/streamedmusic' );
}

add_action('init', 'register_acf_musicinfo');
function register_acf_musicinfo() {
	register_block_type( __DIR__ . '/blocks/musicinfo' );
	// Register js files in block directories
	// You'll have to name your js file so that it adds the right name space for the block.
	foreach ( glob(__DIR__ . '/blocks/musicinfo/*.js') as $path) {
		$file_name = pathinfo($path, PATHINFO_FILENAME);
		wp_register_script( $file_name, get_stylesheet_directory_uri() . '/blocks/musicinfo/' . $file_name . '.js', '');
	}
}

add_action('init', 'register_releasefilter');
function register_releasefilter() {
    // Register the block editor script
    wp_register_script(
        'releasefilter-editor-script',
        get_stylesheet_directory_uri() . '/blocks/releasefilter/index-filter.js',
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ),
        filemtime(get_stylesheet_directory() . '/blocks/releasefilter/index-filter.js')
    );

    // Register the block frontend script
    wp_register_script(
        'releasefilter-frontend-script',
        get_stylesheet_directory_uri() . '/blocks/releasefilter/script-filter.js',
        array(),
        filemtime(get_stylesheet_directory() . '/blocks/releasefilter/script-filter.js'),
        true
    );

    // Register the block type and associate the editor script with it
    register_block_type( get_stylesheet_directory() . '/blocks/releasefilter', array(
        'editor_script' => 'releasefilter-editor-script',
        'script' => 'releasefilter-frontend-script',
    ));
}

add_theme_support('align-wide');
add_theme_support('editor-styles');

// populate Homepage dropdown with post type releases

function add_music_to_dropdown( $pages ){
    $args = array(
        'post_type' => 'music',
		'posts_per_page' => -1
    );
    $items = get_posts($args);
    $pages = array_merge($pages, $items);

    return $pages;
}
add_filter( 'get_pages', 'add_music_to_dropdown' );

function enable_front_page_music( $query ){
    if('' == $query->query_vars['post_type'] && 0 != $query->query_vars['page_id'])
        $query->query_vars['post_type'] = array( 'music' );
}
add_action( 'pre_get_posts', 'enable_front_page_music' );

// attempt to stop CF7 from loading everywhere
add_filter( 'wpcf7_load_js', '__return_false' ); // To remove contact form 7 plugin from loading on all pages //
add_filter( 'wpcf7_load_css', '__return_false' ); // To remove contact form 7 plugin from loading on all pages //

// to add the contact form on the contact page only //
function load_wpcf7_scripts() {
  if ( is_page('Contact') ) {
    if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
      wpcf7_enqueue_scripts();
    }
    if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
      wpcf7_enqueue_styles();
    }
  }
}
add_action('wp_enqueue_scripts', 'load_wpcf7_scripts');


/**
 * Callback function that returns true if the current page is a WooCommerce page or false if otherwise.
 *
 * @return boolean true for WC pages and false for non WC pages
 */
function is_wc_page() {
	return class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() );
}

add_action( 'template_redirect', 'conditionally_remove_wc_assets' );
/**
 * Remove WC stuff on non WC pages.
 */
function conditionally_remove_wc_assets() {
	// if this is a WC page, abort.
	if ( is_wc_page() ) {
		return;
	}

	// remove WC generator tag
	remove_filter( 'get_the_generator_html', 'wc_generator_tag', 10, 2 );
	remove_filter( 'get_the_generator_xhtml', 'wc_generator_tag', 10, 2 );

	// unload WC scripts
	remove_action( 'wp_enqueue_scripts', [ WC_Frontend_Scripts::class, 'load_scripts' ] );
	remove_action( 'wp_print_scripts', [ WC_Frontend_Scripts::class, 'localize_printed_scripts' ], 5 );
	remove_action( 'wp_print_footer_scripts', [ WC_Frontend_Scripts::class, 'localize_printed_scripts' ], 5 );

	// remove "Show the gallery if JS is disabled"
	remove_action( 'wp_head', 'wc_gallery_noscript' );

	// remove WC body class
	remove_filter( 'body_class', 'wc_body_class' );
}

add_filter( 'woocommerce_enqueue_styles', 'conditionally_woocommerce_enqueue_styles' );
/**
 * Unload WC stylesheets on non WC pages
 *
 * @param array $enqueue_styles
 */
function conditionally_woocommerce_enqueue_styles( $enqueue_styles ) {
	return is_wc_page() ? $enqueue_styles : array();
}

add_action( 'wp_enqueue_scripts', 'conditionally_wp_enqueue_scripts' );
/**
 * Remove inline style on non WC pages
 */
function conditionally_wp_enqueue_scripts() {
	if ( ! is_wc_page() ) {
		wp_dequeue_style( 'woocommerce-inline' );
	}
}

// add_action( 'init', 'remove_wc_custom_action' );
function remove_wc_custom_action(){
	remove_action( 'wp_head', 'wc_gallery_noscript' );
}

function meta_description_r(){
if( is_single() || is_page() ) { ?>
 <meta name="description" content="<?= wp_strip_all_tags( get_the_excerpt(), true ); ?>">
<?php } }
add_action('wp_head', 'meta_description_r');

// remove "home" from breadcrumbs in Woo Store
add_filter('woocommerce_breadcrumb_defaults', function( $defaults ) {
    unset($defaults['home']); //removes home link.
    return $defaults; //returns rest of links
});

// Add Meta Pixel Code
function add_meta_pixel_code() {
	?>
		<!-- Meta Pixel Code -->
		<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '2317509318571048');
		fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=2317509318571048&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Meta Pixel Code -->
	<?php
}
add_action('wp_head','add_meta_pixel_code');
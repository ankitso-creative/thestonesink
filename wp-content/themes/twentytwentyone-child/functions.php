<?php
/**
 * Twenty Twenty-One Child Theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue parent and child styles.
 */
function twentytwentyone_child_enqueue_styles() {
	$child_style_path    = get_stylesheet_directory() . '/style.css';
	$child_style_version = file_exists( $child_style_path ) ? (string) filemtime( $child_style_path ) : wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'twentytwentyone-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'twentytwentyone' )->get( 'Version' )
	);

	wp_enqueue_style(
		'twentytwentyone-child-style',
		get_stylesheet_uri(),
		array( 'twentytwentyone-parent-style' ),
		$child_style_version
	);
}
add_action( 'wp_enqueue_scripts', 'twentytwentyone_child_enqueue_styles' );

/**
 * Enqueue Bootstrap and custom header assets.
 */
function twentytwentyone_child_enqueue_header_assets() {
	$theme_version      = wp_get_theme()->get( 'Version' );
	$header_css_path    = get_stylesheet_directory() . '/assets/css/header.css';
	$footer_css_path    = get_stylesheet_directory() . '/assets/css/footer.css';
	$home_css_path      = get_stylesheet_directory() . '/assets/css/home.css';
	$header_css_version = file_exists( $header_css_path ) ? (string) filemtime( $header_css_path ) : $theme_version;
	$footer_css_version = file_exists( $footer_css_path ) ? (string) filemtime( $footer_css_path ) : $theme_version;
	$home_css_version   = file_exists( $home_css_path ) ? (string) filemtime( $home_css_path ) : $theme_version;

	wp_enqueue_style(
		'bootstrap-5',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
		array(),
		'5.3.3'
	);

	wp_enqueue_style(
		'open-sans',
		'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'twentytwentyone-child-header',
		get_stylesheet_directory_uri() . '/assets/css/header.css',
		array( 'bootstrap-5', 'twentytwentyone-child-style' ),
		$header_css_version
	);

	wp_enqueue_style(
		'twentytwentyone-child-footer',
		get_stylesheet_directory_uri() . '/assets/css/footer.css',
		array( 'bootstrap-5', 'twentytwentyone-child-style' ),
		$footer_css_version
	);

	if ( is_home() || is_front_page() ) {
		wp_enqueue_style(
			'twentytwentyone-child-home',
			get_stylesheet_directory_uri() . '/assets/css/home.css',
			array( 'bootstrap-5', 'twentytwentyone-child-style' ),
			$home_css_version
		);
	}

	wp_enqueue_script(
		'bootstrap-5',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
		array(),
		'5.3.3',
		true
	);

	wp_enqueue_script(
		'twentytwentyone-child-header',
		get_stylesheet_directory_uri() . '/assets/js/header.js',
		array( 'bootstrap-5' ),
		$theme_version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'twentytwentyone_child_enqueue_header_assets' );

/**
 * Add WooCommerce cart count fragments for AJAX cart updates.
 *
 * @param array $fragments Cart fragments.
 * @return array
 */
function twentytwentyone_child_cart_count_fragments( $fragments ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $fragments;
	}

	ob_start();
	?>
	<span class="ssc-cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
	<?php
	$fragments['span.ssc-cart-count'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'twentytwentyone_child_cart_count_fragments' );

/**
 * Bootstrap 5 navigation walker with multi-level dropdown support.
 */
class TwentyTwentyOne_Child_Bootstrap_Nav_Walker extends Walker_Nav_Menu {
	/**
	 * Start submenu level.
	 *
	 * @param string $output Used to append additional content.
	 * @param int    $depth  Depth of menu item.
	 * @param object $args   Menu arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$classes = 0 === $depth ? 'dropdown-menu' : 'dropdown-menu ssc-submenu';
		$output .= "\n$indent<ul class=\"" . esc_attr( $classes ) . "\">\n";
	}

	/**
	 * Start menu item.
	 *
	 * @param string $output Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item.
	 * @param object $args   Menu arguments.
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent       = $depth ? str_repeat( "\t", $depth ) : '';
		$has_children = in_array( 'menu-item-has-children', $item->classes, true );
		$is_active    = array_intersect( array( 'current-menu-item', 'current-menu-ancestor', 'current_page_item' ), $item->classes );

		$li_classes = array_filter(
			array(
				'menu-item',
				0 === $depth ? 'nav-item' : '',
				$has_children ? 'dropdown ssc-dropdown' : '',
				$has_children && $depth > 0 ? 'dropend' : '',
				$is_active ? 'active' : '',
			)
		);

		$output .= $indent . '<li class="' . esc_attr( implode( ' ', $li_classes ) ) . '">';

		$link_classes = array_filter(
			array(
				0 === $depth ? 'nav-link' : 'dropdown-item',
				$has_children ? 'dropdown-toggle' : '',
				$is_active ? 'active' : '',
			)
		);

		$attributes = array(
			'class' => implode( ' ', $link_classes ),
			'href'  => ! empty( $item->url ) ? $item->url : '#',
		);

		if ( $has_children ) {
			$attributes['role']           = 'button';
			$attributes['aria-expanded']  = 'false';
			$attributes['data-bs-toggle'] = 'dropdown';
		}

		if ( $is_active ) {
			$attributes['aria-current'] = 'page';
		}

		$attribute_html = '';
		foreach ( $attributes as $attribute => $value ) {
			$attribute_html .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$output .= '<a' . $attribute_html . '>';
		$output .= esc_html( $title );
		$output .= '</a>';
	}
}

/**
 * Enqueue the custom About page stylesheet.
 */
function twentytwentyone_child_enqueue_about_assets() {
	if ( ! is_page( 'about-the-stone-sink-company' ) && ! is_page_template( 'page-about-the-stone-sink-company.php' ) ) {
		return;
	}

	$about_css_path    = get_stylesheet_directory() . '/assets/css/about.css';
	$about_css_version = file_exists( $about_css_path ) ? (string) filemtime( $about_css_path ) : wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'twentytwentyone-child-about',
		get_stylesheet_directory_uri() . '/assets/css/about.css',
		array( 'bootstrap-5', 'twentytwentyone-child-style' ),
		$about_css_version
	);
}
add_action( 'wp_enqueue_scripts', 'twentytwentyone_child_enqueue_about_assets', 20 );

/**
 * Enqueue the custom Care Guide page stylesheet.
 */
function twentytwentyone_child_enqueue_care_guide_assets() {
	if ( ! is_page( 'care-guide-sinks-and-washstands' ) && ! is_page_template( 'page-care-guide-sinks-and-washstands.php' ) ) {
		return;
	}

	$care_css_path    = get_stylesheet_directory() . '/assets/css/care-guide.css';
	$care_css_version = file_exists( $care_css_path ) ? (string) filemtime( $care_css_path ) : wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'twentytwentyone-child-care-guide',
		get_stylesheet_directory_uri() . '/assets/css/care-guide.css',
		array( 'bootstrap-5', 'twentytwentyone-child-style' ),
		$care_css_version
	);
}
add_action( 'wp_enqueue_scripts', 'twentytwentyone_child_enqueue_care_guide_assets', 20 );


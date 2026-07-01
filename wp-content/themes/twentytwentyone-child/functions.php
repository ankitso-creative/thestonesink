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


/**
 * Enqueue product category archive styling.
 */
function twentytwentyone_child_enqueue_product_category_assets() {
	if ( ! function_exists( 'is_product_category' ) || ! is_product_category() ) {
		return;
	}

	$product_category_css_path    = get_stylesheet_directory() . '/assets/css/product-category.css';
	$product_category_css_version = file_exists( $product_category_css_path ) ? (string) filemtime( $product_category_css_path ) : wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'twentytwentyone-child-product-category',
		get_stylesheet_directory_uri() . '/assets/css/product-category.css',
		array( 'bootstrap-5', 'twentytwentyone-child-style' ),
		$product_category_css_version
	);
}
add_action( 'wp_enqueue_scripts', 'twentytwentyone_child_enqueue_product_category_assets', 20 );
/**
 * Enqueue single product assets.
 */
function twentytwentyone_child_enqueue_single_product_assets() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	$single_css_path    = get_stylesheet_directory() . '/assets/css/single-product.css';
	$single_js_path     = get_stylesheet_directory() . '/assets/js/single-product.js';
	$single_css_version = file_exists( $single_css_path ) ? (string) filemtime( $single_css_path ) : wp_get_theme()->get( 'Version' );
	$single_js_version  = file_exists( $single_js_path ) ? (string) filemtime( $single_js_path ) : wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'twentytwentyone-child-single-product',
		get_stylesheet_directory_uri() . '/assets/css/single-product.css',
		array( 'bootstrap-5', 'twentytwentyone-child-style' ),
		$single_css_version
	);

	wp_enqueue_script(
		'twentytwentyone-child-single-product',
		get_stylesheet_directory_uri() . '/assets/js/single-product.js',
		array(),
		$single_js_version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'twentytwentyone_child_enqueue_single_product_assets', 20 );

/**
 * Use a four-column product grid for product category archives.
 *
 * @param int $columns Current WooCommerce column count.
 * @return int
 */
function twentytwentyone_child_product_category_loop_columns( $columns ) {
	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		return 4;
	}

	return $columns;
}
add_filter( 'loop_shop_columns', 'twentytwentyone_child_product_category_loop_columns', 20 );

/**
 * Render product categories as a navigation fallback.
 *
 * @param string $menu_class Menu class attribute.
 */
function twentytwentyone_child_product_category_nav( $menu_class = 'navbar-nav ssc-menu' ) {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'exclude'    => array( get_option( 'default_product_cat' ) ),
			'parent'     => 0,
			'orderby'    => 'menu_order',
			'order'      => 'ASC',
		)
	);

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return;
	}

	echo '<ul class="' . esc_attr( $menu_class ) . '">';

	foreach ( $terms as $term ) {
		$link = get_term_link( $term );

		if ( is_wp_error( $link ) ) {
			continue;
		}

		$is_active = is_tax( 'product_cat', $term->term_id );
		echo '<li class="nav-item menu-item' . ( $is_active ? ' active' : '' ) . '">';
		echo '<a class="nav-link' . ( $is_active ? ' active' : '' ) . '" href="' . esc_url( $link ) . '"' . ( $is_active ? ' aria-current="page"' : '' ) . '>' . esc_html( $term->name ) . '</a>';
		echo '</li>';
	}

	echo '</ul>';
}

/**
 * Default product category archives to price high-to-low, matching the reference layout.
 *
 * @param string $orderby Default ordering key.
 * @return string
 */
function twentytwentyone_child_product_category_default_orderby( $orderby ) {
	if ( function_exists( 'is_product_category' ) && is_product_category() && empty( $_GET['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return 'price-desc';
	}

	return $orderby;
}
add_filter( 'woocommerce_default_catalog_orderby', 'twentytwentyone_child_product_category_default_orderby', 20 );

/**
 * Show product category labels above product titles on category archives.
 */
function twentytwentyone_child_loop_product_category_label() {
	if ( ! twentytwentyone_child_is_custom_product_loop() ) {
		return;
	}

	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$category_list = wc_get_product_category_list( $product->get_id(), ', ' );

	if ( $category_list ) {
		echo '<div class="ssc-loop-product-cats">' . wp_kses_post( $category_list ) . '</div>';
	}
}
add_action( 'woocommerce_shop_loop_item_title', 'twentytwentyone_child_loop_product_category_label', 5 );

/**
 * Show stock/delivery copy below prices on product category archives.
 */
function twentytwentyone_child_loop_product_stock_copy() {
	if ( ! twentytwentyone_child_is_custom_product_loop() ) {
		return;
	}

	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	if ( $product->is_in_stock() ) {
		$stock_quantity = $product->managing_stock() ? $product->get_stock_quantity() : 0;
		$text           = $stock_quantity && 1 === (int) $stock_quantity ? __( '1 in stock', 'twentytwentyone-child' ) : __( 'IN STOCK for Immediate Free Delivery!', 'twentytwentyone-child' );
		$class          = 'in-stock';
	} else {
		$text  = __( 'Out of stock', 'twentytwentyone-child' );
		$class = 'out-of-stock';
	}

	echo '<p class="ssc-loop-stock ' . esc_attr( $class ) . '">' . esc_html( $text ) . '</p>';
}
add_action( 'woocommerce_after_shop_loop_item_title', 'twentytwentyone_child_loop_product_stock_copy', 15 );

/**
 * Force product category archives to use the child theme category layout.
 *
 * @param string $template Resolved template path.
 * @return string
 */
function twentytwentyone_child_product_category_template( $template ) {
	if ( is_tax( 'product_cat' ) ) {
		$category_template = get_stylesheet_directory() . '/woocommerce/archive-product.php';

		if ( file_exists( $category_template ) ) {
			return $category_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'twentytwentyone_child_product_category_template', 99 );
/**
 * Determine whether the current WooCommerce loop should use custom product card details.
 *
 * @return bool
 */
function twentytwentyone_child_is_custom_product_loop() {
	return ( function_exists( 'is_product_category' ) && is_product_category() ) || ( function_exists( 'is_product' ) && is_product() ) || is_front_page() || is_home();
}

/**
 * Add a stable product card class for custom loop styling and hover states.
 *
 * @param string[]   $classes Product post classes.
 * @param WC_Product $product Product object.
 * @return string[]
 */
function twentytwentyone_child_loop_product_card_classes( $classes, $product ) {
	if ( twentytwentyone_child_is_custom_product_loop() && $product instanceof WC_Product ) {
		$classes[] = 'product-card';
		$classes[] = 'ssc-product-card';
	}

	return array_unique( $classes );
}
add_filter( 'woocommerce_post_class', 'twentytwentyone_child_loop_product_card_classes', 10, 2 );

/**
 * Remove the default WooCommerce loop thumbnail before rendering custom flip markup.
 */
function twentytwentyone_child_disable_default_loop_thumbnail() {
	if ( twentytwentyone_child_is_custom_product_loop() ) {
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'twentytwentyone_child_disable_default_loop_thumbnail', 8 );

/**
 * Render a 3D front/back product image for custom product cards.
 */
function twentytwentyone_child_loop_product_flip_image() {
	if ( ! twentytwentyone_child_is_custom_product_loop() ) {
		return;
	}

	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$gallery_ids = $product->get_gallery_image_ids();
	$back_id     = ! empty( $gallery_ids ) ? (int) $gallery_ids[0] : 0;
	$frame_class = $back_id ? 'ssc-loop-image-frame product-image-flip has-product-image-back' : 'ssc-loop-image-frame product-image-flip no-product-image-back';

	echo '<span class="' . esc_attr( $frame_class ) . '">';
	echo '<span class="product-image-flip-inner">';

	echo $product->get_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		'woocommerce_thumbnail',
		array(
			'class'   => 'product-image-front',
			'loading' => 'lazy',
			'alt'     => esc_attr( $product->get_name() ),
		)
	);

	if ( $back_id ) {
		echo wp_get_attachment_image(
			$back_id,
			'woocommerce_thumbnail',
			false,
			array(
				'class'       => 'product-image-back',
				'loading'     => 'lazy',
				'alt'         => '',
				'aria-hidden' => 'true',
			)
		);
	}

	echo '</span>';
	echo '</span>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'twentytwentyone_child_loop_product_flip_image', 10 );
/**
 * Show enough related products for the single product carousel.
 *
 * @param array $args Related products arguments.
 * @return array
 */
function twentytwentyone_child_related_products_args( $args ) {
	if ( function_exists( 'is_product' ) && is_product() ) {
		$args['posts_per_page'] = 8;
		$args['columns']        = 4;
	}

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'twentytwentyone_child_related_products_args', 20 );
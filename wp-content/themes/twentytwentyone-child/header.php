<?php
/**
 * The child theme header.
 *
 * @package Twenty_Twenty_One_Child
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> <?php echo function_exists( 'twentytwentyone_the_html_classes' ) ? twentytwentyone_the_html_classes() : ''; ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$cart_count = 0;
$cart_url   = '#';

if ( function_exists( 'WC' ) && WC()->cart ) {
	$cart_count = WC()->cart->get_cart_contents_count();
	$cart_url   = wc_get_cart_url();
}
?>

<div id="page" class="site ssc-site">
	<a class="skip-link screen-reader-text" href="#content">
		<?php esc_html_e( 'Skip to content', 'twentytwentyone-child' ); ?>
	</a>

	<header id="masthead" class="ssc-header" role="banner">
		<div class="ssc-announcement">
			<p class="ssc-announcement-text mb-0"><?php esc_html_e( '*FREE SHIPPING ON UK ORDERS - INTERNATIONAL AVAILABLE*', 'twentytwentyone-child' ); ?></p>
			<button class="ssc-top-search ssc-search-toggle" type="button" aria-expanded="false" aria-controls="sscHeaderSearch" aria-label="<?php esc_attr_e( 'Open search', 'twentytwentyone-child' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
					<path d="M10.8 18.1a7.3 7.3 0 1 1 0-14.6 7.3 7.3 0 0 1 0 14.6Zm0-2a5.3 5.3 0 1 0 0-10.6 5.3 5.3 0 0 0 0 10.6Z" fill="currentColor"/>
					<path d="m16.3 15 4.2 4.2-1.4 1.4-4.2-4.2 1.4-1.4Z" fill="currentColor"/>
				</svg>
			</button>
		</div>

		<div id="sscHeaderSearch" class="ssc-search-panel" hidden>
			<form role="search" method="get" class="ssc-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="ssc-header-search-field"><?php esc_html_e( 'Search for:', 'twentytwentyone-child' ); ?></label>
				<input id="ssc-header-search-field" type="search" name="s" placeholder="<?php esc_attr_e( 'Search products', 'twentytwentyone-child' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
				<button type="submit"><?php esc_html_e( 'Search', 'twentytwentyone-child' ); ?></button>
			</form>
		</div>

		<div class="ssc-mainbar">
			<button class="ssc-icon-btn ssc-menu-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sscMobileMenu" aria-controls="sscMobileMenu" aria-label="<?php esc_attr_e( 'Open menu', 'twentytwentyone-child' ); ?>">
				<span class="ssc-burger" aria-hidden="true"></span>
			</button>

			<div class="ssc-brand-box">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a class="ssc-site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				<?php endif; ?>
			</div>

			<div class="ssc-desktop-actions d-none d-lg-flex">
				<nav class="ssc-utility-nav" aria-label="<?php esc_attr_e( 'Utility navigation', 'twentytwentyone-child' ); ?>">
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'twentytwentyone-child' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/reviews/' ) ); ?>"><?php esc_html_e( 'Reviews', 'twentytwentyone-child' ); ?></a>
					<a href="<?php echo esc_url( 'mailto:info@example.com' ); ?>"><?php esc_html_e( 'info@example.com', 'twentytwentyone-child' ); ?></a>
					<a href="<?php echo esc_url( 'tel:08000000000' ); ?>"><?php esc_html_e( '0800 000 000', 'twentytwentyone-child' ); ?></a>
					<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ); ?>"><?php esc_html_e( 'My account', 'twentytwentyone-child' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'twentytwentyone-child' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/inspiration/' ) ); ?>"><?php esc_html_e( 'Inspiration', 'twentytwentyone-child' ); ?></a>
				</nav>

				<div class="dropdown ssc-cart-dropdown">
					<button class="ssc-cart-box dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open mini cart', 'twentytwentyone-child' ); ?>">
						<svg width="31" height="31" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
							<path d="M7 18a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM6.2 6l.6 3h10.7l1.2-3H6.2Zm-3-4H6l.4 2h15.2l-3 7H7.2l.4 2H19v2H6L3.8 4H3.2V2Z" fill="currentColor"/>
						</svg>
						<span class="ssc-cart-count"><?php echo esc_html( $cart_count ); ?></span>
					</button>
					<div class="dropdown-menu dropdown-menu-end ssc-mini-cart">
						<?php if ( function_exists( 'woocommerce_mini_cart' ) ) : ?>
							<?php woocommerce_mini_cart(); ?>
						<?php else : ?>
							<p class="ssc-mini-cart-empty"><?php esc_html_e( 'Your cart is ready for products.', 'twentytwentyone-child' ); ?></p>
						<?php endif; ?>
						<a class="ssc-mini-cart-link" href="<?php echo esc_url( $cart_url ); ?>"><?php esc_html_e( 'View cart', 'twentytwentyone-child' ); ?></a>
					</div>
				</div>
			</div>

			<div class="ssc-mobile-actions d-flex d-lg-none">
				<button class="ssc-icon-btn ssc-search-toggle" type="button" aria-expanded="false" aria-controls="sscHeaderSearch" aria-label="<?php esc_attr_e( 'Open search', 'twentytwentyone-child' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path d="M10.8 18.1a7.3 7.3 0 1 1 0-14.6 7.3 7.3 0 0 1 0 14.6Zm0-2a5.3 5.3 0 1 0 0-10.6 5.3 5.3 0 0 0 0 10.6Z" fill="currentColor"/>
						<path d="m16.3 15 4.2 4.2-1.4 1.4-4.2-4.2 1.4-1.4Z" fill="currentColor"/>
					</svg>
				</button>
				<a class="ssc-icon-btn ssc-mobile-cart" href="<?php echo esc_url( $cart_url ); ?>" aria-label="<?php esc_attr_e( 'View cart', 'twentytwentyone-child' ); ?>">
					<svg width="21" height="21" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path d="M7 18a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM6.2 6l.6 3h10.7l1.2-3H6.2Zm-3-4H6l.4 2h15.2l-3 7H7.2l.4 2H19v2H6L3.8 4H3.2V2Z" fill="currentColor"/>
					</svg>
					<span class="ssc-cart-count"><?php echo esc_html( $cart_count ); ?></span>
				</a>
			</div>
		</div>

		<nav class="ssc-primary-nav d-none d-lg-block" aria-label="<?php esc_attr_e( 'Primary menu', 'twentytwentyone-child' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'navbar-nav ssc-menu',
					'container'      => false,
					'fallback_cb'    => false,
					'depth'          => 3,
					'walker'         => new TwentyTwentyOne_Child_Bootstrap_Nav_Walker(),
				)
			);
			?>
		</nav>
	</header>

	<div class="offcanvas offcanvas-start ssc-offcanvas" tabindex="-1" id="sscMobileMenu" aria-labelledby="sscMobileMenuLabel">
		<div class="offcanvas-header">
			<h2 class="offcanvas-title" id="sscMobileMenuLabel"><?php esc_html_e( 'Menu', 'twentytwentyone-child' ); ?></h2>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Close menu', 'twentytwentyone-child' ); ?>"></button>
		</div>
		<div class="offcanvas-body">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'navbar-nav ssc-mobile-menu',
					'container'      => false,
					'fallback_cb'    => false,
					'depth'          => 3,
					'walker'         => new TwentyTwentyOne_Child_Bootstrap_Nav_Walker(),
				)
			);
			?>
		</div>
	</div>

	<div id="content" class="site-content">
		<div id="primary" class="content-area">
			<main id="main" class="site-main">

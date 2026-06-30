<?php
/**
 * The child theme footer.
 *
 * @package Twenty_Twenty_One_Child
 */

$product_links = array(
	'Bowl Sinks'            => '/product-category/bowl-sinks/',
	'Natural Stone Sinks'   => '/product-category/natural-stone-sinks/',
	'Rectangular Sinks'     => '/product-category/rectangular-sinks/',
	'Stone Basins'          => '/product-category/stone-basins/',
	'Petrified Wood Sinks'  => '/product-category/petrified-wood-sinks/',
	'Pedestal Sinks'        => '/product-category/pedestal-sinks/',
	'Zen Sinks'             => '/product-category/zen-sinks/',
	'Wooden Vanity Units'   => '/product-category/wooden-vanity-units/',
	'Marble Sinks'          => '/product-category/marble-sinks/',
);

$service_links = array(
	'Care Guide'       => '/care-guide/',
	'Delivery'         => '/delivery/',
	'Ts And Cs'        => '/terms-and-conditions/',
	'Track Your Order' => '/track-your-order/',
	'Contact'          => '/contact/',
	'Reclaimed Teak'   => '/reclaimed-teak/',
);

$company_links = array(
	'About Us'        => '/about/',
	'Blog'            => '/blog/',
	'Reclaimed Teak'  => '/reclaimed-teak/',
);
?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer ssc-footer" role="contentinfo">
		<div class="ssc-footer-top">
			<div class="ssc-footer-grid">
				<section class="ssc-footer-column" aria-labelledby="ssc-footer-products-title">
					<h2 id="ssc-footer-products-title"><?php esc_html_e( 'Our Products', 'twentytwentyone-child' ); ?></h2>
					<ul class="ssc-footer-links">
						<?php foreach ( $product_links as $label => $url ) : ?>
							<li><a href="<?php echo esc_url( home_url( $url ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</section>

				<section class="ssc-footer-column" aria-labelledby="ssc-footer-service-title">
					<h2 id="ssc-footer-service-title"><?php esc_html_e( 'Our Service', 'twentytwentyone-child' ); ?></h2>
					<ul class="ssc-footer-links">
						<?php foreach ( $service_links as $label => $url ) : ?>
							<li><a href="<?php echo esc_url( home_url( $url ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</section>

				<section class="ssc-footer-column" aria-labelledby="ssc-footer-company-title">
					<h2 id="ssc-footer-company-title"><?php esc_html_e( 'Our Company', 'twentytwentyone-child' ); ?></h2>
					<ul class="ssc-footer-links">
						<?php foreach ( $company_links as $label => $url ) : ?>
							<li><a href="<?php echo esc_url( home_url( $url ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</section>

				<section class="ssc-footer-column ssc-security" aria-labelledby="ssc-footer-security-title">
					<h2 id="ssc-footer-security-title"><?php esc_html_e( 'Security', 'twentytwentyone-child' ); ?></h2>
					<p><?php esc_html_e( 'Safe And Secure', 'twentytwentyone-child' ); ?></p>
					<ul class="ssc-payment-badges" aria-label="<?php esc_attr_e( 'Accepted payment methods', 'twentytwentyone-child' ); ?>">
						<li><?php esc_html_e( 'VISA', 'twentytwentyone-child' ); ?></li>
						<li><?php esc_html_e( 'PayPal', 'twentytwentyone-child' ); ?></li>
						<li><?php esc_html_e( 'Mastercard', 'twentytwentyone-child' ); ?></li>
						<li><?php esc_html_e( 'Discover', 'twentytwentyone-child' ); ?></li>
						<li><?php esc_html_e( 'Amazon', 'twentytwentyone-child' ); ?></li>
					</ul>
				</section>
			</div>
		</div>

		<div class="ssc-footer-bottom">
			<div class="ssc-footer-brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a class="ssc-footer-site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav class="ssc-footer-nav" aria-label="<?php esc_attr_e( 'Footer product navigation', 'twentytwentyone-child' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'ssc-footer-menu',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>
		</div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
<?php
/**
 * Custom single product content.
 *
 * @package Twenty_Twenty_One_Child
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

if ( ! $product instanceof WC_Product ) {
	return;
}

$attachment_ids = $product->get_gallery_image_ids();
$main_image_id  = $product->get_image_id();
$gallery_ids    = array();

if ( $main_image_id ) {
	$gallery_ids[] = $main_image_id;
}

if ( ! empty( $attachment_ids ) ) {
	$gallery_ids = array_merge( $gallery_ids, $attachment_ids );
}

$gallery_ids = array_values( array_unique( array_filter( array_map( 'absint', $gallery_ids ) ) ) );

if ( empty( $gallery_ids ) ) {
	$gallery_ids[] = 0;
}

$category_names     = wc_get_product_category_list( $product->get_id(), ', ' );
$short_description  = wpautop( $product->get_short_description() );
$shipping_seen      = false;
$short_description  = preg_replace_callback(
	'/<p[^>]*>.*?FREE\s+(?:UK\s+)?SHIPPING.*?<\/p>/is',
	function ( $matches ) use ( &$shipping_seen ) {
		if ( $shipping_seen ) {
			return '';
		}

		$shipping_seen = true;
		return $matches[0];
	},
	$short_description
);
$product_content    = apply_filters( 'the_content', get_the_content() );
$product_content    = preg_replace( '/<p[^>]*>.*?FREE\s+(?:UK\s+)?SHIPPING.*?<\/p>/is', '', $product_content );
$stock_label        = $product->is_in_stock() ? __( 'In stock', 'twentytwentyone-child' ) : __( 'Out of stock', 'twentytwentyone-child' );

if ( $product->managing_stock() && $product->get_stock_quantity() ) {
	$stock_label = sprintf(
		/* translators: %s: stock quantity. */
		_n( '%s in stock', '%s in stock', (int) $product->get_stock_quantity(), 'twentytwentyone-child' ),
		number_format_i18n( (int) $product->get_stock_quantity() )
	);
}
?>

<article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'ssc-single-product', $product ); ?>>
	<nav class="ssc-single-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'twentytwentyone-child' ); ?>">
		<?php woocommerce_breadcrumb(); ?>
	</nav>

	<section class="ssc-product-hero" aria-label="<?php esc_attr_e( 'Product details', 'twentytwentyone-child' ); ?>">
		<div class="ssc-product-gallery" data-ssc-product-gallery>
			<div class="ssc-product-gallery-stage">
				<?php foreach ( $gallery_ids as $index => $image_id ) : ?>
					<figure class="ssc-product-gallery-slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-ssc-gallery-slide="<?php echo esc_attr( $index ); ?>">
						<?php
						if ( $image_id ) {
							echo wp_get_attachment_image(
								$image_id,
								'full',
								false,
								array(
									'class'   => 'ssc-product-gallery-image',
									'loading' => 0 === $index ? 'eager' : 'lazy',
									'alt'     => esc_attr( $product->get_name() ),
								)
							);
						} else {
							echo wc_placeholder_img( 'full' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</figure>
				<?php endforeach; ?>

				<?php if ( count( $gallery_ids ) > 1 ) : ?>
					<button class="ssc-gallery-arrow ssc-gallery-arrow-prev" type="button" data-ssc-gallery-prev aria-label="<?php esc_attr_e( 'Previous image', 'twentytwentyone-child' ); ?>">&#8592;</button>
					<button class="ssc-gallery-arrow ssc-gallery-arrow-next" type="button" data-ssc-gallery-next aria-label="<?php esc_attr_e( 'Next image', 'twentytwentyone-child' ); ?>">&#8594;</button>
				<?php endif; ?>
			</div>

			<?php if ( count( $gallery_ids ) > 1 ) : ?>
				<div class="ssc-product-gallery-thumbs" role="list">
					<?php foreach ( $gallery_ids as $index => $image_id ) : ?>
						<button class="ssc-product-gallery-thumb<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" data-ssc-gallery-thumb="<?php echo esc_attr( $index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View product image %d', 'twentytwentyone-child' ), $index + 1 ) ); ?>">
							<?php
							if ( $image_id ) {
								echo wp_get_attachment_image(
									$image_id,
									'woocommerce_thumbnail',
									false,
									array(
										'loading' => 'lazy',
										'alt'     => '',
									)
								);
							} else {
								echo wc_placeholder_img( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							}
							?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="ssc-product-summary">
			<div class="ssc-product-summary-nav" aria-hidden="true">
				<span>&#8592;</span>
				<span>&#8594;</span>
			</div>

			<h1 class="product_title entry-title"><?php the_title(); ?></h1>
			<div class="ssc-single-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

			<?php if ( trim( wp_strip_all_tags( $short_description ) ) ) : ?>
				<div class="ssc-single-excerpt">
					<?php echo wp_kses_post( $short_description ); ?>
				</div>
			<?php endif; ?>

			<p class="ssc-single-stock <?php echo esc_attr( $product->is_in_stock() ? 'in-stock' : 'out-of-stock' ); ?>"><?php echo esc_html( $stock_label ); ?></p>

			<div class="ssc-single-cart">
				<?php woocommerce_template_single_add_to_cart(); ?>
			</div>

			<?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
				<a class="ssc-paypal-placeholder" href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php esc_html_e( 'Pay with', 'twentytwentyone-child' ); ?> <strong><?php esc_html_e( 'PayPal', 'twentytwentyone-child' ); ?></strong></a>
			<?php endif; ?>

			<div class="ssc-single-meta">
				<?php if ( $product->get_sku() ) : ?>
					<p><strong><?php esc_html_e( 'SKU:', 'twentytwentyone-child' ); ?></strong> <?php echo esc_html( $product->get_sku() ); ?></p>
				<?php endif; ?>
				<?php if ( $category_names ) : ?>
					<p><strong><?php esc_html_e( 'Categories:', 'twentytwentyone-child' ); ?></strong> <?php echo wp_kses_post( $category_names ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="ssc-single-tabs" aria-label="<?php esc_attr_e( 'Product information', 'twentytwentyone-child' ); ?>">
		<div class="ssc-single-tab-labels" role="tablist" aria-orientation="vertical">
			<button class="is-active" type="button" data-ssc-single-tab="description"><?php esc_html_e( 'Description', 'twentytwentyone-child' ); ?></button>
			<button type="button" data-ssc-single-tab="additional"><?php esc_html_e( 'Additional information', 'twentytwentyone-child' ); ?></button>
		</div>
		<div class="ssc-single-tab-panels">
			<div class="ssc-single-tab-panel is-active" data-ssc-single-panel="description">
				<?php echo wp_kses_post( $product_content ); ?>
			</div>
			<div class="ssc-single-tab-panel" data-ssc-single-panel="additional">
				<?php wc_display_product_attributes( $product ); ?>
			</div>
		</div>
	</section>

	<?php woocommerce_output_related_products(); ?>

	<section class="ssc-etsy-reviews" aria-labelledby="ssc-etsy-reviews-title">
		<h2 id="ssc-etsy-reviews-title"><?php esc_html_e( 'What our customers say', 'twentytwentyone-child' ); ?></h2>
		<div class="ssc-etsy-summary">
			<strong><?php esc_html_e( 'Etsy Reviews', 'twentytwentyone-child' ); ?></strong>
			<span>4.9 <span class="ssc-stars-text" aria-label="5 star rating">&#9733;&#9733;&#9733;&#9733;&#9733;</span> <small>(119)</small></span>
		</div>
		<div class="ssc-etsy-review-grid">
			<?php
			$reviews = array(
				array( 'name' => __( 'Etsy buyer', 'twentytwentyone-child' ), 'copy' => __( 'The item was delivered quickly and very well packaged.', 'twentytwentyone-child' ) ),
				array( 'name' => __( 'Michael', 'twentytwentyone-child' ), 'copy' => __( 'Lovely sink, looks beautiful and great quality.', 'twentytwentyone-child' ) ),
				array( 'name' => __( 'Krista', 'twentytwentyone-child' ), 'copy' => __( 'Item is exactly as pictured. Excellent quality.', 'twentytwentyone-child' ) ),
				array( 'name' => __( 'Kingsley', 'twentytwentyone-child' ), 'copy' => __( 'Exceeded our expectations. Thanks so much.', 'twentytwentyone-child' ) ),
			);
			foreach ( $reviews as $review ) :
				?>
				<article class="ssc-etsy-review-card">
					<h3><?php echo esc_html( $review['name'] ); ?></h3>
					<p class="ssc-stars" aria-label="5 star rating">&#9733;&#9733;&#9733;&#9733;&#9733;</p>
					<p><?php echo esc_html( $review['copy'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
</article>

<?php do_action( 'woocommerce_after_single_product' ); ?>
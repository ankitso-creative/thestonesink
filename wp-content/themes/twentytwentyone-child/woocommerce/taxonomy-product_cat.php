<?php
/**
 * Product archive template for the Twenty Twenty-One child theme.
 *
 * @package Twenty_Twenty_One_Child
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$term              = is_product_category() ? get_queried_object() : null;
$term_acf_id       = $term && ! is_wp_error( $term ) ? 'product_cat_' . $term->term_id : '';
$category_title    = woocommerce_page_title( false );
$category_subtitle = '';
$intro_text        = '';
$bottom_content    = '';

if ( $term ) {
	$category_subtitle = function_exists( 'get_field' ) ? (string) get_field( 'ssc_product_cat_intro_heading', $term_acf_id ) : '';
	$intro_text        = function_exists( 'get_field' ) ? (string) get_field( 'ssc_product_cat_intro_text', $term_acf_id ) : '';
	$bottom_content    = function_exists( 'get_field' ) ? (string) get_field( 'ssc_product_cat_bottom_content', $term_acf_id ) : '';

	if ( ! $intro_text && ! empty( $term->description ) ) {
		$intro_text = $term->description;
	}
}

if ( ! $category_subtitle ) {
	$category_subtitle = sprintf(
		/* translators: %s: product category title. */
		__( '%s Collection', 'twentytwentyone-child' ),
		$category_title
	);
}

if ( ! $intro_text ) {
	$intro_text = sprintf(
		/* translators: %s: product category title. */
		__( 'Browse our %s range. Each product is photographed individually, ready to compare, and available to update from the WordPress product category editor.', 'twentytwentyone-child' ),
		strtolower( $category_title )
	);
}

if ( ! $bottom_content ) {
	$bottom_content = sprintf(
		'<h2>%1$s - Product Guide</h2><p>%1$s products are selected for distinctive bathrooms, cloakrooms, and vanity spaces. Use the editable product category fields in WordPress admin to add detailed buying advice, material notes, delivery information, and SEO content for this category.</p><h2>Shop %1$s Today</h2><p>Explore the collection above and choose the piece that best suits your project.</p>',
		esc_html( $category_title )
	);
}
?>

<section id="primary" class="ssc-product-category-page">
	<header class="ssc-product-category-hero">
		<?php if ( function_exists( 'woocommerce_breadcrumb' ) ) : ?>
			<div class="ssc-product-category-breadcrumb">
				<?php woocommerce_breadcrumb(); ?>
			</div>
		<?php endif; ?>

		<h1><?php echo esc_html( $category_title ); ?></h1>
		<h2><?php echo esc_html( $category_subtitle ); ?></h2>
		<div class="ssc-product-category-intro">
			<?php echo wp_kses_post( wpautop( $intro_text ) ); ?>
		</div>
	</header>

	<?php if ( woocommerce_product_loop() ) : ?>
		<div class="ssc-product-category-toolbar">
			<?php woocommerce_result_count(); ?>
			<?php woocommerce_catalog_ordering(); ?>
		</div>

		<?php woocommerce_product_loop_start(); ?>

		<?php if ( wc_get_loop_prop( 'total' ) ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php wc_get_template_part( 'content', 'product' ); ?>
			<?php endwhile; ?>
		<?php endif; ?>

		<?php woocommerce_product_loop_end(); ?>
		<?php woocommerce_pagination(); ?>
	<?php else : ?>
		<div class="ssc-product-category-empty">
			<?php do_action( 'woocommerce_no_products_found' ); ?>
		</div>
	<?php endif; ?>

	<section class="ssc-product-category-content">
		<?php echo wp_kses_post( wpautop( $bottom_content ) ); ?>
	</section>

	<?php if ( $term ) : ?>
		<?php
		$faqs = array();
		for ( $i = 1; $i <= 6; $i++ ) {
			$question_key = 'ssc_product_cat_faq_' . $i . '_question';
			$answer_key   = 'ssc_product_cat_faq_' . $i . '_answer';
			$question     = function_exists( 'get_field' ) ? (string) get_field( $question_key, $term_acf_id ) : '';
			$answer       = function_exists( 'get_field' ) ? (string) get_field( $answer_key, $term_acf_id ) : '';

			if ( '' === trim( $question ) && isset( $term->term_id ) ) {
				$question = (string) get_term_meta( $term->term_id, $question_key, true );
			}

			if ( '' === trim( $answer ) && isset( $term->term_id ) ) {
				$answer = (string) get_term_meta( $term->term_id, $answer_key, true );
			}

			if ( '' !== trim( $question ) && '' !== trim( $answer ) ) {
				$faqs[] = array(
					'question' => $question,
					'answer'   => $answer,
				);
			}
		}

		if ( empty( $faqs ) ) {
			$faqs[] = array(
				'question' => sprintf( __( 'What should I know before choosing %s?', 'twentytwentyone-child' ), strtolower( $category_title ) ),
				'answer'   => __( 'Check the product dimensions, weight, finish, and installation requirements before ordering. You can replace these FAQ items from the product category edit screen in WordPress admin.', 'twentytwentyone-child' ),
			);
			$faqs[] = array(
				'question' => __( 'Are these products photographed individually?', 'twentytwentyone-child' ),
				'answer'   => __( 'Product listings can be managed individually in WooCommerce, including images, measurements, stock status, and pricing.', 'twentytwentyone-child' ),
			);
		}
		?>
		<section class="ssc-product-category-faq" aria-labelledby="ssc-product-category-faq-title">
			<h2 id="ssc-product-category-faq-title"><?php esc_html_e( 'Frequently Asked Questions', 'twentytwentyone-child' ); ?></h2>
			<div class="ssc-product-category-faq-list">
				<?php foreach ( $faqs as $index => $faq ) : ?>
					<details class="ssc-product-category-faq-item" <?php echo 0 === $index ? 'open' : ''; ?>>
						<summary class="ssc-product-category-faq-question"><?php echo esc_html( $faq['question'] ); ?></summary>
						<div class="ssc-product-category-faq-answer"><?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?></div>
					</details>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>
</section>

<?php
get_footer( 'shop' );
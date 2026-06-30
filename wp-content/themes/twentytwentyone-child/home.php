<?php
/**
 * Homepage template for the Twenty Twenty-One child theme.
 *
 * Uses ACF fields when available, with fallback content so the page renders
 * before the fields are completed in WordPress admin.
 *
 * @package Twenty_Twenty_One_Child
 */

get_header();

if ( is_front_page() ) {
	$ssc_home_id = (int) get_option( 'page_on_front' );
} elseif ( is_home() ) {
	$ssc_home_id = (int) get_option( 'page_for_posts' );
} else {
	$ssc_home_id = get_queried_object_id();
}

if ( ! $ssc_home_id ) {
	$ssc_home_id = get_queried_object_id();
}

if ( ! function_exists( 'ssc_home_field' ) ) {
	/**
	 * Return an ACF field value with a fallback.
	 *
	 * @param string $name    Field name.
	 * @param mixed  $default Fallback value.
	 * @return mixed
	 */
	function ssc_home_field( $name, $default = '' ) {
		global $ssc_home_id;

		if ( function_exists( 'get_field' ) && $ssc_home_id ) {
			$value = get_field( $name, $ssc_home_id );

			if ( '' !== $value && null !== $value && false !== $value ) {
				return $value;
			}
		}

		return $default;
	}
}

if ( ! function_exists( 'ssc_home_image_url' ) ) {
	/**
	 * Resolve an ACF image field into a URL.
	 *
	 * @param mixed $image ACF image array, attachment ID, or URL.
	 * @return string
	 */
	function ssc_home_image_url( $image ) {
		if ( is_array( $image ) && ! empty( $image['url'] ) ) {
			return $image['url'];
		}

		if ( is_numeric( $image ) ) {
			return wp_get_attachment_image_url( (int) $image, 'large' );
		}

		return is_string( $image ) ? $image : '';
	}
}
if ( ! function_exists( 'ssc_home_first_product_image' ) ) {
	/**
	 * Get the first available product image, optionally from a product category.
	 *
	 * @param string $category_slug Optional product category slug.
	 * @return string
	 */
	function ssc_home_first_product_image( $category_slug = '' ) {
		$args = array(
			'post_type'           => 'product',
			'posts_per_page'      => 1,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'fields'              => 'ids',
		);

		if ( $category_slug ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $category_slug,
				),
			);
		}

		$product_ids = get_posts( $args );
		$product_id  = ! empty( $product_ids ) ? (int) $product_ids[0] : 0;

		return $product_id ? (string) get_the_post_thumbnail_url( $product_id, 'large' ) : '';
	}
}

if ( ! function_exists( 'ssc_home_category_fallback' ) ) {
	/**
	 * Resolve category card URL and image from WooCommerce when ACF is empty.
	 *
	 * @param string $slug Product category slug.
	 * @return array
	 */
	function ssc_home_category_fallback( $slug ) {
		$data = array(
			'url'   => home_url( '/shop/' ),
			'image' => '',
		);

		if ( ! taxonomy_exists( 'product_cat' ) || ! $slug ) {
			return $data;
		}

		$term = get_term_by( 'slug', $slug, 'product_cat' );

		if ( ! $term || is_wp_error( $term ) ) {
			return $data;
		}

		$link = get_term_link( $term );

		if ( ! is_wp_error( $link ) ) {
			$data['url'] = $link;
		}

		$data['image'] = ssc_home_first_product_image( $slug );

		if ( ! $data['image'] ) {
			$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );

			if ( $thumbnail_id ) {
				$data['image'] = wp_get_attachment_image_url( (int) $thumbnail_id, 'large' );
			}
		}

		return $data;
	}
}

$hero_image = ssc_home_image_url( ssc_home_field( 'ssc_home_hero_image' ) );

if ( ! $hero_image ) {
	$hero_image = ssc_home_first_product_image();
}

$hero_style = $hero_image ? ' style="background-image: url(' . esc_url( $hero_image ) . ');"' : '';

$category_defaults = array(
	array( 'Natural Stone Sinks', 'natural-stone-sinks' ),
	array( 'Stone Bathroom Basins & Basin Sink Collection UK', 'stone-bathroom-basins-basin-sink-collection-uk' ),
	array( 'Bowl Sinks', 'bowl-sinks' ),
	array( 'Rectangular Sinks', 'rectangular-sinks' ),
	array( 'Petrified Wood Sinks', 'petrified-wood-sinks' ),
	array( 'Pedestal Sinks', 'pedestal-sinks' ),
	array( 'Zen Sinks', 'zen-sinks' ),
	array( 'Wooden Vanity Units for Stylish Bathrooms', 'wooden-vanity-units' ),
);

$range_defaults = array(
	array( 'Bowl Sinks', 'Our bowl sinks bring sculptural shape and natural character to compact cloakrooms, family bathrooms, and statement vanity units.' ),
	array( 'Natural Stone Sinks', 'Natural stone surfaces make every basin unique, with subtle pattern, tone, and texture differences formed by nature.' ),
	array( 'Rectangular Sinks', 'Rectangular sinks offer a clean architectural look for contemporary bathrooms and practical daily use.' ),
	array( 'Petrified Wood Sinks', 'Petrified wood basins add dramatic grain, rich color, and a one-of-a-kind finish to a bathroom space.' ),
	array( 'Pedestal Sinks', 'Pedestal sinks create a freestanding focal point with strong proportions and a calm natural finish.' ),
	array( 'Wooden Vanity Units', 'Wood vanity units combine warm material texture with useful storage and an easy-to-style bathroom base.' ),
);

$faq_defaults = array(
	array( 'What is a stone sink and how is it different from a regular sink?', 'A stone sink is crafted from natural stone, giving every piece its own texture, pattern, and tone.' ),
	array( 'Is a stone bathroom sink a good choice for daily use?', 'Yes. With sensible care and installation, natural stone sinks are suitable for everyday bathroom use.' ),
	array( 'What materials are used to create a sink from stone?', 'Common materials include marble, river stone, onyx, granite, travertine, and petrified wood.' ),
	array( 'Can I install a stone sink on any bathroom vanity?', 'Most stone sinks can be installed on a suitable vanity, but the vanity must support the weight and match the waste position.' ),
	array( 'Do stone sinks require maintenance or sealing?', 'Some stone sinks benefit from sealing and gentle cleaning products. Avoid harsh acids and abrasive cleaners.' ),
	array( 'Which type of stone sink is best for a bathroom sink setup?', 'The best option depends on your space, style, and vanity size.' ),
);
?>

<div class="ssc-home">
	<section class="ssc-home-hero"<?php echo $hero_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-label="<?php esc_attr_e( 'Stone sink homepage hero', 'twentytwentyone-child' ); ?>">
		<div class="ssc-home-hero__overlay">
			<h1><?php echo esc_html( ssc_home_field( 'ssc_home_hero_title', 'Stone Sinks - Handcrafted Natural Stone Bathroom Sinks in the UK' ) ); ?></h1>
			<p><?php echo esc_html( ssc_home_field( 'ssc_home_hero_subtitle', 'Free Delivery' ) ); ?></p>
			<div class="ssc-home-hero__actions">
				<a class="ssc-home-button" href="<?php echo esc_url( ssc_home_field( 'ssc_home_primary_button_url', home_url( '/shop/' ) ) ); ?>"><?php echo esc_html( ssc_home_field( 'ssc_home_primary_button_text', 'Shop Sinks' ) ); ?></a>
				<a class="ssc-home-button ssc-home-button--light" href="<?php echo esc_url( ssc_home_field( 'ssc_home_secondary_button_url', home_url( '/contact/' ) ) ); ?>"><?php echo esc_html( ssc_home_field( 'ssc_home_secondary_button_text', 'Ask a Question' ) ); ?></a>
			</div>
		</div>
	</section>

	<section class="ssc-home-intro">
		<h2><?php echo esc_html( ssc_home_field( 'ssc_home_intro_title', 'We are The Stone Sink Company.' ) ); ?></h2>
		<div class="ssc-home-copy">
			<?php echo wp_kses_post( wpautop( ssc_home_field( 'ssc_home_intro_text', 'The Stone Sink Company was started by James Tatham, our Managing Director. After years of extensive travels through Indonesia, James formed a relationship with an Indonesian partner Ã¢â‚¬â€œ a strong friendship and business partnership were forged.  We launched from small beginnings, working closely with our Indonesian partner to bring some of the stunning items we saw to the UK. We launched a range of beautiful stone bathroom sinks, stunning reclaimed wooden vanity units and sell reclaimed teak furniture for the entire home via Ombak Furniture.  If youÃ¢â‚¬â„¢re looking for a beautiful sink from stone, we have a wide selection for you Ã¢â‚¬â€œ all handmade in Indonesia, with traceable origin.  Large UK stock is carried, and we offer immediate free UK delivery. The Stone Sink Company also send out stone sinks all over the world Ã¢â‚¬â€œ international delivery to any country is possible. We are proud of what we do. We try to provide beautiful products, sourced the right way, give customer service and be the best we can be! We still have very strong relationships with Indonesia, and we are still working with the same Indonesian partners.' ) ) ); ?>
		</div>
	</section>

	<?php if ( has_nav_menu( 'primary' ) ) : ?>
		<nav class="ssc-home-thin-nav" aria-label="<?php esc_attr_e( 'Homepage product links', 'twentytwentyone-child' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'ssc-home-thin-menu',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	<?php endif; ?>


	<section class="ssc-home-categories" aria-labelledby="ssc-home-categories-title">
		<h2 id="ssc-home-categories-title"><?php echo esc_html( ssc_home_field( 'ssc_home_categories_title', 'Stone Sink Categories' ) ); ?></h2>
		<div class="ssc-home-category-grid">
			<?php for ( $i = 1; $i <= 8; $i++ ) : ?>
				<?php
				$category_fallback = ssc_home_category_fallback( $category_defaults[ $i - 1 ][1] );
				$card_title        = ssc_home_field( 'ssc_home_category_' . $i . '_title', $category_defaults[ $i - 1 ][0] );
				$card_url          = ssc_home_field( 'ssc_home_category_' . $i . '_url', $category_fallback['url'] );
				$card_image        = ssc_home_image_url( ssc_home_field( 'ssc_home_category_' . $i . '_image' ) );

				if ( ! $card_image ) {
					$card_image = $category_fallback['image'];
				}
				?>
				<a class="ssc-home-category-card" href="<?php echo esc_url( $card_url ); ?>">
					<?php if ( $card_image ) : ?>
						<img src="<?php echo esc_url( $card_image ); ?>" alt="<?php echo esc_attr( $card_title ); ?>" loading="lazy" />
					<?php else : ?>
						<span class="ssc-home-image-placeholder"></span>
					<?php endif; ?>
					<span><?php echo esc_html( $card_title ); ?></span>
				</a>
			<?php endfor; ?>
		</div>
	</section>

	<section class="ssc-home-promo">
		<p><?php echo esc_html( ssc_home_field( 'ssc_home_promo_text', 'The UKs finest stone sinks and wooden vanity units - Free UK delivery - International shipping available!' ) ); ?></p>
		<small><?php echo esc_html( ssc_home_field( 'ssc_home_promo_small_text', 'Best international rates. Call for details.' ) ); ?></small>
	</section>

	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<section class="ssc-home-products" aria-labelledby="ssc-home-featured-title">
			<h2 id="ssc-home-featured-title"><?php esc_html_e( 'Featured Products', 'twentytwentyone-child' ); ?></h2>
			<?php
			$featured_products = do_shortcode( '[products limit="4" columns="4" visibility="featured"]' );

			if ( false === strpos( $featured_products, 'product' ) ) {
				$featured_products = do_shortcode( '[products limit="4" columns="4" orderby="date" order="DESC"]' );
			}

			echo $featured_products; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</section>
	<?php endif; ?>

	<section class="ssc-home-range" aria-labelledby="ssc-home-range-title">
		<h2 id="ssc-home-range-title"><?php echo esc_html( ssc_home_field( 'ssc_home_range_title', 'Our Range' ) ); ?></h2>
		<div class="ssc-home-range-grid">
			<?php foreach ( $range_defaults as $index => $range ) : ?>
				<article class="ssc-home-range-card">
					<h3><?php echo esc_html( ssc_home_field( 'ssc_home_range_' . ( $index + 1 ) . '_title', $range[0] ) ); ?></h3>
					<p><?php echo esc_html( ssc_home_field( 'ssc_home_range_' . ( $index + 1 ) . '_text', $range[1] ) ); ?></p>
				</article>
			<?php endforeach; ?>
			<article class="ssc-home-range-card ssc-home-range-card--wide">
				<h3><?php echo esc_html( ssc_home_field( 'ssc_home_range_feature_title', 'Marble Sinks' ) ); ?></h3>
				<p><?php echo esc_html( ssc_home_field( 'ssc_home_range_feature_text', 'Discover the timeless beauty and natural elegance of marble sinks, designed to transform any bathroom into a luxurious space.' ) ); ?></p>
			</article>
		</div>
	</section>

	<section class="ssc-home-social" aria-label="<?php esc_attr_e( 'Follow us', 'twentytwentyone-child' ); ?>">
		<h2><?php esc_html_e( 'Follow Us On', 'twentytwentyone-child' ); ?></h2>
		<div>
			<a href="<?php echo esc_url( ssc_home_field( 'ssc_home_facebook_url', '#' ) ); ?>" aria-label="<?php esc_attr_e( 'Facebook', 'twentytwentyone-child' ); ?>">f</a>
			<a href="<?php echo esc_url( ssc_home_field( 'ssc_home_instagram_url', '#' ) ); ?>" aria-label="<?php esc_attr_e( 'Instagram', 'twentytwentyone-child' ); ?>">IG</a>
		</div>
	</section>

	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<section class="ssc-home-products" aria-labelledby="ssc-home-sales-title">
			<h2 id="ssc-home-sales-title"><?php esc_html_e( 'Sales', 'twentytwentyone-child' ); ?></h2>
			<?php
			$sale_products = do_shortcode( '[sale_products limit="4" columns="4"]' );

			if ( false === strpos( $sale_products, 'product' ) ) {
				$sale_products = do_shortcode( '[products limit="4" columns="4" orderby="date" order="DESC"]' );
			}

			echo $sale_products; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</section>
	<?php endif; ?>

	<section class="ssc-home-articles" aria-labelledby="ssc-home-articles-title">
		<h2 id="ssc-home-articles-title"><?php echo esc_html( ssc_home_field( 'ssc_home_articles_title', 'Latest Articles' ) ); ?></h2>
		<div class="ssc-home-article-grid">
			<?php
			$rendered_articles = 0;
			$latest_posts      = new WP_Query(
				array(
					'post_type'           => 'post',
					'posts_per_page'      => 4,
					'ignore_sticky_posts' => true,
				)
			);
			?>
			<?php if ( $latest_posts->have_posts() ) : ?>
				<?php while ( $latest_posts->have_posts() ) : ?>
					<?php $latest_posts->the_post(); ?>
					<?php $rendered_articles++; ?>
					<article class="ssc-home-article-card">
						<a href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
							<?php else : ?>
								<?php $post_fallback_image = ssc_home_first_product_image(); ?>
								<?php if ( $post_fallback_image ) : ?>
									<img src="<?php echo esc_url( $post_fallback_image ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
								<?php else : ?>
									<span class="ssc-home-image-placeholder"></span>
								<?php endif; ?>
							<?php endif; ?>
							<h3><?php the_title(); ?></h3>
						</a>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
						<a class="ssc-home-read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'twentytwentyone-child' ); ?></a>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>

			<?php if ( $rendered_articles < 4 ) : ?>
				<?php
				$product_articles = new WP_Query(
					array(
						'post_type'           => 'product',
						'posts_per_page'      => 4 - $rendered_articles,
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
					)
				);
				?>
				<?php while ( $product_articles->have_posts() ) : ?>
					<?php $product_articles->the_post(); ?>
					<article class="ssc-home-article-card">
						<a href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
							<?php else : ?>
								<span class="ssc-home-image-placeholder"></span>
							<?php endif; ?>
							<h3><?php the_title(); ?></h3>
						</a>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt() ? get_the_excerpt() : __( 'Discover natural stone bathroom pieces and handcrafted sink designs for your home.', 'twentytwentyone-child' ), 20 ) ); ?></p>
						<a class="ssc-home-read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'twentytwentyone-child' ); ?></a>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</section>

	<section class="ssc-home-faq" aria-labelledby="ssc-home-faq-title">
		<h2 id="ssc-home-faq-title"><?php echo esc_html( ssc_home_field( 'ssc_home_faq_title', 'Frequently Asked Questions' ) ); ?></h2>
		<div class="ssc-home-faq-list">
			<?php foreach ( $faq_defaults as $index => $faq ) : ?>
				<details <?php echo 0 === $index ? 'open' : ''; ?>>
					<summary><?php echo esc_html( ssc_home_field( 'ssc_home_faq_' . ( $index + 1 ) . '_question', $faq[0] ) ); ?></summary>
					<p><?php echo esc_html( ssc_home_field( 'ssc_home_faq_' . ( $index + 1 ) . '_answer', $faq[1] ) ); ?></p>
				</details>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="ssc-home-testimonials" aria-labelledby="ssc-home-testimonials-title">
		<h2 id="ssc-home-testimonials-title"><?php echo esc_html( ssc_home_field( 'ssc_home_testimonials_title', 'What our customers say' ) ); ?></h2>
		<div class="ssc-home-review-summary">
			<strong><?php echo esc_html( ssc_home_field( 'ssc_home_review_brand', 'Etsy Reviews' ) ); ?></strong>
			<span><?php echo esc_html( ssc_home_field( 'ssc_home_review_score', '4.9 / 5' ) ); ?></span>
		</div>
		<div class="ssc-home-testimonial-grid">
			<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
				<article class="ssc-home-testimonial-card">
					<strong><?php echo esc_html( ssc_home_field( 'ssc_home_testimonial_' . $i . '_name', 'Happy customer' ) ); ?></strong>
					<span><?php esc_html_e( '5 stars', 'twentytwentyone-child' ); ?></span>
					<p><?php echo esc_html( ssc_home_field( 'ssc_home_testimonial_' . $i . '_text', 'Beautiful product, carefully packed, and exactly as described.' ) ); ?></p>
				</article>
			<?php endfor; ?>
		</div>
	</section>
</div>

<?php
get_footer();
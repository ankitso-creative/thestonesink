<?php
/**
 * Template Name: Stone Sink About Page
 *
 * About page template for the Twenty Twenty-One child theme.
 *
 * @package Twenty_Twenty_One_Child
 */

get_header();

if ( ! function_exists( 'ssc_about_field' ) ) {
	/**
	 * Return an editable ACF field with fallback content.
	 *
	 * @param string $name    Field name.
	 * @param mixed  $default Fallback value.
	 * @return mixed
	 */
	function ssc_about_field( $name, $default = '' ) {
		$page_id = get_queried_object_id();

		if ( function_exists( 'get_field' ) && $page_id ) {
			$value = get_field( $name, $page_id );

			if ( '' !== $value && null !== $value && false !== $value ) {
				return $value;
			}
		}

		return $default;
	}
}

if ( ! function_exists( 'ssc_about_image_url' ) ) {
	/**
	 * Resolve an ACF image field into a URL.
	 *
	 * @param mixed $image ACF image array, ID, or URL.
	 * @return string
	 */
	function ssc_about_image_url( $image ) {
		if ( is_array( $image ) && ! empty( $image['url'] ) ) {
			return $image['url'];
		}

		if ( is_numeric( $image ) ) {
			return (string) wp_get_attachment_image_url( (int) $image, 'large' );
		}

		return is_string( $image ) ? $image : '';
	}
}

$story_image      = ssc_about_image_url( ssc_about_field( 'ssc_about_story_image' ) );
$wave_image       = ssc_about_image_url( ssc_about_field( 'ssc_about_wave_image' ) );
$partners_image   = ssc_about_image_url( ssc_about_field( 'ssc_about_partners_image' ) );
$indonesia_image  = ssc_about_image_url( ssc_about_field( 'ssc_about_indonesia_image' ) );
$workers_image    = ssc_about_image_url( ssc_about_field( 'ssc_about_workers_image' ) );
$workshop_image   = ssc_about_image_url( ssc_about_field( 'ssc_about_workshop_image' ) );
$about_bullets    = ssc_about_field(
	'ssc_about_partner_bullets',
	array(
		'The products were selected for a modern, fresh bathroom market and a natural handmade look.',
		'Every piece is made with traditional knowledge, local materials, and careful finishing.',
		'The workshop relationship is built on clear communication, fair work, and long-term trust.',
		'The reclaimed wood workshop also creates beautiful vanity units from characterful timber.',
	)
);

if ( is_string( $about_bullets ) ) {
	$about_bullets = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $about_bullets ) ) );
}
?>

<main id="primary" class="site-main ssc-about-page">
	<header class="ssc-about-page__header">
		<h1><?php echo esc_html( ssc_about_field( 'ssc_about_page_title', 'About Us' ) ); ?></h1>
	</header>

	<section class="ssc-about-section ssc-about-section--story">
		<h2><?php echo esc_html( ssc_about_field( 'ssc_about_story_title', 'The Stone Sink Company - The Story' ) ); ?></h2>

		<figure class="ssc-about-figure ssc-about-figure--large">
			<?php if ( $story_image ) : ?>
				<img src="<?php echo esc_url( $story_image ); ?>" alt="<?php echo esc_attr( ssc_about_field( 'ssc_about_story_caption', 'Beautiful Indonesia' ) ); ?>" loading="lazy" />
			<?php else : ?>
				<span class="ssc-about-placeholder ssc-about-placeholder--boat" role="img" aria-label="<?php echo esc_attr( ssc_about_field( 'ssc_about_story_caption', 'Beautiful Indonesia' ) ); ?>"></span>
			<?php endif; ?>
			<figcaption><?php echo esc_html( ssc_about_field( 'ssc_about_story_caption', 'Beautiful Indonesia' ) ); ?></figcaption>
		</figure>

		<div class="ssc-about-copy ssc-about-copy--narrow">
			<?php echo wp_kses_post( wpautop( ssc_about_field( 'ssc_about_story_intro', 'Our story began with time spent in Indonesia and a love of natural stone, craft, and simple honest materials. What started as a small collection grew into a focused range of handmade bathroom sinks, basins, and wooden vanity pieces for homes across the UK.' ) ) ); ?>
		</div>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav class="ssc-about-thin-nav" aria-label="<?php esc_attr_e( 'About page product links', 'twentytwentyone-child' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_class'     => 'ssc-about-thin-menu',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</section>

	<section class="ssc-about-section ssc-about-section--partners">
		<figure class="ssc-about-figure ssc-about-figure--small">
			<?php if ( $wave_image ) : ?>
				<img src="<?php echo esc_url( $wave_image ); ?>" alt="<?php echo esc_attr( ssc_about_field( 'ssc_about_wave_caption', 'A long time ago' ) ); ?>" loading="lazy" />
			<?php else : ?>
				<span class="ssc-about-placeholder ssc-about-placeholder--wave" role="img" aria-label="<?php echo esc_attr( ssc_about_field( 'ssc_about_wave_caption', 'A long time ago' ) ); ?>"></span>
			<?php endif; ?>
			<figcaption><?php echo esc_html( ssc_about_field( 'ssc_about_wave_caption', 'A long time ago' ) ); ?></figcaption>
		</figure>

		<h2><?php echo esc_html( ssc_about_field( 'ssc_about_partner_title', 'A lasting workshop partnership' ) ); ?></h2>

		<ul class="ssc-about-list">
			<?php foreach ( $about_bullets as $bullet ) : ?>
				<li><?php echo esc_html( $bullet ); ?></li>
			<?php endforeach; ?>
		</ul>

		<figure class="ssc-about-figure ssc-about-figure--medium">
			<?php if ( $partners_image ) : ?>
				<img src="<?php echo esc_url( $partners_image ); ?>" alt="<?php echo esc_attr( ssc_about_field( 'ssc_about_partners_caption', 'Same friendship, years later' ) ); ?>" loading="lazy" />
			<?php else : ?>
				<span class="ssc-about-placeholder ssc-about-placeholder--people" role="img" aria-label="<?php echo esc_attr( ssc_about_field( 'ssc_about_partners_caption', 'Same friendship, years later' ) ); ?>"></span>
			<?php endif; ?>
			<figcaption><?php echo esc_html( ssc_about_field( 'ssc_about_partners_caption', 'Same friendship, years later' ) ); ?></figcaption>
		</figure>
	</section>

	<section class="ssc-about-section ssc-about-section--evolution">
		<h2><?php echo esc_html( ssc_about_field( 'ssc_about_evolution_title', 'The Evolution of The Stone Sink Company' ) ); ?></h2>
		<div class="ssc-about-copy">
			<?php echo wp_kses_post( wpautop( ssc_about_field( 'ssc_about_evolution_text', 'From small beginnings, the range has developed around natural materials, dependable service, and long-term relationships with skilled makers. The same principles remain at the centre of the business: thoughtful products, practical advice, and a commitment to the people behind the work.' ) ) ); ?>
		</div>
	</section>

	<section class="ssc-about-section ssc-about-section--indonesia">
		<h2><?php echo esc_html( ssc_about_field( 'ssc_about_indonesia_title', 'The Stone Sink Company in Indonesia' ) ); ?></h2>

		<figure class="ssc-about-figure ssc-about-figure--wide">
			<?php if ( $indonesia_image ) : ?>
				<img src="<?php echo esc_url( $indonesia_image ); ?>" alt="<?php echo esc_attr( ssc_about_field( 'ssc_about_indonesia_caption', 'Stunning Indonesia' ) ); ?>" loading="lazy" />
			<?php else : ?>
				<span class="ssc-about-placeholder ssc-about-placeholder--beach" role="img" aria-label="<?php echo esc_attr( ssc_about_field( 'ssc_about_indonesia_caption', 'Stunning Indonesia' ) ); ?>"></span>
			<?php endif; ?>
			<figcaption><?php echo esc_html( ssc_about_field( 'ssc_about_indonesia_caption', 'Stunning Indonesia' ) ); ?></figcaption>
		</figure>

		<div class="ssc-about-copy">
			<?php echo wp_kses_post( wpautop( ssc_about_field( 'ssc_about_indonesia_text', 'The business keeps a close connection with the workshop and the people who make the products. Regular contact, shared standards, and visits when possible help keep the collection personal rather than faceless.' ) ) ); ?>
		</div>

		<figure class="ssc-about-figure ssc-about-figure--portrait">
			<?php if ( $workers_image ) : ?>
				<img src="<?php echo esc_url( $workers_image ); ?>" alt="<?php echo esc_attr( ssc_about_field( 'ssc_about_workers_caption', 'Our workers' ) ); ?>" loading="lazy" />
			<?php else : ?>
				<span class="ssc-about-placeholder ssc-about-placeholder--workers" role="img" aria-label="<?php echo esc_attr( ssc_about_field( 'ssc_about_workers_caption', 'Our workers' ) ); ?>"></span>
			<?php endif; ?>
			<figcaption><?php echo esc_html( ssc_about_field( 'ssc_about_workers_caption', 'Our workers' ) ); ?></figcaption>
		</figure>

		<figure class="ssc-about-figure ssc-about-figure--wide ssc-about-figure--final">
			<?php if ( $workshop_image ) : ?>
				<img src="<?php echo esc_url( $workshop_image ); ?>" alt="<?php echo esc_attr( ssc_about_field( 'ssc_about_workshop_caption', 'Workshop life' ) ); ?>" loading="lazy" />
			<?php else : ?>
				<span class="ssc-about-placeholder ssc-about-placeholder--workshop" role="img" aria-label="<?php echo esc_attr( ssc_about_field( 'ssc_about_workshop_caption', 'Workshop life' ) ); ?>"></span>
			<?php endif; ?>
			<figcaption><?php echo esc_html( ssc_about_field( 'ssc_about_workshop_caption', 'Workshop life' ) ); ?></figcaption>
		</figure>
	</section>
</main>

<?php
get_footer();
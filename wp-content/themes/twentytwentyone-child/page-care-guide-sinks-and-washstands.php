<?php
/**
 * Template Name: Stone Sink Care Guide
 *
 * Care guide page template for the Twenty Twenty-One child theme.
 *
 * @package Twenty_Twenty_One_Child
 */

get_header();

if ( ! function_exists( 'ssc_care_field' ) ) {
	/**
	 * Return an editable ACF field with fallback content.
	 *
	 * @param string $name    Field name.
	 * @param mixed  $default Fallback value.
	 * @return mixed
	 */
	function ssc_care_field( $name, $default = '' ) {
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

$intro_text = ssc_care_field(
	'ssc_care_intro_text',
	'We sell natural stone sinks and reclaimed teak items designed for long service. Like most quality materials, they stay at their best with simple routine care. Stone basins are supplied sealed, so no first sealing is required.'
);

$marble_text = ssc_care_field(
	'ssc_care_marble_text',
	"These stones are calcium-rich materials, so the key care point is to avoid harsh or acidic products. Remove toothpaste, soap residue, and make-up marks as soon as practical. Use mild soap or a stone-safe detergent and wipe the surface with a soft cloth as part of normal cleaning.\n\nA stone sealer or preserver can be used occasionally, usually every six to twelve months, or when the surface begins to look dull after repeated use. The main rule is simple: do not use acidic cleaners on marble stone items. Avoid vinegar, lemon juice, grout cleaner, tile cleaner, and harsh kitchen degreasers."
);

$natural_text = ssc_care_field(
	'ssc_care_natural_text',
	'Natural stone basins are generally siliceous stones, made mostly from silica or quartz-like particles. They are part of the granite family and are naturally more durable than marble or petrified wood. The same simple cleaning steps are recommended, though these basins are more resistant to many everyday cleaners and temperature changes.'
);

$wood_text = ssc_care_field(
	'ssc_care_wood_text',
	"Wooden vanity units are made from solid reclaimed teak, a durable timber that performs well in bathroom environments. The wood itself handles water well, but sealing before installation is recommended, usually with teak oil.\n\nAfter installation, keep care simple by wiping flat surfaces regularly and removing debris or toothpaste spills. Depending on use, reapply oil to exposed areas every few months or whenever the unit looks like it needs refreshing. Please contact us if you need further care advice."
);
?>

<main id="primary" class="site-main ssc-care-page">
	<header class="ssc-care-page__header">
		<h1><?php echo esc_html( ssc_care_field( 'ssc_care_page_title', 'Care Guide - Stone Basins and Wooden Vanities' ) ); ?></h1>
	</header>

	<div class="ssc-care-content">
		<section class="ssc-care-section ssc-care-section--intro">
			<h2><?php echo esc_html( ssc_care_field( 'ssc_care_intro_heading', 'Stone Sinks and Wooden Vanity Units - Care.' ) ); ?></h2>
			<div class="ssc-care-copy">
				<?php echo wp_kses_post( wpautop( $intro_text ) ); ?>
			</div>
		</section>

		<section class="ssc-care-section">
			<h2><?php echo esc_html( ssc_care_field( 'ssc_care_marble_heading', 'Marble and Petrified Wood Basins' ) ); ?></h2>
			<div class="ssc-care-copy">
				<?php echo wp_kses_post( wpautop( $marble_text ) ); ?>
			</div>
		</section>

		<section class="ssc-care-section">
			<h2><?php echo esc_html( ssc_care_field( 'ssc_care_natural_heading', 'Natural Stone Basins' ) ); ?></h2>
			<div class="ssc-care-copy">
				<?php echo wp_kses_post( wpautop( $natural_text ) ); ?>
			</div>
		</section>

		<section class="ssc-care-section">
			<h2><?php echo esc_html( ssc_care_field( 'ssc_care_wood_heading', 'Wooden Vanity Units.' ) ); ?></h2>
			<div class="ssc-care-copy">
				<?php echo wp_kses_post( wpautop( $wood_text ) ); ?>
			</div>
		</section>
	</div>
</main>

<?php
get_footer();
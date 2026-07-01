<?php
/**
 * Single product template for the Twenty Twenty-One child theme.
 *
 * @package Twenty_Twenty_One_Child
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<main id="primary" class="site-main ssc-single-product-main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php wc_get_template_part( 'content', 'single-product' ); ?>
	<?php endwhile; ?>
</main>

<?php
get_footer( 'shop' );
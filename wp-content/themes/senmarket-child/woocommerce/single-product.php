<?php
/**
 * Custom single product template for SenMarket child theme.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="senmarket-container">
  <?php woocommerce_content(); ?>
</div>
<?php get_footer();

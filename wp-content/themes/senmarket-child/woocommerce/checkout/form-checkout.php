<?php
/**
 * Custom checkout form for SenMarket child theme.
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="woocommerce-checkout senmarket-container">
  <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

  <div class="col2-set" id="customer_details">
    <?php do_action( 'woocommerce_checkout_billing' ); ?>
    <?php do_action( 'woocommerce_checkout_shipping' ); ?>
  </div>

  <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
  <h3 id="order_review_heading"><?php esc_html_e( 'Votre commande', 'woocommerce' ); ?></h3>

  <?php do_action( 'woocommerce_checkout_order_review' ); ?>

  <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
</div>

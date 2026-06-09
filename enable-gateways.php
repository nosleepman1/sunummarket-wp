<?php
define('WP_HTTP_BLOCK_EXTERNAL', true);
require 'wp-load.php';

echo "=== CONFIGURING PAYMENT GATEWAYS ===" . PHP_EOL;

// Get WooCommerce gateways
if (class_exists('WC_Payment_Gateways')) {
    $gateways = WC_Payment_Gateways::instance()->payment_gateways();
    
    // Enable COD (Cash on Delivery)
    update_option('woocommerce_cod_settings', array(
        'enabled'      => 'yes',
        'title'        => 'Paiement par Mobile Money (Wave, Orange Money, Free Money)',
        'description'  => 'Payez directement et en toute sécurité par Wave ou Orange Money. Notre vendeur vous contactera sur WhatsApp au numéro indiqué pour valider le transfert.',
        'instructions' => 'Veuillez effectuer le transfert au numéro de téléphone du vendeur ou de la plateforme (+221 77 000 00 00) puis valider par WhatsApp.',
    ));
    
    // Ensure COD is listed in active gateways
    $enabled_gateways = get_option('woocommerce_gateway_order', array());
    $enabled_gateways['cod'] = 1;
    update_option('woocommerce_gateway_order', $enabled_gateways);
    
    echo "COD (Mobile Money) payment gateway enabled and configured." . PHP_EOL;
} else {
    echo "WooCommerce is not active or WC_Payment_Gateways class not found." . PHP_EOL;
}

// Enable shipping options
update_option('woocommerce_ship_to_destination', 'shipping');

echo "=== CONFIGURATION COMPLETE ===" . PHP_EOL;

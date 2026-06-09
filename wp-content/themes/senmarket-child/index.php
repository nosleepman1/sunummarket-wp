<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Load the parent theme index template when the child theme does not override it.
 */
require get_template_directory() . '/index.php';

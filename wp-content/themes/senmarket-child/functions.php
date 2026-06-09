<?php
/**
 * SenMarket Child Theme — functions.php
 *
 * Chargement des assets, configuration WooCommerce et modales.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_enqueue_scripts', function() {
    // Parent theme
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    // Font Awesome pour icônes réelles
    wp_enqueue_style(
        'senmarket-icons',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        [],
        '6.5.1'
    );

    // Google Fonts
    wp_enqueue_style(
        'senmarket-fonts',
        'https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap',
        [],
        null
    );

    // CSS
    wp_enqueue_style(
        'senmarket-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [ 'parent-style' ],
        '1.0.0'
    );
    wp_enqueue_style(
        'senmarket-components',
        get_stylesheet_directory_uri() . '/assets/css/components.css',
        [ 'senmarket-main' ],
        '1.0.0'
    );
    wp_enqueue_style(
        'senmarket-dark-mode',
        get_stylesheet_directory_uri() . '/assets/css/dark-mode.css',
        [ 'senmarket-main' ],
        '1.0.0'
    );
    wp_enqueue_style(
        'senmarket-modals',
        get_stylesheet_directory_uri() . '/assets/css/modals.css',
        [ 'senmarket-main' ],
        '1.0.0'
    );
    wp_enqueue_style(
        'senmarket-responsive',
        get_stylesheet_directory_uri() . '/assets/css/responsive.css',
        [ 'senmarket-main' ],
        '1.0.0'
    );

    // JS
    wp_enqueue_script(
        'senmarket-darkmode',
        get_stylesheet_directory_uri() . '/assets/js/dark-mode.js',
        [],
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'senmarket-modals',
        get_stylesheet_directory_uri() . '/assets/js/modals.js',
        [ 'jquery' ],
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'senmarket-animations',
        get_stylesheet_directory_uri() . '/assets/js/animations.js',
        [ 'jquery' ],
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'senmarket-filters',
        get_stylesheet_directory_uri() . '/assets/js/filters.js',
        [ 'jquery' ],
        '1.0.0',
        true
    );
    wp_enqueue_script(
        'senmarket-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        [ 'jquery', 'senmarket-darkmode', 'senmarket-modals', 'senmarket-animations', 'senmarket-filters' ],
        '1.0.0',
        true
    );

    wp_localize_script( 'senmarket-main', 'SenMarketConfig', [
        'ajaxUrl'      => esc_url( admin_url( 'admin-ajax.php' ) ),
        'nonce'        => wp_create_nonce( 'senmarket_nonce' ),
        'isLoggedIn'   => is_user_logged_in(),
        'waNumber'     => defined( 'SENMARKET_WA_NUMBER' ) ? SENMARKET_WA_NUMBER : '+221000000000',
        'waMessage'    => defined( 'SENMARKET_WA_MESSAGE' ) ? SENMARKET_WA_MESSAGE : 'Bonjour SenMarket, j\'ai une question...',
        'currency'     => 'XOF',
        'cartUrl'      => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '',
        'checkoutUrl'  => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '',
        'accountUrl'   => function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'dashboard' ) : '',
        'loginModalId' => 'modal-auth',
        'i18n'         => [
            'addedToCart'   => 'Produit ajouté au panier',
            'loginRequired' => 'Connectez-vous pour commander',
            'error'         => 'Une erreur est survenue',
        ],
    ] );
} );

add_action( 'wp_footer', function() {
    get_template_part( 'template-parts/modal-login' );
    get_template_part( 'template-parts/modal-quickview' );
    echo '<div id="toast-container" role="region" aria-live="polite"></div>';
} );

add_action( 'template_redirect', function() {
    if ( function_exists( 'is_cart' ) && function_exists( 'is_checkout' ) && ( is_cart() || is_checkout() ) && ! is_user_logged_in() ) {
        $myaccount_url = 
            function_exists( 'wc_get_page_permalink' ) 
            ? wc_get_page_permalink( 'myaccount' ) 
            : get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

        if ( ! $myaccount_url ) {
            $myaccount_url = home_url( '/my-account/' );
        }

        wp_safe_redirect( $myaccount_url );
        exit;
    }
} );

add_filter( 'woocommerce_price_format', function() {
    return '%2$s %1$s';
} );
add_filter( 'wc_get_price_decimals', function() {
    return 0;
} );

add_action( 'after_setup_theme', function() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
} );

add_action( 'wp_ajax_senmarket_filter_products', function() {
    check_ajax_referer( 'senmarket_nonce', 'nonce' );

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'post_status'    => 'publish',
    ];

    if ( ! empty( $_POST['search'] ) ) {
        $args['s'] = sanitize_text_field( wp_unslash( $_POST['search'] ) );
    }

    if ( ! empty( $_POST['category'] ) ) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => array_map( 'sanitize_text_field', (array) wp_unslash( $_POST['category'] ) ),
            ],
        ];
    }

    $meta_query = [];
    if ( ! empty( $_POST['min_price'] ) || ! empty( $_POST['max_price'] ) ) {
        $price_query = [ 'relation' => 'AND' ];
        if ( ! empty( $_POST['min_price'] ) ) {
            $price_query[] = [
                'key'     => '_price',
                'value'   => floatval( str_replace( [ ',', ' ' ], [ '.', '' ], wp_unslash( $_POST['min_price'] ) ) ),
                'compare' => '>=',
                'type'    => 'NUMERIC',
            ];
        }
        if ( ! empty( $_POST['max_price'] ) ) {
            $price_query[] = [
                'key'     => '_price',
                'value'   => floatval( str_replace( [ ',', ' ' ], [ '.', '' ], wp_unslash( $_POST['max_price'] ) ) ),
                'compare' => '<=',
                'type'    => 'NUMERIC',
            ];
        }
        $meta_query[] = $price_query;
    }

    if ( ! empty( $meta_query ) ) {
        $args['meta_query'] = $meta_query;
    }

    $query = new WP_Query( $args );
    if ( $query->have_posts() ) {
        echo '<ul class="products-grid products">';
        while ( $query->have_posts() ) {
            $query->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        echo '</ul>';
    } else {
        echo '<p>' . esc_html__( 'Aucun produit trouvé.', 'senmarket-child' ) . '</p>';
    }

    wp_reset_postdata();
    wp_die();
} );

add_action( 'wp_ajax_nopriv_senmarket_filter_products', function() {
    do_action( 'wp_ajax_senmarket_filter_products' );
} );

add_action( 'after_switch_theme', function() {
    if ( function_exists( 'update_option' ) ) {
        update_option( 'woocommerce_currency', 'XOF' );
        update_option( 'woocommerce_currency_pos', 'right' );
        update_option( 'woocommerce_price_decimal_sep', ',' );
        update_option( 'woocommerce_price_thousand_sep', ' ' );
        update_option( 'woocommerce_price_num_decimals', 0 );
        update_option( 'woocommerce_enable_guest_checkout', 'no' );
        update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
        update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' );

        senmarket_apply_plugin_defaults();
    }
} );

function senmarket_apply_plugin_defaults() {
    if ( get_option( 'senmarket_plugin_defaults_saved', false ) ) {
        return;
    }

    update_option( 'woocommerce_currency', 'XOF' );
    update_option( 'woocommerce_currency_pos', 'right' );
    update_option( 'woocommerce_price_decimal_sep', ',' );
    update_option( 'woocommerce_price_thousand_sep', ' ' );
    update_option( 'woocommerce_price_num_decimals', 0 );
    update_option( 'woocommerce_enable_guest_checkout', 'no' );
    update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
    update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' );

    senmarket_configure_dokan_settings();
    senmarket_configure_wp_mail_smtp();

    update_option( 'senmarket_plugin_defaults_saved', 'yes' );
}

add_action( 'woocommerce_register_form_start', 'senmarket_render_registration_fields' );
function senmarket_render_registration_fields() {
    if ( ! function_exists( 'is_user_logged_in' ) || is_user_logged_in() ) {
        return;
    }

    $first_name = isset( $_POST['billing_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) ) : '';
    $last_name  = isset( $_POST['billing_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_last_name'] ) ) : '';
    $phone      = isset( $_POST['billing_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : '';

    ?>
    <p class="form-row form-row-first">
        <label for="reg_billing_first_name"><?php esc_html_e( 'Prénom', 'senmarket-child' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php echo esc_attr( $first_name ); ?>" />
    </p>
    <p class="form-row form-row-last">
        <label for="reg_billing_last_name"><?php esc_html_e( 'Nom', 'senmarket-child' ); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php echo esc_attr( $last_name ); ?>" />
    </p>
    <div class="clear"></div>
    <p class="form-row form-row-wide">
        <label for="reg_billing_phone"><?php esc_html_e( 'Téléphone', 'senmarket-child' ); ?> <span class="required">*</span></label>
        <input type="tel" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php echo esc_attr( $phone ); ?>" />
    </p>
    <?php
}

add_filter( 'woocommerce_registration_errors', 'senmarket_validate_registration_fields', 10, 3 );
function senmarket_validate_registration_fields( $errors, $username, $email ) {
    if ( isset( $_POST['billing_first_name'] ) && empty( trim( wp_unslash( $_POST['billing_first_name'] ) ) ) ) {
        $errors->add( 'billing_first_name_error', __( 'Veuillez renseigner votre prénom.', 'senmarket-child' ) );
    }

    if ( isset( $_POST['billing_last_name'] ) && empty( trim( wp_unslash( $_POST['billing_last_name'] ) ) ) ) {
        $errors->add( 'billing_last_name_error', __( 'Veuillez renseigner votre nom.', 'senmarket-child' ) );
    }

    if ( isset( $_POST['billing_phone'] ) && empty( trim( wp_unslash( $_POST['billing_phone'] ) ) ) ) {
        $errors->add( 'billing_phone_error', __( 'Veuillez renseigner votre téléphone.', 'senmarket-child' ) );
    }

    return $errors;
}

add_action( 'woocommerce_created_customer', 'senmarket_save_registration_fields' );
function senmarket_save_registration_fields( $customer_id ) {
    if ( isset( $_POST['billing_first_name'] ) ) {
        update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) ) );
        update_user_meta( $customer_id, 'first_name', sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) ) );
    }

    if ( isset( $_POST['billing_last_name'] ) ) {
        update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( wp_unslash( $_POST['billing_last_name'] ) ) );
        update_user_meta( $customer_id, 'last_name', sanitize_text_field( wp_unslash( $_POST['billing_last_name'] ) ) );
    }

    if ( isset( $_POST['billing_phone'] ) ) {
        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) );
    }
}

function senmarket_configure_dokan_settings() {
    $dokan_general = get_option( 'dokan_general', [] );
    if ( ! is_array( $dokan_general ) ) {
        $dokan_general = [];
    }

    $dokan_general = array_merge( $dokan_general, [
        'enable_selling'     => 'on',
        'seller_can_reg'     => 'yes',
        'new_seller_status'  => 'pending',
        'admin_fee'          => 5,
        'minimum_withdrawal' => 10000,
        'seller_dashboard'   => '/dashboard/',
        'custom_store_url'   => 'store',
    ] );

    update_option( 'dokan_general', $dokan_general );

    $dokan_selling = get_option( 'dokan_selling', [] );
    if ( ! is_array( $dokan_selling ) ) {
        $dokan_selling = [];
    }

    $dokan_selling = array_merge( $dokan_selling, [
        'new_seller_enable_selling' => 'on',
    ] );

    update_option( 'dokan_selling', $dokan_selling );

    $dokan_pages = get_option( 'dokan_pages', [] );
    if ( ! is_array( $dokan_pages ) ) {
        $dokan_pages = [];
    }

    if ( function_exists( 'wc_get_page_id' ) ) {
        $myaccount_page = wc_get_page_id( 'myaccount' );
        if ( $myaccount_page > 0 ) {
            $dokan_pages['myaccount'] = $myaccount_page;
        }
    }

    $dashboard_page = get_page_by_path( 'dashboard' );
    if ( $dashboard_page && ! empty( $dashboard_page->ID ) ) {
        $dokan_pages['dashboard'] = $dashboard_page->ID;
    }

    update_option( 'dokan_pages', $dokan_pages );
}

function senmarket_configure_wp_mail_smtp() {
    if ( ! function_exists( 'is_plugin_active' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $mail_smtp_active = false;
    if ( function_exists( 'is_plugin_active' ) ) {
        $mail_smtp_active = is_plugin_active( 'wp-mail-smtp/wp_mail_smtp.php' ) || is_plugin_active( 'wp-mail-smtp-pro/wp-mail-smtp.php' );
    }

    if ( ! $mail_smtp_active ) {
        return;
    }

    $options = get_option( 'wp_mail_smtp', [] );
    if ( ! is_array( $options ) ) {
        $options = [];
    }

    $options['mail'] = array_merge( $options['mail'] ?? [], [
        'from_name'        => defined( 'SMTP_FROM_NAME' ) ? SMTP_FROM_NAME : 'SenMarket',
        'from_email'       => defined( 'SMTP_FROM_EMAIL' ) ? SMTP_FROM_EMAIL : 'noreply@senmarket.sn',
        'mailer'           => 'sendinblue',
        'return_path'      => true,
        'from_name_force'  => true,
        'from_email_force' => true,
    ] );

    $options['sendinblue'] = array_merge( $options['sendinblue'] ?? [], [
        'api_key' => defined( 'BREVO_API_KEY' ) ? BREVO_API_KEY : '',
    ] );

    update_option( 'wp_mail_smtp', $options );
}

add_action( 'admin_init', function() {
    if ( function_exists( 'wp_get_theme' ) && wp_get_theme()->get_stylesheet() === 'senmarket-child' ) {
        senmarket_apply_plugin_defaults();
    }
} );

/**
 * WooCommerce AJAX fragments update for navbar cart count badge.
 */
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
    if ( function_exists( 'WC' ) && WC()->cart ) {
        $count = WC()->cart->get_cart_contents_count();
        $fragments['span.cart-count-badge'] = '<span class="cart-count-badge">' . esc_html( $count ) . '</span>';
    }
    return $fragments;
} );

/**
 * Rebrand COD (Cash on Delivery) to Mobile Money.
 */
add_filter( 'woocommerce_gateway_title', function( $title, $gateway_id ) {
    if ( 'cod' === $gateway_id ) {
        return 'Mobile Money (Wave / Orange Money / Free Money)';
    }
    return $title;
}, 10, 2 );

add_filter( 'woocommerce_gateway_description', function( $description, $gateway_id ) {
    if ( 'cod' === $gateway_id ) {
        return 'Payez directement et en toute sécurité par Wave, Orange Money ou Free Money. Après confirmation de votre commande, vous pourrez envoyer votre reçu de transfert sur WhatsApp pour accélérer la validation.';
    }
    return $description;
}, 10, 2 );

/**
 * WhatsApp Validation Button on Thank You Page.
 */
add_action( 'woocommerce_thankyou', function( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }
    
    if ( 'cod' === $order->get_payment_method() ) {
        $wa_number = defined( 'SENMARKET_WA_NUMBER' ) ? SENMARKET_WA_NUMBER : '+221770000000';
        // Clean phone number (remove +, spaces, leading zeros or country codes if redundant, but standard ltrim is fine)
        $clean_wa = ltrim( $wa_number, '+' );
        
        $message = sprintf(
            "Bonjour SenMarket, je souhaite valider mon paiement pour la commande #%s d'un montant de %s XOF.",
            $order->get_order_number(),
            $order->get_total()
        );
        $wa_url = 'https://wa.me/' . $clean_wa . '?text=' . rawurlencode( $message );
        
        ?>
        <div class="wa-payment-validation" style="margin: 30px 0; padding: 25px; border: 2px dashed var(--color-gold); border-radius: var(--border-radius-md); background: var(--bg-secondary); text-align: center; box-shadow: var(--shadow-sm);">
            <h3 style="margin-top:0; font-family:var(--font-display); font-size: 1.5rem; color: var(--text-primary);">
                <i class="fab fa-whatsapp" style="color:var(--color-whatsapp); margin-right: 8px;"></i>
                <?php esc_html_e( 'Validation de votre Paiement', 'senmarket-child' ); ?>
            </h3>
            <p style="color: var(--text-secondary); margin-bottom: 20px;">
                <?php esc_html_e( 'Veuillez effectuer le transfert Wave ou Orange Money au numéro de la boutique (+221 77 000 00 00), puis cliquez sur le bouton ci-dessous pour nous envoyer votre reçu sur WhatsApp afin de valider l\'expédition.', 'senmarket-child' ); ?>
            </p>
            <a href="<?php echo esc_url( $wa_url ); ?>" class="btn btn-primary" target="_blank" style="background: var(--color-whatsapp); border-color: var(--color-whatsapp); display: inline-flex; align-items: center; gap: 8px; color: #fff;">
                <i class="fab fa-whatsapp" style="font-size: 20px;"></i>
                <?php esc_html_e( 'Envoyer le reçu sur WhatsApp', 'senmarket-child' ); ?>
            </a>
        </div>
        <?php
    }
}, 10 );

/**
 * AJAX Login Handler.
 */
add_action( 'wp_ajax_nopriv_senmarket_ajax_login', 'senmarket_ajax_login_handler' );
add_action( 'wp_ajax_senmarket_ajax_login', 'senmarket_ajax_login_handler' );

function senmarket_ajax_login_handler() {
    check_ajax_referer( 'senmarket_login_nonce', 'security' );

    $info = [];
    $info['user_login']    = isset( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : '';
    $info['user_password'] = isset( $_POST['password'] ) ? $_POST['password'] : ''; // Don't sanitize passwords
    $info['remember']      = isset( $_POST['rememberme'] ) && $_POST['rememberme'] === 'forever';

    if ( empty( $info['user_login'] ) || empty( $info['user_password'] ) ) {
        wp_send_json_error( [ 'message' => __( 'Veuillez remplir tous les champs.', 'senmarket-child' ) ] );
    }

    $user_signon = wp_signon( $info, false );

    if ( is_wp_error( $user_signon ) ) {
        wp_send_json_error( [ 'message' => $user_signon->get_error_message() ] );
    } else {
        wp_send_json_success( [ 'message' => __( 'Connexion réussie. Redirection...', 'senmarket-child' ) ] );
    }
}

/**
 * AJAX Registration Handler.
 */
add_action( 'wp_ajax_nopriv_senmarket_ajax_register', 'senmarket_ajax_register_handler' );
add_action( 'wp_ajax_senmarket_ajax_register', 'senmarket_ajax_register_handler' );

function senmarket_ajax_register_handler() {
    check_ajax_referer( 'senmarket_register_nonce', 'security' );

    $first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
    $last_name  = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
    $phone      = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
    $email      = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
    $password   = isset( $_POST['password'] ) ? $_POST['password'] : '';
    $role       = isset( $_POST['role'] ) ? sanitize_text_field( wp_unslash( $_POST['role'] ) ) : 'customer';

    if ( empty( $first_name ) || empty( $last_name ) || empty( $phone ) || empty( $email ) || empty( $password ) ) {
        wp_send_json_error( [ 'message' => __( 'Veuillez remplir tous les champs requis.', 'senmarket-child' ) ] );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Adresse e-mail invalide.', 'senmarket-child' ) ] );
    }

    if ( email_exists( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Cette adresse e-mail est déjà utilisée.', 'senmarket-child' ) ] );
    }

    if ( username_exists( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Ce nom d\'utilisateur est déjà utilisé.', 'senmarket-child' ) ] );
    }

    // Determine target role
    $target_role = ( $role === 'seller' ) ? 'seller' : 'customer';

    // Insert new user
    $user_data = [
        'user_login' => $email,
        'user_email' => $email,
        'user_pass'  => $password,
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'role'       => $target_role,
    ];

    $user_id = wp_insert_user( $user_data );

    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error( [ 'message' => $user_id->get_error_message() ] );
    }

    // Save Billing & Profile metadata
    update_user_meta( $user_id, 'billing_first_name', $first_name );
    update_user_meta( $user_id, 'billing_last_name', $last_name );
    update_user_meta( $user_id, 'first_name', $first_name );
    update_user_meta( $user_id, 'last_name', $last_name );
    update_user_meta( $user_id, 'billing_phone', $phone );

    // If seller, configure Dokan settings
    if ( $target_role === 'seller' ) {
        $store_name = isset( $_POST['store_name'] ) ? sanitize_text_field( wp_unslash( $_POST['store_name'] ) ) : $first_name . ' Shop';
        
        $store_info = [
            'store_name' => $store_name,
            'phone'      => $phone,
            'address'    => [
                'street_1' => '',
                'city'     => '',
                'zip'      => '',
                'country'  => 'SN',
            ],
            'social'     => [],
            'payment'    => [],
        ];
        update_user_meta( $user_id, 'dokan_profile_settings', $store_info );
        update_user_meta( $user_id, 'dokan_enable_selling', 'yes' );
    }

    // Auto-login after registration
    wp_set_current_user( $user_id );
    wp_set_auth_cookie( $user_id, true );

    wp_send_json_success( [ 'message' => __( 'Inscription réussie ! Connexion automatique...', 'senmarket-child' ) ] );
}



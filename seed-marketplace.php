<?php
/**
 * Seed Marketplace Catalog
 * Run from the WordPress root: php seed-marketplace.php
 */

require 'wp-load.php';

if ( ! class_exists( 'WooCommerce' ) ) {
    die( "WooCommerce must be active to seed products.\n" );
}

function seed_get_or_create_category( $name, $slug ) {
    $term = get_term_by( 'slug', $slug, 'product_cat' );
    if ( $term ) return $term->term_id;
    $result = wp_insert_term( $name, 'product_cat', [ 'slug' => $slug ] );
    if ( is_wp_error( $result ) ) { echo "Error creating category {$name}: " . $result->get_error_message() . "\n"; return 0; }
    echo "Category created: {$name}.\n";
    return $result['term_id'];
}

function seed_get_or_create_user( $login, $password, $email, $role ) {
    $user = get_user_by( 'login', $login );
    if ( $user ) return $user->ID;
    $user_id = wp_create_user( $login, $password, $email );
    if ( is_wp_error( $user_id ) ) { echo "Error creating user {$login}: " . $user_id->get_error_message() . "\n"; return 0; }
    $wp_user = new WP_User( $user_id );
    $wp_user->set_role( $role );
    echo "Created user {$login} ({$role}).\n";
    return $user_id;
}

function seed_configure_dokan_vendor( $vendor_id, $store_name ) {
    if ( ! $vendor_id || ! function_exists( 'dokan_is_user_seller' ) ) return;
    $store_info = [
        'store_name' => $store_name,
        'phone'      => '+221' . rand( 700000000, 779999999 ),
        'address'    => [ 'street_1' => 'Avenue Léopold Sédar Senghor', 'city' => 'Dakar', 'zip' => '11500', 'country' => 'SN' ],
        'social'     => [],
        'payment'    => [ 'bank' => [ 'ac_name' => $store_name, 'ac_number' => 'SN' . rand( 1000000000, 9999999999 ), 'bank_name' => 'BICIS' ] ],
    ];
    update_user_meta( $vendor_id, 'dokan_profile_settings', $store_info );
    update_user_meta( $vendor_id, 'dokan_enable_selling', 'yes' );
}

/**
 * Génère une image locale avec GD et l'attache comme miniature du produit.
 * Aucun accès internet requis.
 */
function seed_generate_and_attach_image( $product_id, $category_slug, $product_title ) {
    if ( ! function_exists( 'imagecreatetruecolor' ) ) {
        echo "  ⚠ GD non disponible.\n";
        return;
    }

    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $palette = [
        'electronique' => [ 25,  35,  80 ],
        'telephones'   => [ 15,  55, 100 ],
        'informatique' => [  5,  45,  90 ],
        'maison'       => [ 90,  60,  15 ],
        'beaute'       => [150,  40,  90 ],
        'mode-homme'   => [ 35,  35,  45 ],
        'mode-femme'   => [160,  45, 100 ],
        'sports'       => [ 15,  90,  35 ],
        'automobile'   => [ 55,  55,  65 ],
        'livres'       => [100,  65,  15 ],
    ];

    $labels = [
        'electronique' => 'ELECTRONIQUE',
        'telephones'   => 'TELEPHONES',
        'informatique' => 'INFORMATIQUE',
        'maison'       => 'MAISON',
        'beaute'       => 'BEAUTE',
        'mode-homme'   => 'MODE HOMME',
        'mode-femme'   => 'MODE FEMME',
        'sports'       => 'SPORTS',
        'automobile'   => 'AUTOMOBILE',
        'livres'       => 'LIVRES',
    ];

    [ $r, $g, $b ] = $palette[ $category_slug ] ?? [ 60, 60, 80 ];

    $w = 600; $h = 600;
    $img = imagecreatetruecolor( $w, $h );

    // Dégradé vertical
    for ( $y = 0; $y < $h; $y++ ) {
        $f = 1 - ( $y / $h ) * 0.45;
        $c = imagecolorallocate( $img,
            (int) min(255, $r * $f + 15),
            (int) min(255, $g * $f + 15),
            (int) min(255, $b * $f + 15)
        );
        imageline( $img, 0, $y, $w, $y, $c );
    }

    // Cercle central décoratif
    $circle_color = imagecolorallocatealpha( $img, 255, 255, 255, 100 );
    imagefilledellipse( $img, $w/2, $h/2, 320, 320, $circle_color );

    // Texte catégorie
    $white  = imagecolorallocate( $img, 255, 255, 255 );
    $silver = imagecolorallocate( $img, 200, 210, 220 );
    $label  = $labels[ $category_slug ] ?? strtoupper( $category_slug );
    $short  = mb_substr( $product_title, 0, 26 );

    $cw5 = imagefontwidth(5); $ch5 = imagefontheight(5);
    $cw3 = imagefontwidth(3); $ch3 = imagefontheight(3);

    imagestring( $img, 5, (int)(($w - strlen($label)*$cw5)/2), (int)($h/2 - $ch5 - 8), $label, $white );
    imagestring( $img, 3, (int)(($w - strlen($short)*$cw3)/2), (int)($h/2 + 8),          $short,  $silver );

    $tmp = sys_get_temp_dir() . '/wp_seed_' . $product_id . '_' . uniqid() . '.jpg';
    imagejpeg( $img, $tmp, 88 );
    imagedestroy( $img );

    $file_array = [ 'name' => 'product-img-' . $product_id . '.jpg', 'tmp_name' => $tmp ];
    $att_id = media_handle_sideload( $file_array, $product_id, $product_title );
    @unlink( $tmp );

    if ( is_wp_error( $att_id ) ) {
        echo "  ⚠ Image échouée #{$product_id}: " . $att_id->get_error_message() . "\n";
        return;
    }
    set_post_thumbnail( $product_id, $att_id );
}

function seed_create_product( $title, $price, $description, $categories, $vendor_id, $category_slug, $sale_price = null ) {
    $existing = get_page_by_title( $title, OBJECT, 'product' );
    if ( $existing ) return $existing->ID;

    $product = new WC_Product_Simple();
    $product->set_name( $title );
    $product->set_status( 'publish' );
    $product->set_catalog_visibility( 'visible' );
    $product->set_description( $description );
    $product->set_short_description( wp_trim_words( $description, 24, '...' ) );
    $product->set_regular_price( $price );
    if ( $sale_price ) $product->set_sale_price( $sale_price );
    $product->set_category_ids( $categories );
    $product->set_manage_stock( true );
    $product->set_stock_quantity( rand( 14, 120 ) );
    $product->set_reviews_allowed( true );

    $product_id = $product->save();
    if ( is_wp_error( $product_id ) ) { echo "Error: " . $product_id->get_error_message() . "\n"; return 0; }

    wp_update_post( [ 'ID' => $product_id, 'post_author' => $vendor_id ] );
    seed_generate_and_attach_image( $product_id, $category_slug, $title );

    return $product_id;
}

// ── Catégories ──────────────────────────────────────────────────────────────
$category_counts = [
    'electronique' => 50, 'telephones' => 40, 'informatique' => 40,
    'maison' => 40, 'beaute' => 30, 'mode-homme' => 40,
    'mode-femme' => 40, 'sports' => 30, 'automobile' => 30, 'livres' => 10,
];
$category_labels = [
    'electronique' => 'Électronique', 'telephones' => 'Téléphones',
    'informatique' => 'Informatique', 'maison' => 'Maison',
    'beaute' => 'Beauté', 'mode-homme' => 'Mode Homme',
    'mode-femme' => 'Mode Femme', 'sports' => 'Sports',
    'automobile' => 'Automobile', 'livres' => 'Livres',
];
$category_ids = [];
foreach ( $category_labels as $slug => $label ) {
    $category_ids[ $slug ] = seed_get_or_create_category( $label, $slug );
}

// ── Vendeurs ─────────────────────────────────────────────────────────────────
$vendors = [
    [ 'login' => 'tech_sen',     'email' => 'tech@senmarket.sn',   'store' => 'Tech Sénégal' ],
    [ 'login' => 'mode_teranga', 'email' => 'mode@senmarket.sn',   'store' => 'Mode Teranga' ],
    [ 'login' => 'auto_express', 'email' => 'auto@senmarket.sn',   'store' => 'Auto Express' ],
    [ 'login' => 'maison_style', 'email' => 'maison@senmarket.sn', 'store' => 'Maison & Co' ],
    [ 'login' => 'sportif_sn',   'email' => 'sport@senmarket.sn',  'store' => 'Sportif Sénégal' ],
];
$vendor_ids = [];
foreach ( $vendors as $v ) {
    $vendor_ids[] = seed_get_or_create_user( $v['login'], 'Senmarket123!', $v['email'], 'seller' );
}
foreach ( $vendor_ids as $i => $vid ) {
    seed_configure_dokan_vendor( $vid, $vendors[$i]['store'] );
}
seed_get_or_create_user( 'client_amine', 'Senmarket123!', 'amine@senmarket.sn', 'customer' );
seed_get_or_create_user( 'client_sadia', 'Senmarket123!', 'sadia@senmarket.sn', 'customer' );

// ── Données produits ─────────────────────────────────────────────────────────
$adjectives = [ 'Premium', 'Ultra', 'Pro', 'Édition limitée', 'Vintage', 'Compact', 'Grand format', 'Intelligent', 'Connecté', 'Exclusif' ];
$bases = [
    'electronique' => [ 'Casque Bluetooth', 'Enceinte portable', 'Drone de loisir', 'Barre de son', 'Montre connectée' ],
    'telephones'   => [ 'Smartphone Android', 'iPhone reconditionné', 'Téléphone 5G', 'Smartphone double SIM', 'Téléphone tactile' ],
    'informatique' => [ 'Ordinateur portable', 'Clavier mécanique', 'Souris gamer', 'Écran LED', 'SSD NVMe' ],
    'maison'       => [ 'Lampe design', 'Rangement modulable', 'Robot aspirateur', 'Cafetière filtre', 'Housse de canapé' ],
    'beaute'       => [ 'Parfum floral', 'Sérum visage', 'Kit maquillage', 'Crème hydratante', 'Shampooing naturel' ],
    'mode-homme'   => [ 'Costume élégant', 'Veste en jean', 'Chemise slim', 'Pull en laine', 'Chaussures derby' ],
    'mode-femme'   => [ 'Robe fluide', 'Sac à main', 'Blouse soie', 'Jeans skinny', 'Sandales tressées' ],
    'sports'       => [ 'Short de sport', 'Tapis de yoga', 'Ballon de football', 'Veste running', 'Gourde isotherme' ],
    'automobile'   => [ 'Housse de siège', 'Caméra de bord', 'Batterie de démarrage', 'Radar de recul', 'Kit de nettoyage' ],
    'livres'       => [ 'Roman contemporain', 'Guide voyage', 'Livre de cuisine', 'Bande dessinée', 'Essai business' ],
];
$descs = [
    'Cette référence est conçue pour offrir performance, confort et durabilité au quotidien.',
    'Un produit fiable, pensé pour des clients exigeants et une utilisation prolongée.',
    'Idéal pour la maison, les voyages et les besoins professionnels les plus exigeants.',
    'Finition soignée, matériaux premium et fonctionnalités modernes pour une expérience haut de gamme.',
    "Une valeur sûre assortie d'un design contemporain et d'une simplicité d'utilisation optimale.",
];

// ── Création produits ─────────────────────────────────────────────────────────
$created = 0;
foreach ( $category_counts as $slug => $count ) {
    echo "\n📦 Catégorie : {$category_labels[$slug]}\n";
    for ( $i = 1; $i <= $count; $i++ ) {
        $vid   = $vendor_ids[ array_rand( $vendor_ids ) ];
        $title = $bases[$slug][ array_rand($bases[$slug]) ] . ' ' . $adjectives[ array_rand($adjectives) ];
        $price = rand( 12000, 220000 );
        $sale  = rand(0,100) < 35 ? round( $price * rand(70,85)/100 ) : null;
        $desc  = $descs[ array_rand($descs) ] . ' Parfait pour le marché sénégalais, livré avec support client local et paiement sécurisé.';

        $pid = seed_create_product( $title, $price, $desc, [ $category_ids[$slug] ], $vid, $slug, $sale );
        if ( $pid ) { $created++; echo "  ✓ #{$pid} {$title}\n"; }
    }
}

echo "\n✅ Seed terminé : {$created} produits créés dans " . count($category_counts) . " catégories.\n";
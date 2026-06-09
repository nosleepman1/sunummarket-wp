<?php
define('WP_HTTP_BLOCK_EXTERNAL', true);
require 'wp-load.php';

echo "=== START SEEDING ===" . PHP_EOL;

// Helper to create categories
function get_or_create_category($name, $slug) {
    $term = get_term_by('slug', $slug, 'product_cat');
    if ($term) {
        echo "Category '$name' already exists." . PHP_EOL;
        return $term->term_id;
    }
    $result = wp_insert_term($name, 'product_cat', array('slug' => $slug));
    if (is_wp_error($result)) {
        echo "Error creating category '$name': " . $result->get_error_message() . PHP_EOL;
        return 0;
    }
    echo "Category '$name' created successfully." . PHP_EOL;
    return $result['term_id'];
}

$cat_alimentaire = get_or_create_category('Alimentation & Boissons', 'alimentation');
$cat_mode = get_or_create_category('Mode & Textile', 'mode');
$cat_artisanat = get_or_create_category('Artisanat & Déco', 'artisanat');

// Helper to create users with roles
function get_or_create_user($username, $password, $email, $role) {
    $user = get_user_by('login', $username);
    if ($user) {
        echo "User '$username' already exists." . PHP_EOL;
        return $user->ID;
    }
    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
        echo "Error creating user '$username': " . $user_id->get_error_message() . PHP_EOL;
        return 0;
    }
    $u = new WP_User($user_id);
    $u->set_role($role);
    echo "User '$username' ($role) created successfully." . PHP_EOL;
    return $user_id;
}

// Vendors (role: seller for Dokan)
$vendor_diouf = get_or_create_user('vendor_diouf', 'Pass123_diouf', 'diouf@senmarket.sn', 'seller');
$vendor_sall = get_or_create_user('vendor_sall', 'Pass123_sall', 'sall@senmarket.sn', 'seller');

// Configure Dokan settings for vendors
function configure_dokan_vendor($vendor_id, $store_name, $phone) {
    if (!$vendor_id) return;
    $settings = array(
        'store_name' => $store_name,
        'phone'      => $phone,
        'address'    => array(
            'street_1' => 'Avenue Bourguiba',
            'city'     => 'Dakar',
            'zip'      => '12000',
            'country'  => 'SN'
        ),
        'social' => array(),
        'payment' => array(
            'bank' => array(
                'ac_name'   => $store_name,
                'ac_number' => 'SN1234567890',
                'bank_name' => 'BICIS',
            )
        )
    );
    update_user_meta($vendor_id, 'dokan_profile_settings', $settings);
    update_user_meta($vendor_id, 'dokan_enable_selling', 'yes');
    echo "Configured Dokan settings for vendor ID $vendor_id ($store_name)." . PHP_EOL;
}

configure_dokan_vendor($vendor_diouf, 'Teranga Delices', '+221771112233');
configure_dokan_vendor($vendor_sall, 'Ngaye Cuir & Couture', '+221774445566');

// Customers
$customer_amadou = get_or_create_user('client_amadou', 'Pass123_amadou', 'amadou@gmail.com', 'customer');
$customer_fatou = get_or_create_user('client_fatou', 'Pass123_fatou', 'fatou@gmail.com', 'customer');

// Helper to import local image to WordPress Media Library
function import_local_image($file_path, $title) {
    if (!file_exists($file_path)) {
        echo "Image file does not exist at: $file_path" . PHP_EOL;
        return 0;
    }
    
    // Check if attachment already exists by title
    global $wpdb;
    $attachment_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'attachment'", $title));
    if ($attachment_id) {
        echo "Image '$title' already imported as ID $attachment_id." . PHP_EOL;
        return $attachment_id;
    }
    
    $upload_dir = wp_upload_dir();
    $filename = basename($file_path);
    
    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }
    
    if (!copy($file_path, $file)) {
        echo "Failed to copy image to: $file" . PHP_EOL;
        return 0;
    }
    
    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name($title),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );
    
    $attach_id = wp_insert_attachment($attachment, $file);
    if (is_wp_error($attach_id)) {
        echo "Error importing attachment '$title': " . $attach_id->get_error_message() . PHP_EOL;
        return 0;
    }
    
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    wp_update_attachment_metadata($attach_id, $attach_data);
    
    echo "Image '$title' imported successfully as ID $attach_id." . PHP_EOL;
    return $attach_id;
}

// Image paths from brain folder
$img_touba = import_local_image('C:\Users\abash\.gemini\antigravity-ide\brain\fdd9a6d3-2f2d-43a2-9c7a-bd1373e6affe\cafe_touba_1780812192409.png', 'Café Touba');
$img_bissap = import_local_image('C:\Users\abash\.gemini\antigravity-ide\brain\fdd9a6d3-2f2d-43a2-9c7a-bd1373e6affe\jus_bissap_1780812207681.png', 'Jus de Bissap');
$img_bazin = import_local_image('C:\Users\abash\.gemini\antigravity-ide\brain\fdd9a6d3-2f2d-43a2-9c7a-bd1373e6affe\bazin_brode_1780812220675.png', 'Bazin Brodé');
$img_sandales = import_local_image('C:\Users\abash\.gemini\antigravity-ide\brain\fdd9a6d3-2f2d-43a2-9c7a-bd1373e6affe\sandales_ngaye_1780812232599.png', 'Sandales de Ngaye');
$img_panier = import_local_image('C:\Users\abash\.gemini\antigravity-ide\brain\fdd9a6d3-2f2d-43a2-9c7a-bd1373e6affe\panier_tisse_1780812245871.png', 'Panier en Paille Tissé');

// Helper to create WooCommerce product
function create_wc_product($name, $price, $desc, $short_desc, $categories, $image_id, $vendor_id) {
    // Check if product already exists by title
    $args = array(
        'post_type' => 'product',
        'title'     => $name,
    );
    $existing = get_posts($args);
    if (!empty($existing)) {
        echo "Product '$name' already exists as ID " . $existing[0]->ID . "." . PHP_EOL;
        return $existing[0]->ID;
    }

    $product = new WC_Product_Simple();
    $product->set_name($name);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_description($desc);
    $product->set_short_description($short_desc);
    $product->set_regular_price($price);
    $product->set_category_ids($categories);
    $product->set_image_id($image_id);
    $product->set_manage_stock(true);
    $product->set_stock_quantity(50);
    $product->set_reviews_allowed(true);
    $product_id = $product->save();
    
    // Assign to Vendor
    wp_update_post(array(
        'ID'          => $product_id,
        'post_author' => $vendor_id,
    ));
    
    echo "Product '$name' created successfully as ID $product_id, assigned to Vendor $vendor_id." . PHP_EOL;
    return $product_id;
}

$prod_touba = create_wc_product(
    'Café Touba Royal',
    '1500',
    'Découvrez le goût authentique du Café Touba Royal, un café robuste moulu et parfumé au piment noir de Selim (djar). Torréfié de manière traditionnelle, il procure une énergie durable et une saveur épicée inimitable.',
    'Café traditionnel sénégalais épicé au piment noir de Selim (Djar). Sachet de 250g.',
    array($cat_alimentaire),
    $img_touba,
    $vendor_diouf
);

$prod_bissap = create_wc_product(
    'Jus de Bissap Bio',
    '1200',
    'Notre Jus de Bissap est préparé à base d\'infusion de fleurs d\'hibiscus séchées (Bissap), de feuilles de menthe fraîches et d\'une touche subtile de vanille naturelle. Très riche en antioxydants, il se consomme bien glacé pour une fraîcheur intense.',
    'Boisson rafraîchissante traditionnelle à base de fleurs d\'hibiscus bio. Bouteille de 1L.',
    array($cat_alimentaire),
    $img_bissap,
    $vendor_diouf
);

$prod_bazin = create_wc_product(
    'Bazin Riche Brodé - Royal Blue',
    '45000',
    'Ce somptueux boubou en Bazin Riche allemand est brodé à la main par des artisans sénégalais de renom. Le tissu brille d\'un éclat unique et offre un tombé parfait. Idéal pour les grandes occasions (Tabaski, mariages, baptêmes).',
    'Bazin Riche de qualité supérieure avec broderies artisanales dorées. Modèle homme standard.',
    array($cat_mode),
    $img_bazin,
    $vendor_sall
);

$prod_sandales = create_wc_product(
    'Sandales de Ngaye en Cuir Véritable',
    '18000',
    'Fabriquées à la main dans le village historique de Ngaye Mékhé, ces sandales en cuir de vachette tanné végétal combinent robustesse et confort. Elles arborent des coutures décoratives raffinées faites à la main.',
    'Sandales en cuir faites main à Ngaye Mékhé, confortables et durables.',
    array($cat_mode, $cat_artisanat),
    $img_sandales,
    $vendor_sall
);

$prod_panier = create_wc_product(
    'Grand Panier en Paille Tissé',
    '12000',
    'Ce panier de rangement est tressé à la main par des coopératives de femmes dans le sud du Sénégal. Fabriqué en paille naturelle et lanières de plastique recyclé colorées, il apporte une touche bohème et ethnique à votre intérieur tout en restant très pratique.',
    'Panier de rangement tressé main, motifs géométriques traditionnels.',
    array($cat_artisanat),
    $img_panier,
    $vendor_sall
);

// Create Contact Page
$contact_page = get_page_by_path('contact');
if (!$contact_page) {
    $contact_content = '
<div class="contact-page-container">
  <div class="contact-grid">
    <div class="contact-info">
      <h3>Nos Coordonnées</h3>
      <p>Une question concernant un produit ou un problème avec votre commande ? N\'hésitez pas à nous contacter.</p>
      <ul class="contact-details">
        <li><i class="fas fa-phone"></i> +221 77 375 70 77</li>
        <li><i class="fas fa-envelope"></i> abdallahdiouf.dev@gmail.com</li>
        <li><i class="fas fa-map-marker-alt"></i> Keur Massar, Dakar, Sénégal</li>
      </ul>
      <div class="wa-contact-wrapper" style="margin-top: 25px;">
        <a href="https://wa.me/221773757077" class="btn btn-whatsapp" target="_blank" style="background:#25d366; color:#fff; display:inline-flex; align-items:center; gap:8px; padding:12px 20px; border-radius:9999px; font-weight:600;">
          <i class="fab fa-whatsapp" style="font-size:20px;"></i> Discuter sur WhatsApp
        </a>
      </div>
    </div>
    <div class="contact-form-wrapper">
      <h3>Envoyez-nous un message</h3>
      <form class="senmarket-contact-form" action="" method="post" style="display:flex; flex-direction:column; gap:15px; margin-top:20px;">
        <div class="form-group" style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-weight:600; color:var(--text-secondary);">Nom Complet</label>
          <input type="text" name="contact_name" required style="padding:12px; border:1px solid var(--border-color); border-radius:8px; background:var(--bg-secondary); color:var(--text-primary);" />
        </div>
        <div class="form-group" style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-weight:600; color:var(--text-secondary);">Adresse Email</label>
          <input type="email" name="contact_email" required style="padding:12px; border:1px solid var(--border-color); border-radius:8px; background:var(--bg-secondary); color:var(--text-primary);" />
        </div>
        <div class="form-group" style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-weight:600; color:var(--text-secondary);">Votre Message</label>
          <textarea name="contact_message" rows="5" required style="padding:12px; border:1px solid var(--border-color); border-radius:8px; background:var(--bg-secondary); color:var(--text-primary); resize:vertical;"></textarea>
        </div>
        <button type="submit" class="btn-primary" style="align-self:flex-start;">Envoyer</button>
      </form>
    </div>
  </div>
</div>';

    $page_id = wp_insert_post(array(
        'post_title'   => 'Contact',
        'post_name'    => 'contact',
        'post_content' => $contact_content,
        'post_status'  => 'publish',
        'post_type'    => 'page'
    ));
    echo "Contact page created with ID $page_id." . PHP_EOL;
} else {
    echo "Contact page already exists." . PHP_EOL;
}

// Create a mock order if order doesn't exist
global $wpdb;
$existing_orders = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}wc_order_stats");
if ($existing_orders == 0) {
    if ($prod_touba && $customer_amadou) {
        $order = wc_create_order();
        $order->add_product(wc_get_product($prod_touba), 2); // 2 units
        $order->set_address(array(
            'first_name' => 'Amadou',
            'last_name'  => 'Diop',
            'email'      => 'amadou@gmail.com',
            'phone'      => '+221775556677',
            'address_1'  => 'Avenue Cheikh Anta Diop',
            'city'       => 'Dakar',
            'country'    => 'SN'
        ), 'billing');
        
        $order->set_payment_method('cod');
        $order->set_payment_method_title('Paiement par Mobile Money (Wave / Orange Money)');
        $order->calculate_totals();
        $order->update_status('processing', 'Nouvelle commande Mobile Money simulée en attente.');
        
        // Associate user
        $order->set_customer_id($customer_amadou);
        $order->save();
        
        echo "Mock order created successfully as ID " . $order->get_id() . "." . PHP_EOL;
    }
} else {
    echo "Orders already exist in database." . PHP_EOL;
}

echo "=== SEEDING COMPLETED ===" . PHP_EOL;

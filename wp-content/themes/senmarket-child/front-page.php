<?php
/**
 * SenMarket Child Theme Front Page
 */

defined( 'ABSPATH' ) || exit;

$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : get_permalink( get_option( 'woocommerce_shop_page_id' ) );
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
$shop_url    = $shop_url ? $shop_url : home_url( '/shop/' );
$account_url = $account_url ? $account_url : home_url( '/my-account/' );

$popular_categories = [
    [ 'name' => 'Électronique', 'slug' => 'electronique', 'icon' => 'fa-tv' ],
    [ 'name' => 'Téléphones', 'slug' => 'telephones', 'icon' => 'fa-mobile-screen' ],
    [ 'name' => 'Informatique', 'slug' => 'informatique', 'icon' => 'fa-laptop-code' ],
    [ 'name' => 'Mode Homme', 'slug' => 'mode-homme', 'icon' => 'fa-shirt' ],
    [ 'name' => 'Mode Femme', 'slug' => 'mode-femme', 'icon' => 'fa-person-dress' ],
    [ 'name' => 'Beauté', 'slug' => 'beaute', 'icon' => 'fa-face-smile' ],
    [ 'name' => 'Maison', 'slug' => 'maison', 'icon' => 'fa-house' ],
    [ 'name' => 'Automobile', 'slug' => 'automobile', 'icon' => 'fa-car' ],
    [ 'name' => 'Sports', 'slug' => 'sports', 'icon' => 'fa-basketball-ball' ],
    [ 'name' => 'Livres', 'slug' => 'livres', 'icon' => 'fa-book' ],
];

$recent_products = new WP_Query([
    'post_type'      => 'product',
    'posts_per_page' => 8,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

$featured_products = wc_get_products([
    'status'   => 'publish',
    'limit'    => 8,
    'featured' => true,
]);

// Fallback to highest rated products if no featured products are set
if ( empty( $featured_products ) ) {
    $featured_products = wc_get_products([
        'status'   => 'publish',
        'limit'    => 8,
        'orderby'  => 'rating',
        'order'    => 'DESC',
    ]);
}

$sale_products = wc_get_products([
    'status'        => 'publish',
    'limit'         => 6,
    'on_sale'       => true,
    'orderby'       => 'date',
    'order'         => 'DESC',
]);

// Fallback to recent products if no sale products are set
if ( empty( $sale_products ) ) {
    $sale_products = wc_get_products([
        'status'   => 'publish',
        'limit'    => 6,
        'orderby'  => 'date',
        'order'    => 'DESC',
    ]);
}

$vendor_args = [
    'role__in' => [ 'seller', 'vendor' ],
    'orderby'   => 'registered',
    'order'     => 'DESC',
    'number'    => 6,
];
$popular_vendors = get_users( $vendor_args );

get_header();
?>
<main class="senmarket-container home-page">
    <section class="hero-section">
        <div class="hero-grid">
            <div class="hero-copy">
                <span class="eyebrow">Marketplace multi-vendeurs</span>
                <h3 class="hero-title">Trouvez les meilleurs produits auprès de vendeurs vérifiés</h1>
                <p class="hero-copy-text">Des milliers de produits, des vendeurs certifiés et des offres exceptionnelles.</p>
                <div class="hero-actions">
                    <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-primary">Acheter maintenant</a>
                    <a href="<?php echo esc_url( $account_url ); ?>" class="btn btn-secondary">Devenir vendeur</a>
                </div>
                <form class="hero-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <label class="screen-reader-text" for="home-search">Rechercher des produits</label>
                    <input id="home-search" type="search" name="s" placeholder="Rechercher un produit, une boutique ou une marque" />
                    <input type="hidden" name="post_type" value="product" />
                    <button type="submit" class="btn-primary">Recherche</button>
                </form>
                <div class="hero-highlights">
                    <span><strong>+300</strong> produits disponibles</span>
                    <span><strong>+120</strong> vendeurs certifiés</span>
                    <span><strong>Support</strong> local en français</span>
                </div>
            </div>
            <div class="hero-panel">
                <div class="hero-card">
                    <div class="hero-card-header">
                        <p>Offre du jour</p>
                        <span class="badge">-20 %</span>
                    </div>
                    <h2>Smartphone Android Pro</h2>
                    <p>Design fin, 128 Go, quadruple caméra et batterie longue durée.</p>
                    <div class="hero-card-meta">
                        <span>Vendeur: Tech Sénégal</span>
                        <span>Note: 4.8/5</span>
                    </div>
                    <div class="hero-card-price">
                        <strong>179 900 XOF</strong>
                        <del>225 000 XOF</del>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section categories-section">
        <div class="section-header">
            <h2>Catégories populaires</h2>
            <p>Découvrez les meilleures catégories et trouvez rapidement votre prochain achat.</p>
        </div>
        <div class="category-grid">
            <?php foreach ( $popular_categories as $category_item ) :
                $term = get_term_by( 'slug', $category_item['slug'], 'product_cat' );
                $count = $term ? $term->count : 0;
                $link  = $term ? get_term_link( $term ) : $shop_url;
            ?>
                <a href="<?php echo esc_url( $link ); ?>" class="category-card">
                    <span class="category-icon"><i class="fas <?php echo esc_attr( $category_item['icon'] ); ?>" aria-hidden="true"></i></span>
                    <h3><?php echo esc_html( $category_item['name'] ); ?></h3>
                    <p><?php echo esc_html( $count ); ?> produits</p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="home-section featured-products-section">
        <div class="section-header">
            <h2>Produits vedettes</h2>
            <p>Une sélection premium de produits populaires et bien notés.</p>
        </div>
        <ul class="products-grid products">
            <?php if ( $featured_products ) : foreach ( $featured_products as $featured_product ) :
                $featured_post = get_post( $featured_product->get_id() );
                if ( $featured_post ) :
                    $GLOBALS['post']    = $featured_post;
                    setup_postdata( $featured_post );
                    $GLOBALS['product'] = $featured_product;
                    wc_get_template_part( 'content', 'product' );
                endif;
            endforeach; wp_reset_postdata(); endif; ?>
        </ul>
    </section>

    <section class="home-section recent-products-section">
        <div class="section-header">
            <h2>Produits récents</h2>
            <p>Les nouveautés fraîchement publiées par nos vendeurs.</p>
        </div>
        <ul class="products-grid products">
            <?php if ( $recent_products->have_posts() ) : while ( $recent_products->have_posts() ) : $recent_products->the_post(); wc_get_template_part( 'content', 'product' ); endwhile; wp_reset_postdata(); else : ?><p><?php esc_html_e( 'Aucun produit trouvé.', 'senmarket-child' ); ?></p><?php endif; ?>
        </ul>
    </section>

    <section id="promotions" class="home-section promotions-section">
        <div class="section-header">
            <h2>Offres spéciales</h2>
            <p>Profitez des réductions du moment avant qu'elles ne disparaissent.</p>
        </div>
        <div class="promo-grid">
            <?php if ( $sale_products ) : foreach ( $sale_products as $product ) : setup_postdata( $GLOBALS['post'] = get_post( $product->get_id() ) ); ?>
                <article class="promo-card">
                    <div class="promo-badge">Offre limitée</div>
                    <div class="promo-content">
                        <h3><a href="<?php the_permalink(); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
                        <p><?php echo wp_trim_words( $product->get_short_description() ?: $product->get_description(), 18, '...' ); ?></p>
                        <div class="promo-meta">
                            <span class="price-sale"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                            <span class="promo-countdown" data-countdown="<?php echo esc_attr( gmdate( 'c', strtotime( '+3 days' ) ) ); ?>">00:00:00</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; wp_reset_postdata(); endif; ?>
        </div>
    </section>

    <section class="home-section vendors-section">
        <div class="section-header">
            <h2>Vendeurs populaires</h2>
            <p>Découvrez les boutiques les mieux notées et leurs produits.</p>
        </div>
        <div class="vendor-grid">
            <?php if ( $popular_vendors ) : foreach ( $popular_vendors as $vendor ) :
                $store_name = function_exists( 'dokan_get_store_info' ) ? dokan_get_store_info( $vendor->ID ) : [];
                $avatar     = get_avatar_url( $vendor->ID, [ 'size' => 96 ] );
                $store_name = ! empty( $store_name['store_name'] ) ? $store_name['store_name'] : $vendor->display_name;
                $store_link = function_exists( 'dokan_get_store_url' ) ? dokan_get_store_url( $vendor->ID ) : get_author_posts_url( $vendor->ID );
                $product_count = count_user_posts( $vendor->ID, 'product' );
            ?>
                <article class="vendor-card">
                    <div class="vendor-avatar" style="background-image:url('<?php echo esc_url( $avatar ); ?>');"></div>
                    <div class="vendor-body">
                        <h3><a href="<?php echo esc_url( $store_link ); ?>"><?php echo esc_html( $store_name ); ?></a></h3>
                        <p><?php echo esc_html( $product_count ); ?> produits</p>
                        <div class="vendor-meta">
                            <span>Note 4.9</span>
                            <a class="btn btn-secondary" href="<?php echo esc_url( $store_link ); ?>">Visiter</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; endif; ?>
        </div>
    </section>

    <section class="home-section testimonials-section">
        <div class="section-header">
            <h2>Avis clients</h2>
            <p>Ils ont fait confiance à SenMarket et racontent leur expérience.</p>
        </div>
        <div class="testimonial-grid">
            <article class="testimonial-card">
                <p>"Service rapide, produit livré en excellent état. La boutique est très professionnelle."</p>
                <strong>Fatou N.</strong>
                <span>Acheteuse</span>
            </article>
            <article class="testimonial-card">
                <p>"J'ai trouvé un vendeur de confiance pour mes fournitures informatiques. Recommande à 100 %."</p>
                <strong>Ismaël D.</strong>
                <span>Client</span>
            </article>
            <article class="testimonial-card">
                <p>"En tant que vendeur, la gestion de ma boutique avec Dokan est claire et efficace."</p>
                <strong>Mamadou S.</strong>
                <span>Vendeur</span>
            </article>
        </div>
    </section>

    <section class="home-section newsletter-section">
        <div class="newsletter-panel">
            <div>
                <span class="eyebrow">Newsletter</span>
                <h2>Restez informé(e) des meilleures offres</h2>
                <p>Recevez les nouveautés, promotions et recommandations de nos vendeurs directement dans votre boîte mail.</p>
            </div>
            <form class="newsletter-form" method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label class="screen-reader-text" for="newsletter-email">Email</label>
                <input id="newsletter-email" type="email" name="newsletter_email" placeholder="Votre email" required />
                <button type="submit" class="btn-primary">S'inscrire</button>
            </form>
        </div>
    </section>
</main>
<?php
get_footer();

<?php
/**
 * SenMarket Child Theme Home Page
 */

defined( 'ABSPATH' ) || exit;

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : get_permalink( get_option( 'woocommerce_shop_page_id' ) );
$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
$shop_url = $shop_url ? $shop_url : home_url( '/shop/' );
$account_url = $account_url ? $account_url : home_url( '/my-account/' );

get_header();
?>
<main class="senmarket-container">
  <section class="hero-section" data-reveal>
    <div class="hero-content">
      <span class="eyebrow">Marketplace Sénégal</span>
      <h1 class="hero-title">
        SenMarket — la marketplace des vendeurs sénégalais
      </h1>
      <p class="hero-copy">
        Découvrez des produits locaux, achetez en toute confiance et vendez facilement avec Dokan.
      </p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( $shop_url ); ?>" class="btn-primary">
          Voir la boutique
        </a>
        <a href="<?php echo esc_url( $account_url ); ?>" class="btn btn-secondary">
          Mon compte
        </a>
      </div>
    </div>
  </section>

  <section class="home-section" data-reveal>
    <div class="section-header">
      <h2>Produits récents</h2>
      <p>Une sélection de produits récents provenant de nos vendeurs.</p>
    </div>

    <div class="products-grid">
      <?php
      $recent_products = new WP_Query( [
        'post_type'      => 'product',
        'posts_per_page' => 8,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
      ] );

      if ( $recent_products->have_posts() ) :
        while ( $recent_products->have_posts() ) : $recent_products->the_post();
          wc_get_template_part( 'content', 'product' );
        endwhile;
        wp_reset_postdata();
      else :
        echo '<p>' . esc_html__( 'Aucun produit trouvé.', 'senmarket-child' ) . '</p>';
      endif;
      ?>
    </div>
  </section>

  <section class="home-section" data-reveal>
    <div class="section-header">
      <h2>Pourquoi SenMarket ?</h2>
      <p>Une marketplace simple, accessible et adaptée aux commerçants sénégalais.</p>
    </div>
    <div class="feature-grid">
      <div class="feature-card">
        <h3>Multi-vendeur</h3>
        <p>Chaque vendeur dispose d’un tableau de bord complet et d’un espace boutique dédié.</p>
      </div>
      <div class="feature-card">
        <h3>Commission claire</h3>
        <p>Commission administrateur fixe de 5 % pour un modèle transparent.</p>
      </div>
      <div class="feature-card">
        <h3>Paiement XOF</h3>
        <p>Configuration adaptée au marché sénégalais avec monnaies locales et options de paiement.</p>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();

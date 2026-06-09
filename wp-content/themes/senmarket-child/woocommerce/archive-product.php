<?php
/**
 * Custom archive product template for SenMarket child theme.
 */

defined( 'ABSPATH' ) || exit;

get_header();

$total_products = wc_get_loop_prop( 'total' );
$categories     = get_terms( [
  'taxonomy'   => 'product_cat',
  'hide_empty' => true,
] );
?>
<main class="senmarket-container">
  <!-- Hero Section -->
  <section class="shop-hero" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); color: white; padding: 60px 20px; border-radius: var(--border-radius-lg); margin-bottom: 50px; text-align: center;">
    <h1 style="font-size: clamp(2rem, 5vw, 3rem); margin: 0 0 15px 0; font-weight: 800;">
      <?php esc_html_e( 'Notre Boutique', 'senmarket-child' ); ?>
    </h1>
    <p style="font-size: 1.05rem; margin: 0; opacity: 0.95;">
      <?php printf( esc_html( _n( '%d produit disponible', '%d produits disponibles', $total_products, 'senmarket-child' ) ), $total_products ); ?>
    </p>
  </section>

  <!-- Filters Section -->
  <section class="product-filter-section" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); padding: 30px; margin-bottom: 40px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
      <h2 style="margin: 0; font-size: 1.3rem;"><?php esc_html_e( 'Filtrer les produits', 'senmarket-child' ); ?></h2>
      <button type="button" class="filter-toggle" style="background: var(--color-primary); color: white; border: none; padding: 8px 16px; border-radius: var(--border-radius-md); cursor: pointer; font-weight: 600; display: none;">
        <i class="fas fa-filter" style="margin-right: 6px;"></i><?php esc_html_e( 'Filtres', 'senmarket-child' ); ?>
      </button>
    </div>

    <form class="filter-form" data-filter-form data-filter-target="#senmarket-filter-results" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
      <div>
        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-secondary);">
          <i class="fas fa-search" style="margin-right: 6px;"></i><?php esc_html_e( 'Rechercher', 'senmarket-child' ); ?>
        </label>
        <input type="search" name="search" placeholder="Produit, marque..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: var(--border-radius-md); background: var(--bg-secondary); color: var(--text-primary); outline: none; box-sizing: border-box;" />
      </div>

      <div>
        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-secondary);">
          <i class="fas fa-tag" style="margin-right: 6px;"></i><?php esc_html_e( 'Catégorie', 'senmarket-child' ); ?>
        </label>
        <select name="category" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: var(--border-radius-md); background: var(--bg-secondary); color: var(--text-primary); outline: none; cursor: pointer; box-sizing: border-box;">
          <option value=""><?php esc_html_e( 'Toutes les catégories', 'senmarket-child' ); ?></option>
          <?php
          foreach ( $categories as $category ) :
            printf(
              '<option value="%s">%s (%d)</option>',
              esc_attr( $category->slug ),
              esc_html( $category->name ),
              intval( $category->count )
            );
          endforeach;
          ?>
        </select>
      </div>

      <div>
        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-secondary);">
          <i class="fas fa-money-bill" style="margin-right: 6px;"></i><?php esc_html_e( 'Prix min', 'senmarket-child' ); ?>
        </label>
        <input type="number" step="1" name="min_price" placeholder="0" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: var(--border-radius-md); background: var(--bg-secondary); color: var(--text-primary); outline: none; box-sizing: border-box;" />
      </div>

      <div>
        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-secondary);">
          <i class="fas fa-money-bill-wave" style="margin-right: 6px;"></i><?php esc_html_e( 'Prix max', 'senmarket-child' ); ?>
        </label>
        <input type="number" step="1" name="max_price" placeholder="100000" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: var(--border-radius-md); background: var(--bg-secondary); color: var(--text-primary); outline: none; box-sizing: border-box;" />
      </div>

      <div style="display: flex; align-items: flex-end; gap: 10px;">
        <button type="submit" class="btn btn-primary" style="flex: 1; padding: 10px 20px; font-weight: 600; cursor: pointer; border: none; border-radius: var(--border-radius-md);">
          <i class="fas fa-check" style="margin-right: 6px;"></i><?php esc_html_e( 'Appliquer', 'senmarket-child' ); ?>
        </button>
        <button type="reset" class="btn" style="padding: 10px 20px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: var(--border-radius-md); cursor: pointer; color: var(--text-primary); font-weight: 600;">
          <i class="fas fa-redo" style="margin-right: 6px;"></i><?php esc_html_e( 'Réinitialiser', 'senmarket-child' ); ?>
        </button>
      </div>
    </form>
  </section>

  <!-- Results Section -->
  <div id="senmarket-filter-results">
    <?php if ( woocommerce_product_loop() ) : ?>
      <?php do_action( 'woocommerce_before_shop_loop' ); ?>

      <!-- Products Count -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding: 15px; background: var(--bg-secondary); border-radius: var(--border-radius-md);">
        <div style="color: var(--text-secondary);">
          <?php printf( esc_html( _n( '%d produit trouvé', '%d produits trouvés', $total_products, 'senmarket-child' ) ), $total_products ); ?>
        </div>
        <div style="display: flex; gap: 10px;">
          <button class="view-toggle" data-view="grid" style="background: var(--color-primary); color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: 600;">
            <i class="fas fa-th"></i>
          </button>
          <button class="view-toggle" data-view="list" style="background: var(--border-color); color: var(--text-primary); border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: 600;">
            <i class="fas fa-list"></i>
          </button>
        </div>
      </div>

      <?php woocommerce_product_loop_start(); ?>

        <?php if ( wc_get_loop_prop( 'total' ) ) : ?>
          <?php while ( have_posts() ) : the_post(); ?>
            <?php wc_get_template_part( 'content', 'product' ); ?>
          <?php endwhile; ?>
        <?php endif; ?>

      <?php woocommerce_product_loop_end(); ?>
      <?php do_action( 'woocommerce_after_shop_loop' ); ?>
    <?php else : ?>
      <div style="text-align: center; padding: 60px 20px; background: var(--bg-secondary); border-radius: var(--border-radius-lg);">
        <i class="fas fa-inbox" style="font-size: 60px; color: var(--text-muted); margin-bottom: 20px; display: block;"></i>
        <h3 style="margin-top: 0;"><?php esc_html_e( 'Aucun produit trouvé', 'senmarket-child' ); ?></h3>
        <p style="color: var(--text-secondary);">
          <?php esc_html_e( 'Essayez de modifier vos critères de filtrage.', 'senmarket-child' ); ?>
        </p>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary" style="margin-top: 20px; display: inline-block; padding: 10px 30px; text-decoration: none; border-radius: var(--border-radius-md); font-weight: 600;">
          <?php esc_html_e( 'Retour à la boutique', 'senmarket-child' ); ?>
        </a>
      </div>
      <?php do_action( 'woocommerce_no_products_found' ); ?>
    <?php endif; ?>
  </div>
</main>

<style>
  @media (max-width: 768px) {
    .filter-form {
      grid-template-columns: 1fr !important;
    }

    .filter-toggle {
      display: inline-block !important;
    }

    .shop-hero {
      padding: 40px 15px !important;
    }

    [style*="grid-template-columns: repeat"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>

<?php get_footer();

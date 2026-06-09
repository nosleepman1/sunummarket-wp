<?php
/**
 * Product card template for SenMarket child theme.
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

$author_id   = get_post_field( 'post_author', $product->get_id() );
$vendor_name = get_the_author_meta( 'display_name', $author_id );
$vendor_name = $vendor_name ? $vendor_name : esc_html__( 'Vendeur SenMarket', 'senmarket-child' );
$product_label = $product->is_on_sale() ? esc_html__( 'Promo', 'senmarket-child' ) : ( $product->is_featured() ? esc_html__( 'Vedette', 'senmarket-child' ) : '' );
?>

<li <?php wc_product_class( 'product-card', $product ); ?>>
    <?php if ( $product_label ) : ?>
        <span class="product-badge <?php echo esc_attr( strtolower( $product_label ) ); ?>"><?php echo esc_html( $product_label ); ?></span>
    <?php endif; ?>

    <div class="card-img-wrap">
        <a href="<?php the_permalink(); ?>" class="product-thumbnail-link">
            <?php echo $product->get_image( 'medium' ); ?>
        </a>
        <div class="card-image-overlays">
            <button type="button" class="wishlist-overlay-btn" aria-label="Ajouter aux favoris" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                <i class="far fa-heart"></i>
            </button>
            <button type="button" class="quickview-overlay-btn" data-modal-open="modal-quickview" data-product="<?php echo esc_attr( wp_json_encode( [
                'name'        => $product->get_name(),
                'price'       => strip_tags( $product->get_price_html() ),
                'description' => wp_strip_all_tags( $product->get_short_description() ?: $product->get_description() ),
                'image'       => wp_get_attachment_image_url( $product->get_image_id(), 'medium' ),
                'url'         => get_permalink(),
            ] ) ); ?>" aria-label="Aperçu rapide">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <span class="vendor-name"><?php printf( esc_html__( 'Par %s', 'senmarket-child' ), esc_html( $vendor_name ) ); ?></span>
        <a class="product-title" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
        
        <div class="product-rating">
            <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
            <span class="reviews-count">(<?php echo absint( $product->get_review_count() ); ?>)</span>
        </div>

        <div class="product-meta">
            <span class="product-price"><?php echo $product->get_price_html(); ?></span>
        </div>

        <div class="card-footer-action">
            <?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
                <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn btn-cart-bottom" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
                    <i class="fas fa-shopping-basket" style="margin-right: 6px;"></i> <?php echo esc_html( $product->add_to_cart_text() ); ?>
                </a>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>" class="btn btn-view-bottom">
                    <i class="fas fa-eye" style="margin-right: 6px;"></i> <?php esc_html_e( 'Voir le produit', 'senmarket-child' ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</li>

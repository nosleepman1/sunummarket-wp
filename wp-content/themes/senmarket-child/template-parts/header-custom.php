<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$shop_url      = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$account_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
$cart_url      = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
$home_url      = home_url( '/' );
$current_user = wp_get_current_user();
$is_logged_in = is_user_logged_in();
?>
<header class="site-header">
    <div class="senmarket-container header-inner">
        <!-- Logo -->
        <a class="site-logo" href="<?php echo esc_url( $home_url ); ?>">
            <span class="logo-accent">Sen</span>Market
        </a>

        <!-- Desktop Navigation -->
        <nav class="nav-desktop">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-link">Accueil</a>
            <a href="<?php echo esc_url( $shop_url ); ?>" class="nav-link">Boutique</a>
            <a href="<?php echo esc_url( home_url( '/#promotions' ) ); ?>" class="nav-link">Promotions</a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="nav-link">Contact</a>
        </nav>

        <!-- Action Buttons (Desktop) -->
        <div class="action-buttons-desktop">
            <a href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>" class="icon-btn" title="Wishlist">
                <i class="fas fa-heart"></i>
            </a>
            <a href="<?php echo esc_url( $cart_url ); ?>" class="icon-btn cart-btn" title="Panier">
                <i class="fas fa-shopping-basket"></i>
                <?php if ( function_exists( 'WC' ) && WC()->cart ) : ?>
                    <span class="cart-badge"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                <?php endif; ?>
            </a>
            <button class="icon-btn theme-btn" data-toggle-theme data-theme-icon aria-label="Thème"></button>
            <?php if ( $is_logged_in ) : 
                $user_name = $current_user->display_name;
                $is_seller = in_array( 'seller', (array) $current_user->roles ) || in_array( 'administrator', (array) $current_user->roles );
                $dashboard_url = function_exists( 'dokan_get_navigation_url' ) ? dokan_get_navigation_url() : home_url( '/dashboard/' );
            ?>
                <div class="user-dropdown">
                    <button class="btn btn-secondary user-dropdown-toggle">
                        <i class="fas fa-user-circle" style="margin-right: 6px;"></i> 
                        <span class="user-display-name"><?php echo esc_html( $user_name ); ?></span> 
                        <i class="fas fa-chevron-down" style="font-size: 0.75rem; margin-left: 6px;"></i>
                    </button>
                    <div class="user-dropdown-menu">
                        <?php if ( $is_seller ) : ?>
                            <a href="<?php echo esc_url( $dashboard_url ); ?>"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( $account_url ); ?>"><i class="fas fa-user-cog"></i> Mon compte</a>
                        <a href="<?php echo esc_url( wp_logout_url( $home_url ) ); ?>" class="dropdown-logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                    </div>
                </div>
            <?php else : ?>
                <button class="btn btn-primary" data-modal-open="modal-auth"><i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i> Connexion</button>
            <?php endif; ?>
        </div>

       
    </div>

    <!-- Mobile Side Drawer Navigation -->
    <div class="mobile-drawer-overlay" data-modal-close></div>
    <nav class="nav-mobile-drawer">
        <div class="drawer-header">
            <span class="drawer-logo"><span class="logo-accent">Sen</span>Market</span>
            <button class="drawer-close-btn" aria-label="Fermer le menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="drawer-body">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="drawer-link"><i class="fas fa-home"></i> Accueil</a>
            <a href="<?php echo esc_url( $shop_url ); ?>" class="drawer-link"><i class="fas fa-store"></i> Boutique</a>
            <a href="<?php echo esc_url( home_url( '/#promotions' ) ); ?>" class="drawer-link"><i class="fas fa-tags"></i> Promotions</a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="drawer-link"><i class="fas fa-envelope"></i> Contact</a>
            <a href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>" class="drawer-link"><i class="fas fa-heart"></i> Wishlist</a>
            <div class="drawer-divider"></div>
            <?php if ( $is_logged_in ) : 
                $is_seller = in_array( 'seller', (array) $current_user->roles ) || in_array( 'administrator', (array) $current_user->roles );
                $dashboard_url = function_exists( 'dokan_get_navigation_url' ) ? dokan_get_navigation_url() : home_url( '/dashboard/' );
            ?>
                <?php if ( $is_seller ) : ?>
                    <a href="<?php echo esc_url( $dashboard_url ); ?>" class="drawer-link"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
                <?php endif; ?>
                <a href="<?php echo esc_url( $account_url ); ?>" class="drawer-link"><i class="fas fa-user-cog"></i> Mon compte</a>
                <a href="<?php echo esc_url( wp_logout_url( $home_url ) ); ?>" class="drawer-link drawer-logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            <?php else : ?>
                <button class="btn btn-primary" data-modal-open="modal-auth" style="width: 100%; margin-top: 15px;"><i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i> Connexion / Inscription</button>
            <?php endif; ?>
        </div>
    </nav>
</header>

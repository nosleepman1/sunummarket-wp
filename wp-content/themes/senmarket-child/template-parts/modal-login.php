<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$lost_password_url = function_exists( 'wc_lostpassword_url' ) ? wc_lostpassword_url() : wp_lostpassword_url();
?>
<div
  id="modal-auth"
  class="modal-overlay modal-auth"
  role="dialog"
  aria-modal="true"
  aria-label="Connexion / Inscription"
>
  <div class="modal-box">
    <button class="modal-close" data-modal-close aria-label="Fermer">
      <i class="fas fa-times" aria-hidden="true"></i>
    </button>

    <div class="tab-switcher" role="tablist">
      <button class="tab-btn active" data-tab="login" role="tab">
        <i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i> Connexion
      </button>
      <button class="tab-btn" data-tab="register" role="tab">
        <i class="fas fa-user-plus" style="margin-right: 6px;"></i> Inscription
      </button>
    </div>

    <!-- Login Panel -->
    <div id="tab-login" class="tab-pane">
      <h2 class="modal-title">Bon retour</h2>
      <p class="modal-subtitle">Connectez-vous pour accéder à votre espace.</p>
      
      <form id="senmarket-login-form" class="auth-form" method="post">
        <?php wp_nonce_field( 'senmarket_login_nonce', 'security' ); ?>
        
        <div class="form-group">
          <label for="login_user"><i class="fas fa-envelope"></i> E-mail ou Identifiant</label>
          <input type="text" id="login_user" name="username" required placeholder="Ex: moussa@email.sn" />
        </div>
        
        <div class="form-group">
          <label for="login_pass"><i class="fas fa-lock"></i> Mot de passe</label>
          <input type="password" id="login_pass" name="password" required placeholder="••••••••" />
        </div>
        
        <div class="form-meta">
          <label class="remember-me">
            <input type="checkbox" name="rememberme" value="forever" checked />
            <span>Se souvenir de moi</span>
          </label>
          <a href="<?php echo esc_url( $lost_password_url ); ?>" class="lost-pass-link">Mot de passe oublié ?</a>
        </div>
        
        <button type="submit" class="btn btn-primary btn-submit">
          <span class="btn-text">Se connecter</span>
          <span class="btn-spinner" hidden><i class="fas fa-circle-notch fa-spin"></i></span>
        </button>
      </form>
    </div>

    <!-- Register Panel -->
    <div id="tab-register" class="tab-pane" hidden>
      <h2 class="modal-title">Rejoindre SenMarket</h2>
      <p class="modal-subtitle">Sélectionnez votre profil pour commencer.</p>

      <div class="role-selector" role="tablist">
        <label class="role-card">
          <input type="radio" name="register_role" value="customer" checked />
          <div class="role-card-content">
            <i class="fas fa-shopping-bag"></i>
            <span>Je veux acheter</span>
          </div>
        </label>
        <label class="role-card">
          <input type="radio" name="register_role" value="seller" />
          <div class="role-card-content">
            <i class="fas fa-store"></i>
            <span>Je veux vendre</span>
          </div>
        </label>
      </div>

      <!-- Customer Form -->
      <form id="senmarket-register-customer-form" class="auth-form register-panel" data-role="customer">
        <?php wp_nonce_field( 'senmarket_register_nonce', 'security' ); ?>
        <input type="hidden" name="role" value="customer" />
        
        <div class="form-row-double">
          <div class="form-group">
            <label for="reg_c_firstname">Prénom</label>
            <input type="text" id="reg_c_firstname" name="first_name" required placeholder="Moussa" />
          </div>
          <div class="form-group">
            <label for="reg_c_lastname">Nom</label>
            <input type="text" id="reg_c_lastname" name="last_name" required placeholder="Diop" />
          </div>
        </div>

        <div class="form-group">
          <label for="reg_c_phone"><i class="fas fa-phone-alt"></i> Téléphone</label>
          <input type="tel" id="reg_c_phone" name="phone" required placeholder="Ex: 771234567" />
        </div>

        <div class="form-group">
          <label for="reg_c_email"><i class="fas fa-envelope"></i> E-mail</label>
          <input type="email" id="reg_c_email" name="email" required placeholder="moussa@email.sn" />
        </div>

        <div class="form-group">
          <label for="reg_c_pass"><i class="fas fa-lock"></i> Mot de passe</label>
          <input type="password" id="reg_c_pass" name="password" required placeholder="••••••••" />
        </div>

        <button type="submit" class="btn btn-primary btn-submit">
          <span class="btn-text">Créer mon compte</span>
          <span class="btn-spinner" hidden><i class="fas fa-circle-notch fa-spin"></i></span>
        </button>
      </form>

      <!-- Seller Form -->
      <form id="senmarket-register-seller-form" class="auth-form register-panel" data-role="seller" hidden>
        <?php wp_nonce_field( 'senmarket_register_nonce', 'security' ); ?>
        <input type="hidden" name="role" value="seller" />
        
        <div class="form-row-double">
          <div class="form-group">
            <label for="reg_s_firstname">Prénom</label>
            <input type="text" id="reg_s_firstname" name="first_name" required placeholder="Fatou" />
          </div>
          <div class="form-group">
            <label for="reg_s_lastname">Nom</label>
            <input type="text" id="reg_s_lastname" name="last_name" required placeholder="Ndiaye" />
          </div>
        </div>

        <div class="form-group">
          <label for="reg_s_phone"><i class="fas fa-phone-alt"></i> Téléphone</label>
          <input type="tel" id="reg_s_phone" name="phone" required placeholder="Ex: 771234567" />
        </div>

        <div class="form-group">
          <label for="reg_s_email"><i class="fas fa-envelope"></i> E-mail</label>
          <input type="email" id="reg_s_email" name="email" required placeholder="fatou@boutique.sn" />
        </div>

        <div class="form-group">
          <label for="reg_s_store"><i class="fas fa-store"></i> Nom de la Boutique</label>
          <input type="text" id="reg_s_store" name="store_name" required placeholder="Teranga Concept" />
        </div>

        <div class="form-group">
          <label for="reg_s_pass"><i class="fas fa-lock"></i> Mot de passe</label>
          <input type="password" id="reg_s_pass" name="password" required placeholder="••••••••" />
        </div>

        <button type="submit" class="btn btn-primary btn-submit">
          <span class="btn-text">Ouvrir ma boutique</span>
          <span class="btn-spinner" hidden><i class="fas fa-circle-notch fa-spin"></i></span>
        </button>
      </form>
    </div>
  </div>
</div>

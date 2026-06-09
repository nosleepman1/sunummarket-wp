<?php
/**
 * Template Name: Contact Page Custom
 * Slug: contact
 */

defined( 'ABSPATH' ) || exit;

// Handle form submission
$message_sent = false;
$error_message = '';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['senmarket_contact_nonce'] ) ) {
  if ( wp_verify_nonce( $_POST['senmarket_contact_nonce'], 'senmarket_contact_form' ) ) {
    $name    = sanitize_text_field( $_POST['contact_name'] ?? '' );
    $email   = sanitize_email( $_POST['contact_email'] ?? '' );
    $subject = sanitize_text_field( $_POST['contact_subject'] ?? '' );
    $message = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

    if ( ! empty( $name ) && ! empty( $email ) && ! empty( $subject ) && ! empty( $message ) ) {
      $to      = get_option( 'admin_email' );
      $headers = array( 'Content-Type: text/html; charset=UTF-8' );
      $headers[] = 'From: ' . $name . ' <' . $email . '>';

      $email_subject = '[SenMarket] ' . $subject;
      $email_body    = '<h3>' . $subject . '</h3><p><strong>De:</strong> ' . $name . ' (' . $email . ')</p><p>' . nl2br( $message ) . '</p>';

      if ( wp_mail( $to, $email_subject, $email_body, $headers ) ) {
        $message_sent = true;
      } else {
        $error_message = 'Une erreur est survenue. Veuillez réessayer.';
      }
    } else {
      $error_message = 'Tous les champs sont obligatoires.';
    }
  }
}

get_header();
?>
<main class="senmarket-container page-content">
  <!-- Hero Section -->
  <section class="page-hero" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); color: white; padding: 80px 20px; text-align: center; border-radius: var(--border-radius-lg); margin-bottom: 60px;">
    <h1 class="hero-title" style="font-size: clamp(2rem, 5vw, 3.5rem); margin: 0 0 20px 0; font-weight: 800;">
      <?php esc_html_e( 'Contactez-nous', 'senmarket-child' ); ?>
    </h1>
    <p style="font-size: 1.1rem; margin: 0; opacity: 0.95; max-width: 600px; margin: 0 auto;">
      <?php esc_html_e( 'Nous répondons à vos questions en moins de 24h. Votre satisfaction est notre priorité.', 'senmarket-child' ); ?>
    </p>
  </section>

  <div style="display: grid; grid-template-columns: 1fr; gap: 40px; margin-bottom: 60px; max-width: 1200px; margin-left: auto; margin-right: auto;">
    <!-- Contact Info Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
      <div class="info-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); padding: 30px; text-align: center; transition: transform 0.3s, box-shadow 0.3s;">
        <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--color-primary); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; margin: 0 auto 20px;">
          <i class="fas fa-phone"></i>
        </div>
        <h3 style="margin: 0 0 10px 0; font-size: 1.2rem;"><?php esc_html_e( 'Téléphone', 'senmarket-child' ); ?></h3>
        <p style="color: var(--text-secondary); margin: 0;">+221 77 375 70 77</p>
      </div>

      <div class="info-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); padding: 30px; text-align: center; transition: transform 0.3s, box-shadow 0.3s;">
        <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--color-secondary); display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; margin: 0 auto 20px;">
          <i class="fas fa-envelope"></i>
        </div>
        <h3 style="margin: 0 0 10px 0; font-size: 1.2rem;"><?php esc_html_e( 'Email', 'senmarket-child' ); ?></h3>
        <p style="color: var(--text-secondary); margin: 0;">abdallahdiouf.dev@gmail.com</p>
      </div>

      <div class="info-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); padding: 30px; text-align: center; transition: transform 0.3s, box-shadow 0.3s;">
        <div style="width: 60px; height: 60px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; margin: 0 auto 20px;">
          <i class="fab fa-whatsapp"></i>
        </div>
        <h3 style="margin: 0 0 10px 0; font-size: 1.2rem;"><?php esc_html_e( 'WhatsApp', 'senmarket-child' ); ?></h3>
        <a href="https://wa.me/221773757077" target="_blank" rel="noopener noreferrer" style="color: var(--color-primary); text-decoration: none; font-weight: 600;">
          <?php esc_html_e( 'Discuter maintenant', 'senmarket-child' ); ?>
        </a>
      </div>
    </div>
  </div>

  <!-- Contact Form Section -->
  <div style="max-width: 700px; margin: 0 auto;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); padding: 40px; box-shadow: var(--shadow-md);">
      <h2 style="margin-top: 0; margin-bottom: 30px; font-size: 1.8rem;"><?php esc_html_e( 'Envoyer un message', 'senmarket-child' ); ?></h2>

      <?php if ( $message_sent ) : ?>
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px 20px; border-radius: var(--border-radius-md); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
          <i class="fas fa-check-circle" style="font-size: 20px;"></i>
          <div>
            <strong><?php esc_html_e( 'Succès!', 'senmarket-child' ); ?></strong>
            <p style="margin: 5px 0 0 0;"><?php esc_html_e( 'Votre message a été envoyé. Nous vous répondrons bientôt.', 'senmarket-child' ); ?></p>
          </div>
        </div>
      <?php elseif ( ! empty( $error_message ) ) : ?>
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px 20px; border-radius: var(--border-radius-md); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
          <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
          <div>
            <strong><?php esc_html_e( 'Erreur', 'senmarket-child' ); ?></strong>
            <p style="margin: 5px 0 0 0;"><?php echo esc_html( $error_message ); ?></p>
          </div>
        </div>
      <?php endif; ?>

      <form class="senmarket-contact-form" method="post" style="display: flex; flex-direction: column; gap: 20px;">
        <?php wp_nonce_field( 'senmarket_contact_form', 'senmarket_contact_nonce' ); ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          <div>
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-secondary);"><?php esc_html_e( 'Votre Nom', 'senmarket-child' ); ?> <span style="color: var(--color-error);">*</span></label>
            <input type="text" name="contact_name" required placeholder="Jean Dupont" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: var(--border-radius-md); background: var(--bg-secondary); color: var(--text-primary); font-size: 1rem; outline: none; box-sizing: border-box;" />
          </div>
          <div>
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-secondary);"><?php esc_html_e( 'Votre Email', 'senmarket-child' ); ?> <span style="color: var(--color-error);">*</span></label>
            <input type="email" name="contact_email" required placeholder="jean@example.com" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: var(--border-radius-md); background: var(--bg-secondary); color: var(--text-primary); font-size: 1rem; outline: none; box-sizing: border-box;" />
          </div>
        </div>

        <div>
          <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-secondary);"><?php esc_html_e( 'Sujet', 'senmarket-child' ); ?> <span style="color: var(--color-error);">*</span></label>
          <input type="text" name="contact_subject" required placeholder="Comment pouvons-nous vous aider?" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: var(--border-radius-md); background: var(--bg-secondary); color: var(--text-primary); font-size: 1rem; outline: none; box-sizing: border-box;" />
        </div>

        <div>
          <label style="display: block; font-weight: 600; margin-bottom: 8px; color: var(--text-secondary);"><?php esc_html_e( 'Message', 'senmarket-child' ); ?> <span style="color: var(--color-error);">*</span></label>
          <textarea name="contact_message" rows="6" required placeholder="Décrivez votre message..." style="width: 100%; padding: 12px 16px; border: 1px solid var(--border-color); border-radius: var(--border-radius-md); background: var(--bg-secondary); color: var(--text-primary); font-size: 1rem; outline: none; resize: vertical; box-sizing: border-box;"></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="align-self: flex-start; min-height: 48px; padding: 0 40px; font-weight: 600; cursor: pointer;">
          <i class="fas fa-paper-plane" style="margin-right: 8px;"></i><?php esc_html_e( 'Envoyer', 'senmarket-child' ); ?>
        </button>
      </form>
    </div>
  </div>

  <!-- Additional Info -->
  <section style="margin-top: 80px; padding: 60px 20px; background: var(--bg-secondary); border-radius: var(--border-radius-lg);">
    <div style="max-width: 600px; margin: 0 auto; text-align: center;">
      <h2 style="margin-top: 0; margin-bottom: 20px;"><?php esc_html_e( 'Informations Utiles', 'senmarket-child' ); ?></h2>
      <p style="color: var(--text-secondary); line-height: 1.8;">
        <?php esc_html_e( 'SenMarket est une plateforme e-commerce innovative qui connecte vendeurs et acheteurs au Sénégal. Pour toute question sur nos services, nos conditions d\'utilisation, ou pour signaler un problème, n\'hésitez pas à nous contacter. Notre équipe multilingue est disponible du lundi au dimanche.', 'senmarket-child' ); ?>
      </p>
    </div>
  </section>
</main>

<style>
  .info-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
  }

  @media (max-width: 768px) {
    .page-hero {
      padding: 50px 20px !important;
    }

    [style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>

<?php
get_footer();

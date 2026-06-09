<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div
  id="modal-quickview"
  class="modal-overlay modal-quickview"
  role="dialog"
  aria-modal="true"
  aria-label="Aperçu produit"
>
  <div class="modal-box">
    <button class="modal-close" data-modal-close aria-label="Fermer">
      <i class="fas fa-times" aria-hidden="true"></i>
    </button>

    <img class="modal-img" data-modal-img src="" alt="" />
    <div class="modal-product-info">
      <h3 data-modal-name></h3>
      <p class="modal-price" data-modal-price></p>
      <p data-modal-desc></p>
      <a data-modal-link href="#" class="btn-primary">Voir le produit complet</a>
      <button class="btn-cart" type="button" onclick="addToCartQuick()">
        Ajouter au panier
      </button>
    </div>
  </div>
</div>

/**
 * SenMarket — Modal Manager
 * Gère : Login · Inscription · Quick View produit · Confirmation
 * Accessibilité : focus trap + fermeture Escape + aria
 */

const ModalManager = (() => {
  let activeModal = null;
  let lastFocused = null;

  const open = (modalId, data = {}) => {
    const modal = document.getElementById( modalId );
    if ( ! modal ) {
      return console.warn( `Modal #${ modalId } introuvable` );
    }

    lastFocused = document.activeElement;
    activeModal = modal;

    if ( data.product ) {
      injectProductData( modal, data.product );
    }

    modal.classList.add( 'is-open' );
    modal.setAttribute( 'aria-hidden', 'false' );
    document.body.classList.add( 'modal-open' );

    const focusable = modal.querySelector(
      'button, input, select, textarea, a[href], [tabindex]:not([tabindex="-1"])',
    );
    if ( focusable ) {
      setTimeout( () => focusable.focus(), 100 );
    }

    requestAnimationFrame( () => modal.classList.add( 'is-visible' ) );
    document.dispatchEvent(
      new CustomEvent( 'senmarket:modalopen', {
        detail: { modalId, data },
      } ),
    );
  };

  const close = (modalId) => {
    const modal = modalId ? document.getElementById( modalId ) : activeModal;
    if ( ! modal ) {
      return;
    }

    modal.classList.remove( 'is-visible' );
    modal.setAttribute( 'aria-hidden', 'true' );

    modal.addEventListener(
      'transitionend',
      () => {
        modal.classList.remove( 'is-open' );
        document.body.classList.remove( 'modal-open' );
        activeModal = null;
        if ( lastFocused ) {
          lastFocused.focus();
        }
      },
      { once: true },
    );
  };

  const injectProductData = (modal, product) => {
    const img = modal.querySelector( '[data-modal-img]' );
    const name = modal.querySelector( '[data-modal-name]' );
    const price = modal.querySelector( '[data-modal-price]' );
    const desc = modal.querySelector( '[data-modal-desc]' );
    const link = modal.querySelector( '[data-modal-link]' );

    if ( img ) {
      img.src = product.image || '';
      img.alt = product.name || '';
    }
    if ( name ) {
      name.textContent = product.name || '';
    }
    if ( price ) {
      price.textContent = product.price ? product.price + ' XOF' : '';
    }
    if ( desc ) {
      desc.innerHTML = product.description || '';
    }
    if ( link ) {
      link.href = product.url || '#';
    }
  };

  const trapFocus = (e) => {
    if ( ! activeModal ) {
      return;
    }
    const focusables = activeModal.querySelectorAll(
      'button, input, select, textarea, a[href], [tabindex]:not([tabindex="-1"])',
    );
    const first = focusables[0];
    const last = focusables[focusables.length - 1];

    if ( e.key === 'Tab' ) {
      if ( e.shiftKey && document.activeElement === first ) {
        e.preventDefault();
        last.focus();
      } else if ( ! e.shiftKey && document.activeElement === last ) {
        e.preventDefault();
        first.focus();
      }
    }
    if ( e.key === 'Escape' ) {
      close();
    }
  };

  const handleAuthSubmissions = () => {
    const loginForm = document.getElementById('senmarket-login-form');
    const customerForm = document.getElementById('senmarket-register-customer-form');
    const sellerForm = document.getElementById('senmarket-register-seller-form');

    const handleFormSubmit = (form, actionName) => {
      form.addEventListener('submit', (e) => {
        e.preventDefault();

        const submitBtn = form.querySelector('.btn-submit');
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = submitBtn.querySelector('.btn-spinner');

        // Show loading state
        submitBtn.disabled = true;
        if (btnText) btnText.style.display = 'none';
        if (spinner) spinner.removeAttribute('hidden');

        const formData = new FormData(form);
        formData.append('action', actionName);

        fetch(SenMarketConfig.ajaxUrl, {
          method: 'POST',
          body: formData,
          credentials: 'same-origin',
        })
          .then((response) => response.json())
          .then((data) => {
            const toast = window.SenMarket?.Toast || { show: (msg, type) => alert(msg) };
            if (data.success) {
              toast.show(data.data.message || 'Succès !', 'success');
              setTimeout(() => {
                window.location.reload();
              }, 1200);
            } else {
              toast.show(data.data.message || 'Une erreur est survenue.', 'error');
              // Reset button
              submitBtn.disabled = false;
              if (btnText) btnText.style.display = 'inline';
              if (spinner) spinner.setAttribute('hidden', 'true');
            }
          })
          .catch((err) => {
            console.error(err);
            const toast = window.SenMarket?.Toast || { show: (msg, type) => alert(msg) };
            toast.show('Erreur de communication avec le serveur.', 'error');
            submitBtn.disabled = false;
            if (btnText) btnText.style.display = 'inline';
            if (spinner) spinner.setAttribute('hidden', 'true');
          });
      });
    };

    if (loginForm) {
      handleFormSubmit(loginForm, 'senmarket_ajax_login');
    }
    if (customerForm) {
      handleFormSubmit(customerForm, 'senmarket_ajax_register');
    }
    if (sellerForm) {
      handleFormSubmit(sellerForm, 'senmarket_ajax_register');
    }
  };

  const init = () => {
    document.addEventListener( 'click', ( e ) => {
      const trigger = e.target.closest( '[data-modal-open]' );
      if ( trigger ) {
        e.preventDefault();
        const id = trigger.dataset.modalOpen;
        const product = trigger.dataset.product ? JSON.parse( trigger.dataset.product ) : {};
        open( id, { product } );
      }

      if ( e.target.closest( '[data-modal-close]' ) || e.target.classList.contains( 'modal-overlay' ) ) {
        close();
      }
    } );

    document.addEventListener( 'keydown', trapFocus );
    handleAuthSubmissions();
  };

  return { init, open, close };
})();

document.addEventListener( 'DOMContentLoaded', ModalManager.init );

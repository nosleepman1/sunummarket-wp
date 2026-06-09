/**
 * SenMarket — Main JS
 * Init globale, navigation mobile, onglets d'authentification et compte à rebours.
 */

const SenMarketMain = (() => {
  const toggleMobileMenu = () => {
    const burger = document.querySelector( '.mobile-menu-btn' );
    const closeBtn = document.querySelector( '.drawer-close-btn' );
    const overlay = document.querySelector( '.mobile-drawer-overlay' );
    const drawer = document.querySelector( '.nav-mobile-drawer' );

    if ( ! burger || ! drawer ) {
      return;
    }

    const openDrawer = () => {
      drawer.classList.add( 'is-open' );
      if ( overlay ) overlay.classList.add( 'is-visible' );
      document.body.classList.add( 'drawer-open' );
    };

    const closeDrawer = () => {
      drawer.classList.remove( 'is-open' );
      if ( overlay ) overlay.classList.remove( 'is-visible' );
      document.body.classList.remove( 'drawer-open' );
    };

    burger.addEventListener( 'click', (e) => {
      e.preventDefault();
      openDrawer();
    } );

    if ( closeBtn ) {
      closeBtn.addEventListener( 'click', closeDrawer );
    }

    if ( overlay ) {
      overlay.addEventListener( 'click', closeDrawer );
    }

    document.addEventListener( 'click', ( e ) => {
      if ( drawer.classList.contains( 'is-open' ) && ! e.target.closest( '.nav-mobile-drawer' ) && ! e.target.closest( '.mobile-menu-btn' ) ) {
        closeDrawer();
      }
    } );

    document.addEventListener( 'keydown', (e) => {
      if ( e.key === 'Escape' && drawer.classList.contains( 'is-open' ) ) {
        closeDrawer();
      }
    } );
  };

  const initUserDropdown = () => {
    const toggle = document.querySelector( '.user-dropdown-toggle' );
    const dropdown = document.querySelector( '.user-dropdown' );

    if ( ! toggle || ! dropdown ) {
      return;
    }

    toggle.addEventListener( 'click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      dropdown.classList.toggle( 'is-open' );
    } );

    document.addEventListener( 'click', (e) => {
      if ( ! e.target.closest( '.user-dropdown' ) ) {
        dropdown.classList.remove( 'is-open' );
      }
    } );
  };

  const initAuthTabs = () => {
    const tabs = document.querySelectorAll( '.tab-btn' );

    if ( tabs.length === 0 ) {
      return;
    }

    tabs.forEach( ( tab ) => {
      tab.addEventListener( 'click', ( e ) => {
        const target = e.currentTarget.dataset.tab;
        const parent = e.currentTarget.closest( '.tab-switcher' );
        if ( ! target || ! parent ) {
          return;
        }

        parent.querySelectorAll( '.tab-btn' ).forEach( ( button ) => button.classList.remove( 'active' ) );
        e.currentTarget.classList.add( 'active' );

        document.querySelectorAll( '.tab-pane' ).forEach( ( pane ) => {
          pane.hidden = pane.id !== `tab-${ target }`;
        } );
      } );
    } );

    const roleInputs = document.querySelectorAll( '.role-selector input[name="register_role"]' );
    if ( roleInputs.length ) {
      const switchRole = ( value ) => {
        document.querySelectorAll( '.register-panel' ).forEach( ( panel ) => {
          panel.hidden = panel.dataset.role !== value;
        } );
      };

      roleInputs.forEach( ( input ) => {
        input.addEventListener( 'change', ( e ) => {
          if ( e.target.checked ) {
            switchRole( e.target.value );
          }
        } );
      } );

      switchRole( document.querySelector( '.role-selector input[name="register_role"]:checked' )?.value || 'customer' );
    }
  };

  const initPromoCountdown = () => {
    const countdowns = document.querySelectorAll( '[data-countdown]' );
    countdowns.forEach( ( node ) => {
      const targetDate = new Date( node.dataset.countdown );
      if ( Number.isNaN( targetDate.getTime() ) ) {
        return;
      }

      const updateCountdown = () => {
        const now = new Date();
        const diff = targetDate.getTime() - now.getTime();
        if ( diff <= 0 ) {
          node.textContent = 'Offre terminée';
          return;
        }

        const hours = String( Math.floor( diff / ( 1000 * 60 * 60 ) ) ).padStart( 2, '0' );
        const minutes = String( Math.floor( ( diff / ( 1000 * 60 ) ) % 60 ) ).padStart( 2, '0' );
        const seconds = String( Math.floor( ( diff / 1000 ) % 60 ) ).padStart( 2, '0' );
        node.textContent = `${ hours }:${ minutes }:${ seconds }`;
      };

      updateCountdown();
      setInterval( updateCountdown, 1000 );
    } );
  };

  const init = () => {
    toggleMobileMenu();
    initUserDropdown();
    initAuthTabs();
    initPromoCountdown();
  };

  return { init };
})();

document.addEventListener( 'DOMContentLoaded', SenMarketMain.init );

window.addToCartQuick = () => {
  if ( window.SenMarket?.Toast ) {
    window.SenMarket.Toast.show( 'Ajout au panier depuis la fiche produit.', 'info' );
  }
};

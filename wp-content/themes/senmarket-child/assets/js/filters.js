/**
 * SenMarket — Filters Manager
 * Gestion simple des filtres produits et recherche.
 */

const ProductFilters = (() => {
  const init = () => {
    document.querySelectorAll( '[data-filter-form]' ).forEach( ( form ) => {
      form.addEventListener( 'submit', ( e ) => {
        e.preventDefault();
        applyFilters( form );
      } );
    } );
  };

  const applyFilters = ( form ) => {
    const data = new FormData( form );
    data.append( 'action', 'senmarket_filter_products' );
    data.append( 'nonce', SenMarketConfig.nonce );

    fetch( SenMarketConfig.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: data,
    } )
      .then( ( response ) => response.text() )
      .then( ( html ) => {
        const target = document.querySelector( form.dataset.filterTarget );
        if ( target ) {
          target.innerHTML = html;
        }
      } )
      .catch( () => {
        window.SenMarket.Toast?.show( 'Impossible de charger les produits.', 'error' );
      } );
  };

  return { init };
})();

document.addEventListener( 'DOMContentLoaded', ProductFilters.init );

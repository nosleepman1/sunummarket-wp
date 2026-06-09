/**
 * SenMarket — Animations
 * Intersection Observer pour révéler les éléments au scroll
 * Hover effects sur les cards produit
 */

const ScrollReveal = () => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if ( entry.isIntersecting ) {
          entry.target.classList.add( 'revealed' );
          observer.unobserve( entry.target );
        }
      } );
    },
    { threshold: 0.12, rootMargin: '0px 0px -60px 0px' },
  );

  document.querySelectorAll( '[data-reveal]' ).forEach( (el) => observer.observe( el ) );
};

const CardHover = () => {
  document.querySelectorAll( '.product-card' ).forEach( ( card ) => {
    card.addEventListener( 'mousemove', ( e ) => {
      const rect = card.getBoundingClientRect();
      const x = ( e.clientX - rect.left ) / rect.width - 0.5;
      const y = ( e.clientY - rect.top ) / rect.height - 0.5;
      card.style.transform = `perspective(600px) rotateY(${ x * 6 }deg) rotateX(${ -y * 6 }deg) translateY(-4px)`;
    } );
    card.addEventListener( 'mouseleave', () => {
      card.style.transform = '';
    } );
  } );
};

const AnimateCounters = () => {
  document.querySelectorAll( '[data-count]' ).forEach( ( el ) => {
    const target = parseInt( el.dataset.count, 10 );
    if ( Number.isNaN( target ) ) {
      return;
    }
    const duration = 2000;
    const step = target / ( duration / 16 );
    let current = 0;

    const timer = setInterval( () => {
      current += step;
      if ( current >= target ) {
        current = target;
        clearInterval( timer );
      }
      el.textContent = Math.floor( current ).toLocaleString( 'fr-FR' );
    }, 16 );
  } );
};

const Toast = {
  show( message, type = 'success', duration = 3500 ) {
    const icons = {
      success: 'fas fa-check-circle',
      error: 'fas fa-times-circle',
      info: 'fas fa-info-circle',
      warning: 'fas fa-exclamation-circle',
    };
    const toast = document.createElement( 'div' );
    toast.className = `toast toast--${ type }`;
    toast.innerHTML = `
      <span class="toast-icon"><i class="${ icons[type] || icons.info }" aria-hidden="true"></i></span>
      <span class="toast-msg">${ message }</span>
    `;
    document.getElementById( 'toast-container' )?.appendChild( toast );
    requestAnimationFrame( () => toast.classList.add( 'is-visible' ) );
    setTimeout( () => {
      toast.classList.remove( 'is-visible' );
      toast.addEventListener( 'transitionend', () => toast.remove(), { once: true } );
    }, duration );
  },
};

const MainToast = () => {
  window.SenMarket = window.SenMarket || {};
  window.SenMarket.Toast = Toast;
};

const initApp = () => {
  document.body.classList.add( 'js-enabled' );
  ScrollReveal();
  CardHover();
  MainToast();

  const statsSection = document.querySelector( '.stats-section' );
  if ( statsSection ) {
    new IntersectionObserver( ( [entry] ) => {
      if ( entry.isIntersecting ) {
        AnimateCounters();
      }
    } ).observe( statsSection );
  }
};

document.addEventListener( 'DOMContentLoaded', initApp );

if ( window.jQuery ) {
  jQuery( document ).on( 'added_to_cart', () => {
    Toast.show( 'Produit ajouté au panier', 'success' );
  } );
}

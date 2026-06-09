/**
 * SenMarket — Dark Mode Manager
 * Persistance via localStorage
 * Respect de la préférence système
 */

const DarkMode = (() => {
  const STORAGE_KEY = 'senmarket_theme';
  const ATTR = 'data-theme';
  const root = document.documentElement;
  const ICONS = {
    dark: 'fas fa-sun',
    light: 'fas fa-moon',
  };

  const getPreference = () => {
    const stored = localStorage.getItem(STORAGE_KEY);
    if ( stored ) {
      return stored;
    }
    return window.matchMedia( '(prefers-color-scheme: dark)' ).matches ? 'dark' : 'light';
  };

  const updateIcons = (theme) => {
    document.querySelectorAll( '[data-theme-icon]' ).forEach( ( icon ) => {
      icon.innerHTML = `<i class="${ theme === 'dark' ? ICONS.dark : ICONS.light }" aria-hidden="true"></i>`;
      icon.setAttribute( 'aria-label', theme === 'dark' ? 'Passer en mode clair' : 'Passer en mode sombre' );
    } );
  };

  const apply = (theme) => {
    root.setAttribute( ATTR, theme );
    localStorage.setItem( STORAGE_KEY, theme );
    updateIcons( theme );
    document.dispatchEvent(
      new CustomEvent( 'senmarket:themechange', {
        detail: { theme },
      } ),
    );
  };

  const toggle = () => {
    const current = root.getAttribute( ATTR ) || 'light';
    apply( current === 'dark' ? 'light' : 'dark' );
  };

  const init = () => {
    apply( getPreference() );
    document.addEventListener( 'click', ( e ) => {
      if ( e.target.closest( '[data-toggle-theme]' ) ) {
        e.preventDefault();
        toggle();
      }
    } );
    window.matchMedia( '(prefers-color-scheme: dark)' ).addEventListener( 'change', ( e ) => {
      if ( ! localStorage.getItem( STORAGE_KEY ) ) {
        apply( e.matches ? 'dark' : 'light' );
      }
    } );
  };

  return { init, toggle, apply };
})();

document.addEventListener( 'DOMContentLoaded', DarkMode.init );

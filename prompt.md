# 🛠️ SenMarket — Prompt de Réalisation Technique

### Guide complet : Plugins · Thèmes · JS · Modales · Responsive · Config

---

## 🎯 Contexte du Projet

> **SenMarket** est une marketplace multi-vendeurs WordPress (type Expat Dakar) où :
>
> - Les **visiteurs** voient les produits librement
> - Les **acheteurs** s'inscrivent pour commander
> - Les **vendeurs** s'inscrivent pour publier des produits
> - L'**admin** valide les vendeurs et gère les commissions

**Stack** : WordPress + WooCommerce + Dokan + Elementor + Thème enfant custom (PHP/CSS/JS)

le site ne doit pas avoir d emoji ressemblant a celui fait par l ia ca doit etre professionnel, tout ce qui est emoi , mettre de tres reels icones comme celles de react icon ou font awesome

---

## 📦 PLUGINS — Installation & Ordre

### Ordre d'installation (important pour les dépendances)

```
1. WooCommerce                        → Base obligatoire avant tout
2. Dokan Multivendor Marketplace      → Dépend de WooCommerce
3. Astra                              → Activer en premier
4. Elementor                          → Après le thème
5. Essential Addons for Elementor     → Après Elementor
6. WooCommerce PayDunya / CinetPay    → Après WooCommerce
7. WP Mail SMTP                       → Indépendant
8. WP Social Chat                     → Indépendant
9. Yoast SEO                          → Indépendant
10. Wordfence Security                → En dernier (scan initial)
11. UpdraftPlus                       → En dernier (backup initial)
```

### Configuration de chaque plugin

#### WooCommerce

```
WooCommerce → Réglages → Général
├── Pays de vente          : Sénégal (SN)
├── Devise                 : Franc CFA (XOF)
├── Position symbole       : Droite avec espace → 5 000 XOF
├── Séparateur milliers    : espace (  )
├── Séparateur décimal     : ,
└── Nombre de décimales    : 0

WooCommerce → Réglages → Comptes & Confidentialité
├── Autoriser l'inscription sur la page Mon compte : ✅
├── Permettre aux visiteurs de passer commande    : ❌ (forcer connexion)
├── Effacement des données sur demande            : ✅
└── Politique de confidentialité                  : [lier la page]

WooCommerce → Réglages → Emails
├── Email expéditeur : noreply@senmarket.sn
└── Nom expéditeur   : SenMarket
```

#### Dokan Multivendor

```
https://wordpress.org/plugins/dokan-lite/
pour le mode gratuit pout test

Dokan → Réglages → Général
├── Activer le multi-vendeur               : ✅
├── Qui peut être vendeur                  : Utilisateurs inscrits
├── Validation vendeur par admin           : ✅ (manuel)
├── Partager les revenus                   : Commission admin 5%
├── Retrait minimum                        : 10 000 XOF
└── URL du tableau de bord vendeur         : /dashboard/

Dokan → Réglages → Apparence
├── Afficher le nom de la boutique         : ✅
├── Afficher le panneau vendeur (sidebar)  : ✅
└── Couleur du tableau de bord             : Suivre le thème (voir variables CSS)
```

#### Elementor

```
Elementor → Réglages → Général
├── Désactiver les couleurs par défaut     : ✅
├── Désactiver les polices par défaut      : ✅
└── Enregistrer le journal d'activité      : ✅

Elementor → Réglages → Style
├── Couleur principale   : #E8B84B  (or sénégalais)
├── Couleur secondaire   : #1A1A2E  (nuit profonde)
├── Couleur texte        : #2D2D2D  (mode clair)
├── Police titre         : Syne (Google Fonts)
└── Police corps         : DM Sans (Google Fonts)

Elementor → Réglages → Avancé
├── Méthode de chargement CSS  : Fichier externe (performance)
└── Éditeur de code            : ✅ Activer
```

#### Yoast SEO

```
Yoast → Réglages du site
├── Nom du site       : SenMarket
├── Slogan            : La marketplace du Sénégal
└── Type de site      : Boutique en ligne

Yoast → Recherche → Général
├── Séparateur de titre : |
└── Format              : %%title%% | %%sitename%%

Yoast → Sitemaps XML  : ✅ Activé
Yoast → Réseaux sociaux
├── Facebook URL      : [url page facebook]
└── Image Open Graph  : Logo SenMarket
```

#### WP Mail SMTP

```
WP Mail SMTP → Réglages
├── Service SMTP     : Brevo (Sendinblue)
├── Clé API Brevo    : [clé depuis brevo.com]
├── Email From       : noreply@senmarket.sn
└── Nom From         : SenMarket

→ Tester l'envoi après configuration ✅
```

#### WP Social Chat (WhatsApp)

```
WP Social Chat → Réglages
├── Numéro             : +221XXXXXXXXX
├── Message par défaut : Bonjour SenMarket, j'ai une question...
├── Position           : Bottom Right
├── Couleur fond       : #25D366
├── Couleur texte      : #FFFFFF
├── Afficher sur       : Tout le site
└── Délai d'apparition : 3 secondes
```

#### UpdraftPlus

```
Réglages → UpdraftPlus
├── Planification fichiers   : Hebdomadaire
├── Planification base de données : Quotidienne
├── Stockage distant         : Google Drive
├── Nombre de sauvegardes    : 4
└── Email de rapport         : admin@senmarket.sn
```

#### Wordfence

```
Wordfence → Tous les options
├── Pare-feu activé               : ✅ Mode étendu
├── Blocage IP brute force        : ✅ après 5 tentatives
├── Scan planifié                 : Quotidien 3h00
├── Alertes email                 : ✅
└── Masquer la version WordPress  : ✅
```

---

## 🎨 THÈME — Hello Elementor + Thème Enfant

### Structure du thème enfant

```
/wp-content/themes/senmarket-child/
│
├── style.css               ← Déclaration thème enfant
├── functions.php           ← Logique PHP + hooks
├── index.php               ← Fallback
│
├── assets/
│   ├── css/
│   │   ├── main.css        ← Styles globaux
│   │   ├── dark-mode.css   ← Variables mode sombre
│   │   ├── components.css  ← Boutons, cards, badges
│   │   ├── modals.css      ← Styles boîtes modales
│   │   └── responsive.css  ← Media queries
│   │
│   └── js/
│       ├── main.js         ← Init globale
│       ├── dark-mode.js    ← Toggle clair/sombre
│       ├── modals.js       ← Gestion des modales
│       ├── filters.js      ← Filtres produits AJAX
│       └── animations.js   ← Micro-interactions
│
├── woocommerce/            ← Surcharge templates WooCommerce
│   ├── archive-product.php
│   ├── single-product.php
│   ├── cart/
│   │   └── cart.php
│   └── checkout/
│       └── form-checkout.php
│
└── template-parts/         ← Blocs réutilisables
    ├── header-custom.php
    ├── footer-custom.php
    ├── modal-login.php
    ├── modal-register.php
    └── modal-quickview.php
```

---

## 🌗 THÈME CLAIR / SOMBRE

### Variables CSS — `assets/css/main.css`

```css
/* ═══════════════════════════════════════════
   DESIGN TOKENS — SENMARKET
   Palette inspirée de l'or sénégalais et du
   bleu nuit de l'Atlantique
═══════════════════════════════════════════ */

:root {
  /* — Couleurs primaires — */
  --color-gold: #e8b84b; /* or sénégalais */
  --color-gold-dark: #c9952a; /* or foncé hover */
  --color-gold-light: #f5d98a; /* or clair */
  --color-night: #0d1b2a; /* nuit atlantique */
  --color-ocean: #1b4f72; /* bleu océan */
  --color-terracotta: #c0392b; /* terre rouge */
  --color-success: #27ae60; /* vert validation */
  --color-warning: #f39c12; /* orange alerte */
  --color-error: #e74c3c; /* rouge erreur */
  --color-whatsapp: #25d366; /* vert WhatsApp */

  /* — Mode CLAIR (défaut) — */
  --bg-primary: #ffffff;
  --bg-secondary: #f8f5f0; /* blanc cassé chaud */
  --bg-card: #ffffff;
  --bg-header: #ffffff;
  --bg-footer: #0d1b2a;
  --bg-input: #f8f5f0;
  --bg-modal: rgba(13, 27, 42, 0.75);
  --bg-overlay: rgba(0, 0, 0, 0.5);

  --text-primary: #1a1a1a;
  --text-secondary: #555555;
  --text-muted: #999999;
  --text-inverse: #ffffff;
  --text-gold: #c9952a;

  --border-color: #e8e0d5;
  --border-focus: #e8b84b;
  --border-radius-sm: 6px;
  --border-radius-md: 12px;
  --border-radius-lg: 20px;
  --border-radius-full: 9999px;

  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
  --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
  --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.16);
  --shadow-gold: 0 4px 20px rgba(232, 184, 75, 0.35);

  --font-display: "Syne", sans-serif; /* titres */
  --font-body: "DM Sans", sans-serif; /* corps */
  --font-mono: "JetBrains Mono", monospace;

  --transition-fast: 150ms ease;
  --transition-base: 250ms ease;
  --transition-slow: 400ms cubic-bezier(0.4, 0, 0.2, 1);

  --z-dropdown: 100;
  --z-sticky: 200;
  --z-modal: 1000;
  --z-toast: 1100;
  --z-tooltip: 900;

  --header-height: 70px;
  --sidebar-width: 260px;
}

/* ═══════════════════════════════════════════
   MODE SOMBRE
═══════════════════════════════════════════ */

[data-theme="dark"] {
  --bg-primary: #0d1b2a;
  --bg-secondary: #132337;
  --bg-card: #1a2e42;
  --bg-header: #0a1520;
  --bg-footer: #060f18;
  --bg-input: #1a2e42;

  --text-primary: #f0ede8;
  --text-secondary: #b8b0a5;
  --text-muted: #7a7570;
  --text-gold: #e8b84b;

  --border-color: #2a3f55;
  --border-focus: #e8b84b;

  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
  --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.4);
  --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.5);
}
```

---

## 🌙 JAVASCRIPT — Toggle Clair/Sombre

### `assets/js/dark-mode.js`

```javascript
/**
 * SenMarket — Dark Mode Manager
 * Persistance via localStorage
 * Respect de la préférence système
 */

const DarkMode = (() => {
  const STORAGE_KEY = "senmarket_theme";
  const ATTR = "data-theme";
  const root = document.documentElement;

  // Lire la préférence : localStorage > système > défaut clair
  const getPreference = () => {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) return stored;
    return window.matchMedia("(prefers-color-scheme: dark)").matches
      ? "dark"
      : "light";
  };

  const apply = (theme) => {
    root.setAttribute(ATTR, theme);
    localStorage.setItem(STORAGE_KEY, theme);

    // Mettre à jour les icônes du bouton
    const icons = document.querySelectorAll("[data-theme-icon]");
    icons.forEach((icon) => {
      icon.textContent = theme === "dark" ? "☀️" : "🌙";
      icon.setAttribute(
        "aria-label",
        theme === "dark" ? "Passer en mode clair" : "Passer en mode sombre",
      );
    });

    // Informer Elementor si présent (pour les widgets)
    document.dispatchEvent(
      new CustomEvent("senmarket:themechange", {
        detail: { theme },
      }),
    );
  };

  const toggle = () => {
    const current = root.getAttribute(ATTR) || "light";
    apply(current === "dark" ? "light" : "dark");
  };

  const init = () => {
    // Appliquer immédiatement (évite le flash FOUC)
    apply(getPreference());

    // Écouter les clics sur le bouton toggle
    document.addEventListener("click", (e) => {
      if (e.target.closest("[data-toggle-theme]")) toggle();
    });

    // Synchroniser si l'utilisateur change la préférence système
    window
      .matchMedia("(prefers-color-scheme: dark)")
      .addEventListener("change", (e) => {
        if (!localStorage.getItem(STORAGE_KEY)) {
          apply(e.matches ? "dark" : "light");
        }
      });
  };

  return { init, toggle, apply };
})();

// Init au chargement du DOM
document.addEventListener("DOMContentLoaded", DarkMode.init);
```

### Bouton toggle HTML (à insérer dans le header Elementor)

utiliser vrai icone, pas des emoji

```html
<!-- Insérer via Elementor → Widget HTML -->
<button
  data-toggle-theme
  data-theme-icon
  class="theme-toggle-btn"
  aria-label="Changer le thème"
  title="Mode clair / sombre"
>

</button>

<style>
  .theme-toggle-btn {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius-full);
    width: 42px;
      height: 42px;
      cursor: pointer;
      font-size: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: var(--transition-base);
    }
    .theme-toggle-btn:hover {
      border-color: var(--color-gold);
      transform: rotate(20deg) scale(1.1);
      box-shadow: var(--shadow-gold);
    }
  </style>
</button>
```

---

## 🪟 BOÎTES MODALES — JavaScript

### `assets/js/modals.js`

```javascript
/**
 * SenMarket — Modal Manager
 * Gère : Login · Inscription · Quick View produit · Confirmation
 * Accessibilité : focus trap + fermeture Escape + aria
 */

const ModalManager = (() => {
  let activeModal = null;
  let lastFocused = null;

  // ─── Ouvrir une modale ──────────────────────────────────
  const open = (modalId, data = {}) => {
    const modal = document.getElementById(modalId);
    if (!modal) return console.warn(`Modal #${modalId} introuvable`);

    // Mémoriser le dernier élément focusé (pour restaurer après fermeture)
    lastFocused = document.activeElement;
    activeModal = modal;

    // Injecter les données dynamiques si besoin (ex: Quick View)
    if (data.product) injectProductData(modal, data.product);

    // Afficher
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.body.classList.add("modal-open"); // bloquer le scroll

    // Focus sur le premier élément focusable de la modale
    const focusable = modal.querySelector(
      'button, input, select, textarea, a[href], [tabindex]:not([tabindex="-1"])',
    );
    if (focusable) setTimeout(() => focusable.focus(), 100);

    // Déclencher l'animation d'entrée
    requestAnimationFrame(() => modal.classList.add("is-visible"));

    document.dispatchEvent(
      new CustomEvent("senmarket:modalopen", {
        detail: { modalId, data },
      }),
    );
  };

  // ─── Fermer une modale ──────────────────────────────────
  const close = (modalId) => {
    const modal = modalId ? document.getElementById(modalId) : activeModal;
    if (!modal) return;

    modal.classList.remove("is-visible");
    modal.setAttribute("aria-hidden", "true");

    // Attendre la fin de l'animation CSS avant de masquer
    modal.addEventListener(
      "transitionend",
      () => {
        modal.classList.remove("is-open");
        document.body.classList.remove("modal-open");
        activeModal = null;
        if (lastFocused) lastFocused.focus();
      },
      { once: true },
    );
  };

  // ─── Injection données produit (Quick View) ─────────────
  const injectProductData = (modal, product) => {
    const img = modal.querySelector("[data-modal-img]");
    const name = modal.querySelector("[data-modal-name]");
    const price = modal.querySelector("[data-modal-price]");
    const desc = modal.querySelector("[data-modal-desc]");
    const link = modal.querySelector("[data-modal-link]");

    if (img) {
      img.src = product.image;
      img.alt = product.name;
    }
    if (name) name.textContent = product.name;
    if (price) price.textContent = product.price + " XOF";
    if (desc) desc.innerHTML = product.description;
    if (link) link.href = product.url;
  };

  // ─── Trap focus à l'intérieur de la modale ──────────────
  const trapFocus = (e) => {
    if (!activeModal) return;
    const focusables = activeModal.querySelectorAll(
      'button, input, select, textarea, a[href], [tabindex]:not([tabindex="-1"])',
    );
    const first = focusables[0];
    const last = focusables[focusables.length - 1];

    if (e.key === "Tab") {
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }
    if (e.key === "Escape") close();
  };

  // ─── Init ───────────────────────────────────────────────
  const init = () => {
    // Boutons qui ouvrent une modale via data-modal-open="id"
    document.addEventListener("click", (e) => {
      const trigger = e.target.closest("[data-modal-open]");
      if (trigger) {
        e.preventDefault();
        const id = trigger.dataset.modalOpen;
        const product = trigger.dataset.product
          ? JSON.parse(trigger.dataset.product)
          : {};
        open(id, { product });
      }

      // Fermeture via data-modal-close ou clic sur l'overlay
      if (
        e.target.closest("[data-modal-close]") ||
        e.target.classList.contains("modal-overlay")
      ) {
        close();
      }
    });

    document.addEventListener("keydown", trapFocus);
  };

  return { init, open, close };
})();

document.addEventListener("DOMContentLoaded", ModalManager.init);
```

### CSS des modales — `assets/css/modals.css`

```css
/* ═══════════════════════════════════════════
   MODALES SENMARKET
═══════════════════════════════════════════ */

/* Overlay sombre */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: var(--bg-modal);
  z-index: var(--z-modal);
  display: none;
  align-items: center;
  justify-content: center;
  padding: 20px;
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
}

.modal-overlay.is-open {
  display: flex;
}
.modal-overlay.is-visible .modal-box {
  opacity: 1;
  transform: translateY(0) scale(1);
}

/* Boîte centrale */
.modal-box {
  background: var(--bg-card);
  border-radius: var(--border-radius-lg);
  box-shadow: var(--shadow-lg);
  width: 100%;
  max-width: 520px;
  max-height: 90vh;
  overflow-y: auto;
  padding: 40px;
  position: relative;
  opacity: 0;
  transform: translateY(30px) scale(0.97);
  transition:
    opacity var(--transition-slow),
    transform var(--transition-slow);
}

/* Bouton fermeture × */
.modal-close {
  position: absolute;
  top: 16px;
  right: 16px;
  background: var(--bg-secondary);
  border: none;
  border-radius: var(--border-radius-full);
  width: 36px;
  height: 36px;
  font-size: 20px;
  cursor: pointer;
  color: var(--text-secondary);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--transition-fast);
  line-height: 1;
}
.modal-close:hover {
  background: var(--color-error);
  color: white;
  transform: rotate(90deg);
}

/* Bloquer scroll du body */
body.modal-open {
  overflow: hidden;
}

/* ─── Modale Quick View Produit ─── */
.modal-quickview .modal-box {
  max-width: 780px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 32px;
  padding: 32px;
}
.modal-quickview .modal-img {
  border-radius: var(--border-radius-md);
  width: 100%;
  aspect-ratio: 1;
  object-fit: cover;
}

/* ─── Modale Connexion / Inscription ─── */
.modal-auth .modal-box {
  max-width: 440px;
}
.modal-auth .modal-title {
  font-family: var(--font-display);
  font-size: 1.8rem;
  color: var(--text-primary);
  margin-bottom: 8px;
}
.modal-auth .modal-subtitle {
  color: var(--text-secondary);
  font-size: 0.9rem;
  margin-bottom: 28px;
}
.modal-auth .tab-switcher {
  display: flex;
  gap: 0;
  margin-bottom: 28px;
  background: var(--bg-secondary);
  border-radius: var(--border-radius-sm);
  padding: 4px;
}
.modal-auth .tab-btn {
  flex: 1;
  padding: 10px;
  border: none;
  background: transparent;
  border-radius: calc(var(--border-radius-sm) - 2px);
  cursor: pointer;
  font-weight: 600;
  color: var(--text-muted);
  transition: var(--transition-base);
}
.modal-auth .tab-btn.active {
  background: var(--color-gold);
  color: #000;
  box-shadow: var(--shadow-sm);
}

/* ─── Responsive modales ─── */
@media (max-width: 640px) {
  .modal-box {
    padding: 24px;
  }
  .modal-quickview .modal-box {
    grid-template-columns: 1fr;
    max-height: 95vh;
  }
}
```

### HTML Modale Connexion/Inscription

```html
<!-- Insérer dans footer via functions.php : wp_footer hook -->
<!-- Modal Login / Register -->
<div
  id="modal-auth"
  class="modal-overlay modal-auth"
  role="dialog"
  aria-modal="true"
  aria-label="Connexion"
>
  <div class="modal-box">
    <button class="modal-close" data-modal-close aria-label="Fermer">×</button>

    <!-- Onglets -->
    <div class="tab-switcher" role="tablist">
      <button class="tab-btn active" data-tab="login" role="tab">
        Connexion
      </button>
      <button class="tab-btn" data-tab="register" role="tab">
        Inscription
      </button>
    </div>

    <!-- Formulaire Connexion -->
    <div id="tab-login" class="tab-pane">
      <h2 class="modal-title">Bon retour</h2>
      <p class="modal-subtitle">Connectez-vous pour accéder à votre compte</p>
      <?php echo do_shortcode('[woocommerce_my_account]'); ?>
    </div>

    <!-- Formulaire Inscription -->
    <div id="tab-register" class="tab-pane" hidden>
      <h2 class="modal-title">Rejoindre SenMarket</h2>
      <p class="modal-subtitle">Créez votre compte gratuitement</p>

      <!-- Sélecteur de rôle -->
      <div class="role-selector">
        <label class="role-card">
          <input type="radio" name="role" value="customer" checked />
          <span>🛒 Je veux acheter</span>
        </label>
        <label class="role-card">
          <input type="radio" name="role" value="seller" />
          <span>🏪 Je veux vendre</span>
        </label>
      </div>

      <?php echo do_shortcode('[dokan-seller-registration]'); ?>
    </div>
  </div>
</div>

<!-- Modal Quick View Produit -->
<div
  id="modal-quickview"
  class="modal-overlay modal-quickview"
  role="dialog"
  aria-modal="true"
  aria-label="Aperçu produit"
>
  <div class="modal-box">
    <button class="modal-close" data-modal-close aria-label="Fermer">×</button>
    <img class="modal-img" data-modal-img src="" alt="" />
    <div class="modal-product-info">
      <h3 data-modal-name></h3>
      <p class="modal-price" data-modal-price></p>
      <p data-modal-desc></p>
      <a data-modal-link href="#" class="btn-primary"
        >Voir le produit complet</a
      >
      <button class="btn-cart" onclick="addToCartQuick()">
        Ajouter au panier
      </button>
    </div>
  </div>
</div>
```

---

## ✨ ANIMATIONS & MICRO-INTERACTIONS

### `assets/js/animations.js`

```javascript
/**
 * SenMarket — Animations
 * Intersection Observer pour révéler les éléments au scroll
 * Hover effects sur les cards produit
 */

// ─── Scroll Reveal ───────────────────────────────────────
const ScrollReveal = () => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("revealed");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.12, rootMargin: "0px 0px -60px 0px" },
  );

  document.querySelectorAll("[data-reveal]").forEach((el) => {
    observer.observe(el);
  });
};

// ─── Cards produit — hover parallax léger ───────────────
const CardHover = () => {
  document.querySelectorAll(".product-card").forEach((card) => {
    card.addEventListener("mousemove", (e) => {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;
      card.style.transform = `perspective(600px) rotateY(${x * 6}deg) rotateX(${-y * 6}deg) translateY(-4px)`;
    });
    card.addEventListener("mouseleave", () => {
      card.style.transform = "";
    });
  });
};

// ─── Compteur animé (stats hero) ─────────────────────────
const AnimateCounters = () => {
  document.querySelectorAll("[data-count]").forEach((el) => {
    const target = parseInt(el.dataset.count);
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;

    const timer = setInterval(() => {
      current += step;
      if (current >= target) {
        current = target;
        clearInterval(timer);
      }
      el.textContent = Math.floor(current).toLocaleString("fr-FR");
    }, 16);
  });
};

// ─── Toast notifications ─────────────────────────────────
const Toast = {
  show(message, type = "success", duration = 3500) {
    const toast = document.createElement("div");
    toast.className = `toast toast--${type}`;
    toast.innerHTML = `
      <span class="toast-icon">
        ${{ success: "✅", error: "❌", info: "ℹ️", warning: "⚠️" }[type]}
      </span>
      <span class="toast-msg">${message}</span>
    `;
    document.getElementById("toast-container")?.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add("is-visible"));
    setTimeout(() => {
      toast.classList.remove("is-visible");
      toast.addEventListener("transitionend", () => toast.remove(), {
        once: true,
      });
    }, duration);
  },
};

// ─── Init ─────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
  ScrollReveal();
  CardHover();

  // Compteurs : déclencher quand visibles
  const statsSection = document.querySelector(".stats-section");
  if (statsSection) {
    new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) AnimateCounters();
    }).observe(statsSection);
  }

  // Exposer Toast globalement
  window.SenMarket = window.SenMarket || {};
  window.SenMarket.Toast = Toast;
});

// Intercepter ajout panier WooCommerce → toast
jQuery(document).on("added_to_cart", () => {
  Toast.show("Produit ajouté au panier 🛒", "success");
});
```

### CSS Animations — dans `main.css`

```css
/* ═══ Scroll Reveal ═══════════════════════════════════════ */
[data-reveal] {
  opacity: 0;
  transform: translateY(28px);
  transition:
    opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1),
    transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
[data-reveal="left"] {
  transform: translateX(-40px);
}
[data-reveal="right"] {
  transform: translateX(40px);
}
[data-reveal="scale"] {
  transform: scale(0.92);
}

[data-reveal].revealed {
  opacity: 1;
  transform: none;
}

/* Délais en cascade pour les grilles */
[data-reveal]:nth-child(1) {
  transition-delay: 0ms;
}
[data-reveal]:nth-child(2) {
  transition-delay: 80ms;
}
[data-reveal]:nth-child(3) {
  transition-delay: 160ms;
}
[data-reveal]:nth-child(4) {
  transition-delay: 240ms;
}
[data-reveal]:nth-child(5) {
  transition-delay: 320ms;
}
[data-reveal]:nth-child(6) {
  transition-delay: 400ms;
}

/* ═══ Product Card ════════════════════════════════════════ */
.product-card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  overflow: hidden;
  transition: var(--transition-slow);
  will-change: transform;
  cursor: pointer;
}
.product-card:hover {
  border-color: var(--color-gold);
  box-shadow: var(--shadow-gold);
}
.product-card .card-img-wrap {
  position: relative;
  overflow: hidden;
  aspect-ratio: 4/3;
}
.product-card .card-img-wrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}
.product-card:hover .card-img-wrap img {
  transform: scale(1.06);
}
.product-card .card-actions {
  position: absolute;
  bottom: 12px;
  left: 50%;
  transform: translateX(-50%) translateY(8px);
  display: flex;
  gap: 8px;
  opacity: 0;
  transition: var(--transition-base);
}
.product-card:hover .card-actions {
  opacity: 1;
  transform: translateX(-50%) translateY(0);
}

/* ═══ Toast ═══════════════════════════════════════════════ */
#toast-container {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: var(--z-toast);
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.toast {
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius-md);
  padding: 14px 18px;
  box-shadow: var(--shadow-md);
  font-size: 0.9rem;
  color: var(--text-primary);
  opacity: 0;
  transform: translateX(30px);
  transition: var(--transition-slow);
  min-width: 260px;
}
.toast.is-visible {
  opacity: 1;
  transform: translateX(0);
}
.toast--success {
  border-left: 4px solid var(--color-success);
}
.toast--error {
  border-left: 4px solid var(--color-error);
}
.toast--info {
  border-left: 4px solid var(--color-ocean);
}
.toast--warning {
  border-left: 4px solid var(--color-warning);
}
```

---

## 📱 RESPONSIVE — Media Queries

### `assets/css/responsive.css`

```css
/* ═══════════════════════════════════════════
   BREAKPOINTS SENMARKET
   Mobile-first approach
═══════════════════════════════════════════

   xs  : < 480px   (petit mobile)
   sm  : 480–767px (mobile)
   md  : 768–1023px (tablette)
   lg  : 1024–1279px (petit desktop)
   xl  : ≥ 1280px  (desktop)
═══════════════════════════════════════════ */

/* ─── Container ─────────────────────────────────────────── */
.senmarket-container {
  width: 100%;
  max-width: 1280px;
  margin-inline: auto;
  padding-inline: 16px;
}
@media (min-width: 640px) {
  .senmarket-container {
    padding-inline: 24px;
  }
}
@media (min-width: 1024px) {
  .senmarket-container {
    padding-inline: 40px;
  }
}

/* ─── Grille produits ────────────────────────────────────── */
.products-grid {
  display: grid;
  gap: 16px;
  grid-template-columns: repeat(2, 1fr); /* xs: 2 col */
}
@media (min-width: 640px) {
  .products-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
}
@media (min-width: 768px) {
  .products-grid {
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }
}
@media (min-width: 1024px) {
  .products-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

/* ─── Header / Navigation ────────────────────────────────── */
.site-header {
  height: var(--header-height);
  position: sticky;
  top: 0;
  z-index: var(--z-sticky);
  background: var(--bg-header);
  border-bottom: 1px solid var(--border-color);
  box-shadow: var(--shadow-sm);
  transition: background var(--transition-base);
}
.header-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100%;
}

/* Menu desktop */
.nav-desktop {
  display: none;
}
@media (min-width: 1024px) {
  .nav-desktop {
    display: flex;
    gap: 32px;
    align-items: center;
  }
  .nav-mobile {
    display: none;
  }
  .burger-btn {
    display: none;
  }
}

/* Menu mobile (hamburger) */
.nav-mobile {
  position: fixed;
  inset: 0;
  top: var(--header-height);
  background: var(--bg-primary);
  z-index: var(--z-dropdown);
  padding: 32px 24px;
  transform: translateX(-100%);
  transition: transform var(--transition-slow);
  overflow-y: auto;
}
.nav-mobile.is-open {
  transform: translateX(0);
}

/* ─── Hero Section ───────────────────────────────────────── */
.hero-section {
  min-height: 100svh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 80px 16px 40px;
}
.hero-title {
  font-family: var(--font-display);
  font-size: clamp(2rem, 7vw, 5rem);
  font-weight: 800;
  line-height: 1.1;
  color: var(--text-primary);
}
.hero-title .highlight {
  color: var(--color-gold);
  display: block;
}

/* ─── Layout Boutique (Sidebar + Produits) ───────────────── */
.shop-layout {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}
@media (min-width: 768px) {
  .shop-layout {
    grid-template-columns: 240px 1fr;
    align-items: start;
  }
  .shop-sidebar {
    position: sticky;
    top: calc(var(--header-height) + 20px);
  }
}

/* ─── Dashboard Vendeur ──────────────────────────────────── */
.dashboard-layout {
  display: grid;
  grid-template-columns: 1fr;
  min-height: 100vh;
}
@media (min-width: 1024px) {
  .dashboard-layout {
    grid-template-columns: var(--sidebar-width) 1fr;
  }
}

/* ─── Fiche Produit ──────────────────────────────────────── */
.product-single {
  display: grid;
  grid-template-columns: 1fr;
  gap: 32px;
}
@media (min-width: 768px) {
  .product-single {
    grid-template-columns: 1fr 1fr;
    gap: 48px;
  }
}

/* ─── Page Checkout ──────────────────────────────────────── */
.woocommerce-checkout .col2-set {
  display: grid;
  grid-template-columns: 1fr;
  gap: 32px;
}
@media (min-width: 768px) {
  .woocommerce-checkout .col2-set {
    grid-template-columns: 1fr 1fr;
  }
}

/* ─── Utilitaires responsive ─────────────────────────────── */
.hide-mobile {
  display: block;
}
.show-mobile {
  display: none;
}
@media (max-width: 767px) {
  .hide-mobile {
    display: none !important;
  }
  .show-mobile {
    display: block !important;
  }
}

/* Touch targets (boutons cliquables) min 44×44px */
@media (hover: none) and (pointer: coarse) {
  .btn,
  button,
  a.wc-btn {
    min-height: 44px;
    min-width: 44px;
  }
}
```

---

## ⚙️ VARIABLES D'ENVIRONNEMENT & CONFIGURATION

### Fichier `wp-config.php` — constantes à ajouter

```php
<?php
// ─── Mode de l'application ──────────────────────────────
define('WP_ENVIRONMENT_TYPE', 'production'); // 'development' | 'staging' | 'production'
define('WP_DEBUG',            false);        // true en développement uniquement
define('WP_DEBUG_LOG',        false);        // Logs dans /wp-content/debug.log
define('WP_DEBUG_DISPLAY',    false);        // Jamais afficher les erreurs en prod

// ─── Performance ────────────────────────────────────────
define('WP_CACHE',               true);
define('COMPRESS_SCRIPTS',       true);
define('COMPRESS_CSS',           true);
define('CONCATENATE_SCRIPTS',    false); // false si conflit plugins
define('AUTOSAVE_INTERVAL',      120);   // secondes entre autosaves
define('WP_POST_REVISIONS',      5);     // max 5 révisions par post

// ─── Sécurité ────────────────────────────────────────────
define('DISALLOW_FILE_EDIT',     true);  // Désactiver éditeur de fichiers dans l'admin
define('DISALLOW_FILE_MODS',     false); // true = bloquer install plugins (prod avancé)
define('FORCE_SSL_ADMIN',        true);  // Forcer HTTPS dans l'admin
define('WP_HTTP_BLOCK_EXTERNAL', false); // true = bloquer requêtes externes

// ─── Clés PayDunya ───────────────────────────────────────
define('PAYDUNYA_MASTER_KEY',    getenv('PAYDUNYA_MASTER_KEY')    ?: 'votre_master_key');
define('PAYDUNYA_PUBLIC_KEY',    getenv('PAYDUNYA_PUBLIC_KEY')    ?: 'votre_public_key');
define('PAYDUNYA_PRIVATE_KEY',   getenv('PAYDUNYA_PRIVATE_KEY')   ?: 'votre_private_key');
define('PAYDUNYA_MODE',          'test'); // 'test' | 'live'

// ─── CinetPay (alternative) ──────────────────────────────
define('CINETPAY_API_KEY',       getenv('CINETPAY_API_KEY')       ?: 'votre_api_key');
define('CINETPAY_SITE_ID',       getenv('CINETPAY_SITE_ID')       ?: 'votre_site_id');

// ─── Email SMTP (Brevo) ──────────────────────────────────
define('BREVO_API_KEY',          getenv('BREVO_API_KEY')          ?: 'votre_api_key_brevo');
define('SMTP_FROM_EMAIL',        'noreply@senmarket.sn');
define('SMTP_FROM_NAME',         'SenMarket');

// ─── WhatsApp ────────────────────────────────────────────
define('SENMARKET_WA_NUMBER',    '+221700000000');
define('SENMARKET_WA_MESSAGE',   'Bonjour SenMarket, j\'ai une question...');

// ─── Dokan / Commission ──────────────────────────────────
define('DOKAN_ADMIN_COMMISSION', 5);   // % de commission sur chaque vente
define('DOKAN_MIN_WITHDRAW',     10000); // minimum retrait en XOF
```

### Fichier `.env` (si utilisation d'un plugin dotenv)

```env
# SenMarket — Variables d'environnement
# NE PAS COMMITTER CE FICHIER (.gitignore)

APP_ENV=production
APP_DEBUG=false
APP_URL=https://senmarket.sn

# Base de données
DB_NAME=senmarket_db
DB_USER=senmarket_user
DB_PASSWORD=mot_de_passe_fort_ici
DB_HOST=localhost
DB_PREFIX=smkt_

# PayDunya
PAYDUNYA_MASTER_KEY=pk_live_xxxxxxxxxxxx
PAYDUNYA_PUBLIC_KEY=pk_live_xxxxxxxxxxxx
PAYDUNYA_PRIVATE_KEY=sk_live_xxxxxxxxxxxx
PAYDUNYA_MODE=live

# CinetPay
CINETPAY_API_KEY=xxxxxxxxxxxxxxxx
CINETPAY_SITE_ID=xxxxxxxxxx

# Brevo SMTP
BREVO_API_KEY=xkeysib-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-xxxxxx

# Google Drive (UpdraftPlus)
GDRIVE_CLIENT_ID=xxxx.apps.googleusercontent.com
GDRIVE_CLIENT_SECRET=xxxx

# Clés WordPress (générer sur : https://api.wordpress.org/secret-key/1.1/salt/)
AUTH_KEY=générer_ici
SECURE_AUTH_KEY=générer_ici
LOGGED_IN_KEY=générer_ici
NONCE_KEY=générer_ici
```

### `.gitignore` à la racine WordPress

```gitignore
# WordPress
wp-config.php
.env
.env.*
!.env.example

# Uploads utilisateurs
wp-content/uploads/

# Cache
wp-content/cache/
wp-content/advanced-cache.php
wp-content/wp-cache-config.php

# Logs
wp-content/debug.log
*.log

# Thème — node_modules si usage npm/sass
wp-content/themes/senmarket-child/node_modules/

# OS
.DS_Store
Thumbs.db
```

---

## 🔌 ENQUEUE SCRIPTS — `functions.php`

```php
<?php
/**
 * SenMarket Child Theme — functions.php
 */

// ─── Chargement des assets ──────────────────────────────
add_action('wp_enqueue_scripts', function() {

  // Hériter du parent
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

  // Google Fonts
  wp_enqueue_style('senmarket-fonts',
    'https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap',
    [], null
  );

  // CSS
  wp_enqueue_style('senmarket-main',
    get_stylesheet_directory_uri() . '/assets/css/main.css', [], '1.0.0');
  wp_enqueue_style('senmarket-modals',
    get_stylesheet_directory_uri() . '/assets/css/modals.css',
    ['senmarket-main'], '1.0.0');
  wp_enqueue_style('senmarket-responsive',
    get_stylesheet_directory_uri() . '/assets/css/responsive.css',
    ['senmarket-main'], '1.0.0');

  // JS
  wp_enqueue_script('senmarket-darkmode',
    get_stylesheet_directory_uri() . '/assets/js/dark-mode.js',
    [], '1.0.0', true);
  wp_enqueue_script('senmarket-modals',
    get_stylesheet_directory_uri() . '/assets/js/modals.js',
    ['jquery'], '1.0.0', true);
  wp_enqueue_script('senmarket-animations',
    get_stylesheet_directory_uri() . '/assets/js/animations.js',
    ['jquery'], '1.0.0', true);
  wp_enqueue_script('senmarket-main',
    get_stylesheet_directory_uri() . '/assets/js/main.js',
    ['jquery', 'senmarket-darkmode', 'senmarket-modals', 'senmarket-animations'],
    '1.0.0', true);

  // Passer des variables PHP → JS
  wp_localize_script('senmarket-main', 'SenMarketConfig', [
    'ajaxUrl'       => admin_url('admin-ajax.php'),
    'nonce'         => wp_create_nonce('senmarket_nonce'),
    'isLoggedIn'    => is_user_logged_in(),
    'waNumber'      => SENMARKET_WA_NUMBER,
    'waMessage'     => SENMARKET_WA_MESSAGE,
    'currency'      => 'XOF',
    'cartUrl'       => wc_get_cart_url(),
    'checkoutUrl'   => wc_get_checkout_url(),
    'accountUrl'    => wc_get_account_endpoint_url('dashboard'),
    'loginModalId'  => 'modal-auth',
    'i18n'          => [
      'addedToCart'  => 'Produit ajouté au panier 🛒',
      'loginRequired'=> 'Connectez-vous pour commander',
      'error'        => 'Une erreur est survenue',
    ],
  ]);
});

// ─── Injecter les modales avant </body> ─────────────────
add_action('wp_footer', function() {
  get_template_part('template-parts/modal-login');
  get_template_part('template-parts/modal-quickview');
  // Conteneur pour les toasts
  echo '<div id="toast-container" role="region" aria-live="polite"></div>';
});

// ─── Empêcher commande sans connexion ────────────────────
add_action('template_redirect', function() {
  if ((is_cart() || is_checkout()) && !is_user_logged_in()) {
    wp_redirect(wc_get_page_permalink('myaccount'));
    exit;
  }
});

// ─── Devise XOF sans décimale ────────────────────────────
add_filter('woocommerce_price_format', fn() => '%2$s %1$s');
add_filter('wc_get_price_decimals',    fn() => 0);

// ─── Supports thème ─────────────────────────────────────
add_action('after_setup_theme', function() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('woocommerce');
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
});
```

---

## 📋 RÉSUMÉ DES FICHIERS À CRÉER

```
senmarket-child/
│
├── style.css                          ✅ Déclaration thème
├── functions.php                      ✅ Hooks + enqueue + config
│
├── assets/css/
│   ├── main.css                       ✅ Variables + base + cards + toasts
│   ├── modals.css                     ✅ Overlay + boîtes modales
│   └── responsive.css                 ✅ Breakpoints + grilles
│
├── assets/js/
│   ├── dark-mode.js                   ✅ Toggle clair/sombre + localStorage
│   ├── modals.js                      ✅ Ouverture/fermeture + focus trap
│   ├── animations.js                  ✅ Scroll reveal + hover parallax + toasts
│   └── main.js                        ✅ Init globale + navigation mobile
│
├── template-parts/
│   ├── modal-login.php                ✅ HTML modale connexion/inscription
│   └── modal-quickview.php            ✅ HTML modale aperçu produit
│
└── woocommerce/
    ├── single-product.php             ✅ Template produit custom
    ├── archive-product.php            ✅ Template catalogue custom
    └── checkout/form-checkout.php     ✅ Template checkout custom

wp-config.php                          ✅ Constantes + sécurité
.env                                   ✅ Clés API (jamais committer)
.gitignore                             ✅ Exclure fichiers sensibles
```

---

_SenMarket — Prompt de Réalisation Technique v1.0 · Juin 2026_

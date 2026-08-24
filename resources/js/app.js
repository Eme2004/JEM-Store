import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const announcements = window.jemAnnouncements ?? [];
    const announcementBar = document.querySelector('[data-announcement-bar]');

    if (!announcementBar || announcements.length === 0) {
        return;
    }

    const textElement = announcementBar.querySelector('[data-announcement-text]');
    const primaryLink = announcementBar.querySelector('[data-announcement-primary]');
    const secondaryLink = announcementBar.querySelector('[data-announcement-secondary]');

    let currentIndex = 0;

    const renderAnnouncement = (item) => {
        textElement.textContent = item.text;

        primaryLink.textContent = item.primary_label;
        primaryLink.href = item.primary_url;

        secondaryLink.textContent = item.secondary_label;
        secondaryLink.href = item.secondary_url;
    };

    renderAnnouncement(announcements[currentIndex]);

    if (announcements.length === 1) {
        return;
    }

    setInterval(() => {
        announcementBar.classList.add('is-changing');

        setTimeout(() => {
            currentIndex = (currentIndex + 1) % announcements.length;
            renderAnnouncement(announcements[currentIndex]);
            announcementBar.classList.remove('is-changing');
        }, 180);
    }, 5000);
});


document.addEventListener('DOMContentLoaded', () => {
    const REVEAL_SELECTORS = [
        '.product-card',
        '.jem-home-collection-card',
        '.jem-home-value',
        '.cart-item',
        '.account-order-row',
        '.checkout-payment-option',
        '.report-stat',
        '.auth-card',
        '.account-panel',
        '.profile-edit-panel',
        '.checkout-success-panel',
        '.catalog-filters',
        '.jem-home-heading',
        '.jem-home-statement__title',
        '.recent-products-header',
    ];

    const revealElements = document.querySelectorAll(REVEAL_SELECTORS.join(','));

    if (revealElements.length === 0) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    revealElements.forEach((el, index) => {
        el.classList.add('jem-reveal');
        el.style.transitionDelay = `${(index % 4) * 70}ms`;
    });

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        revealElements.forEach((el) => el.classList.add('jem-reveal--visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('jem-reveal--visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

    revealElements.forEach((el) => observer.observe(el));
});


document.addEventListener('DOMContentLoaded', () => {
    const paymentMethods = document.querySelectorAll('[data-payment-method]');
    const cardFields = document.querySelector('[data-card-fields]');

    if (paymentMethods.length === 0 || !cardFields) {
        return;
    }

    const syncCardFields = () => {
        const selected = document.querySelector('[data-payment-method]:checked');
        cardFields.classList.toggle('d-none', selected?.value !== 'card');
    };

    paymentMethods.forEach((input) => {
        input.addEventListener('change', syncCardFields);
    });

    syncCardFields();
});


document.addEventListener('DOMContentLoaded', () => {
    const STORAGE_KEY = 'jem_cookie_notice_dismissed';
    const notice = document.querySelector('[data-cookie-notice]');
    const acceptButton = document.querySelector('[data-cookie-notice-accept]');

    if (!notice || !acceptButton) {
        return;
    }

    let dismissed = false;

    try {
        dismissed = window.localStorage.getItem(STORAGE_KEY) === '1';
    } catch (error) {
        dismissed = false;
    }

    if (!dismissed) {
        notice.classList.remove('d-none');
    }

    acceptButton.addEventListener('click', () => {
        notice.classList.add('d-none');

        try {
            window.localStorage.setItem(STORAGE_KEY, '1');
        } catch (error) {
            // localStorage no disponible (modo privado, etc.): el aviso
            // simplemente volverá a mostrarse en la próxima visita.
        }
    });
});


document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-confirm-modal]');

    if (!modal) {
        return;
    }

    const messageEl = modal.querySelector('[data-confirm-modal-message]');
    const confirmButton = modal.querySelector('[data-confirm-modal-confirm]');
    const cancelTriggers = modal.querySelectorAll('[data-confirm-modal-cancel]');

    let pendingForm = null;

    const openModal = (form) => {
        pendingForm = form;
        messageEl.textContent = form.dataset.confirmMessage || '¿Confirmar esta acción?';
        modal.classList.remove('d-none');
        confirmButton.focus();
    };

    const closeModal = () => {
        pendingForm = null;
        modal.classList.add('d-none');
    };

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('[data-confirm-submit]');

        if (!form || form.dataset.confirmed === '1') {
            return;
        }

        event.preventDefault();
        openModal(form);
    });

    confirmButton.addEventListener('click', () => {
        if (!pendingForm) {
            return;
        }

        pendingForm.dataset.confirmed = '1';
        pendingForm.requestSubmit();
        closeModal();
    });

    cancelTriggers.forEach((trigger) => {
        trigger.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('d-none')) {
            closeModal();
        }
    });
});


document.addEventListener('DOMContentLoaded', () => {
    const passwordInputs = document.querySelectorAll('.auth-card input[type="password"]');

    passwordInputs.forEach((input) => {
        const wrap = document.createElement('div');
        wrap.className = 'auth-password-wrap';
        input.parentNode.insertBefore(wrap, input);
        wrap.appendChild(input);

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'auth-password-toggle';
        toggle.setAttribute('aria-label', 'Mostrar contraseña');
        toggle.setAttribute('aria-pressed', 'false');
        toggle.innerHTML = `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M2 12C2 12 5.5 5 12 5C18.5 5 22 12 22 12C22 12 18.5 19 12 19C5.5 19 2 12 2 12Z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        `;
        wrap.appendChild(toggle);

        toggle.addEventListener('click', () => {
            const isVisible = input.type === 'text';
            input.type = isVisible ? 'password' : 'text';
            toggle.setAttribute('aria-pressed', String(!isVisible));
            toggle.setAttribute('aria-label', isVisible ? 'Mostrar contraseña' : 'Ocultar contraseña');
            toggle.classList.toggle('auth-password-toggle--visible', !isVisible);
        });
    });
});

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

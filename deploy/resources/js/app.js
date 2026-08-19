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

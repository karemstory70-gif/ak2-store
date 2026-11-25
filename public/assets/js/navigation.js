
document.addEventListener('DOMContentLoaded', function () {

    const header = document.querySelector('.site-header');

    /* ========================================
       STICKY HEADER SCROLL EFFECT
    ======================================== */

    if (header) {

        function updateHeader() {
            header.classList.toggle('is-scrolled', window.scrollY > 40);
        }

        // Apply the correct state when the page loads
        updateHeader();

        // Update header while scrolling
        window.addEventListener('scroll', updateHeader, {
            passive: true
        });
    }

    /* ========================================
       MOBILE NAVIGATION
    ======================================== */

    const menuButton = document.getElementById('mobileMenuToggle');
    const navigation = document.getElementById('mainNavigation');

    if (!menuButton || !navigation) {
        return;
    }

    // Open and close mobile navigation
    menuButton.addEventListener('click', function () {

        const isOpen = navigation.classList.toggle('active');

        menuButton.setAttribute('aria-expanded', isOpen);

        menuButton.setAttribute(
            'aria-label',
            isOpen ? 'Close menu' : 'Open menu'
        );

    });

    // Close menu after clicking a navigation link
    const navigationLinks = navigation.querySelectorAll('.nav-link');

    navigationLinks.forEach(function (link) {

        link.addEventListener('click', function () {

            navigation.classList.remove('active');

            menuButton.setAttribute('aria-expanded', 'false');
            menuButton.setAttribute('aria-label', 'Open menu');

        });

    });

    // Close menu when resizing to desktop
    window.addEventListener('resize', function () {

        if (window.innerWidth > 768) {

            navigation.classList.remove('active');

            menuButton.setAttribute('aria-expanded', 'false');
            menuButton.setAttribute('aria-label', 'Open menu');

        }

    });

});

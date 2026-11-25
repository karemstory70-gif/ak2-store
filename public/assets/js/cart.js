document.addEventListener('click', function (event) {
    const cartDropdowns = document.querySelectorAll('.cart-dropdown[open]');

    cartDropdowns.forEach(function (dropdown) {
        if (!dropdown.contains(event.target)) {
            dropdown.removeAttribute('open');
        }
    });
});
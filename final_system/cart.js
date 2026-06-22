// cart.js – Week 3 interactive features
// Handles alerts, DOM interactions, and cart count updates.

document.addEventListener('DOMContentLoaded', () => {
    const cartCountEl = document.getElementById('cartCount');
    let cartCount = 0;

    // Add to Cart button handler
    const addButtons = document.querySelectorAll('.add-to-cart');
    addButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const price = btn.getAttribute('data-price');
            cartCount++;
            cartCountEl.textContent = cartCount;
            alert(`Car added to cart! Price: Ksh ${price}\nCart items: ${cartCount}`);
        });
    });

    // Heart icon toggle with simple alert
    const hearts = document.querySelectorAll('.heart-icon');
    hearts.forEach(icon => {
        icon.addEventListener('click', (e) => {
            e.preventDefault();
            icon.classList.toggle('liked');
            if (icon.classList.contains('liked')) {
                icon.style.color = '#e74c3c'; // red
                alert('Added to favorites!');
            } else {
                icon.style.color = 'white';
                alert('Removed from favorites.');
            }
        });
    });
});

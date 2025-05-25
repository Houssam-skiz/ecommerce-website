// Animate elements when they come into view
const animateOnScroll = () => {
    const elements = document.querySelectorAll('.animate-on-scroll');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementBottom = element.getBoundingClientRect().bottom;
        
        if (elementTop < window.innerHeight && elementBottom > 0) {
            element.classList.add('animate');
        }
    });
};

// Product hover effects
const initProductCards = () => {
    const cards = document.querySelectorAll('.product-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', (e) => {
            const image = card.querySelector('.product-image');
            if (image) {
                image.style.transform = 'scale(1.1)';
            }
        });
        
        card.addEventListener('mouseleave', (e) => {
            const image = card.querySelector('.product-image');
            if (image) {
                image.style.transform = 'scale(1)';
            }
        });
    });
};

// Category card hover effects
const initCategoryCards = () => {
    const cards = document.querySelectorAll('.category-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', (e) => {
            const icon = card.querySelector('.category-icon');
            if (icon) {
                icon.style.transform = 'translateY(-5px) rotate(5deg)';
            }
        });
        
        card.addEventListener('mouseleave', (e) => {
            const icon = card.querySelector('.category-icon');
            if (icon) {
                icon.style.transform = 'translateY(0) rotate(0)';
            }
        });
    });
};

// Cart functionality
let cartCount = parseInt(localStorage.getItem('cartCount')) || 0;

const updateCartCount = (count) => {
    const badge = document.querySelector('#cart-count');
    if (badge) {
        badge.textContent = count;
        badge.classList.add('pulse');
        setTimeout(() => badge.classList.remove('pulse'), 300);
        localStorage.setItem('cartCount', count);
    }
};

// Add to cart animation
const addToCart = (button) => {
    // Get product details
    const card = button.closest('.product-card');
    const image = card.querySelector('.product-image');
    const cartIcon = document.querySelector('#cart-icon');
    
    if (image && cartIcon) {
        // Create flying image
        const flyingImage = image.cloneNode();
        
        // Get positions
        const imageRect = image.getBoundingClientRect();
        const cartRect = cartIcon.getBoundingClientRect();
        
        // Set initial position
        flyingImage.style.cssText = `
            position: fixed;
            z-index: 1000;
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            top: ${imageRect.top}px;
            left: ${imageRect.left}px;
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        `;
        
        document.body.appendChild(flyingImage);
        
        // Trigger animation
        setTimeout(() => {
            flyingImage.style.cssText += `
                transform: translate(${cartRect.left - imageRect.left}px, ${cartRect.top - imageRect.top}px) scale(0.2);
                opacity: 0;
            `;
        }, 50);
        
        // Update cart
        setTimeout(() => {
            cartCount++;
            updateCartCount(cartCount);
            flyingImage.remove();
        }, 800);
        
        // Update button
        button.innerHTML = '<i class="fas fa-check"></i> Added!';
        button.classList.add('btn-success');
        button.disabled = true;
        
        setTimeout(() => {
            button.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Add to Cart';
            button.classList.remove('btn-success');
            button.disabled = false;
        }, 2000);
    }
};

// Initialize animations
document.addEventListener('DOMContentLoaded', () => {
    initProductCards();
    initCategoryCards();
    animateOnScroll();
    window.addEventListener('scroll', animateOnScroll);
    
    // Initialize cart count
    updateCartCount(cartCount);
});

// Make addToCart function available globally
window.addToCart = addToCart;

// Filter animation
window.filterProducts = (category) => {
    const products = document.querySelectorAll('.product-card');
    products.forEach(product => {
        if (category === 'all' || product.dataset.category === category) {
            product.style.display = 'block';
            product.classList.add('animate-fade-in');
        } else {
            product.style.display = 'none';
            product.classList.remove('animate-fade-in');
        }
    });
};

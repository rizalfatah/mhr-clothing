import './bootstrap';

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Scroll to top button
    const scrollToTopBtn = createScrollToTopButton();
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
            scrollToTopBtn.classList.add('opacity-100');
        } else {
            scrollToTopBtn.classList.add('opacity-0', 'pointer-events-none');
            scrollToTopBtn.classList.remove('opacity-100');
        }
    });

    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card-hover, .section-padding > div').forEach(el => {
        observer.observe(el);
    });

    // Search functionality
    setupSearch();

    // Cart functionality
    setupCart();
});

// Create Scroll to Top Button
function createScrollToTopButton() {
    const button = document.createElement('button');
    button.innerHTML = `
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    `;
    button.className = 'fixed bottom-8 right-8 z-50 w-12 h-12 bg-gray-900 text-white rounded-full shadow-lg hover:bg-gray-800 transition opacity-0 pointer-events-none flex items-center justify-center';
    button.setAttribute('aria-label', 'Scroll to top');
    button.onclick = () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    };
    document.body.appendChild(button);
    return button;
}

// Search Setup
function setupSearch() {
    const searchButtons = document.querySelectorAll('[aria-label="Search"], .search-toggle');
    searchButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            // Toggle search modal or input
            console.log('Search clicked');
            // You can implement a search modal here
        });
    });
}

// Cart Setup
function setupCart() {
    // Add to cart buttons
    document.addEventListener('click', (e) => {
        if (e.target.closest('.add-to-cart') || e.target.closest('button')?.textContent.includes('Add to Cart')) {
            const button = e.target.closest('button');
            if (button) {
                // Animate button
                button.classList.add('scale-95');
                setTimeout(() => {
                    button.classList.remove('scale-95');
                    showNotification('Product added to cart!');
                    updateCartCount();
                }, 200);
            }
        }
    });
}

// Show Notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-8 z-50 px-6 py-4 rounded-lg shadow-lg text-white transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Update Cart Count
function updateCartCount() {
    const cartBadges = document.querySelectorAll('.cart-count, [class*="cart"] span.absolute');
    cartBadges.forEach(badge => {
        const currentCount = parseInt(badge.textContent) || 0;
        badge.textContent = currentCount + 1;
        badge.classList.add('scale-125');
        setTimeout(() => {
            badge.classList.remove('scale-125');
        }, 300);
    });
}

// Export functions for use in other modules
export { showNotification, updateCartCount };

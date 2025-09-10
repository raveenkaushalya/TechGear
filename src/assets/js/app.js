// Combined application logic for TechGear Shop
// -------------------------------- Products Data --------------------------------
const products = [
    { id: 'k1', name: 'Mechanical Keyboard', description: 'RGB Backlit Mechanical Gaming Keyboard with Blue Switches', price: 89.99, image: '../assets/images/k1.jpg', category: 'keyboards', featured: true },
    { id: 'k2', name: 'Wireless Mechanical Keyboard', description: 'Low-latency Wireless Mechanical Keyboard with Brown Switches', price: 129.99, image: '../assets/images/k2.jpg', category: 'keyboards' },
    { id: 'k3', name: 'Compact 60% Keyboard', description: 'Compact 60% Layout Mechanical Keyboard with PBT Keycaps', price: 79.99, image: '../assets/images/k3.jpg', category: 'keyboards' },
    { id: 'k4', name: 'Premium Mechanical Keyboard', description: 'Premium Aluminum Frame Keyboard with Hot-swappable Switches', price: 149.99, image: '../assets/images/k4.jpg', category: 'keyboards' },
    { id: 'k5', name: 'Silent Mechanical Keyboard', description: 'Ultra-quiet Mechanical Keyboard for Office and Gaming', price: 99.99, image: '../assets/images/k5.jpg', category: 'keyboards' },
    { id: 'm1', name: 'Cyberpunk Edition Mouse', description: 'Exclusive Cyberpunk RGB Wireless Gaming Mouse – Only while stocks last!', price: 129.99, image: '../assets/images/m1-cyberpunk.jpg', category: 'mice', featured: true, limited: true },
    { id: 'm2', name: 'Wireless Gaming Mouse', description: 'Ultra-lightweight Wireless Gaming Mouse with 20,000 DPI Sensor', price: 69.99, image: '../assets/images/m2.jpg', category: 'mice' },
    { id: 'm3', name: 'Ergonomic Mouse', description: 'Vertical Ergonomic Mouse for Reduced Wrist Strain', price: 49.99, image: '../assets/images/m3.jpg', category: 'mice' },
    { id: 'm4', name: 'MMO Gaming Mouse', description: 'MMO Gaming Mouse with 12 Programmable Side Buttons', price: 79.99, image: '../assets/images/m4.jpg', category: 'mice' },
    { id: 'm5', name: 'Premium Gaming Mouse', description: 'Ultralight Gaming Mouse with PTFE Feet and Paracord Cable', price: 89.99, image: '../assets/images/m5.jpg', category: 'mice' },
    { id: 'm6', name: 'Classic Mouse', description: 'Reliable Wired Mouse for Everyday Use', price: 29.99, image: '../assets/images/m6.jpg', category: 'mice' },
    { id: 'mn1', name: 'Gaming Monitor', description: '27-inch 144Hz Gaming Monitor with 1ms Response Time', price: 299.99, image: '../assets/images/mn1.jpg', category: 'monitors', featured: true },
    { id: 'mn2', name: 'Ultrawide Monitor', description: '34-inch Curved Ultrawide Monitor with 21:9 Aspect Ratio', price: 449.99, image: '../assets/images/mn2.jpg', category: 'monitors' },
    { id: 'mn3', name: '4K Professional Monitor', description: '32-inch 4K Professional Monitor with 99% Adobe RGB Coverage', price: 599.99, image: '../assets/images/mn3.jpg', category: 'monitors' },
    { id: 'mn4', name: '240Hz Esports Monitor', description: '24.5-inch 1080p 240Hz TN Monitor for Competitive Gaming', price: 349.99, image: '../assets/images/mn4.jpg', category: 'monitors' },
    { id: 'mn5', name: 'Budget Gaming Monitor', description: '24-inch 1080p 75Hz Monitor with FreeSync Technology', price: 179.99, image: '../assets/images/mn5.jpg', category: 'monitors' },
    { id: 'h1', name: 'Wireless Gaming Headset', description: 'Low-latency Wireless Gaming Headset with 7.1 Surround Sound', price: 129.99, image: '../assets/images/h1.jpg', category: 'headphones', featured: true },
    { id: 'h2', name: 'Studio Headphones', description: 'Professional Studio Monitoring Headphones with Flat Response', price: 149.99, image: '../assets/images/h2.jpg', category: 'headphones' },
    { id: 'h3', name: 'Noise Cancelling Headphones', description: 'Wireless Noise Cancelling Headphones with 30-hour Battery Life', price: 199.99, image: '../assets/images/h3.jpg', category: 'headphones' },
    { id: 'h4', name: 'Budget Gaming Headset', description: 'Affordable Gaming Headset with RGB Lighting and Microphone', price: 49.99, image: '../assets/images/h4.jpg', category: 'headphones' }
];

// ----------------------------- Components Loader -----------------------------
function loadComponents() {
    console.log("Loading components...");
    
    // Highlight active navigation link (for the embedded header)
    const current = window.location.pathname.split('/').pop();
    document.querySelectorAll('nav ul li a').forEach(a => {
        const href = a.getAttribute('href');
        const hrefPage = href ? href.split('/').pop() : '';
        a.classList.toggle('active', hrefPage === current);
    });
    updateCartIcon();
    
    // Load product modal only
    fetch('/ShoppingAPP/src/components/product-modal.html')
        .then(r => {
            if (!r.ok) throw new Error(`Failed to load product modal: ${r.status} ${r.statusText}`);
            return r.text();
        })
        .then(html => {
            console.log("Product modal loaded successfully");
            const placeholder = document.getElementById('product-modal-placeholder');
            if (!placeholder) {
                console.error("Product modal placeholder not found");
                return;
            }
            placeholder.innerHTML = html;
            setupProductModal();
        })
        .catch(err => {
            console.error("Error loading product modal:", err);
            const placeholder = document.getElementById('product-modal-placeholder');
            if (placeholder) {
                placeholder.innerHTML = `<div style="color:red;padding:20px;">Error loading product modal: ${err.message}</div>`;
            }
        });
}

// ----------------------------- Modal Logic -----------------------------
function setupProductModal() {
    const modal = document.getElementById('product-modal');
    if (!modal) return;
    
    const modalClose = modal.querySelector('.modal-close');
    modalClose?.addEventListener('click', () => { closeModal(); });
    window.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    window.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    // Tabs
    const tabHeader = modal.querySelectorAll('.tab-header div');
    const tabContent = modal.querySelectorAll('.tab-body');
    tabHeader.forEach((tab, i) => tab.addEventListener('click', () => {
        tabHeader.forEach(t => t.classList.remove('active'));
        tabContent.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        tabContent[i].classList.add('active');
    }));

    // Quantity
    const plus = modal.querySelector('.quantity-btn.plus');
    const minus = modal.querySelector('.quantity-btn.minus');
    const input = modal.querySelector('.quantity-input');
    plus?.addEventListener('click', () => { 
        if (input.value < input.max) input.value = Number(input.value) + 1; 
    });
    minus?.addEventListener('click', () => { 
        if (input.value > input.min) input.value = Number(input.value) - 1; 
    });

    // Prevent non-numeric input in quantity field
    input?.addEventListener('input', () => {
        if (isNaN(input.value) || input.value < 1) input.value = 1;
        if (input.value > 10) input.value = 10;
    });

    modal.querySelector('.modal-add-to-cart')?.addEventListener('click', () => {
        const pid = modal.querySelector('.modal-product')?.dataset.productId;
        const qty = Number(input.value) || 1;
        if (pid) addToCart(pid, qty);
        closeModal();
    });
}

function closeModal() {
    const modal = document.getElementById('product-modal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        document.body.style.paddingRight = ''; // Reset padding for scrollbar
    }
}

function openProductModal(product) {
    const modal = document.getElementById('product-modal');
    if (!modal) return;
    
    // Prevent background scrolling when modal is open
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    document.body.style.overflow = 'hidden';
    document.body.style.paddingRight = `${scrollbarWidth}px`;
    
    const wrap = modal.querySelector('.modal-product');
    wrap.dataset.productId = product.id;
    document.getElementById('modal-product-image').src = product.image;
    document.getElementById('modal-product-name').textContent = product.name;
    document.getElementById('modal-product-description').textContent = product.description;
    document.getElementById('modal-product-price').textContent = `$${product.price.toFixed(2)}`;
    const qty = modal.querySelector('.quantity-input');
    if (qty) qty.value = 1;
    modal.style.display = 'block';
    
    // Set focus to close button for accessibility
    setTimeout(() => modal.querySelector('.modal-close')?.focus(), 100);
}

// ----------------------------- Product Cards -----------------------------
function createProductCard(p) {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.dataset.productId = p.id;
    if (p.limited) { card.classList.add('limited-edition'); }
    card.innerHTML = `
        <div class="product-image"><img src="${p.image}" alt="${p.name}" loading="lazy"></div>
        <div class="product-info">
            <h3>${p.name} ${p.limited ? '<span class="limited-edition-label">(Limited Edition)</span>' : ''}</h3>
            <p class="product-description">${p.description}</p>
            <p class="product-price">$${p.price.toFixed(2)}</p>
            <button class="btn-add-to-cart" aria-label="Add ${p.name} to cart">Add to Cart</button>
        </div>`;
    // Only add click event for opening the modal, not for add-to-cart
    card.addEventListener('click', e => { 
        if (!e.target.classList.contains('btn-add-to-cart')) openProductModal(p); 
    });
    return card;
}

function loadProducts() {
    const map = {
        featured: '.featured-products .product-grid',
        keyboards: '#keyboards .product-grid',
        mice: '#mice .product-grid',
        monitors: '#monitors .product-grid',
        headphones: '#headphones .product-grid'
    };
    
    if (document.querySelector(map.featured)) {
        products.filter(p => p.featured).forEach(p => {
            document.querySelector(map.featured).appendChild(createProductCard(p));
        });
    }
    
    ['keyboards','mice','monitors','headphones'].forEach(cat => {
        const el = document.querySelector(map[cat]);
        if (el) {
            products.filter(p => p.category === cat).forEach(p => el.appendChild(createProductCard(p)));
        }
    });
}

function setupStaticProductCards() {
    document.querySelectorAll('.product-card').forEach(card => {
        if (card.getAttribute('data-listeners-added')) return;
        const id = card.dataset.productId;
        if (!id) return;
        const product = products.find(p => p.id === id);
        const data = product || (() => {
            const name = card.querySelector('h3')?.textContent.trim();
            const desc = card.querySelector('.product-description')?.textContent.trim();
            const price = parseFloat(card.querySelector('.product-price')?.textContent.replace('$','') || '0');
            const image = card.querySelector('.product-image img')?.src;
            return { id, name, description: desc, price, image };
        })();
        // Only add click event for opening the modal, not for add-to-cart
        card.addEventListener('click', e => { 
            if (!e.target.classList.contains('btn-add-to-cart')) openProductModal(data); 
        });
        card.setAttribute('data-listeners-added', 'true');
    });
}

// ----------------------------- Cart Logic -----------------------------
function getCart() { 
    try {
        return JSON.parse(localStorage.getItem('cart')) || []; 
    } catch (e) {
        console.error("Error reading cart from localStorage:", e);
        return [];
    }
}

function saveCart(c) { 
    try {
        localStorage.setItem('cart', JSON.stringify(c)); 
        updateCartIcon();
        if (document.body.classList.contains('cart-page')) {
            renderCartPage();
        }
    } catch (e) {
        console.error("Error saving cart to localStorage:", e);
        showNotification("Error saving cart data", "error");
    }
}

function addToCart(id, qty=1) {
    const c = getCart();
    const p = products.find(p => p.id === id);
    if (!p) return console.error('Product not found');
    
    const existing = c.find(i => i.id === id);
    if (existing) {
        existing.quantity += qty;
    } else {
        c.push({ 
            id, 
            name: p.name, 
            price: p.price, 
            image: p.image, 
            quantity: qty 
        });
    }
    
    saveCart(c); 
    showNotification(`${p.name} added to cart.`);
}

function updateCartIcon() {
    const count = getCart().reduce((s, i) => s + i.quantity, 0);
    const cartLinks = document.querySelectorAll('.cart a');
    cartLinks.forEach(link => {
        link.innerHTML = `<i class="fas fa-shopping-cart" aria-hidden="true"></i> Cart (${count})`;
    });
}

function showNotification(msg, type = "success") {
    let container = document.getElementById('notification-container');
    if (!container) { 
        container = document.createElement('div'); 
        container.id = 'notification-container'; 
        document.body.appendChild(container);
    }    
    
    const note = document.createElement('div');
    note.className = `notification ${type}`;
    
    const icon = type === "error" ? "fa-exclamation-circle" : "fa-check-circle";
    note.innerHTML = `<i class="fas ${icon}"></i><p>${msg}</p>`;
    container.appendChild(note);
    
    setTimeout(() => { 
        note.classList.add('fade-out'); 
        setTimeout(() => note.remove(), 500); 
    }, 3000);
}

// Cart page specific
function renderCartPage() {
    if (!document.body.classList.contains('cart-page')) return;
    
    const cart = getCart();
    const list = document.querySelector('.cart-items-list');
    const empty = document.querySelector('.empty-cart-message');
    const layout = document.querySelector('.cart-layout');
    
    if (!list) return;
    
    if (cart.length === 0) { 
        if (empty) empty.classList.add('active'); 
        if (layout) layout.style.display = 'none'; 
        return; 
    }
    
    if (empty) empty.classList.remove('active'); 
    if (layout) layout.style.display = 'grid';
    
    list.innerHTML = '';
    cart.forEach(item => {
        const el = document.createElement('div');
        el.className = 'cart-item';
        el.innerHTML = `
            <div class="cart-item-image">
                <img src="${item.image}" alt="${item.name}" loading="lazy">
            </div>
            <div class="cart-item-details">
                <h3>${item.name}</h3>
                <p class="cart-item-price">$${item.price.toFixed(2)}</p>
                <div class="quantity-selector">
                    <button class="quantity-btn minus" data-id="${item.id}" aria-label="Decrease quantity">-</button>
                    <input type="number" value="${item.quantity}" min="1" class="quantity-input" data-id="${item.id}" aria-label="Quantity">
                    <button class="quantity-btn plus" data-id="${item.id}" aria-label="Increase quantity">+</button>
                </div>
            </div>
            <div class="cart-item-total">
                <p>$${(item.price * item.quantity).toFixed(2)}</p>
                <button class="remove-item-btn" data-id="${item.id}" aria-label="Remove ${item.name} from cart">&times;</button>
            </div>`;
        list.appendChild(el);
    });
    
    updateCartSummary(cart);
    addCartEventListeners();
}

function updateCartSummary(cart) {
    const subtotal = cart.reduce((s, i) => s + (i.price * i.quantity), 0);
    const tax = subtotal * 0.07; 
    const total = subtotal + tax;
    
    const set = (id, val) => { 
        const el = document.getElementById(id); 
        if (el) el.textContent = val; 
    };
    
    set('summary-subtotal', `$${subtotal.toFixed(2)}`);
    set('summary-tax', `$${tax.toFixed(2)}`);
    set('summary-total', `$${total.toFixed(2)}`);
}

function addCartEventListeners() {
    const list = document.querySelector('.cart-items-list'); 
    if (!list) return;
    
    list.onclick = e => { 
        const id = e.target.dataset.id; 
        if (!id) return;
        
        if (e.target.classList.contains('remove-item-btn')) {
            removeFromCart(id);
        } else if (e.target.classList.contains('plus')) {
            updateQuantity(id, 1);
        } else if (e.target.classList.contains('minus')) {
            updateQuantity(id, -1);
        }
    };
    
    list.onchange = e => { 
        const id = e.target.dataset.id; 
        if (!id) return;
        
        if (e.target.classList.contains('quantity-input')) {
            const q = parseInt(e.target.value);
            if (q > 0) {
                updateQuantity(id, q, true);
            } else {
                removeFromCart(id);
            }
        }
    };
}

function removeFromCart(id) { 
    const c = getCart().filter(i => i.id !== id); 
    saveCart(c); 
}

function updateQuantity(id, change, absolute = false) { 
    const c = getCart(); 
    const item = c.find(i => i.id === id); 
    if (!item) return;
    
    if (absolute) {
        item.quantity = change;
    } else {
        item.quantity += change;
    }
    
    if (item.quantity <= 0) {
        removeFromCart(id);
    } else {
        saveCart(c);
    }
}

// ----------------------------- Global Events -----------------------------
function setupGlobalEventHandlers() {
    // Use a flag to prevent duplicate add-to-cart events
    window.cartEventHandlersAdded = window.cartEventHandlersAdded || false;
    
    if (!window.cartEventHandlersAdded) {
        document.addEventListener('click', e => {
            if (e.target.classList.contains('btn-add-to-cart')) {
                const card = e.target.closest('.product-card');
                if (card?.dataset.productId) { 
                    addToCart(card.dataset.productId, 1); 
                    e.stopPropagation(); 
                }
            }
            
            if (e.target.classList.contains('continue-shopping')) { 
                window.location.href = 'categories.php'; 
            }
            
            if (e.target.classList.contains('checkout-btn')) {
                // Check if the user is logged in
                if (typeof isUserLoggedIn === 'function' && !isUserLoggedIn()) {
                    // Show notification that login is required
                    if (typeof showLoginNotification === 'function') {
                        showLoginNotification('Please login to proceed to checkout');
                    } else {
                        alert('Please login to proceed to checkout');
                    }
                    
                    // Save current page URL to redirect back after login
                    localStorage.setItem('checkoutRedirect', window.location.href);
                    
                    // Redirect to login page
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 1500);
                } else {
                    // User is logged in, proceed with checkout
                    alert('Checkout functionality would be implemented here!');
                }
            }
        });
        
        window.cartEventHandlersAdded = true;
    }
    
    // Add keyboard navigation for modal
    document.addEventListener('keydown', e => {
        const modal = document.getElementById('product-modal');
        if (modal && modal.style.display === 'block') {
            if (e.key === 'Escape') {
                closeModal();
            }
            
            // Trap focus inside modal
            if (e.key === 'Tab') {
                const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        lastElement.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        firstElement.focus();
                        e.preventDefault();
                    }
                }
            }
        }
    });
}

// ----------------------------- Init -----------------------------
document.addEventListener('DOMContentLoaded', () => {
    // Check if we're on a page with embedded components
    const hasEmbeddedHeader = document.querySelector('header:not(#product-modal header)');
    
    if (!hasEmbeddedHeader) {
        loadComponents();
    } else {
        // Update cart icon for pages with embedded header
        updateCartIcon();
    }
    
    loadProducts();
    setupStaticProductCards();
    setupGlobalEventHandlers();
    renderCartPage();
    
    // Add a small delay to ensure all elements are properly initialized
    setTimeout(() => {
        const loadingIndicator = document.getElementById('loading-indicator');
        if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
        }
    }, 300);
});

// Add a simple loading indicator at the very beginning
document.write(`
    <div id="loading-indicator" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--dark-color);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.3s;
    ">
        <div style="color: var(--primary-color); font-size: 24px;">Loading TechGear...</div>
    </div>
`);
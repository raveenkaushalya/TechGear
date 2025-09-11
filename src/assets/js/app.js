// Combined application logic for TechGear Shop
// -------------------------------- Products Data --------------------------------
// Products will be loaded dynamically from the database API
let products = [];

// Function to load products from API
async function fetchProductsFromAPI() {
    try {
        // Use absolute path to ensure it works from any page
        const apiUrl = '/TechGear/src/admin/api/products.php?status=active';
        
        const response = await fetch(apiUrl);
        
        const json = await response.json();
        
        if (json.success) {
            products = json.data || [];
            return products;
        } else {
            console.error('API Error:', json.error);
            return [];
        }
    } catch (error) {
        console.error('Failed to fetch products:', error);
        return [];
    }
}

// ----------------------------- Components Loader -----------------------------
function loadComponents() {
    
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
    
    // Ensure price is a number
    const price = parseFloat(p.price) || 0;
    
    // Handle different image path formats
    let imageSrc = p.image || '';
    if (imageSrc && !imageSrc.startsWith('http')) {
        // Ensure relative paths work from pages directory
        if (imageSrc.startsWith('assets/')) {
            imageSrc = '../' + imageSrc;
        } else if (imageSrc.startsWith('src/assets/')) {
            imageSrc = imageSrc.replace('src/assets/', '../assets/');
        } else if (imageSrc.startsWith('uploads/')) {
            imageSrc = '../' + imageSrc;
        }
        // imageSrc already starting with ../ should work fine (this includes ../uploads/)
    }
    
    card.innerHTML = `
        <div class="product-image"><img src="${imageSrc}" alt="${p.name}" loading="lazy" onerror="this.src='../assets/images/placeholder.jpg'"></div>
        <div class="product-info">
            <h3>${p.name} ${p.limited ? '<span class="limited-edition-label">(Limited Edition)</span>' : ''}</h3>
            <p class="product-description">${p.description || ''}</p>
            <p class="product-price">$${price.toFixed(2)}</p>
            <button class="btn-add-to-cart" ${p.quantity <= 0 ? 'disabled' : ''} aria-label="Add ${p.name} to cart">
                ${p.quantity > 0 ? 'Add to Cart' : 'Out of Stock'}
            </button>
        </div>`;
    
    // Only add click event for opening the modal, not for add-to-cart
    card.addEventListener('click', e => { 
        if (!e.target.classList.contains('btn-add-to-cart')) openProductModal(p); 
    });
    
    // Note: Add to cart is handled by global event listener
    
    return card;
}

async function loadProducts() {
    // First fetch products from API
    await fetchProductsFromAPI();
    
    // Determine which page we're on
    const isHomePage = window.location.pathname.includes('index.php') || window.location.pathname === '/' || window.location.pathname.endsWith('/TechGear/');
    const isCategoriesPage = window.location.pathname.includes('categories.php');
    
    const map = {
        featured: '.featured-products .product-grid',
        keyboards: '#keyboards .product-grid',
        mice: '#mice .product-grid',
        monitors: '#monitors .product-grid',
        headphones: '#headphones .product-grid'
    };
    
    // Also check for categories page structure with data-category attributes
    const categoryMap = {
        keyboards: '[data-category="keyboards"]',
        mice: '[data-category="mice"]',
        monitors: '[data-category="monitors"]',
        headphones: '[data-category="headphones"]'
    };
    
    // Clear existing content for the appropriate page
    if (isHomePage) {
        // Clear home page elements
        Object.values(map).forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.innerHTML = '';
            }
        });
    } else if (isCategoriesPage) {
        // Clear categories page elements
        Object.values(categoryMap).forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.innerHTML = '';
            }
        });
    }
    
    // Load featured products if featured section exists (home page only)
    if (isHomePage && document.querySelector(map.featured)) {
        // Since database products don't have featured flag, show first few products as featured
        const featuredProducts = products.slice(0, 4);
        featuredProducts.forEach(p => {
            document.querySelector(map.featured).appendChild(createProductCard(p));
        });
    }
    
    // Load products by category
    ['keyboards','mice','monitors','headphones'].forEach(cat => {
        const categoryProducts = products.filter(p => p.category === cat);
        
        if (isHomePage) {
            // Home page structure
            const homeEl = document.querySelector(map[cat]);
            if (homeEl) {
                categoryProducts.forEach(p => homeEl.appendChild(createProductCard(p)));
            }
        } else if (isCategoriesPage) {
            // Categories page structure
            const categoryEl = document.querySelector(categoryMap[cat]);
            if (categoryEl) {
                categoryProducts.forEach(p => categoryEl.appendChild(createProductCard(p)));
            }
        }
    });
}

function setupStaticProductCards() {
    // No longer setting up static product cards - only using database products
    // This function is kept for backward compatibility but does nothing
    return;
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
    // Convert id to string for consistent comparison since database IDs are numeric
    const productId = String(id);
    const p = products.find(p => String(p.id) === productId);
    if (!p) return console.error('Product not found:', id);
    
    // Check if product is in stock
    if (p.quantity <= 0) {
        showNotification(`${p.name} is out of stock!`, 'error');
        return;
    }
    
    const existing = c.find(i => String(i.id) === productId);
    const currentCartQuantity = existing ? existing.quantity : 0;
    
    // Check if adding more would exceed available stock
    if (currentCartQuantity + qty > p.quantity) {
        showNotification(`Only ${p.quantity} ${p.name} available in stock!`, 'error');
        return;
    }
    
    if (existing) {
        existing.quantity += qty;
    } else {
        c.push({ 
            id: productId, 
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

// Process checkout - update inventory and clear cart
async function processCheckout() {
    const cart = getCart();
    
    if (cart.length === 0) {
        showNotification('Your cart is empty!', 'error');
        return;
    }
    
    let allSuccessful = true;
    const errors = [];
    
    // Process each item in the cart
    for (const item of cart) {
        try {
            const formData = new FormData();
            formData.append('action', 'purchase');
            formData.append('id', item.id);
            formData.append('quantity', item.quantity);
            
            const response = await fetch('/TechGear/src/admin/api/products.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (!result.success) {
                allSuccessful = false;
                errors.push(`${item.name}: ${result.error}`);
            }
        } catch (error) {
            allSuccessful = false;
            errors.push(`${item.name}: Network error`);
        }
    }
    
    if (allSuccessful) {
        // Clear cart and show success message
        saveCart([]);
        updateCartIcon();
        showNotification('Purchase successful! Thank you for your order.');
        
        // Redirect to a success page or refresh the cart page
        if (window.location.pathname.includes('cart.php')) {
            renderCartPage();
        }
    } else {
        // Show errors
        const errorMessage = 'Some items could not be purchased:\n' + errors.join('\n');
        showNotification(errorMessage, 'error');
    }
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
                window.location.href = '/TechGear/src/pages/categories.php'; 
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
                        window.location.href = '/TechGear/src/pages/login.php';
                    }, 1500);
                } else {
                    // User is logged in, proceed with checkout
                    processCheckout();
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
    
    // Only load products if not already loaded by the page-specific script
    if (!window.productsLoadedByPage) {
        loadProducts();
    }
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
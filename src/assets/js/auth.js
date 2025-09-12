// Authentication related functions

// Check if user is logged in by making a server request
async function isUserLoggedIn() {
    try {
        const response = await fetch('/TechGear/src/pages/check_auth.php');
        const data = await response.json();
        return data.logged_in === true;
    } catch (error) {
        console.error('Error checking authentication:', error);
        // Fallback to localStorage for offline functionality
        return localStorage.getItem('userLoggedIn') === 'true';
    }
}

// Function to handle successful login
function loginUser(username, fullname = null) {
    // Store in localStorage for immediate UI updates
    localStorage.setItem('userLoggedIn', 'true');
    localStorage.setItem('username', username);
    if (fullname) {
        localStorage.setItem('fullname', fullname);
    }
    
    // Update UI elements if they exist
    updateAuthUI(true, username, fullname);
}

// Function to handle logout
async function logoutUser() {
    try {
        // Make server request to destroy session
        const response = await fetch('/TechGear/src/pages/logout.php', {
            method: 'POST'
        });
        
        if (response.ok) {
            // Clear localStorage
            localStorage.removeItem('userLoggedIn');
            localStorage.removeItem('username');
            localStorage.removeItem('fullname');
            
            // Update UI
            updateAuthUI(false);
            
            // Redirect to home page
            window.location.href = '/TechGear/index.php';
        }
    } catch (error) {
        console.error('Error during logout:', error);
        // Fallback: clear localStorage anyway
        localStorage.removeItem('userLoggedIn');
        localStorage.removeItem('username');
        localStorage.removeItem('fullname');
        updateAuthUI(false);
    }
}

// Function to update authentication UI elements
function updateAuthUI(isLoggedIn, username = null, fullname = null) {
    // Get the navbar sections
    const loggedOutSection = document.getElementById('logged-out-section');
    const loggedInSection = document.getElementById('logged-in-section');
    const usernameDisplay = document.getElementById('username-display');
    
    if (isLoggedIn && username) {
        // Show logged in state - hide login link, show username dropdown
        if (loggedOutSection) {
            loggedOutSection.classList.add('hidden');
        }
        if (loggedInSection) {
            loggedInSection.classList.remove('hidden');
        }
        if (usernameDisplay) {
            usernameDisplay.textContent = fullname || username;
        }
    } else {
        // Show logged out state - show login link, hide username dropdown
        if (loggedOutSection) {
            loggedOutSection.classList.remove('hidden');
        }
        if (loggedInSection) {
            loggedInSection.classList.add('hidden');
        }
    }
}

// Function to show notification
function showLoginNotification(message, type = 'error') {
    // Create a notification container if it doesn't exist
    let container = document.getElementById('notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            max-width: 400px;
        `;
        document.body.appendChild(container);
    }
    
    // Create the notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.style.cssText = `
        background: ${type === 'error' ? '#dc3545' : type === 'success' ? '#28a745' : '#007bff'};
        color: white;
        padding: 15px 20px;
        margin-bottom: 10px;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        animation: slideIn 0.3s ease-out;
        display: flex;
        align-items: center;
        gap: 10px;
    `;
    
    notification.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Add it to the container
    container.appendChild(notification);
    
    // Remove it after a delay
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
    
    // Add CSS animations if not already added
    if (!document.getElementById('auth-notification-styles')) {
        const style = document.createElement('style');
        style.id = 'auth-notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
}

// Initialize authentication state on page load
document.addEventListener('DOMContentLoaded', async function() {
    try {
        // Check authentication status from server
        const response = await fetch('/TechGear/src/pages/check_auth.php');
        const authData = await response.json();
        
        let loggedIn = false;
        let username = null;
        let fullname = null;
        
        if (authData.logged_in && authData.user_data) {
            loggedIn = true;
            username = authData.user_data.username;
            fullname = authData.user_data.fullname;
            
            // Update localStorage for consistency
            localStorage.setItem('userLoggedIn', 'true');
            localStorage.setItem('username', username);
            localStorage.setItem('fullname', fullname);
        } else {
            // Not logged in, clear localStorage
            localStorage.removeItem('userLoggedIn');
            localStorage.removeItem('username');
            localStorage.removeItem('fullname');
        }
        
        // Update the UI
        updateAuthUI(loggedIn, username, fullname);
        
    } catch (error) {
        console.error('Error checking authentication:', error);
        
        // Fallback to localStorage
        const loggedIn = localStorage.getItem('userLoggedIn') === 'true';
        const username = localStorage.getItem('username');
        const fullname = localStorage.getItem('fullname');
        
        updateAuthUI(loggedIn, username, fullname);
    }
    
    // Set up logout handlers
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('logout-link') || e.target.closest('.logout-link')) {
            e.preventDefault();
            logoutUser();
        }
    });
});

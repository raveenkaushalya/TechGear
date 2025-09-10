// Authentication related functions

// Check if user is logged in
function isUserLoggedIn() {
    // For demonstration purposes, we'll use localStorage
    // In a real application, this would check server-side sessions
    return localStorage.getItem('userLoggedIn') === 'true';
}

// Function to simulate login (for demonstration)
function loginUser(username) {
    localStorage.setItem('userLoggedIn', 'true');
    localStorage.setItem('username', username);
}

// Function to simulate logout (for demonstration)
function logoutUser() {
    localStorage.removeItem('userLoggedIn');
    localStorage.removeItem('username');
}

// Function to show notification
function showLoginNotification(message, type = 'error') {
    // Create a notification container if it doesn't exist
    let container = document.getElementById('notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        document.body.appendChild(container);
    }
    
    // Create the notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
        <p>${message}</p>
    `;
    
    // Add it to the container
    container.appendChild(notification);
    
    // Remove it after a delay
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}

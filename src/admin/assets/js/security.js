/**
 * Security enhancements for admin pages
 */

// Prevent browser back button after logout
(function() {
    // This prevents going back to protected pages after logout
    window.history.pushState(null, null, window.location.href);
    
    window.addEventListener('popstate', function() {
        window.history.pushState(null, null, window.location.href);
    });
    
    // Detect when the page is about to be unloaded (e.g., closing tab or navigating away)
    window.addEventListener('beforeunload', function(e) {
        // This helps prevent caching when navigating away
        // No confirmation dialog will appear (empty string)
        e.preventDefault();
        e.returnValue = '';
    });
})();

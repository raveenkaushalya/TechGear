/**
 * Bootstrap Integration for IndigoFlow
 * 
 * Enhances the UI with Bootstrap components where needed
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize Bootstrap components
  initializeBootstrapTooltips();
  initializeBootstrapPopovers();
  initializeBootstrapToasts();
  initializeBootstrapModals();
});

/**
 * Initialize Bootstrap tooltips
 */
function initializeBootstrapTooltips() {
  if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
  }
}

/**
 * Initialize Bootstrap popovers
 */
function initializeBootstrapPopovers() {
  if (typeof bootstrap !== 'undefined' && bootstrap.Popover) {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
  }
}

/**
 * Initialize Bootstrap toasts
 */
function initializeBootstrapToasts() {
  if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
    const toastElList = document.querySelectorAll('.toast');
    [...toastElList].map(toastEl => new bootstrap.Toast(toastEl));
  }
}

/**
 * Initialize Bootstrap modals
 */
function initializeBootstrapModals() {
  if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
    // Bootstrap modals can be initialized manually if needed
    // or they can work with data attributes
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(trigger => {
      trigger.addEventListener('click', function() {
        const targetSelector = this.getAttribute('data-bs-target');
        if (targetSelector) {
          const modalElement = document.querySelector(targetSelector);
          if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
          }
        }
      });
    });
  }
}

/**
 * Show a Bootstrap toast message
 * @param {string} message - The message to display
 * @param {string} type - The toast type (success, error, warning, info)
 */
function showToast(message, type = 'info') {
  if (typeof bootstrap === 'undefined' || !bootstrap.Toast) {
    // Fallback to alert if Bootstrap isn't available
    alert(message);
    return;
  }

  // Create toast element
  const toastElement = document.createElement('div');
  toastElement.className = `toast align-items-center text-white bg-${type} border-0`;
  toastElement.setAttribute('role', 'alert');
  toastElement.setAttribute('aria-live', 'assertive');
  toastElement.setAttribute('aria-atomic', 'true');
  
  toastElement.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">
        ${message}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  `;
  
  // Add to toast container or create one if it doesn't exist
  let toastContainer = document.querySelector('.toast-container');
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    document.body.appendChild(toastContainer);
  }
  
  toastContainer.appendChild(toastElement);
  
  // Initialize and show the toast
  const toast = new bootstrap.Toast(toastElement);
  toast.show();
  
  // Remove from DOM after hidden
  toastElement.addEventListener('hidden.bs.toast', function() {
    toastElement.remove();
  });
}

// Export functions for global use
window.showToast = showToast;

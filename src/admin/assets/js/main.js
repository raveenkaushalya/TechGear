/**
 * IndigoFlow Main JavaScript
 * 
 * Handles functionality that was previously managed by React
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize all components
  initializeSidebar();
  initializeDropdowns();
  initializeModals();
  initializeForms();
  initializeCharts();

  // Handle any page-specific initialization based on the current page
  const currentPath = window.location.pathname;
  if (currentPath.includes('products.php')) {
    initializeProductsPage();
  }
});

/**
 * Sidebar functionality
 */
function initializeSidebar() {
  const sidebarTrigger = document.querySelector('.sidebar-trigger');
  const sidebar = document.querySelector('.sidebar');
  
  if (sidebarTrigger && sidebar) {
    sidebarTrigger.addEventListener('click', function() {
      if (sidebar.getAttribute('data-collapsible') === 'icon') {
        sidebar.setAttribute('data-collapsible', 'expanded');
      } else {
        sidebar.setAttribute('data-collapsible', 'icon');
      }
    });
  }
  
  // Handle window resize for responsive sidebar
  window.addEventListener('resize', function() {
    if (window.innerWidth < 768 && sidebar) {
      sidebar.setAttribute('data-collapsible', 'icon');
    }
  });

  // Mark the active nav item based on current URL
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('.sidebar-menu-button a');
  
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (currentPath === href || (href !== '/' && currentPath.includes(href))) {
      link.closest('.sidebar-menu-button').classList.add('is-active');
    }
  });
}

/**
 * Dropdown menu functionality
 */
function initializeDropdowns() {
  const dropdownTriggers = document.querySelectorAll('.dropdown-menu-trigger');
  
  dropdownTriggers.forEach(trigger => {
    const dropdown = trigger.closest('.dropdown-menu').querySelector('.dropdown-menu-content');
    
    if (trigger && dropdown) {
      trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // Close all other open dropdowns first
        document.querySelectorAll('.dropdown-menu-content').forEach(content => {
          if (content !== dropdown && content.style.display !== 'none') {
            content.style.display = 'none';
          }
        });
        
        // Toggle current dropdown
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
      });
    }
  });
  
  // Close dropdowns when clicking outside
  document.addEventListener('click', function() {
    document.querySelectorAll('.dropdown-menu-content').forEach(dropdown => {
      dropdown.style.display = 'none';
    });
  });
  
  // Prevent dropdown content clicks from closing the dropdown
  document.querySelectorAll('.dropdown-menu-content').forEach(content => {
    content.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  });
}

/**
 * Modal dialog functionality
 */
function initializeModals() {
  // Setup modal triggers
  document.querySelectorAll('[data-modal-trigger]').forEach(trigger => {
    const targetId = trigger.getAttribute('data-modal-trigger');
    const modal = document.getElementById(targetId);
    
    if (trigger && modal) {
      trigger.addEventListener('click', function() {
        modal.style.display = 'flex';
      });
    }
  });
  
  // Setup modal close buttons
  document.querySelectorAll('[data-modal-close]').forEach(closeBtn => {
    const targetId = closeBtn.getAttribute('data-modal-close');
    const modal = document.getElementById(targetId);
    
    if (closeBtn && modal) {
      closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
      });
    }
  });
  
  // Close modal when clicking outside content
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  });
}

/**
 * Form handling functionality
 */
function initializeForms() {
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
      const formId = form.getAttribute('id');
      
      // Handle specific forms
      if (formId === 'product-form') {
        e.preventDefault();
        handleProductFormSubmit(form);
      }
    });
  });
}

/**
 * Chart initialization
 * Uses Chart.js if available
 */
function initializeCharts() {
  if (typeof Chart === 'undefined') {
    return; // Chart.js not loaded
  }
  
  // Initialize visitor trends chart if container exists
  const visitorChartContainer = document.getElementById('visitor-chart');
  if (visitorChartContainer) {
    const ctx = visitorChartContainer.getContext('2d');
    const visitorData = window.visitorData || [
      { month: 'Jan', visitors: 1300 },
      { month: 'Feb', visitors: 1500 },
      { month: 'Mar', visitors: 1400 },
      { month: 'Apr', visitors: 1800 },
      { month: 'May', visitors: 2000 },
      { month: 'Jun', visitors: 2300 }
    ];
    
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: visitorData.map(d => d.month),
        datasets: [{
          label: 'Visitors',
          data: visitorData.map(d => d.visitors),
          backgroundColor: 'hsl(var(--chart-1))',
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    });
  }
  
  // Initialize demographics pie chart if container exists
  const demographicsChartContainer = document.getElementById('demographics-chart');
  if (demographicsChartContainer) {
    const ctx = demographicsChartContainer.getContext('2d');
    const demographicData = window.demographicData || [
      { browser: 'Chrome', visitors: 65, fill: 'hsl(var(--chart-1))' },
      { browser: 'Safari', visitors: 23, fill: 'hsl(var(--chart-2))' },
      { browser: 'Firefox', visitors: 10, fill: 'hsl(var(--chart-3))' },
      { browser: 'Other', visitors: 2, fill: 'hsl(var(--chart-4))' }
    ];
    
    new Chart(ctx, {
      type: 'pie',
      data: {
        labels: demographicData.map(d => d.browser),
        datasets: [{
          data: demographicData.map(d => d.visitors),
          backgroundColor: demographicData.map(d => d.fill)
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    });
  }
}

/**
 * Product page specific functionality
 */
function initializeProductsPage() {
  // Product dialog functionality
  window.openProductDialog = function(productId = null) {
    const dialog = document.getElementById('product-dialog');
    const dialogTitle = document.getElementById('dialog-title');
    const form = document.getElementById('product-form');
    
    if (!dialog || !form) return;
    
    // Reset the form
    form.reset();
    
    if (productId) {
      // Edit mode - populate the form with product data
      dialogTitle.textContent = 'Edit Product';
      
      // Fetch product data and populate form
      const product = window.products ? window.products.find(p => p.id === productId) : null;
      if (product) {
        for (const field in product) {
          const input = form.querySelector(`[name="${field}"]`);
          if (input) {
            input.value = product[field];
          }
        }
      }
    } else {
      // Add mode
      dialogTitle.textContent = 'Add Product';
    }
    
    dialog.style.display = 'flex';
  };
  
  window.closeProductDialog = function() {
    const dialog = document.getElementById('product-dialog');
    if (dialog) {
      dialog.style.display = 'none';
    }
  };
  
  window.editProduct = function(productId) {
    window.openProductDialog(productId);
  };
  
  window.deleteProduct = function(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
      // In a real implementation, this would submit an AJAX request
      // For now, we'll just simulate removal from the DOM
      const row = document.querySelector(`tr[data-product-id="${productId}"]`);
      if (row) {
        row.remove();
      }
      
      // Close any open dropdown
      document.querySelectorAll('.dropdown-menu-content').forEach(dropdown => {
        dropdown.style.display = 'none';
      });
    }
  };
  
  window.toggleDropdown = function(productId) {
    const dropdown = document.getElementById(`dropdown-${productId}`);
    if (dropdown) {
      // Close all other open dropdowns first
      document.querySelectorAll('.dropdown-menu-content').forEach(content => {
        if (content !== dropdown && content.style.display !== 'none') {
          content.style.display = 'none';
        }
      });
      
      dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }
  };
  
  // Handle product form submission
  function handleProductFormSubmit(form) {
    const formData = new FormData(form);
    const productData = {};
    
    for (const [key, value] of formData.entries()) {
      productData[key] = value;
    }
    
    // For this example, we're just closing the dialog
    // In a real implementation, you'd send an AJAX request and handle the response
    window.closeProductDialog();
    
    // Optionally reload the page or update the UI directly
    // location.reload();
  }
}

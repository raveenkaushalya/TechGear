/**
 * Users Page JavaScript
 * Handles user management functionality
 */

(function() {
    'use strict';

    // Users namespace
    const UsersPage = {
        
        // Configuration
        config: {
            apiEndpoint: 'api/users.php',
            pageSize: 10,
            currentPage: 1
        },

        // Current user data for editing
        currentUser: null,

        // Initialize users page
        init: function() {
            this.setupEventListeners();
            this.initializeTooltips();
            this.loadUsers();
        },

        // Set up event listeners
        setupEventListeners: function() {
            // Add user button
            const addUserBtn = document.querySelector('[onclick="openUserDialog()"]');
            if (addUserBtn) {
                addUserBtn.removeAttribute('onclick');
                addUserBtn.addEventListener('click', this.openUserDialog.bind(this));
            }

            // User form submission
            const userForm = document.getElementById('user-form');
            if (userForm) {
                userForm.addEventListener('submit', this.handleUserSubmit.bind(this));
            }

            // Modal close buttons
            const closeButtons = document.querySelectorAll('[onclick="closeUserDialog()"]');
            closeButtons.forEach(btn => {
                btn.removeAttribute('onclick');
                btn.addEventListener('click', this.closeUserDialog.bind(this));
            });

            // Search functionality
            const searchInput = document.getElementById('user-search');
            if (searchInput) {
                searchInput.addEventListener('input', this.debounce(this.handleSearch.bind(this), 300));
            }

            // Filter buttons
            const filterButtons = document.querySelectorAll('[data-filter]');
            filterButtons.forEach(btn => {
                btn.addEventListener('click', this.handleFilter.bind(this));
            });

            // Pagination
            this.setupPagination();
        },

        // Initialize Bootstrap tooltips
        initializeTooltips: function() {
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        },

        // Load users data
        loadUsers: function(page = 1, search = '', filter = '') {
            this.showLoading(true);
            
            // Simulate API call (replace with actual API call)
            setTimeout(() => {
                const users = this.getMockUsers();
                this.renderUsers(users);
                this.updatePagination(users.length);
                this.showLoading(false);
            }, 500);
        },

        // Get mock users data (replace with actual API call)
        getMockUsers: function() {
            return [
                {
                    id: 1,
                    name: 'John Smith',
                    email: 'john.smith@example.com',
                    role: 'Admin',
                    status: 'Active',
                    lastLogin: '2025-09-09 14:32:15',
                    avatar: 'assets/images/users/user1.jpg'
                },
                {
                    id: 2,
                    name: 'Sarah Johnson',
                    email: 'sarah.j@example.com',
                    role: 'Editor',
                    status: 'Active',
                    lastLogin: '2025-09-08 10:15:42',
                    avatar: 'assets/images/users/user2.jpg'
                }
                // Add more mock users as needed
            ];
        },

        // Render users in table
        renderUsers: function(users) {
            const tbody = document.querySelector('.table-body');
            if (!tbody) return;

            tbody.innerHTML = '';

            users.forEach(user => {
                const row = this.createUserRow(user);
                tbody.appendChild(row);
            });

            // Reattach event listeners for new rows
            this.attachRowEventListeners();
        },

        // Create user table row
        createUserRow: function(user) {
            const row = document.createElement('tr');
            row.className = 'table-row';
            row.dataset.userId = user.id;

            const statusClass = this.getStatusClass(user.status);

            row.innerHTML = `
                <td class="table-cell hidden sm:table-cell">
                    <img
                        alt="${this.escapeHtml(user.name)} avatar"
                        class="rounded-full aspect-square object-cover"
                        height="40"
                        src="${this.escapeHtml(user.avatar)}"
                        width="40"
                        loading="lazy"
                    />
                </td>
                <td class="table-cell font-medium">${this.escapeHtml(user.name)}</td>
                <td class="table-cell">${this.escapeHtml(user.email)}</td>
                <td class="table-cell">${this.escapeHtml(user.role)}</td>
                <td class="table-cell">
                    <span class="${statusClass}">${this.escapeHtml(user.status)}</span>
                </td>
                <td class="table-cell hidden md:table-cell">
                    ${this.escapeHtml(user.lastLogin)}
                </td>
                <td class="table-cell">
                    <div class="dropdown-menu">
                        <button class="dropdown-menu-trigger button size-icon variant-ghost" 
                                data-user-action="toggle-dropdown"
                                data-user-id="${user.id}"
                                data-bs-toggle="tooltip"
                                data-bs-title="Actions">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="5" r="1"></circle>
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="12" cy="19" r="1"></circle>
                            </svg>
                            <span class="sr-only">Toggle menu</span>
                        </button>
                        <div class="dropdown-menu-content" id="dropdown-user-${user.id}" style="display: none;">
                            <div class="dropdown-menu-label">Actions</div>
                            <button class="dropdown-menu-item" data-user-action="edit" data-user-id="${user.id}">Edit</button>
                            <button class="dropdown-menu-item" data-user-action="view" data-user-id="${user.id}">View Details</button>
                            <button class="dropdown-menu-item text-destructive" data-user-action="delete" data-user-id="${user.id}">Delete</button>
                        </div>
                    </div>
                </td>
            `;

            return row;
        },

        // Attach event listeners to table rows
        attachRowEventListeners: function() {
            // Dropdown toggles
            const toggleButtons = document.querySelectorAll('[data-user-action="toggle-dropdown"]');
            toggleButtons.forEach(btn => {
                btn.addEventListener('click', this.toggleUserDropdown.bind(this));
            });

            // Action buttons
            const actionButtons = document.querySelectorAll('[data-user-action]:not([data-user-action="toggle-dropdown"])');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', this.handleUserAction.bind(this));
            });
        },

        // Handle user actions (edit, view, delete)
        handleUserAction: function(event) {
            const action = event.currentTarget.dataset.userAction;
            const userId = parseInt(event.currentTarget.dataset.userId);

            switch(action) {
                case 'edit':
                    this.editUser(userId);
                    break;
                case 'view':
                    this.viewUserDetails(userId);
                    break;
                case 'delete':
                    this.deleteUser(userId);
                    break;
            }

            // Close dropdown
            this.closeAllDropdowns();
        },

        // Toggle user dropdown
        toggleUserDropdown: function(event) {
            const userId = event.currentTarget.dataset.userId;
            const dropdown = document.getElementById(`dropdown-user-${userId}`);
            
            if (dropdown) {
                const isVisible = dropdown.style.display !== 'none';
                this.closeAllDropdowns();
                
                if (!isVisible) {
                    dropdown.style.display = 'block';
                }
            }
        },

        // Close all dropdowns
        closeAllDropdowns: function() {
            const dropdowns = document.querySelectorAll('.dropdown-menu-content');
            dropdowns.forEach(dropdown => {
                dropdown.style.display = 'none';
            });
        },

        // Open user dialog
        openUserDialog: function(userData = null) {
            const dialog = document.getElementById('user-dialog');
            const title = document.getElementById('dialog-title');
            const form = document.getElementById('user-form');
            const passwordHelp = document.getElementById('password-help');

            if (!dialog) return;

            this.currentUser = userData;

            if (userData) {
                // Edit mode
                title.textContent = 'Edit User';
                passwordHelp.style.display = 'block';
                this.populateUserForm(userData);
            } else {
                // Add mode
                title.textContent = 'Add User';
                passwordHelp.style.display = 'none';
                form.reset();
            }

            dialog.style.display = 'block';
            dialog.classList.add('modal-active');

            // Focus first input
            const firstInput = form.querySelector('input:not([type="hidden"])');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        },

        // Close user dialog
        closeUserDialog: function() {
            const dialog = document.getElementById('user-dialog');
            if (dialog) {
                dialog.style.display = 'none';
                dialog.classList.remove('modal-active');
            }
            this.currentUser = null;
        },

        // Populate user form for editing
        populateUserForm: function(userData) {
            const form = document.getElementById('user-form');
            if (!form || !userData) return;

            // Populate form fields
            const fields = {
                'user-id': userData.id,
                'user-name': userData.name,
                'user-email': userData.email,
                'user-role': userData.role,
                'user-status': userData.status,
                'user-avatar': userData.avatar
            };

            Object.entries(fields).forEach(([fieldId, value]) => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.value = value || '';
                }
            });
        },

        // Handle user form submission
        handleUserSubmit: function(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const userData = Object.fromEntries(formData.entries());

            // Validate form
            if (!this.validateUserForm(userData)) {
                return;
            }

            this.saveUser(userData);
        },

        // Validate user form
        validateUserForm: function(userData) {
            const errors = [];

            if (!userData.name?.trim()) {
                errors.push('Name is required');
            }

            if (!userData.email?.trim()) {
                errors.push('Email is required');
            } else if (!this.isValidEmail(userData.email)) {
                errors.push('Please enter a valid email address');
            }

            if (!userData.role) {
                errors.push('Role is required');
            }

            if (errors.length > 0) {
                this.showNotification(errors.join(', '), 'error');
                return false;
            }

            return true;
        },

        // Save user (create or update)
        saveUser: function(userData) {
            this.showLoading(true);

            // Simulate API call
            setTimeout(() => {
                if (this.currentUser) {
                    // Update existing user
                    this.showNotification('User updated successfully!', 'success');
                } else {
                    // Create new user
                    this.showNotification('User created successfully!', 'success');
                }

                this.closeUserDialog();
                this.loadUsers();
                this.showLoading(false);
            }, 1000);
        },

        // Edit user
        editUser: function(userId) {
            // In a real app, fetch user data from API
            const userData = this.getMockUsers().find(user => user.id === userId);
            if (userData) {
                this.openUserDialog(userData);
            }
        },

        // View user details
        viewUserDetails: function(userId) {
            this.showNotification(`Viewing details for user ${userId}`, 'info');
            // In a real app, show user details modal or navigate to details page
        },

        // Delete user
        deleteUser: function(userId) {
            if (confirm(`Are you sure you want to delete user ${userId}?`)) {
                this.showLoading(true);
                
                // Simulate API call
                setTimeout(() => {
                    this.showNotification('User deleted successfully!', 'success');
                    this.loadUsers();
                    this.showLoading(false);
                }, 500);
            }
        },

        // Handle search
        handleSearch: function(event) {
            const searchTerm = event.target.value;
            this.loadUsers(1, searchTerm);
        },

        // Handle filter
        handleFilter: function(event) {
            const filter = event.currentTarget.dataset.filter;
            this.loadUsers(1, '', filter);
        },

        // Setup pagination
        setupPagination: function() {
            // Pagination implementation would go here
        },

        // Update pagination
        updatePagination: function(totalItems) {
            // Update pagination display
        },

        // Show/hide loading state
        showLoading: function(show) {
            const loadingEl = document.getElementById('users-loading');
            if (loadingEl) {
                loadingEl.style.display = show ? 'block' : 'none';
            }

            // Disable/enable buttons during loading
            const buttons = document.querySelectorAll('.button');
            buttons.forEach(btn => {
                btn.disabled = show;
            });
        },

        // Show notification
        showNotification: function(message, type = 'info') {
            // Use the same notification system as dashboard
            if (window.Dashboard && window.Dashboard.showNotification) {
                window.Dashboard.showNotification(message, type);
            } else {
                alert(message); // Fallback
            }
        },

        // Utility functions
        getStatusClass: function(status) {
            const baseClass = 'badge badge-';
            switch(status) {
                case 'Active':
                    return baseClass + 'success';
                case 'Inactive':
                    return baseClass + 'secondary';
                case 'Pending':
                    return baseClass + 'warning';
                default:
                    return baseClass + 'outline';
            }
        },

        escapeHtml: function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },

        isValidEmail: function(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },

        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            UsersPage.init();
        });
    } else {
        UsersPage.init();
    }

    // Make available globally
    window.UsersPage = UsersPage;

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown-menu')) {
            UsersPage.closeAllDropdowns();
        }
    });

})();

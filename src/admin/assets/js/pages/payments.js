/**
 * Payments Page JavaScript
 * Handles payment management functionality
 */

(function() {
    'use strict';

    // Payments namespace
    const PaymentsPage = {
        
        // Configuration
        config: {
            apiEndpoint: 'api/payments.php',
            pageSize: 10,
            currentPage: 1,
            chartColors: {
                completed: 'rgba(75, 192, 192, 0.6)',
                refunded: 'rgba(153, 102, 255, 0.6)',
                failed: 'rgba(255, 99, 132, 0.6)',
                pending: 'rgba(255, 205, 86, 0.6)'
            }
        },

        // Current payment data
        currentPayment: null,
        chart: null,

        // Initialize payments page
        init: function() {
            this.setupEventListeners();
            this.initializeChart();
            this.initializeTooltips();
            this.loadPayments();
            this.loadFinancialSummary();
        },

        // Set up event listeners
        setupEventListeners: function() {
            // Add payment button
            const addPaymentBtn = document.querySelector('[onclick="openPaymentDialog()"]');
            if (addPaymentBtn) {
                addPaymentBtn.removeAttribute('onclick');
                addPaymentBtn.addEventListener('click', this.openPaymentDialog.bind(this));
            }

            // Payment form submission
            const paymentForm = document.getElementById('payment-form');
            if (paymentForm) {
                paymentForm.addEventListener('submit', this.handlePaymentSubmit.bind(this));
            }

            // Modal close buttons
            const closeButtons = document.querySelectorAll('[onclick="closePaymentDialog()"]');
            closeButtons.forEach(btn => {
                btn.removeAttribute('onclick');
                btn.addEventListener('click', this.closePaymentDialog.bind(this));
            });

            // Export button
            const exportBtn = document.querySelector('[onclick="exportPayments()"]');
            if (exportBtn) {
                exportBtn.removeAttribute('onclick');
                exportBtn.addEventListener('click', this.exportPayments.bind(this));
            }

            // Filter and search
            this.setupFiltersAndSearch();

            // Auto-refresh setup
            this.setupAutoRefresh();
        },

        // Initialize payment analytics chart
        initializeChart: function() {
            const ctx = document.getElementById('payment-chart');
            if (!ctx || typeof Chart === 'undefined') return;

            this.chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                    datasets: [
                        {
                            label: 'Completed',
                            data: [5200, 6100, 5800, 7200, 8100, 7800, 8600, 9200, 8900],
                            backgroundColor: this.config.chartColors.completed,
                            borderColor: this.config.chartColors.completed.replace('0.6', '1'),
                            borderWidth: 1
                        },
                        {
                            label: 'Refunded',
                            data: [120, 90, 180, 210, 160, 140, 190, 130, 150],
                            backgroundColor: this.config.chartColors.refunded,
                            borderColor: this.config.chartColors.refunded.replace('0.6', '1'),
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': $' + context.raw.toLocaleString();
                                }
                            }
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
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

        // Load payments data
        loadPayments: function(page = 1, search = '', filter = '') {
            this.showLoading(true);
            
            // Simulate API call
            setTimeout(() => {
                const payments = this.getMockPayments();
                this.renderPayments(payments);
                this.updatePagination(payments.length);
                this.showLoading(false);
            }, 500);
        },

        // Load financial summary
        loadFinancialSummary: function() {
            // Simulate API call for financial data
            setTimeout(() => {
                this.updateFinancialCards();
            }, 300);
        },

        // Get mock payments data
        getMockPayments: function() {
            return [
                {
                    id: 'PAY-1234567',
                    customer: 'Sarah Johnson',
                    amount: 199.99,
                    date: '2025-09-08',
                    status: 'Completed',
                    method: 'Credit Card',
                    details: 'Premium Headphones - Order #ORD-8452'
                },
                {
                    id: 'PAY-1234568',
                    customer: 'Michael Chen',
                    amount: 249.99,
                    date: '2025-09-07',
                    status: 'Completed',
                    method: 'PayPal',
                    details: 'Ergonomic Chair - Order #ORD-8451'
                },
                {
                    id: 'PAY-1234569',
                    customer: 'John Smith',
                    amount: 599.99,
                    date: '2025-09-06',
                    status: 'Processing',
                    method: 'Bank Transfer',
                    details: 'Smartphone - Order #ORD-8450'
                }
            ];
        },

        // Render payments in table
        renderPayments: function(payments) {
            const tbody = document.querySelector('.table-body');
            if (!tbody) return;

            tbody.innerHTML = '';

            payments.forEach(payment => {
                const row = this.createPaymentRow(payment);
                tbody.appendChild(row);
            });

            this.attachRowEventListeners();
        },

        // Create payment table row
        createPaymentRow: function(payment) {
            const row = document.createElement('tr');
            row.className = 'table-row';
            row.dataset.paymentId = payment.id;

            const statusClass = this.getStatusClass(payment.status);
            const dropdownId = payment.id.replace(/[^a-zA-Z0-9]/g, '');

            row.innerHTML = `
                <td class="table-cell font-mono">${this.escapeHtml(payment.id)}</td>
                <td class="table-cell">${this.escapeHtml(payment.customer)}</td>
                <td class="table-cell font-medium">$${payment.amount.toFixed(2)}</td>
                <td class="table-cell">${this.escapeHtml(payment.date)}</td>
                <td class="table-cell">
                    <span class="${statusClass}">${this.escapeHtml(payment.status)}</span>
                </td>
                <td class="table-cell hidden md:table-cell">${this.escapeHtml(payment.method)}</td>
                <td class="table-cell hidden lg:table-cell">${this.escapeHtml(payment.details)}</td>
                <td class="table-cell">
                    <div class="dropdown-menu">
                        <button class="dropdown-menu-trigger button size-icon variant-ghost" 
                                data-payment-action="toggle-dropdown"
                                data-payment-id="${payment.id}"
                                data-bs-toggle="tooltip"
                                data-bs-title="Actions">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="5" r="1"></circle>
                                <circle cx="12" cy="12" r="1"></circle>
                                <circle cx="12" cy="19" r="1"></circle>
                            </svg>
                            <span class="sr-only">Toggle menu</span>
                        </button>
                        <div class="dropdown-menu-content" id="dropdown-payment-${dropdownId}" style="display: none;">
                            <div class="dropdown-menu-label">Actions</div>
                            <button class="dropdown-menu-item" data-payment-action="view" data-payment-id="${payment.id}">View Details</button>
                            <button class="dropdown-menu-item" data-payment-action="receipt" data-payment-id="${payment.id}">Print Receipt</button>
                            ${payment.status === 'Processing' ? 
                                `<button class="dropdown-menu-item" data-payment-action="approve" data-payment-id="${payment.id}">Approve</button>
                                 <button class="dropdown-menu-item text-destructive" data-payment-action="reject" data-payment-id="${payment.id}">Reject</button>` :
                                payment.status === 'Completed' ?
                                `<button class="dropdown-menu-item" data-payment-action="refund" data-payment-id="${payment.id}">Issue Refund</button>` : ''
                            }
                        </div>
                    </div>
                </td>
            `;

            return row;
        },

        // Attach event listeners to table rows
        attachRowEventListeners: function() {
            // Dropdown toggles
            const toggleButtons = document.querySelectorAll('[data-payment-action="toggle-dropdown"]');
            toggleButtons.forEach(btn => {
                btn.addEventListener('click', this.togglePaymentDropdown.bind(this));
            });

            // Action buttons
            const actionButtons = document.querySelectorAll('[data-payment-action]:not([data-payment-action="toggle-dropdown"])');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', this.handlePaymentAction.bind(this));
            });
        },

        // Handle payment actions
        handlePaymentAction: function(event) {
            const action = event.currentTarget.dataset.paymentAction;
            const paymentId = event.currentTarget.dataset.paymentId;

            switch(action) {
                case 'view':
                    this.viewPaymentDetails(paymentId);
                    break;
                case 'receipt':
                    this.printReceipt(paymentId);
                    break;
                case 'approve':
                    this.approvePayment(paymentId);
                    break;
                case 'reject':
                    this.rejectPayment(paymentId);
                    break;
                case 'refund':
                    this.refundPayment(paymentId);
                    break;
            }

            this.closeAllDropdowns();
        },

        // Toggle payment dropdown
        togglePaymentDropdown: function(event) {
            const paymentId = event.currentTarget.dataset.paymentId;
            const dropdownId = paymentId.replace(/[^a-zA-Z0-9]/g, '');
            const dropdown = document.getElementById(`dropdown-payment-${dropdownId}`);
            
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

        // Payment action methods
        viewPaymentDetails: function(paymentId) {
            this.showNotification(`Viewing details for payment ${paymentId}`, 'info');
            // In a real app, show payment details modal
        },

        printReceipt: function(paymentId) {
            this.showNotification(`Printing receipt for payment ${paymentId}`, 'info');
            // In a real app, generate and print receipt
        },

        approvePayment: function(paymentId) {
            if (confirm(`Approve payment ${paymentId}?`)) {
                this.showLoading(true);
                
                setTimeout(() => {
                    this.showNotification(`Payment ${paymentId} approved successfully!`, 'success');
                    this.loadPayments();
                    this.showLoading(false);
                }, 500);
            }
        },

        rejectPayment: function(paymentId) {
            if (confirm(`Reject payment ${paymentId}?`)) {
                this.showLoading(true);
                
                setTimeout(() => {
                    this.showNotification(`Payment ${paymentId} rejected`, 'warning');
                    this.loadPayments();
                    this.showLoading(false);
                }, 500);
            }
        },

        refundPayment: function(paymentId) {
            if (confirm(`Process refund for payment ${paymentId}?`)) {
                this.showLoading(true);
                
                setTimeout(() => {
                    this.showNotification(`Refund initiated for payment ${paymentId}`, 'success');
                    this.loadPayments();
                    this.loadFinancialSummary();
                    this.showLoading(false);
                }, 1000);
            }
        },

        // Open payment dialog
        openPaymentDialog: function() {
            const dialog = document.getElementById('payment-dialog');
            const form = document.getElementById('payment-form');

            if (!dialog) return;

            form.reset();
            dialog.style.display = 'block';
            dialog.classList.add('modal-active');

            // Focus first input
            const firstInput = form.querySelector('input');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        },

        // Close payment dialog
        closePaymentDialog: function() {
            const dialog = document.getElementById('payment-dialog');
            if (dialog) {
                dialog.style.display = 'none';
                dialog.classList.remove('modal-active');
            }
        },

        // Handle payment form submission
        handlePaymentSubmit: function(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const paymentData = Object.fromEntries(formData.entries());

            if (!this.validatePaymentForm(paymentData)) {
                return;
            }

            this.savePayment(paymentData);
        },

        // Validate payment form
        validatePaymentForm: function(paymentData) {
            const errors = [];

            if (!paymentData.customer?.trim()) {
                errors.push('Customer is required');
            }

            if (!paymentData.amount || parseFloat(paymentData.amount) <= 0) {
                errors.push('Valid amount is required');
            }

            if (!paymentData.method) {
                errors.push('Payment method is required');
            }

            if (errors.length > 0) {
                this.showNotification(errors.join(', '), 'error');
                return false;
            }

            return true;
        },

        // Save payment
        savePayment: function(paymentData) {
            this.showLoading(true);

            setTimeout(() => {
                this.showNotification('Payment recorded successfully!', 'success');
                this.closePaymentDialog();
                this.loadPayments();
                this.loadFinancialSummary();
                this.showLoading(false);
            }, 1000);
        },

        // Export payments
        exportPayments: function() {
            this.showNotification('Exporting payments data...', 'info');
            
            // Simulate export process
            setTimeout(() => {
                // In a real app, generate and download CSV/PDF
                this.showNotification('Export completed!', 'success');
            }, 2000);
        },

        // Update financial summary cards
        updateFinancialCards: function() {
            const cards = document.querySelectorAll('[data-financial-metric]');
            
            cards.forEach(card => {
                const metric = card.dataset.financialMetric;
                const valueEl = card.querySelector('.text-2xl');
                
                if (valueEl) {
                    // Animate value changes
                    this.animateFinancialValue(valueEl, metric);
                }
            });
        },

        // Animate financial values
        animateFinancialValue: function(element, metric) {
            const currentValue = parseFloat(element.textContent.replace(/[^0-9.]/g, ''));
            const variation = (Math.random() - 0.5) * currentValue * 0.05; // ±5% variation
            const newValue = Math.max(0, currentValue + variation);
            
            this.animateNumber(element, currentValue, newValue, (value) => {
                return '$' + value.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            });
        },

        // Animate number changes
        animateNumber: function(element, from, to, formatter) {
            const duration = 1000;
            const step = (to - from) / (duration / 16);
            let current = from;
            
            const animate = () => {
                current += step;
                
                if ((step > 0 && current >= to) || (step < 0 && current <= to)) {
                    current = to;
                }
                
                element.textContent = formatter ? formatter(current) : Math.round(current).toLocaleString();
                
                if (current !== to) {
                    requestAnimationFrame(animate);
                }
            };
            
            animate();
        },

        // Setup filters and search
        setupFiltersAndSearch: function() {
            // Status filter
            const statusFilter = document.getElementById('status-filter');
            if (statusFilter) {
                statusFilter.addEventListener('change', this.handleStatusFilter.bind(this));
            }

            // Date range filter
            const dateFrom = document.getElementById('date-from');
            const dateTo = document.getElementById('date-to');
            
            if (dateFrom) {
                dateFrom.addEventListener('change', this.handleDateFilter.bind(this));
            }
            if (dateTo) {
                dateTo.addEventListener('change', this.handleDateFilter.bind(this));
            }

            // Search
            const searchInput = document.getElementById('payment-search');
            if (searchInput) {
                searchInput.addEventListener('input', this.debounce(this.handleSearch.bind(this), 300));
            }
        },

        // Handle status filter
        handleStatusFilter: function(event) {
            const status = event.target.value;
            this.loadPayments(1, '', status);
        },

        // Handle date filter
        handleDateFilter: function() {
            const dateFrom = document.getElementById('date-from')?.value;
            const dateTo = document.getElementById('date-to')?.value;
            // Apply date filtering logic
            this.loadPayments(1, '', '', { dateFrom, dateTo });
        },

        // Handle search
        handleSearch: function(event) {
            const searchTerm = event.target.value;
            this.loadPayments(1, searchTerm);
        },

        // Setup auto-refresh
        setupAutoRefresh: function() {
            // Refresh every 2 minutes
            setInterval(() => {
                this.loadPayments();
                this.loadFinancialSummary();
                this.updateChart();
            }, 120000);
        },

        // Update chart data
        updateChart: function() {
            if (!this.chart) return;

            // Generate new data
            const newData = this.generateChartData();
            
            this.chart.data.datasets.forEach((dataset, index) => {
                dataset.data = newData[index];
            });

            this.chart.update('active');
        },

        // Generate chart data
        generateChartData: function() {
            const months = 9;
            const completedData = [];
            const refundedData = [];
            
            for (let i = 0; i < months; i++) {
                completedData.push(5000 + Math.random() * 4000);
                refundedData.push(100 + Math.random() * 150);
            }
            
            return [completedData, refundedData];
        },

        // Update pagination
        updatePagination: function(totalItems) {
            // Pagination implementation
        },

        // Show/hide loading state
        showLoading: function(show) {
            const loadingEl = document.getElementById('payments-loading');
            if (loadingEl) {
                loadingEl.style.display = show ? 'block' : 'none';
            }

            // Disable/enable buttons
            const buttons = document.querySelectorAll('.button');
            buttons.forEach(btn => {
                btn.disabled = show;
            });
        },

        // Show notification
        showNotification: function(message, type = 'info') {
            if (window.Dashboard && window.Dashboard.showNotification) {
                window.Dashboard.showNotification(message, type);
            } else {
                alert(message);
            }
        },

        // Utility functions
        getStatusClass: function(status) {
            const baseClass = 'badge badge-';
            switch(status) {
                case 'Completed':
                    return baseClass + 'success';
                case 'Processing':
                    return baseClass + 'warning';
                case 'Failed':
                    return baseClass + 'error';
                case 'Refunded':
                    return baseClass + 'secondary';
                default:
                    return baseClass + 'outline';
            }
        },

        escapeHtml: function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
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
            PaymentsPage.init();
        });
    } else {
        PaymentsPage.init();
    }

    // Make available globally
    window.PaymentsPage = PaymentsPage;

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown-menu')) {
            PaymentsPage.closeAllDropdowns();
        }
    });

})();

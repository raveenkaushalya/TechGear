/**
 * Dashboard JavaScript
 * Handles dashboard-specific functionality and interactions
 */

(function() {
    'use strict';

    // Dashboard namespace
    const Dashboard = {
        
        // Configuration
        config: {
            refreshInterval: 300000, // 5 minutes
            chartAnimationDuration: 1000,
            autoRefresh: true
        },

        // Initialize dashboard
        init: function() {
            this.setupEventListeners();
            this.initializeCharts();
            this.startAutoRefresh();
            this.updateLastRefreshTime();
        },

        // Set up event listeners
        setupEventListeners: function() {
            // Refresh button (if exists)
            const refreshBtn = document.getElementById('refresh-dashboard');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', this.refreshDashboard.bind(this));
            }

            // Quick action buttons
            const quickActions = document.querySelectorAll('[data-quick-action]');
            quickActions.forEach(button => {
                button.addEventListener('click', this.handleQuickAction.bind(this));
            });

            // Card hover effects
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', this.onCardHover);
                card.addEventListener('mouseleave', this.onCardLeave);
            });
        },

        // Initialize charts
        initializeCharts: function() {
            // Performance chart is already initialized in the main dashboard.php file
            // This function can be extended for additional charts

            // Add chart update functionality
            setTimeout(() => {
                this.updateChartData();
            }, 2000);
        },

        // Update chart data with animation
        updateChartData: function() {
            if (typeof Chart !== 'undefined' && window.dashboardChart) {
                // Simulate data updates (in real app, fetch from API)
                const newData = this.generateRandomData();
                
                window.dashboardChart.data.datasets.forEach((dataset, index) => {
                    dataset.data = newData[index];
                });

                window.dashboardChart.update('active');
            }
        },

        // Generate sample data for demonstration
        generateRandomData: function() {
            const months = 9;
            const datasets = [];
            
            // Users data
            const usersData = [];
            let userBase = 980;
            for (let i = 0; i < months; i++) {
                userBase += Math.floor(Math.random() * 50) + 10;
                usersData.push(userBase);
            }
            datasets.push(usersData);

            // Products data
            const productsData = [];
            let productBase = 280;
            for (let i = 0; i < months; i++) {
                productBase += Math.floor(Math.random() * 15) + 2;
                productsData.push(productBase);
            }
            datasets.push(productsData);

            // Revenue data (x100)
            const revenueData = [];
            let revenueBase = 850;
            for (let i = 0; i < months; i++) {
                revenueBase += Math.floor(Math.random() * 80) + 20;
                revenueData.push(revenueBase);
            }
            datasets.push(revenueData);

            return datasets;
        },

        // Handle quick action clicks
        handleQuickAction: function(event) {
            const action = event.currentTarget.dataset.quickAction;
            
            switch(action) {
                case 'add-user':
                    this.showNotification('Redirecting to Add User...', 'info');
                    break;
                case 'add-product':
                    this.showNotification('Redirecting to Add Product...', 'info');
                    break;
                case 'record-payment':
                    this.showNotification('Redirecting to Record Payment...', 'info');
                    break;
                default:
                    console.log('Unknown quick action:', action);
            }
        },

        // Card hover effects
        onCardHover: function(event) {
            event.currentTarget.style.transform = 'translateY(-2px)';
            event.currentTarget.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
            event.currentTarget.style.transition = 'all 0.2s ease';
        },

        onCardLeave: function(event) {
            event.currentTarget.style.transform = 'translateY(0)';
            event.currentTarget.style.boxShadow = '';
        },

        // Refresh dashboard data
        refreshDashboard: function() {
            this.showNotification('Refreshing dashboard...', 'info');
            
            // Simulate API call
            setTimeout(() => {
                this.updateStats();
                this.updateChartData();
                this.updateLastRefreshTime();
                this.showNotification('Dashboard refreshed successfully!', 'success');
            }, 1000);
        },

        // Update dashboard statistics
        updateStats: function() {
            const statElements = document.querySelectorAll('[data-stat]');
            
            statElements.forEach(element => {
                const currentValue = parseInt(element.textContent.replace(/[^0-9]/g, ''));
                const variation = Math.floor(Math.random() * 20) - 10; // -10 to +10
                const newValue = Math.max(0, currentValue + variation);
                
                // Animate number change
                this.animateNumber(element, currentValue, newValue);
            });
        },

        // Animate number changes
        animateNumber: function(element, from, to) {
            const duration = 1000;
            const step = (to - from) / (duration / 16);
            let current = from;
            
            const animate = () => {
                current += step;
                
                if ((step > 0 && current >= to) || (step < 0 && current <= to)) {
                    current = to;
                }
                
                // Format number based on element's data attributes
                const formatted = this.formatStatNumber(current, element.dataset.format);
                element.textContent = formatted;
                
                if (current !== to) {
                    requestAnimationFrame(animate);
                }
            };
            
            animate();
        },

        // Format statistics numbers
        formatStatNumber: function(value, format) {
            switch(format) {
                case 'currency':
                    return '$' + value.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                case 'percentage':
                    return value.toFixed(1) + '%';
                default:
                    return Math.round(value).toLocaleString();
            }
        },

        // Start auto-refresh timer
        startAutoRefresh: function() {
            if (!this.config.autoRefresh) return;
            
            setInterval(() => {
                this.refreshDashboard();
            }, this.config.refreshInterval);
        },

        // Update last refresh time display
        updateLastRefreshTime: function() {
            const timeElement = document.getElementById('last-refresh-time');
            if (timeElement) {
                const now = new Date();
                timeElement.textContent = now.toLocaleTimeString();
            }
        },

        // Show notifications
        showNotification: function(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            `;

            // Add to page
            document.body.appendChild(notification);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);

            // Add CSS if not already present
            this.addNotificationStyles();
        },

        // Add notification styles
        addNotificationStyles: function() {
            if (document.getElementById('notification-styles')) return;

            const styles = document.createElement('style');
            styles.id = 'notification-styles';
            styles.textContent = `
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 1000;
                    min-width: 300px;
                    max-width: 500px;
                    padding: 16px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    animation: slideInRight 0.3s ease;
                }
                
                .notification-info {
                    background: #3b82f6;
                    color: white;
                }
                
                .notification-success {
                    background: #10b981;
                    color: white;
                }
                
                .notification-warning {
                    background: #f59e0b;
                    color: white;
                }
                
                .notification-error {
                    background: #ef4444;
                    color: white;
                }
                
                .notification-content {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                
                .notification-close {
                    background: none;
                    border: none;
                    color: inherit;
                    cursor: pointer;
                    padding: 4px;
                    margin-left: 12px;
                }
                
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
            `;
            
            document.head.appendChild(styles);
        },

        // Utility function to format currency
        formatCurrency: function(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        },

        // Utility function to format dates
        formatDate: function(date) {
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(date);
        }
    };

    // Initialize dashboard when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            Dashboard.init();
        });
    } else {
        Dashboard.init();
    }

    // Make Dashboard available globally for debugging
    window.Dashboard = Dashboard;

})();

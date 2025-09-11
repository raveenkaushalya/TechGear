<?php
/**
 * Main Admin Dashboard
 * 
 * Overview dashboard with statistics from Users, Product Management, and Payments
 */

// Include the main layout component
require_once('components/main-layout.php');

// Include any other required components
require_once('components/page-header.php');
require_once('components/ui/card.php');
require_once('components/ui/table.php');
require_once('components/ui/badge.php');

// Sample dashboard data (in a real app, this would come from database aggregations)
$dashboardStats = [
    'totalUsers' => 1247,
    'activeUsers' => 1089,
    'totalProducts' => 345,
    'lowStockProducts' => 12,
    'totalRevenue' => 125647.50,
    'pendingPayments' => 8,
    'recentOrders' => 23,
    'failedTransactions' => 3
];

// Recent activity data
$recentActivity = [
    [
        'type' => 'user',
        'message' => 'New user registration: john.doe@example.com',
        'timestamp' => '2025-09-12 14:30:00',
        'status' => 'info'
    ],
    [
        'type' => 'payment',
        'message' => 'Payment completed: $299.99 - Order #ORD-8453',
        'timestamp' => '2025-09-12 14:15:00',
        'status' => 'success'
    ],
    [
        'type' => 'product',
        'message' => 'Low stock alert: Gaming Headset (5 units remaining)',
        'timestamp' => '2025-09-12 13:45:00',
        'status' => 'warning'
    ],
    [
        'type' => 'payment',
        'message' => 'Payment failed: $149.99 - Order #ORD-8452',
        'timestamp' => '2025-09-12 13:20:00',
        'status' => 'error'
    ],
    [
        'type' => 'user',
        'message' => 'User role updated: sarah.wilson@example.com (Admin)',
        'timestamp' => '2025-09-12 12:55:00',
        'status' => 'info'
    ]
];

// Quick stats for each section
$userStats = [
    'total' => $dashboardStats['totalUsers'],
    'active' => $dashboardStats['activeUsers'],
    'growth' => '+12%'
];

$productStats = [
    'total' => $dashboardStats['totalProducts'],
    'lowStock' => $dashboardStats['lowStockProducts'],
    'growth' => '+5%'
];

$paymentStats = [
    'revenue' => $dashboardStats['totalRevenue'],
    'pending' => $dashboardStats['pendingPayments'],
    'growth' => '+18%'
];

// Generate the dashboard content
$pageContent = '
<div class="flex flex-col gap-8">
    ' . renderPageHeader('Admin Dashboard', 'Welcome back! Here\'s an overview of your TechGear store.') . '
    
    <!-- Key Performance Indicators -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <!-- Total Users -->
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">Total Users</div>
                <div class="text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">' . number_format($userStats['total']) . '</div>
                <p class="text-xs text-muted-foreground">' . $userStats['growth'] . ' from last month</p>
            </div>
        </div>
        
        <!-- Active Users -->
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">Active Users</div>
                <div class="text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">' . number_format($userStats['active']) . '</div>
                <p class="text-xs text-muted-foreground">87% of total users</p>
            </div>
        </div>
        
        <!-- Total Products -->
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">Total Products</div>
                <div class="text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="9" cy="9" r="2"></circle>
                        <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                    </svg>
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">' . number_format($productStats['total']) . '</div>
                <p class="text-xs text-muted-foreground">' . $productStats['growth'] . ' from last month</p>
            </div>
        </div>
        
        <!-- Total Revenue -->
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">Total Revenue</div>
                <div class="text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">$' . number_format($paymentStats['revenue'], 2) . '</div>
                <p class="text-xs text-muted-foreground">' . $paymentStats['growth'] . ' from last month</p>
            </div>
        </div>
    </div>
    
    <!-- Section Quick Access -->
    <div class="grid gap-6 md:grid-cols-3">
        <!-- Users Section -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <div class="card-title">User Management</div>
                    <a href="users.php" class="button variant-outline size-sm">
                        View All
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12l14 0"></path>
                            <path d="M12 5l7 7l-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Total Users</span>
                        <span class="font-medium">' . number_format($userStats['total']) . '</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Active Users</span>
                        <span class="font-medium text-green-600">' . number_format($userStats['active']) . '</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Inactive Users</span>
                        <span class="font-medium text-red-600">' . number_format($userStats['total'] - $userStats['active']) . '</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t">
                    <div class="flex gap-2">
                        <a href="users.php?action=add" class="button size-sm flex-1">Add User</a>
                        <a href="users.php?filter=active" class="button variant-outline size-sm flex-1">View Active</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products Section -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <div class="card-title">Product Management</div>
                    <a href="product-manager.php" class="button variant-outline size-sm">
                        View All
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12l14 0"></path>
                            <path d="M12 5l7 7l-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Total Products</span>
                        <span class="font-medium">' . number_format($productStats['total']) . '</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm">In Stock</span>
                        <span class="font-medium text-green-600">' . number_format($productStats['total'] - $productStats['lowStock']) . '</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Low Stock</span>
                        <span class="font-medium text-orange-600">' . number_format($productStats['lowStock']) . '</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t">
                    <div class="flex gap-2">
                        <a href="product-manager.php?action=add" class="button size-sm flex-1">Add Product</a>
                        <a href="product-manager.php?filter=low-stock" class="button variant-outline size-sm flex-1">Low Stock</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payments Section -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <div class="card-title">Payment Management</div>
                    <a href="payments.php" class="button variant-outline size-sm">
                        View All
                        <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12l14 0"></path>
                            <path d="M12 5l7 7l-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Monthly Revenue</span>
                        <span class="font-medium">$' . number_format($paymentStats['revenue'] / 12, 2) . '</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Pending Payments</span>
                        <span class="font-medium text-orange-600">' . number_format($paymentStats['pending']) . '</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm">Failed Transactions</span>
                        <span class="font-medium text-red-600">' . number_format($dashboardStats['failedTransactions']) . '</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t">
                    <div class="flex gap-2">
                        <a href="payments.php?action=add" class="button size-sm flex-1">Record Payment</a>
                        <a href="payments.php?filter=pending" class="button variant-outline size-sm flex-1">View Pending</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity & Charts -->
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Recent Activity</div>
                <div class="card-description">Latest updates across your system</div>
            </div>
            <div class="card-content">
                <div class="space-y-4">';

foreach ($recentActivity as $activity) {
    $iconClass = '';
    $bgClass = '';
    
    switch($activity['status']) {
        case 'success':
            $iconClass = 'text-green-600';
            $bgClass = 'bg-green-100';
            break;
        case 'warning':
            $iconClass = 'text-orange-600';
            $bgClass = 'bg-orange-100';
            break;
        case 'error':
            $iconClass = 'text-red-600';
            $bgClass = 'bg-red-100';
            break;
        default:
            $iconClass = 'text-blue-600';
            $bgClass = 'bg-blue-100';
    }
    
    $pageContent .= '
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 rounded-full ' . $bgClass . ' flex items-center justify-center">
                    <svg class="w-4 h-4 ' . $iconClass . '" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-900">' . htmlspecialchars($activity['message']) . '</p>
                <p class="text-xs text-gray-500">' . htmlspecialchars($activity['timestamp']) . '</p>
            </div>
        </div>';
}

$pageContent .= '
                </div>
            </div>
        </div>
        
        <!-- Analytics Chart -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Performance Overview</div>
                <div class="card-description">Monthly trends for key metrics</div>
            </div>
            <div class="card-content">
                <div class="h-[300px] w-full">
                    <canvas id="dashboard-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Quick Actions</div>
            <div class="card-description">Common administrative tasks</div>
        </div>
        <div class="card-content">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <a href="users.php?action=add" class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <line x1="19" y1="8" x2="19" y2="14"></line>
                            <line x1="22" y1="11" x2="16" y2="11"></line>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium">Add User</div>
                        <div class="text-sm text-gray-500">Create new user account</div>
                    </div>
                </a>
                
                <a href="product-manager.php?action=add" class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium">Add Product</div>
                        <div class="text-sm text-gray-500">Create new product listing</div>
                    </div>
                </a>
                
                <a href="payments.php?action=add" class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="M7 15h0M2 9.5h20"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium">Record Payment</div>
                        <div class="text-sm text-gray-500">Manual payment entry</div>
                    </div>
                </a>
                
                <a href="settings.php" class="flex items-center space-x-3 p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-medium">Settings</div>
                        <div class="text-sm text-gray-500">Configure system settings</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for dashboard functionality -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize dashboard chart
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById("dashboard-chart").getContext("2d");
        const dashboardChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                datasets: [
                    {
                        label: "Users",
                        data: [980, 1020, 1050, 1120, 1180, 1200, 1220, 1235, 1247],
                        borderColor: "rgb(59, 130, 246)",
                        backgroundColor: "rgba(59, 130, 246, 0.1)",
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: "Products",
                        data: [280, 295, 310, 320, 325, 330, 335, 340, 345],
                        borderColor: "rgb(16, 185, 129)",
                        backgroundColor: "rgba(16, 185, 129, 0.1)",
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: "Revenue (x100)",
                        data: [850, 920, 980, 1050, 1120, 1180, 1200, 1230, 1256],
                        borderColor: "rgb(139, 92, 246)",
                        backgroundColor: "rgba(139, 92, 246, 0.1)",
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: "index"
                },
                plugins: {
                    legend: {
                        position: "top"
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || "";
                                if (label) {
                                    label += ": ";
                                }
                                if (context.datasetIndex === 2) {
                                    label += "$" + (context.raw * 100).toLocaleString();
                                } else {
                                    label += context.raw.toLocaleString();
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: "Month"
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: "Count"
                        }
                    }
                }
            }
        });
    });
    
    // Auto-refresh dashboard data every 5 minutes
    setInterval(function() {
        // In a real app, you would fetch updated data via AJAX
        console.log("Refreshing dashboard data...");
    }, 300000); // 5 minutes
</script>';

// Set page title
$pageTitle = 'TechGear Admin - Dashboard';

// Page-specific JavaScript
$pageSpecificJS = ['assets/js/pages/dashboard.js'];

// Include header
include_once('includes/header.php');

// Render the layout with our content
$fullPage = renderMainLayout($pageContent);

// Output the layout content
echo $fullPage;

// Include footer
include_once('includes/footer.php');
?>

<?php
/**
 * Activity Log Page
 * 
 * Displays system and user activity logs
 */

// Include the main layout component
require_once('components/main-layout.php');

// Include any other required components
require_once('components/page-header.php');
require_once('components/ui/card.php');
require_once('components/ui/table.php');
require_once('components/ui/badge.php');

// Sample activity data (in a real app, this would come from a database)
$activityLogs = [
    [
        'id' => 1,
        'user' => 'John Smith',
        'action' => 'User Login',
        'timestamp' => '2025-09-10 09:15:23',
        'details' => 'User logged in successfully',
        'ip' => '192.168.1.45',
        'category' => 'auth'
    ],
    [
        'id' => 2,
        'user' => 'Sarah Johnson',
        'action' => 'Product Created',
        'timestamp' => '2025-09-10 08:45:12',
        'details' => 'New product "Wireless Earbuds Pro" created',
        'ip' => '192.168.1.30',
        'category' => 'product'
    ],
    [
        'id' => 3,
        'user' => 'System',
        'action' => 'Backup Completed',
        'timestamp' => '2025-09-10 03:00:00',
        'details' => 'Daily backup completed successfully',
        'ip' => 'localhost',
        'category' => 'system'
    ],
    [
        'id' => 4,
        'user' => 'Michael Chen',
        'action' => 'Payment Processed',
        'timestamp' => '2025-09-09 16:23:45',
        'details' => 'Payment of $249.99 for Order #ORD-8451 processed successfully',
        'ip' => '192.168.1.62',
        'category' => 'payment'
    ],
    [
        'id' => 5,
        'user' => 'Lisa Wong',
        'action' => 'Content Updated',
        'timestamp' => '2025-09-09 14:12:30',
        'details' => 'Page "How to Get Started" updated',
        'ip' => '192.168.1.28',
        'category' => 'content'
    ],
    [
        'id' => 6,
        'user' => 'Alex Rodriguez',
        'action' => 'User Login Failed',
        'timestamp' => '2025-09-09 10:45:18',
        'details' => 'Failed login attempt: incorrect password',
        'ip' => '192.168.1.75',
        'category' => 'auth'
    ],
    [
        'id' => 7,
        'user' => 'John Smith',
        'action' => 'Settings Changed',
        'timestamp' => '2025-09-08 15:32:41',
        'details' => 'System email notification settings updated',
        'ip' => '192.168.1.45',
        'category' => 'settings'
    ],
    [
        'id' => 8,
        'user' => 'Sarah Johnson',
        'action' => 'User Added',
        'timestamp' => '2025-09-08 11:20:05',
        'details' => 'New user "Emily Wilson" added with Editor role',
        'ip' => '192.168.1.30',
        'category' => 'user'
    ],
    [
        'id' => 9,
        'user' => 'System',
        'action' => 'Maintenance Mode',
        'timestamp' => '2025-09-08 02:00:00',
        'details' => 'System entered maintenance mode for scheduled updates',
        'ip' => 'localhost',
        'category' => 'system'
    ],
    [
        'id' => 10,
        'user' => 'System',
        'action' => 'Maintenance Completed',
        'timestamp' => '2025-09-08 02:15:32',
        'details' => 'System maintenance completed successfully',
        'ip' => 'localhost',
        'category' => 'system'
    ]
];

// Activity stats
$activityStats = [
    'totalActivities' => 248,
    'userLogins' => 85,
    'systemEvents' => 42,
    'contentChanges' => 64,
    'paymentEvents' => 57
];

// Generate the activity log content
$pageContent = '
<div class="flex flex-col gap-8">
    ' . renderPageHeader('Activity Log') . '
    
    <!-- Activity Stats Cards -->
    <div class="grid gap-4 md:grid-cols-4">
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">Total Events</div>
                <div class="text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">' . number_format($activityStats['totalActivities']) . '</div>
                <p class="text-xs text-muted-foreground">Last 30 days</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">User Logins</div>
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
                <div class="text-2xl font-bold">' . number_format($activityStats['userLogins']) . '</div>
                <p class="text-xs text-muted-foreground">Last 30 days</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">Content Changes</div>
                <div class="text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">' . number_format($activityStats['contentChanges']) . '</div>
                <p class="text-xs text-muted-foreground">Last 30 days</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">System Events</div>
                <div class="text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">' . number_format($activityStats['systemEvents']) . '</div>
                <p class="text-xs text-muted-foreground">Last 30 days</p>
            </div>
        </div>
    </div>
    
    <!-- Activity Filters -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Activity Filters</div>
            <div class="card-description">Filter the activity log by various criteria</div>
        </div>
        <div class="card-content">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="form-group">
                    <label for="filter-category">Category</label>
                    <select id="filter-category" class="form-control">
                        <option value="">All Categories</option>
                        <option value="auth">Authentication</option>
                        <option value="user">User Management</option>
                        <option value="product">Products</option>
                        <option value="payment">Payments</option>
                        <option value="content">Content</option>
                        <option value="settings">Settings</option>
                        <option value="system">System</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter-user">User</label>
                    <select id="filter-user" class="form-control">
                        <option value="">All Users</option>
                        <option value="John Smith">John Smith</option>
                        <option value="Sarah Johnson">Sarah Johnson</option>
                        <option value="Michael Chen">Michael Chen</option>
                        <option value="Lisa Wong">Lisa Wong</option>
                        <option value="Alex Rodriguez">Alex Rodriguez</option>
                        <option value="System">System</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter-date-from">Date From</label>
                    <input type="date" id="filter-date-from" class="form-control">
                </div>
                <div class="form-group">
                    <label for="filter-date-to">Date To</label>
                    <input type="date" id="filter-date-to" class="form-control">
                </div>
            </div>
            <div class="mt-4">
                <button class="button" onclick="applyFilters()">Apply Filters</button>
                <button class="button variant-outline ml-2" onclick="resetFilters()">Reset</button>
                <button class="button variant-outline ml-2" onclick="exportActivity()">Export Log</button>
            </div>
        </div>
    </div>
    
    <!-- Activity Table -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Activity Log</div>
            <div class="card-description">Recent system and user activities</div>
        </div>
        <div class="card-content">
            <div class="table-container">
                <table class="table">
                    <thead class="table-header">
                        <tr class="table-row">
                            <th class="table-head">Timestamp</th>
                            <th class="table-head">User</th>
                            <th class="table-head">Action</th>
                            <th class="table-head hidden md:table-cell">Category</th>
                            <th class="table-head">Details</th>
                            <th class="table-head hidden lg:table-cell">IP Address</th>
                            <th class="table-head">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-body">';

// Generate table rows for each activity log
foreach ($activityLogs as $log) {
    $categoryClass = 'badge badge-';
    switch($log['category']) {
        case 'auth':
            $categoryClass .= 'secondary';
            break;
        case 'product':
            $categoryClass .= 'success';
            break;
        case 'system':
            $categoryClass .= 'warning';
            break;
        case 'payment':
            $categoryClass .= 'primary';
            break;
        case 'content':
            $categoryClass .= 'info';
            break;
        case 'user':
            $categoryClass .= 'outline';
            break;
        case 'settings':
            $categoryClass .= 'outline';
            break;
        default:
            $categoryClass .= 'outline';
    }
    
    $pageContent .= '
        <tr class="table-row" data-log-id="' . $log['id'] . '">
            <td class="table-cell font-mono text-sm">' . htmlspecialchars($log['timestamp']) . '</td>
            <td class="table-cell">' . htmlspecialchars($log['user']) . '</td>
            <td class="table-cell font-medium">' . htmlspecialchars($log['action']) . '</td>
            <td class="table-cell hidden md:table-cell">
                <span class="' . $categoryClass . '">' . htmlspecialchars(ucfirst($log['category'])) . '</span>
            </td>
            <td class="table-cell">' . htmlspecialchars($log['details']) . '</td>
            <td class="table-cell hidden lg:table-cell font-mono text-sm">' . htmlspecialchars($log['ip']) . '</td>
            <td class="table-cell">
                <button class="button size-icon variant-ghost" 
                        onclick="viewActivityDetails(' . $log['id'] . ')"
                        data-bs-toggle="tooltip"
                        data-bs-title="View Details">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span class="sr-only">View details</span>
                </button>
            </td>
        </tr>';
}

$pageContent .= '
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center mt-4">
                <div>
                    Showing 10 of ' . $activityStats['totalActivities'] . ' entries
                </div>
                <div>
                    <button class="button variant-outline">Load More</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Activity Chart -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Activity Trends</div>
            <div class="card-description">Activity frequency over time</div>
        </div>
        <div class="card-content">
            <div class="h-[300px] w-full">
                <canvas id="activity-chart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Activity Detail Modal -->
    <div id="activity-detail-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2 class="mb-4">Activity Details</h2>
            <div id="activity-detail-content">
                <!-- Content will be populated via JavaScript -->
            </div>
            <div class="mt-4 text-right">
                <button class="button" onclick="closeActivityModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for activity log functionality -->
<script>
    function viewActivityDetails(logId) {
        // In a real app, you would fetch the details from an API
        // For now, we\'ll just display the log ID
        const modal = document.getElementById("activity-detail-modal");
        const content = document.getElementById("activity-detail-content");
        
        // Find the log entry in our data
        const logEntry = findLogById(logId);
        
        if (logEntry) {
            let detailHTML = `
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">${logEntry.action}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">ID: ${logEntry.id}</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>User:</strong> ${logEntry.user}</li>
                        <li class="list-group-item"><strong>Timestamp:</strong> ${logEntry.timestamp}</li>
                        <li class="list-group-item"><strong>Category:</strong> ${logEntry.category}</li>
                        <li class="list-group-item"><strong>IP Address:</strong> ${logEntry.ip}</li>
                        <li class="list-group-item"><strong>Details:</strong> ${logEntry.details}</li>
                    </ul>
                </div>
            `;
            
            content.innerHTML = detailHTML;
        } else {
            content.innerHTML = `<div class="alert alert-warning">Activity log ID ${logId} not found.</div>`;
        }
        
        modal.style.display = "block";
    }
    
    function closeActivityModal() {
        document.getElementById("activity-detail-modal").style.display = "none";
    }
    
    function findLogById(id) {
        // This is a placeholder for what would normally be an API call
        // We\'re simulating finding the log in our data
        const logs = [
            // Same data as the PHP array, just in JavaScript format
            {
                id: 1,
                user: "John Smith",
                action: "User Login",
                timestamp: "2025-09-10 09:15:23",
                details: "User logged in successfully",
                ip: "192.168.1.45",
                category: "auth"
            },
            {
                id: 2,
                user: "Sarah Johnson",
                action: "Product Created",
                timestamp: "2025-09-10 08:45:12",
                details: "New product \\"Wireless Earbuds Pro\\" created",
                ip: "192.168.1.30",
                category: "product"
            },
            // ... add more logs as needed
        ];
        
        return logs.find(log => log.id === id) || null;
    }
    
    function applyFilters() {
        // In a real app, this would filter the table based on selected criteria
        alert("Applying filters... (would filter activity log in a real app)");
    }
    
    function resetFilters() {
        // In a real app, this would reset all filters and reload the data
        document.getElementById("filter-category").value = "";
        document.getElementById("filter-user").value = "";
        document.getElementById("filter-date-from").value = "";
        document.getElementById("filter-date-to").value = "";
        alert("Filters reset");
    }
    
    function exportActivity() {
        // In a real app, this would generate a CSV or PDF export
        alert("Exporting activity log...");
    }
    
    // Initialize charts when the page loads
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById("activity-chart").getContext("2d");
        const activityChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Sep 1", "Sep 2", "Sep 3", "Sep 4", "Sep 5", "Sep 6", "Sep 7", "Sep 8", "Sep 9", "Sep 10"],
                datasets: [
                    {
                        label: "Auth Events",
                        data: [12, 8, 10, 5, 9, 7, 11, 9, 12, 10],
                        backgroundColor: "rgba(153, 102, 255, 0.6)"
                    },
                    {
                        label: "Content Events",
                        data: [5, 7, 8, 9, 6, 4, 7, 8, 9, 5],
                        backgroundColor: "rgba(75, 192, 192, 0.6)"
                    },
                    {
                        label: "System Events",
                        data: [3, 4, 2, 5, 3, 4, 2, 6, 3, 4],
                        backgroundColor: "rgba(255, 205, 86, 0.6)"
                    },
                    {
                        label: "Other Events",
                        data: [8, 6, 7, 9, 8, 7, 6, 8, 7, 9],
                        backgroundColor: "rgba(201, 203, 207, 0.6)"
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            }
        });
    });
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById("activity-detail-modal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>';

// Set page title
$pageTitle = 'IndigoFlow - Activity Log';

// Page-specific JavaScript
$pageSpecificJS = ['assets/js/pages/activity.js'];

// Include header
include_once('includes/header.php');

// Render the layout with our content
$fullPage = renderMainLayout($pageContent);

// Output the layout content
echo $fullPage;

// Include footer
include_once('includes/footer.php');
?>

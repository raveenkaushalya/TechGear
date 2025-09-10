<?php
/**
 * Payments Management Page
 * 
 * Displays payment transactions and financial information
 */

// Include the main layout component
require_once('components/main-layout.php');

// Include any other required components
require_once('components/page-header.php');
require_once('components/ui/card.php');
require_once('components/ui/table.php');
require_once('components/ui/badge.php');
require_once('components/ui/dropdown-menu.php');

// Sample payment data (in a real app, this would come from a database)
$payments = [
    [
        'id' => 'PAY-1234567',
        'customer' => 'Sarah Johnson',
        'amount' => 199.99,
        'date' => '2025-09-08',
        'status' => 'Completed',
        'method' => 'Credit Card',
        'details' => 'Premium Headphones - Order #ORD-8452'
    ],
    [
        'id' => 'PAY-1234568',
        'customer' => 'Michael Chen',
        'amount' => 249.99,
        'date' => '2025-09-07',
        'status' => 'Completed',
        'method' => 'PayPal',
        'details' => 'Ergonomic Chair - Order #ORD-8451'
    ],
    [
        'id' => 'PAY-1234569',
        'customer' => 'John Smith',
        'amount' => 599.99,
        'date' => '2025-09-06',
        'status' => 'Processing',
        'method' => 'Bank Transfer',
        'details' => 'Smartphone - Order #ORD-8450'
    ],
    [
        'id' => 'PAY-1234570',
        'customer' => 'Lisa Wong',
        'amount' => 149.99,
        'date' => '2025-09-05',
        'status' => 'Completed',
        'method' => 'Credit Card',
        'details' => 'Wireless Earbuds - Order #ORD-8449'
    ],
    [
        'id' => 'PAY-1234571',
        'customer' => 'Alex Rodriguez',
        'amount' => 1299.99,
        'date' => '2025-09-04',
        'status' => 'Failed',
        'method' => 'Credit Card',
        'details' => 'Laptop - Order #ORD-8448'
    ],
    [
        'id' => 'PAY-1234572',
        'customer' => 'Emily Wilson',
        'amount' => 49.99,
        'date' => '2025-09-03',
        'status' => 'Refunded',
        'method' => 'PayPal',
        'details' => 'Phone Case - Order #ORD-8447'
    ]
];

// Financial summary data
$financialSummary = [
    'totalRevenue' => 42124.75,
    'pendingAmount' => 1845.50,
    'refundedAmount' => 312.45,
    'failedAmount' => 2589.99
];

// Generate the payments content
$pageContent = '
<div class="flex flex-col gap-8">
    ' . renderPageHeader('Payment Management') . '
    
    <!-- Financial Summary Cards -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
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
                <div class="text-2xl font-bold">$' . number_format($financialSummary['totalRevenue'], 2) . '</div>
                <p class="text-xs text-muted-foreground">+12% from last month</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">Pending Payments</div>
                <div class="text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">$' . number_format($financialSummary['pendingAmount'], 2) . '</div>
                <p class="text-xs text-muted-foreground">3 transactions pending</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">Refunded</div>
                <div class="text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">$' . number_format($financialSummary['refundedAmount'], 2) . '</div>
                <p class="text-xs text-muted-foreground">-2% from last month</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">Failed Transactions</div>
                <div class="text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">$' . number_format($financialSummary['failedAmount'], 2) . '</div>
                <p class="text-xs text-muted-foreground">+5% from last month</p>
            </div>
        </div>
    </div>
    
    <!-- Payments Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div>
                    <div class="card-title">Payment Transactions</div>
                    <div class="card-description">
                        View and manage all payment transactions.
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="button" onclick="exportPayments()">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export
                    </button>
                    <button class="button" onclick="openPaymentDialog()">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Record Payment
                    </button>
                </div>
            </div>
        </div>
        <div class="card-content">
            <div class="table-container">
                <table class="table">
                    <thead class="table-header">
                        <tr class="table-row">
                            <th class="table-head">ID</th>
                            <th class="table-head">Customer</th>
                            <th class="table-head">Amount</th>
                            <th class="table-head">Date</th>
                            <th class="table-head">Status</th>
                            <th class="table-head hidden md:table-cell">Method</th>
                            <th class="table-head hidden lg:table-cell">Details</th>
                            <th class="table-head">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-body">';

// Generate table rows for each payment
foreach ($payments as $payment) {
    $statusClass = 'badge badge-';
    switch($payment['status']) {
        case 'Completed':
            $statusClass .= 'success';
            break;
        case 'Processing':
            $statusClass .= 'warning';
            break;
        case 'Failed':
            $statusClass .= 'error';
            break;
        case 'Refunded':
            $statusClass .= 'secondary';
            break;
        default:
            $statusClass .= 'outline';
    }
    
    $pageContent .= '
        <tr class="table-row" data-payment-id="' . $payment['id'] . '">
            <td class="table-cell font-mono">' . htmlspecialchars($payment['id']) . '</td>
            <td class="table-cell">' . htmlspecialchars($payment['customer']) . '</td>
            <td class="table-cell font-medium">$' . number_format($payment['amount'], 2) . '</td>
            <td class="table-cell">' . htmlspecialchars($payment['date']) . '</td>
            <td class="table-cell">
                <span class="' . $statusClass . '">' . htmlspecialchars($payment['status']) . '</span>
            </td>
            <td class="table-cell hidden md:table-cell">' . htmlspecialchars($payment['method']) . '</td>
            <td class="table-cell hidden lg:table-cell">' . htmlspecialchars($payment['details']) . '</td>
            <td class="table-cell">
                <div class="dropdown-menu">
                    <button class="dropdown-menu-trigger button size-icon variant-ghost" 
                            onclick="togglePaymentDropdown(\'' . $payment['id'] . '\')"
                            data-bs-toggle="tooltip"
                            data-bs-title="Actions">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="5" r="1"></circle>
                            <circle cx="12" cy="12" r="1"></circle>
                            <circle cx="12" cy="19" r="1"></circle>
                        </svg>
                        <span class="sr-only">Toggle menu</span>
                    </button>
                    <div class="dropdown-menu-content" id="dropdown-payment-' . str_replace('-', '', $payment['id']) . '" style="display: none;">
                        <div class="dropdown-menu-label">Actions</div>
                        <button class="dropdown-menu-item" onclick="viewPaymentDetails(\'' . $payment['id'] . '\')">View Details</button>
                        <button class="dropdown-menu-item" onclick="printReceipt(\'' . $payment['id'] . '\')">Print Receipt</button>';
                        
    if ($payment['status'] === 'Processing') {
        $pageContent .= '
                        <button class="dropdown-menu-item" onclick="approvePayment(\'' . $payment['id'] . '\')">Approve</button>
                        <button class="dropdown-menu-item text-destructive" onclick="rejectPayment(\'' . $payment['id'] . '\')">Reject</button>';
    } elseif ($payment['status'] === 'Completed') {
        $pageContent .= '
                        <button class="dropdown-menu-item" onclick="refundPayment(\'' . $payment['id'] . '\')">Issue Refund</button>';
    }
                        
    $pageContent .= '
                    </div>
                </div>
            </td>
        </tr>';
}

$pageContent .= '
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Payment Chart -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Payment Analytics</div>
            <div class="card-description">Monthly payment totals and trends</div>
        </div>
        <div class="card-content">
            <div class="h-[300px] w-full">
                <canvas id="payment-chart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Payment Dialog (modal) with form -->
    <div id="payment-dialog" class="modal" style="display: none;">
        <div class="modal-content">
            <h2 id="dialog-title" class="mb-4">Record Payment</h2>
            <form id="payment-form">
                <div class="form-group">
                    <label for="payment-customer">Customer</label>
                    <input type="text" id="payment-customer" name="customer" class="form-control" required />
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment-amount">Amount ($)</label>
                            <input type="number" id="payment-amount" name="amount" class="form-control" step="0.01" min="0" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment-method">Payment Method</label>
                            <select id="payment-method" name="method" class="form-control" required>
                                <option value="Credit Card">Credit Card</option>
                                <option value="PayPal">PayPal</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="payment-details">Details</label>
                    <textarea id="payment-details" name="details" class="form-control" rows="2"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" onclick="closePaymentDialog()" class="button variant-outline">Cancel</button>
                    <button type="submit" class="button">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for payment functionality -->
<script>
    function togglePaymentDropdown(paymentId) {
        const dropdown = document.getElementById(`dropdown-payment-${paymentId.replace("-", "")}`);
        if (dropdown) {
            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
        }
    }
    
    function viewPaymentDetails(paymentId) {
        alert(`Viewing details for payment ${paymentId}`);
        // In a real app, you would redirect to a details page or show a modal
    }
    
    function printReceipt(paymentId) {
        alert(`Printing receipt for payment ${paymentId}`);
        // In a real app, you would generate a printable receipt
    }
    
    function approvePayment(paymentId) {
        if (confirm(`Approve payment ${paymentId}?`)) {
            // In a real app, you would call an API to update the payment status
            alert(`Payment ${paymentId} approved`);
        }
    }
    
    function rejectPayment(paymentId) {
        if (confirm(`Reject payment ${paymentId}?`)) {
            // In a real app, you would call an API to update the payment status
            alert(`Payment ${paymentId} rejected`);
        }
    }
    
    function refundPayment(paymentId) {
        if (confirm(`Process refund for payment ${paymentId}?`)) {
            // In a real app, you would call an API to process the refund
            alert(`Refund initiated for payment ${paymentId}`);
        }
    }
    
    function exportPayments() {
        // In a real app, you would generate a CSV or PDF export
        alert("Exporting payments data...");
    }
    
    function openPaymentDialog() {
        document.getElementById("payment-dialog").style.display = "block";
    }
    
    function closePaymentDialog() {
        document.getElementById("payment-dialog").style.display = "none";
    }
    
    // Initialize chart
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById("payment-chart").getContext("2d");
        const paymentChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                datasets: [
                    {
                        label: "Completed",
                        data: [5200, 6100, 5800, 7200, 8100, 7800, 8600, 9200, 8900],
                        backgroundColor: "rgba(75, 192, 192, 0.6)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1
                    },
                    {
                        label: "Refunded",
                        data: [120, 90, 180, 210, 160, 140, 190, 130, 150],
                        backgroundColor: "rgba(153, 102, 255, 0.6)",
                        borderColor: "rgba(153, 102, 255, 1)",
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
                                return "$" + value;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ": $" + context.raw;
                            }
                        }
                    }
                }
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener("click", function(event) {
        if (!event.target.closest(".dropdown-menu")) {
            const dropdowns = document.querySelectorAll(".dropdown-menu-content");
            dropdowns.forEach(dropdown => {
                dropdown.style.display = "none";
            });
        }
    });
</script>';

// Set page title
$pageTitle = 'IndigoFlow - Payment Management';

// Page-specific JavaScript
$pageSpecificJS = ['assets/js/pages/payments.js'];

// Include header
include_once('includes/header.php');

// Render the layout with our content
$fullPage = renderMainLayout($pageContent);

// Output the layout content
echo $fullPage;

// Include footer
include_once('includes/footer.php');
?>

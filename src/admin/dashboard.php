<?php
/**
 * Dashboard Page
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and structure
 */

// Include security functions
require_once('includes/security.php');

// Start secure session
secureSession();

// Check if user is logged in, redirect to login if not
if (!isAdminLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Check for session timeout
checkSessionTimeout();

// Include the main layout component
require_once('components/main-layout.php');

// Include any other required components
require_once('components/ui/card.php');
require_once('components/page-header.php');

// Sample data (in a real app, this would come from a database or API)
$performanceStats = [
    [
        'title' => 'Total Visitors',
        'value' => '324,245',
        'change' => '+14% from last month'
    ],
    [
        'title' => 'New Users',
        'value' => '1,325',
        'change' => '+5% from last month'
    ],
    [
        'title' => 'Bounce Rate',
        'value' => '42%',
        'change' => '-3% from last month'
    ],
    [
        'title' => 'Revenue',
        'value' => '$42,124',
        'change' => '+18% from last month'
    ]
];

// Generate the icon HTML based on the stat title
function getStatIcon($title) {
    switch ($title) {
        case 'Total Visitors':
            return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        
        case 'New Users':
            return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
            
        case 'Bounce Rate':
            return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg>';
            
        case 'Revenue':
            return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>';
            
        default:
            return '';
    }
}

// Generate the dashboard content
$pageContent = '
<div class="flex flex-col gap-8">
    ' . renderPageHeader('Performance Overview') . '
    
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">';

// Generate stat cards
foreach ($performanceStats as $stat) {
    $pageContent .= '
        <div class="card">
            <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                <div class="card-title text-sm font-medium">
                    ' . $stat['title'] . '
                </div>
                <div class="text-muted-foreground">
                    ' . getStatIcon($stat['title']) . '
                </div>
            </div>
            <div class="card-content">
                <div class="text-2xl font-bold">' . $stat['value'] . '</div>
                <p class="text-xs text-muted-foreground">' . $stat['change'] . '</p>
            </div>
        </div>';
}

$pageContent .= '
    </div>
    
    <div class="grid gap-4 md:grid-cols-2">
        <!-- Chart placeholders -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Visitor Trends</div>
            </div>
            <div class="card-content">
                <div class="aspect-video h-[300px] w-full">
                    <canvas id="visitor-chart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-title">User Demographics</div>
            </div>
            <div class="card-content">
                <div class="aspect-video h-[300px] w-full">
                    <canvas id="demographics-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>';

// Render the layout with our content
$fullPage = renderMainLayout($pageContent);

// Set page title and page-specific data
$pageTitle = 'IndigoFlow Dashboard';

// JSON data for charts that will be used by JavaScript
$inlineJS = '
// Sample data for charts
window.visitorData = [
    { month: "Jan", visitors: 1300 },
    { month: "Feb", visitors: 1500 },
    { month: "Mar", visitors: 1400 },
    { month: "Apr", visitors: 1800 },
    { month: "May", visitors: 2000 },
    { month: "Jun", visitors: 2300 }
];

window.demographicData = [
    { browser: "Chrome", visitors: 65, fill: "hsl(var(--chart-1))" },
    { browser: "Safari", visitors: 23, fill: "hsl(var(--chart-2))" },
    { browser: "Firefox", visitors: 10, fill: "hsl(var(--chart-3))" },
    { browser: "Other", visitors: 2, fill: "hsl(var(--chart-4))" }
];
';

// Include header
include_once('includes/header.php');

// Output the main content
echo $fullPage;

// Include footer
include_once('includes/footer.php');
?>

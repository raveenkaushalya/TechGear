<?php
/**
 * Content Management Page
 * 
 * Displays and manages website content
 */

// Include the main layout component
require_once('components/main-layout.php');

// Include any other required components
require_once('components/page-header.php');
require_once('components/ui/card.php');
require_once('components/ui/table.php');
require_once('components/ui/badge.php');
require_once('components/ui/dropdown-menu.php');

// Sample content data (in a real app, this would come from a database)
$contentItems = [
    [
        'id' => 1,
        'title' => 'Welcome to IndigoFlow',
        'type' => 'Page',
        'author' => 'John Smith',
        'status' => 'Published',
        'lastUpdated' => '2025-09-01',
        'slug' => 'welcome-page'
    ],
    [
        'id' => 2,
        'title' => 'How to Get Started with Our Platform',
        'type' => 'Article',
        'author' => 'Sarah Johnson',
        'status' => 'Published',
        'lastUpdated' => '2025-08-25',
        'slug' => 'getting-started'
    ],
    [
        'id' => 3,
        'title' => 'Product Features Overview',
        'type' => 'Page',
        'author' => 'Michael Chen',
        'status' => 'Published',
        'lastUpdated' => '2025-09-05',
        'slug' => 'features'
    ],
    [
        'id' => 4,
        'title' => 'September Newsletter',
        'type' => 'Newsletter',
        'author' => 'Lisa Wong',
        'status' => 'Draft',
        'lastUpdated' => '2025-09-09',
        'slug' => 'newsletter-sept-2025'
    ],
    [
        'id' => 5,
        'title' => 'Latest Product Updates',
        'type' => 'Article',
        'author' => 'Alex Rodriguez',
        'status' => 'Scheduled',
        'lastUpdated' => '2025-09-07',
        'slug' => 'product-updates'
    ],
    [
        'id' => 6,
        'title' => 'Customer Success Stories',
        'type' => 'Page',
        'author' => 'John Smith',
        'status' => 'Published',
        'lastUpdated' => '2025-08-30',
        'slug' => 'success-stories'
    ],
    [
        'id' => 7,
        'title' => 'Privacy Policy',
        'type' => 'Legal',
        'author' => 'Legal Team',
        'status' => 'Published',
        'lastUpdated' => '2025-07-15',
        'slug' => 'privacy-policy'
    ]
];

// Generate the content management page
$pageContent = '
<div class="flex flex-col gap-8">
    ' . renderPageHeader('Content Management') . '
    
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div>
                    <div class="card-title">Content Library</div>
                    <div class="card-description">
                        Manage your website content, articles, and pages.
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="button variant-outline" onclick="showContentFilters()">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        Filter
                    </button>
                    <button class="button" onclick="openContentDialog()">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Create Content
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Filter controls (hidden by default) -->
        <div id="content-filters" class="card-content border-b" style="display: none;">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="form-group">
                    <label for="filter-type">Type</label>
                    <select id="filter-type" class="form-control">
                        <option value="">All Types</option>
                        <option value="Page">Page</option>
                        <option value="Article">Article</option>
                        <option value="Newsletter">Newsletter</option>
                        <option value="Legal">Legal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter-status">Status</label>
                    <select id="filter-status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="Published">Published</option>
                        <option value="Draft">Draft</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Archived">Archived</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter-author">Author</label>
                    <select id="filter-author" class="form-control">
                        <option value="">All Authors</option>
                        <option value="John Smith">John Smith</option>
                        <option value="Sarah Johnson">Sarah Johnson</option>
                        <option value="Michael Chen">Michael Chen</option>
                        <option value="Lisa Wong">Lisa Wong</option>
                        <option value="Alex Rodriguez">Alex Rodriguez</option>
                        <option value="Legal Team">Legal Team</option>
                    </select>
                </div>
                <div class="form-group d-flex align-items-end">
                    <button type="button" class="button mt-4" onclick="applyContentFilters()">Apply Filters</button>
                </div>
            </div>
        </div>
        
        <div class="card-content">
            <div class="table-container">
                <table class="table">
                    <thead class="table-header">
                        <tr class="table-row">
                            <th class="table-head">Title</th>
                            <th class="table-head">Type</th>
                            <th class="table-head">Author</th>
                            <th class="table-head">Status</th>
                            <th class="table-head hidden md:table-cell">Last Updated</th>
                            <th class="table-head hidden lg:table-cell">Slug</th>
                            <th class="table-head">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-body">';

// Generate table rows for each content item
foreach ($contentItems as $item) {
    $statusClass = 'badge badge-';
    switch($item['status']) {
        case 'Published':
            $statusClass .= 'success';
            break;
        case 'Draft':
            $statusClass .= 'secondary';
            break;
        case 'Scheduled':
            $statusClass .= 'warning';
            break;
        case 'Archived':
            $statusClass .= 'outline';
            break;
        default:
            $statusClass .= 'outline';
    }
    
    $pageContent .= '
        <tr class="table-row" data-content-id="' . $item['id'] . '">
            <td class="table-cell font-medium">' . htmlspecialchars($item['title']) . '</td>
            <td class="table-cell">' . htmlspecialchars($item['type']) . '</td>
            <td class="table-cell">' . htmlspecialchars($item['author']) . '</td>
            <td class="table-cell">
                <span class="' . $statusClass . '">' . htmlspecialchars($item['status']) . '</span>
            </td>
            <td class="table-cell hidden md:table-cell">' . htmlspecialchars($item['lastUpdated']) . '</td>
            <td class="table-cell hidden lg:table-cell font-mono text-sm">' . htmlspecialchars($item['slug']) . '</td>
            <td class="table-cell">
                <div class="dropdown-menu">
                    <button class="dropdown-menu-trigger button size-icon variant-ghost" 
                            onclick="toggleContentDropdown(' . $item['id'] . ')"
                            data-bs-toggle="tooltip"
                            data-bs-title="Actions">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="5" r="1"></circle>
                            <circle cx="12" cy="12" r="1"></circle>
                            <circle cx="12" cy="19" r="1"></circle>
                        </svg>
                        <span class="sr-only">Toggle menu</span>
                    </button>
                    <div class="dropdown-menu-content" id="dropdown-content-' . $item['id'] . '" style="display: none;">
                        <div class="dropdown-menu-label">Actions</div>
                        <button class="dropdown-menu-item" onclick="editContent(' . $item['id'] . ')">Edit</button>
                        <button class="dropdown-menu-item" onclick="viewContent(' . $item['id'] . ')">View</button>
                        <button class="dropdown-menu-item" onclick="duplicateContent(' . $item['id'] . ')">Duplicate</button>';
    
    if ($item['status'] === 'Draft') {
        $pageContent .= '
                        <button class="dropdown-menu-item" onclick="publishContent(' . $item['id'] . ')">Publish</button>';
    } elseif ($item['status'] === 'Published') {
        $pageContent .= '
                        <button class="dropdown-menu-item" onclick="unpublishContent(' . $item['id'] . ')">Unpublish</button>';
    }
    
    $pageContent .= '
                        <button class="dropdown-menu-item text-destructive" onclick="deleteContent(' . $item['id'] . ')">Delete</button>
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
    
    <!-- Content Analytics Card -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Content Performance</div>
            <div class="card-description">
                View the performance and engagement metrics for your content
            </div>
        </div>
        <div class="card-content">
            <div class="h-[300px] w-full">
                <canvas id="content-analytics-chart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Content Dialog (modal) with form -->
    <div id="content-dialog" class="modal" style="display: none;">
        <div class="modal-content">
            <h2 id="dialog-title" class="mb-4">Create Content</h2>
            <form id="content-form">
                <input type="hidden" name="id" id="content-id">
                
                <div class="form-group">
                    <label for="content-title">Title</label>
                    <input type="text" id="content-title" name="title" class="form-control" required />
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="content-type">Content Type</label>
                            <select id="content-type" name="type" class="form-control" required>
                                <option value="Page">Page</option>
                                <option value="Article">Article</option>
                                <option value="Newsletter">Newsletter</option>
                                <option value="Legal">Legal</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="content-status">Status</label>
                            <select id="content-status" name="status" class="form-control" required>
                                <option value="Draft">Draft</option>
                                <option value="Published">Published</option>
                                <option value="Scheduled">Scheduled</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="content-slug">Slug</label>
                    <div class="input-group">
                        <span class="input-group-text">/</span>
                        <input type="text" id="content-slug" name="slug" class="form-control" />
                    </div>
                    <small class="form-text text-muted">Leave blank to auto-generate from title</small>
                </div>
                
                <div class="form-group">
                    <label for="content-body">Content</label>
                    <textarea id="content-body" name="body" class="form-control" rows="10"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" onclick="closeContentDialog()" class="button variant-outline">Cancel</button>
                    <button type="submit" class="button">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for content management functionality -->
<script>
    function toggleContentDropdown(contentId) {
        const dropdown = document.getElementById(`dropdown-content-${contentId}`);
        if (dropdown) {
            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
        }
    }
    
    function showContentFilters() {
        const filters = document.getElementById("content-filters");
        filters.style.display = filters.style.display === "none" ? "block" : "none";
    }
    
    function applyContentFilters() {
        // In a real app, this would filter the table based on selected criteria
        alert("Applying filters... (would filter content in a real app)");
    }
    
    function openContentDialog() {
        document.getElementById("content-dialog").style.display = "block";
        document.getElementById("dialog-title").textContent = "Create Content";
        document.getElementById("content-form").reset();
    }
    
    function closeContentDialog() {
        document.getElementById("content-dialog").style.display = "none";
    }
    
    function editContent(contentId) {
        document.getElementById("content-dialog").style.display = "block";
        document.getElementById("dialog-title").textContent = "Edit Content";
        document.getElementById("content-id").value = contentId;
        // In a real app, you would populate the form with content data
        alert(`Editing content ID: ${contentId}`);
    }
    
    function viewContent(contentId) {
        // In a real app, this would open a preview or navigate to the content
        alert(`Viewing content ID: ${contentId}`);
    }
    
    function duplicateContent(contentId) {
        // In a real app, this would create a copy of the content
        alert(`Duplicating content ID: ${contentId}`);
    }
    
    function publishContent(contentId) {
        // In a real app, this would update the content status to Published
        alert(`Publishing content ID: ${contentId}`);
    }
    
    function unpublishContent(contentId) {
        // In a real app, this would update the content status to Draft
        alert(`Unpublishing content ID: ${contentId}`);
    }
    
    function deleteContent(contentId) {
        if (confirm(`Are you sure you want to delete content ID: ${contentId}?`)) {
            // In a real app, this would delete the content
            alert(`Content ID: ${contentId} deleted`);
        }
    }
    
    // Generate slug from title
    document.addEventListener("DOMContentLoaded", function() {
        const titleInput = document.getElementById("content-title");
        const slugInput = document.getElementById("content-slug");
        
        if (titleInput && slugInput) {
            titleInput.addEventListener("blur", function() {
                if (slugInput.value === "") {
                    // Simple slug generation
                    slugInput.value = titleInput.value
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, "")
                        .replace(/\s+/g, "-");
                }
            });
        }
        
        // Initialize content analytics chart
        const ctx = document.getElementById("content-analytics-chart").getContext("2d");
        const contentChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                datasets: [
                    {
                        label: "Page Views",
                        data: [1500, 1800, 2100, 2300, 2200, 2600, 2800, 3100, 3400],
                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 2,
                        tension: 0.3
                    },
                    {
                        label: "Unique Visitors",
                        data: [900, 1200, 1400, 1500, 1400, 1700, 1900, 2200, 2500],
                        backgroundColor: "rgba(54, 162, 235, 0.2)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 2,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
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
$pageTitle = 'IndigoFlow - Content Management';

// Page-specific JavaScript
$pageSpecificJS = ['assets/js/pages/content.js'];

// Include header
include_once('includes/header.php');

// Render the layout with our content
$fullPage = renderMainLayout($pageContent);

// Output the layout content
echo $fullPage;

// Include footer
include_once('includes/footer.php');
?>

<?php
/**
 * Users Management Page
 * 
 * Displays user management functionality
 */

// Include the main layout component
require_once('components/main-layout.php');

// Include any other required components
require_once('components/page-header.php');
require_once('components/ui/card.php');
require_once('components/ui/table.php');
require_once('components/ui/badge.php');
require_once('components/ui/dropdown-menu.php');

// Sample user data (in a real app, this would come from a database)
$users = [
    [
        'id' => 1,
        'name' => 'John Smith',
        'email' => 'john.smith@example.com',
        'role' => 'Admin',
        'status' => 'Active',
        'lastLogin' => '2025-09-09 14:32:15',
        'avatar' => 'assets/images/users/user1.jpg'
    ],
    [
        'id' => 2,
        'name' => 'Sarah Johnson',
        'email' => 'sarah.j@example.com',
        'role' => 'Editor',
        'status' => 'Active',
        'lastLogin' => '2025-09-08 10:15:42',
        'avatar' => 'assets/images/users/user2.jpg'
    ],
    [
        'id' => 3,
        'name' => 'Michael Chen',
        'email' => 'michael.c@example.com',
        'role' => 'User',
        'status' => 'Active',
        'lastLogin' => '2025-09-07 16:45:21',
        'avatar' => 'assets/images/users/user3.jpg'
    ],
    [
        'id' => 4,
        'name' => 'Alex Rodriguez',
        'email' => 'alex.r@example.com',
        'role' => 'User',
        'status' => 'Inactive',
        'lastLogin' => '2025-08-25 09:10:33',
        'avatar' => 'assets/images/users/user4.jpg'
    ],
    [
        'id' => 5,
        'name' => 'Lisa Wong',
        'email' => 'lisa.w@example.com',
        'role' => 'Editor',
        'status' => 'Pending',
        'lastLogin' => '2025-09-01 11:23:45',
        'avatar' => 'assets/images/users/user5.jpg'
    ]
];

// Generate the users content
$pageContent = '
<div class="flex flex-col gap-8">
    ' . renderPageHeader('User Management') . '
    
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div>
                    <div class="card-title">Users</div>
                    <div class="card-description">
                        Manage your users and their access levels.
                    </div>
                </div>
                <button class="button" onclick="openUserDialog()">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    Add User
                </button>
            </div>
        </div>
        <div class="card-content">
            <div class="table-container">
                <table class="table">
                    <thead class="table-header">
                        <tr class="table-row">
                            <th class="table-head hidden w-[50px] sm:table-cell">
                                <span class="sr-only">Avatar</span>
                            </th>
                            <th class="table-head">Name</th>
                            <th class="table-head">Email</th>
                            <th class="table-head">Role</th>
                            <th class="table-head">Status</th>
                            <th class="table-head hidden md:table-cell">Last Login</th>
                            <th class="table-head">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-body">';

// Generate table rows for each user
foreach ($users as $user) {
    $statusClass = 'badge badge-';
    switch($user['status']) {
        case 'Active':
            $statusClass .= 'success';
            break;
        case 'Inactive':
            $statusClass .= 'secondary';
            break;
        case 'Pending':
            $statusClass .= 'warning';
            break;
        default:
            $statusClass .= 'outline';
    }
    
    $pageContent .= '
        <tr class="table-row" data-user-id="' . $user['id'] . '">
            <td class="table-cell hidden sm:table-cell">
                <img
                    alt="' . htmlspecialchars($user['name']) . ' avatar"
                    class="rounded-full aspect-square object-cover"
                    height="40"
                    src="' . htmlspecialchars($user['avatar']) . '"
                    width="40"
                    loading="lazy"
                />
            </td>
            <td class="table-cell font-medium">' . htmlspecialchars($user['name']) . '</td>
            <td class="table-cell">' . htmlspecialchars($user['email']) . '</td>
            <td class="table-cell">' . htmlspecialchars($user['role']) . '</td>
            <td class="table-cell">
                <span class="' . $statusClass . '">' . htmlspecialchars($user['status']) . '</span>
            </td>
            <td class="table-cell hidden md:table-cell">
                ' . htmlspecialchars($user['lastLogin']) . '
            </td>
            <td class="table-cell">
                <div class="dropdown-menu">
                    <button class="dropdown-menu-trigger button size-icon variant-ghost" 
                            onclick="toggleUserDropdown(' . $user['id'] . ')"
                            data-bs-toggle="tooltip"
                            data-bs-title="Actions">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="5" r="1"></circle>
                            <circle cx="12" cy="12" r="1"></circle>
                            <circle cx="12" cy="19" r="1"></circle>
                        </svg>
                        <span class="sr-only">Toggle menu</span>
                    </button>
                    <div class="dropdown-menu-content" id="dropdown-user-' . $user['id'] . '" style="display: none;">
                        <div class="dropdown-menu-label">Actions</div>
                        <button class="dropdown-menu-item" onclick="editUser(' . $user['id'] . ')">Edit</button>
                        <button class="dropdown-menu-item" onclick="viewUserDetails(' . $user['id'] . ')">View Details</button>
                        <button class="dropdown-menu-item text-destructive" onclick="deleteUser(' . $user['id'] . ')">Delete</button>
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
    
    <!-- User Dialog (modal) with form -->
    <div id="user-dialog" class="modal" style="display: none;">
        <div class="modal-content">
            <h2 id="dialog-title" class="mb-4">Add User</h2>
            <form id="user-form">
                <input type="hidden" name="id" id="user-id">
                
                <div class="form-group">
                    <label for="user-name">Full Name</label>
                    <input type="text" id="user-name" name="name" class="form-control" required />
                </div>
                
                <div class="form-group">
                    <label for="user-email">Email</label>
                    <input type="email" id="user-email" name="email" class="form-control" required />
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user-role">Role</label>
                            <select id="user-role" name="role" class="form-control" required>
                                <option value="Admin">Admin</option>
                                <option value="Editor">Editor</option>
                                <option value="User">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user-status">Status</label>
                            <select id="user-status" name="status" class="form-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="user-password">Password</label>
                    <input type="password" id="user-password" name="password" class="form-control" />
                    <small class="form-text text-muted" id="password-help">Leave blank to keep current password when editing.</small>
                </div>
                
                <div class="form-group">
                    <label for="user-avatar">Avatar URL</label>
                    <input type="text" id="user-avatar" name="avatar" class="form-control" />
                </div>
                
                <div class="form-actions">
                    <button type="button" onclick="closeUserDialog()" class="button variant-outline">Cancel</button>
                    <button type="submit" class="button">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Simple JavaScript to make interactive elements work -->
<script>
    function toggleUserDropdown(userId) {
        const dropdown = document.getElementById(`dropdown-user-${userId}`);
        if (dropdown) {
            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
        }
    }
    
    function openUserDialog() {
        document.getElementById("user-dialog").style.display = "block";
        document.getElementById("dialog-title").textContent = "Add User";
        document.getElementById("password-help").style.display = "none";
        document.getElementById("user-form").reset();
    }
    
    function closeUserDialog() {
        document.getElementById("user-dialog").style.display = "none";
    }
    
    function editUser(userId) {
        document.getElementById("user-dialog").style.display = "block";
        document.getElementById("dialog-title").textContent = "Edit User";
        document.getElementById("password-help").style.display = "block";
        // In a real app, you would fetch the user data and populate the form
    }
    
    function viewUserDetails(userId) {
        // In a real app, you would redirect to a user details page or show a modal
        alert(`Viewing details for user ${userId}`);
    }
    
    function deleteUser(userId) {
        if (confirm(`Are you sure you want to delete user ${userId}?`)) {
            // In a real app, you would submit a form to delete the user
            console.log("Deleting user:", userId);
        }
    }
    
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
$pageTitle = 'IndigoFlow - User Management';

// Page-specific JavaScript
$pageSpecificJS = ['assets/js/pages/users.js'];

// Include header
include_once('includes/header.php');

// Render the layout with our content
$fullPage = renderMainLayout($pageContent);

// Output the layout content
echo $fullPage;

// Include footer
include_once('includes/footer.php');
?>

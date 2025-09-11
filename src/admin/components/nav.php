<?php
/**
 * Navigation Component
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and classes
 * Enhanced with Bootstrap tooltips
 */

// Function to render the navigation
function renderNav() {
    // Define navigation items
    $navItems = [
        ['href' => 'dashboard.php', 'label' => 'Dashboard', 'icon' => 'dashboard'],
        ['href' => 'users.php', 'label' => 'Users', 'icon' => 'users'],
        ['href' => 'product-manager.php', 'label' => 'Products', 'icon' => 'products'],
        ['href' => 'payments.php', 'label' => 'Payments', 'icon' => 'payments'],
        ['href' => 'settings.php', 'label' => 'Settings', 'icon' => 'settings'],
    ];

    // Get current path to highlight active item
    $currentPath = $_SERVER['REQUEST_URI'];
    $scriptName = basename($_SERVER['SCRIPT_NAME']);
    
    // Begin sidebar menu
    echo '<div class="sidebar-menu">';
    
    foreach ($navItems as $item) {
        // Check if this menu item is active
        $isActive = '';
        
        // Simple check to see if the current script name matches the href
        if (strpos($currentPath, $item['href']) !== false || 
            $scriptName === basename($item['href'])) {
            $isActive = 'is-active';
        }
        
        // Output the menu item
        echo '<div class="sidebar-menu-item">';
        echo '<div class="sidebar-menu-button ' . $isActive . '" data-bs-toggle="tooltip" data-bs-placement="right" title="' . $item['label'] . '">';
        echo '<a href="' . $item['href'] . '" class="flex items-center gap-3 text-decoration-none">';
        
        // Render the appropriate icon based on the icon name
        switch ($item['icon']) {
            case 'dashboard':
                renderDashboardIcon('size-4');
                break;
            case 'users':
                renderUserIcon('size-4');
                break;
            case 'products':
                renderProductIcon('size-4');
                break;
            case 'payments':
                renderPaymentIcon('size-4');
                break;
            case 'settings':
                renderSettingsIcon('size-4');
                break;
            case 'ai-content':
                renderAiContentIcon('size-4');
                break;
        }
        
        echo '<span class="group-data-[collapsible=icon]:hidden">' . $item['label'] . '</span>';
        echo '</a>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
}
?>

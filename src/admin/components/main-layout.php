<?php
/**
 * Main Layout Component
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and classes
 * Enhanced with Bootstrap for better responsiveness
 */

// Function to render the main layout with content
// $sidebarCollapsed: if true, sidebar is collapsed (icon mode) by default
function renderMainLayout($content, $sidebarCollapsed = false) {
    $sidebarCollapsible = $sidebarCollapsed ? 'icon' : 'expanded';
    
    // Include required components
    require_once('icons.php');
    require_once('nav.php');
    require_once('user-nav.php');
    
    ob_start();
?>
    <div class="sidebar-provider">
        <div class="sidebar border-r border-sidebar-border" data-variant="inset" data-collapsible="<?php echo $sidebarCollapsible; ?>">
            <div class="sidebar-header flex h-16 items-center justify-center p-4 lg:justify-start">
                <a href="dashboard.php" class="flex items-center gap-3 text-decoration-none">
                    <?php renderDashboardIcon('size-8 text-primary group-data-[collapsible=icon]:size-6'); ?>
                    <span class="text-lg font-semibold group-data-[collapsible=icon]:hidden">
                        IndigoFlow
                    </span>
                </a>
            </div>
            <div class="sidebar-content">
                <?php renderNav(); ?>
            </div>
            <div class="sidebar-footer p-4">
                <button class="button variant-ghost h-10 w-full justify-start p-2 group-data-[collapsible=icon]:h-8 group-data-[collapsible=icon]:w-8 group-data-[collapsible=icon]:justify-center"
                       data-bs-toggle="tooltip" data-bs-placement="right" title="Logout">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span class="group-data-[collapsible=icon]:hidden">Logout</span>
                </button>
            </div>
            <div class="sidebar-rail"></div>
        </div>
        <div class="sidebar-inset">
            <div class="p-4 sm:p-6 lg:p-8 container-fluid">
                <!-- Toast container for notifications -->
                <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>
                
                <!-- Main content -->
                <?php echo $content; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Initialize sidebar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarTrigger = document.querySelector('.sidebar-trigger');
            const sidebar = document.querySelector('.sidebar');
            
            if (sidebarTrigger && sidebar) {
                sidebarTrigger.addEventListener('click', function() {
                    if (sidebar.getAttribute('data-collapsible') === 'icon') {
                        sidebar.setAttribute('data-collapsible', 'expanded');
                    } else {
                        sidebar.setAttribute('data-collapsible', 'icon');
                    }
                });
            }
            
            // Handle window resize for responsive sidebar
            window.addEventListener('resize', function() {
                if (window.innerWidth < 768 && sidebar) {
                    sidebar.setAttribute('data-collapsible', 'icon');
                }
            });
        });
    </script>
<?php
    return ob_get_clean();
}
?>
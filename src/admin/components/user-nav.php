<?php
/**
 * User Navigation Component
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and classes
 */

/**
 * Function to render the user navigation dropdown
 * 
 * @return string HTML for the user navigation
 */
function renderUserNav() {
    $html = '
    <div class="dropdown-menu">
        <button class="dropdown-menu-trigger button variant-ghost relative h-8 w-8 rounded-full">
            <div class="avatar h-9 w-9">
                <img 
                    class="avatar-image"
                    src="https://picsum.photos/seed/admin-user/100/100" 
                    alt="@admin" 
                    data-ai-hint="profile picture"
                />
                <div class="avatar-fallback">AD</div>
            </div>
        </button>
        <div class="dropdown-menu-content w-56" data-align="end" style="display: none;">
            <div class="dropdown-menu-label font-normal">
                <div class="flex flex-col space-y-1">
                    <p class="text-sm font-medium leading-none">Admin</p>
                    <p class="text-xs leading-none text-muted-foreground">
                        admin@example.com
                    </p>
                </div>
            </div>
            <div class="dropdown-menu-separator"></div>
            <div class="dropdown-menu-group">
                <a href="/users" class="dropdown-menu-item">Profile</a>
                <a href="/settings" class="dropdown-menu-item">Settings</a>
            </div>
            <div class="dropdown-menu-separator"></div>
            <a href="#" class="dropdown-menu-item">Log out</a>
        </div>
    </div>';

    // Add a simple script to handle dropdown toggle
    $html .= '
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const trigger = document.querySelector(".dropdown-menu-trigger");
            const content = document.querySelector(".dropdown-menu-content");
            
            if (trigger && content) {
                trigger.addEventListener("click", function(e) {
                    e.stopPropagation();
                    content.style.display = content.style.display === "none" ? "block" : "none";
                });
                
                document.addEventListener("click", function() {
                    content.style.display = "none";
                });
                
                content.addEventListener("click", function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>';
    
    return $html;
}
?>

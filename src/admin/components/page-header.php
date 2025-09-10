<?php
/**
 * Page Header Component
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and classes
 */

// Include required components
require_once(__DIR__ . '/user-nav.php');

/**
 * Function to render a page header
 * 
 * @param string $title The header title
 * @param string $children Optional additional content
 * @return string HTML for the page header
 */
function renderPageHeader($title, $children = '') {
    $html = '
    <header class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <button class="sidebar-trigger md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
                <span class="sr-only">Toggle sidebar</span>
            </button>
            <h1 class="text-2xl font-bold tracking-tight md:text-3xl">
                ' . htmlspecialchars($title) . '
            </h1>
        </div>
        <div class="flex items-center space-x-4">
            ' . $children . '
            ' . renderUserNav() . '
        </div>
    </header>';
    
    return $html;
}
?>

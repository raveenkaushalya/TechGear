<?php
/**
 * Badge Component
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and classes
 */

/**
 * Function to render a badge
 * 
 * @param string $content The badge content
 * @param string $variant Badge variant (default, outline, secondary, etc.)
 * @param string $className Additional classes
 * @return string HTML for the badge
 */
function renderBadge($content = '', $variant = 'default', $className = '') {
    $badgeClass = 'badge';
    
    // Add variant class
    if (!empty($variant)) {
        $badgeClass .= " badge-{$variant}";
    }
    
    // Add any additional classes
    if (!empty($className)) {
        $badgeClass .= " {$className}";
    }
    
    return '<span class="' . $badgeClass . '">' . $content . '</span>';
}
?>

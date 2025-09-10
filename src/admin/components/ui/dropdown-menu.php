<?php
/**
 * Dropdown Menu Component
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and classes
 */

/**
 * Function to render a dropdown menu
 * 
 * @param string $trigger The HTML for the trigger element
 * @param string $content The HTML for the dropdown content
 * @param string $align Alignment of the dropdown (left, right, center)
 * @param string $className Additional classes for the container
 * @return string HTML for the dropdown menu
 */
function renderDropdownMenu($trigger = '', $content = '', $align = 'left', $className = '') {
    $html = '<div class="dropdown-menu ' . $className . '">';
    $html .= $trigger;
    $html .= '<div class="dropdown-menu-content" data-align="' . $align . '" style="display: none;">';
    $html .= $content;
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Function to render a dropdown menu trigger
 * 
 * @param string $content The trigger content
 * @param string $className Additional classes
 * @return string HTML for the dropdown trigger
 */
function renderDropdownMenuTrigger($content = '', $className = '') {
    return '<button class="dropdown-menu-trigger ' . $className . '">' . $content . '</button>';
}

/**
 * Function to render dropdown menu content
 * 
 * @param string $content The dropdown content
 * @param string $className Additional classes
 * @param string $align Alignment of the dropdown (left, right, center)
 * @return string HTML for the dropdown content container
 */
function renderDropdownMenuContent($content = '', $className = '', $align = 'left') {
    return '<div class="dropdown-menu-content ' . $className . '" data-align="' . $align . '">' . $content . '</div>';
}

/**
 * Function to render a dropdown menu label
 * 
 * @param string $content The label content
 * @param string $className Additional classes
 * @return string HTML for the dropdown label
 */
function renderDropdownMenuLabel($content = '', $className = '') {
    return '<div class="dropdown-menu-label ' . $className . '">' . $content . '</div>';
}

/**
 * Function to render a dropdown menu item
 * 
 * @param string $content The item content
 * @param string $className Additional classes
 * @return string HTML for the dropdown item
 */
function renderDropdownMenuItem($content = '', $className = '') {
    return '<div class="dropdown-menu-item ' . $className . '">' . $content . '</div>';
}

/**
 * Function to render a dropdown menu separator
 * 
 * @return string HTML for the dropdown separator
 */
function renderDropdownMenuSeparator() {
    return '<div class="dropdown-menu-separator"></div>';
}
?>

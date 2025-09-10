<?php
/**
 * Card Component
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and classes
 */

/**
 * Function to render a card component
 * 
 * @param string $content The card content
 * @param string $className Additional classes
 * @return string HTML for the card
 */
function renderCard($content = '', $className = '') {
    return '<div class="card ' . $className . '">' . $content . '</div>';
}

/**
 * Function to render a card header
 * 
 * @param string $content The header content
 * @param string $className Additional classes
 * @return string HTML for the card header
 */
function renderCardHeader($content = '', $className = '') {
    return '<div class="card-header ' . $className . '">' . $content . '</div>';
}

/**
 * Function to render a card title
 * 
 * @param string $content The title content
 * @param string $className Additional classes
 * @return string HTML for the card title
 */
function renderCardTitle($content = '', $className = '') {
    return '<div class="card-title ' . $className . '">' . $content . '</div>';
}

/**
 * Function to render card content
 * 
 * @param string $content The content
 * @param string $className Additional classes
 * @return string HTML for the card content
 */
function renderCardContent($content = '', $className = '') {
    return '<div class="card-content ' . $className . '">' . $content . '</div>';
}

/**
 * Function to render a full card with header, title, and content
 * 
 * @param string $title The card title
 * @param string $content The card content
 * @param string $className Additional classes for the card
 * @param string $headerClassName Additional classes for the header
 * @param string $titleClassName Additional classes for the title
 * @param string $contentClassName Additional classes for the content
 * @return string Complete HTML for the card
 */
function renderFullCard($title = '', $content = '', $className = '', $headerClassName = '', $titleClassName = '', $contentClassName = '') {
    $html = '<div class="card ' . $className . '">';
    
    if (!empty($title)) {
        $html .= '<div class="card-header ' . $headerClassName . '">';
        $html .= '<div class="card-title ' . $titleClassName . '">' . $title . '</div>';
        $html .= '</div>';
    }
    
    $html .= '<div class="card-content ' . $contentClassName . '">' . $content . '</div>';
    $html .= '</div>';
    
    return $html;
}
?>

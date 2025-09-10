<?php
/**
 * Button Component
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and classes
 */

/**
 * Function to render a button with variants
 * 
 * @param string $variant Button variant (default, primary, ghost, etc.)
 * @param string $className Additional classes
 * @param string $content Button content
 * @param array $attributes Additional HTML attributes
 * @return string HTML button
 */
function renderButton($variant = 'default', $className = '', $content = '', $attributes = []) {
    $class = 'button';
    
    // Add variant class
    if (!empty($variant)) {
        $class .= " variant-{$variant}";
    }
    
    // Add any additional classes
    if (!empty($className)) {
        $class .= " {$className}";
    }
    
    // Start building the button HTML
    $html = '<button class="' . $class . '"';
    
    // Add any additional attributes
    foreach ($attributes as $key => $value) {
        $html .= ' ' . $key . '="' . $value . '"';
    }
    
    // Close opening tag and add content
    $html .= '>' . $content . '</button>';
    
    return $html;
}

// Function to output a button directly
function outputButton($variant = 'default', $className = '', $content = '', $attributes = []) {
    echo renderButton($variant, $className, $content, $attributes);
}
?>

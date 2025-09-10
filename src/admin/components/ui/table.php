<?php
/**
 * Table Component
 * 
 * Converted from React/TypeScript to PHP
 * Preserving all styling and classes
 */

/**
 * Function to render a table
 * 
 * @param string $content The table content (thead, tbody)
 * @param string $className Additional classes
 * @return string HTML for the table
 */
function renderTable($content = '', $className = '') {
    return '<div class="table-container"><table class="table ' . $className . '">' . $content . '</table></div>';
}

/**
 * Function to render a table header section
 * 
 * @param string $content The header content (rows)
 * @param string $className Additional classes
 * @return string HTML for the table header
 */
function renderTableHeader($content = '', $className = '') {
    return '<thead class="table-header ' . $className . '">' . $content . '</thead>';
}

/**
 * Function to render a table body section
 * 
 * @param string $content The body content (rows)
 * @param string $className Additional classes
 * @return string HTML for the table body
 */
function renderTableBody($content = '', $className = '') {
    return '<tbody class="table-body ' . $className . '">' . $content . '</tbody>';
}

/**
 * Function to render a table row
 * 
 * @param string $content The row content (cells)
 * @param string $className Additional classes
 * @return string HTML for the table row
 */
function renderTableRow($content = '', $className = '') {
    return '<tr class="table-row ' . $className . '">' . $content . '</tr>';
}

/**
 * Function to render a table header cell
 * 
 * @param string $content The cell content
 * @param string $className Additional classes
 * @return string HTML for the table header cell
 */
function renderTableHead($content = '', $className = '') {
    return '<th class="table-head ' . $className . '">' . $content . '</th>';
}

/**
 * Function to render a table data cell
 * 
 * @param string $content The cell content
 * @param string $className Additional classes
 * @return string HTML for the table data cell
 */
function renderTableCell($content = '', $className = '') {
    return '<td class="table-cell ' . $className . '">' . $content . '</td>';
}
?>

# IndigoFlow PHP Conversion

This project is a conversion of a Next.js/TypeScript-based React application to PHP while maintaining the same UI, styling, and functionality.

## Architecture

The PHP conversion maintains a component-based structure similar to React:

1. **Component-based approach**: Each React component is converted to a PHP function that generates HTML
2. **JavaScript integration**: Client-side functionality is handled by vanilla JavaScript
3. **Bootstrap integration**: Added Bootstrap for enhanced UI components and responsive design
4. **CSS**: Maintained the original styling approach with CSS classes

## Directory Structure

```
/php
  /assets
    /css
      main.css           # Main CSS file containing all styles
    /js
      main.js            # Core JavaScript functionality
      bootstrap-integration.js  # Bootstrap integration
      /pages             # Page-specific JavaScript
        products.js      # Products page functionality
  /components            # PHP components (similar to React components)
    /ui                  # UI components like buttons, cards, etc.
      button.php
      card.php
      table.php
      badge.php
      dropdown-menu.php
    icons.php
    main-layout.php
    nav.php
    page-header.php
    user-nav.php
  /includes              # Common includes
    header.php           # Common header with CSS/JS links
    footer.php           # Common footer with JS scripts
  dashboard.php          # Main dashboard page
  index.php              # Homepage
  products.php           # Products page
```

## Key Features

1. **Component Reusability**: PHP functions that generate HTML, similar to React components
2. **Client-side Interactivity**: JavaScript handles dynamic UI interactions
3. **Maintained Styling**: All CSS classes and styling from the original app
4. **Data Handling**: PHP handles data that was previously managed by React state
5. **Responsive Design**: Maintained responsive design with both custom CSS and Bootstrap

## JavaScript Features

- Sidebar collapsing
- Dropdown menus
- Modal dialogs
- Form handling
- Chart rendering
- Dynamic table updates
- Toast notifications

## How It Works

1. PHP components render the HTML structure
2. JavaScript adds interactivity similar to React
3. Data is passed from PHP to JavaScript via JSON for client-side manipulation
4. Bootstrap enhances the UI components and provides additional functionality

## Pages Converted

- Main layout structure
- Dashboard page with charts
- Products page with CRUD functionality

## Usage

Access the PHP pages directly in a browser through a PHP-enabled web server. No build step is required.

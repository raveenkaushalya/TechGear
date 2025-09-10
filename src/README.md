# TechGear Shopping Application

A responsive e-commerce shopping application for tech products built with HTML, CSS, and JavaScript.

## Project Structure

The project follows a modern and organized structure:

```
src/
├── assets/
│   ├── css/
│   │   ├── style.css      # Main stylesheet
│   │   └── utility.css    # Utility classes
│   ├── images/            # Product images
│   └── js/
│       └── app.js         # Combined JavaScript code
├── components/
│   ├── footer.html        # Shared footer component
│   ├── header.html        # Shared header component
│   └── product-modal.html # Product detail modal component
└── pages/
    ├── cart.html          # Shopping cart page
    ├── categories.html    # Product categories page
    └── index.html         # Home page (main application page)
```

## Features

- Responsive design for all device sizes
- Product browsing by categories
- Featured products section
- Product details modal
- Shopping cart functionality with local storage
- Dynamic component loading
- Dynamic product/catalog rendering
- Shared component inclusion via fetch API
- LocalStorage based cart with quantity management and notifications

## Technology Stack

- HTML5
- CSS3 with custom properties (variables)
- Vanilla JavaScript
- Font Awesome icons
- Local Storage API for cart persistence

## How to Run

1. Clone the repository
2. Navigate to the project directory
3. Open `index.html` in a web browser or serve it using a web server like XAMPP, Apache, Nginx, or live-server

## Development

The application uses a component-based approach with HTML includes loaded via JavaScript fetch API. The main JavaScript file (app.js) contains all the application logic including:

- Product data
- Component loading
- Product display
- Cart functionality
- Modal behavior

## License

This project is licensed under the MIT License

## Future Enhancements

- Add search / filtering functionality
- Implement user authentication and session management
- Replace localStorage with backend API for data persistence
- Extract product data to JSON fetched over network

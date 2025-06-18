let allProducts = [];
let currentCategory = '';
const PRODUCTS_PER_PAGE = 6;

// Initialize the product system
function initProductSystem() {
    loadAllProducts();
    setupFilterButtons();
    filterProducts('all'); // Show all products initially
}

// Load all products from the DOM
function loadAllProducts() {
    allProducts = Array.from(document.querySelectorAll('.product-item'));
    console.log(`Loaded ${allProducts.length} products`);
}

// Set up event listeners for filter buttons
function setupFilterButtons() {
    document.querySelectorAll('.essentials-button button').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.getAttribute('onclick').match(/'([^']+)'/)[1];
            filterProducts(type);
        });
    });
}

// Filter products by type
function filterProducts(type) {
    currentCategory = type;
    console.log(`Filtering products by: ${type}`);

    const filteredProducts = type === 'all' 
        ? allProducts 
        : allProducts.filter(product => {
            return product.dataset.type.toLowerCase() === type.toLowerCase();
        });

    console.log(`Found ${filteredProducts.length} products for ${type}`);
    displayProducts(filteredProducts);
}

// Display products with pagination
function displayProducts(products) {
    // Hide all products first
    allProducts.forEach(product => {
        product.style.display = 'none';
    });

    // Show only the first page of filtered products
    const productsToShow = products.slice(0, PRODUCTS_PER_PAGE);
    productsToShow.forEach(product => {
        product.style.display = 'block';
    });

    // Update pagination controls (you'll need to implement this)
    updatePagination(products.length);
}

// Update pagination controls (basic implementation)
function updatePagination(totalProducts) {
    const totalPages = Math.ceil(totalProducts / PRODUCTS_PER_PAGE);
    console.log(`Total pages: ${totalPages}`);
    // You would update your pagination UI here
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", initProductSystem);
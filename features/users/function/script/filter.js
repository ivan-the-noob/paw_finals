let allProducts = [];
let currentCategory = '';

function loadAllProducts() {
    const productElements = document.querySelectorAll('.product-item');
    allProducts = Array.from(productElements);
    displayLimitedProducts(allProducts);
}

function filterProducts(type) {
    currentCategory = type;

    const filteredProducts = allProducts.filter(product => {
        const productType = product.dataset.type;
        if (type === 'all') return true;
        return productType.toLowerCase() === type.toLowerCase();
    });

    displayLimitedProducts(filteredProducts);
}

function displayLimitedProducts(products) {
    allProducts.forEach(product => {
        product.style.display = 'none';
    });

    products.forEach(product => {
        product.style.display = 'block';
    });
}

document.addEventListener("DOMContentLoaded", function () {
    loadAllProducts();
    filterProducts('all'); // Show all initially
});

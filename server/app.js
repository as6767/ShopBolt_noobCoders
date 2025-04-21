// script.js

let products = []; // Array to hold products in memory (acting as a temporary database)
let editingProductId = null; // Variable to track the product being edited

const productTableBody = document.getElementById('productTableBody');

// Create Product Function (for adding a new product)
const createProduct = (event) => {
    event.preventDefault();

    // Get the form values (excluding the image)
    const name = document.getElementById('name').value;
    const description = document.getElementById('description').value;
    const price = document.getElementById('price').value;
    const category = document.getElementById('category').value;
    const stock = document.getElementById('stock').value;
    
    // If editing a product, update it, otherwise add a new product
    if (editingProductId !== null) {
        // Find the product by ID and update its data
        const productIndex = products.findIndex(p => p.id === editingProductId);
        if (productIndex !== -1) {
            products[productIndex] = {
                id: editingProductId,
                name,
                description,
                price,
                category,
                stock
            };
        }
        editingProductId = null; // Reset editingProductId after saving
        alert('Product Updated Successfully!');
    } else {
        // Generate a unique product ID (in a real app, this would come from a database)
        const productId = products.length + 1;

        const product = {
            id: productId,
            name,
            description,
            price,
            category,
            stock
        };

        // Add the product to our array (acting as a database here)
        products.push(product);
        alert('Product Created Successfully!');
    }

    // Clear the form
    document.getElementById('productForm').reset();

    // Re-render the product list
    renderProductList();
};

// Render Product List
const renderProductList = () => {
    productTableBody.innerHTML = ''; // Clear current product list

    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>$${product.price}</td>
            <td>${product.category}</td>
            <td>${product.stock}</td>
            <td>
                <button onclick="editProduct(${product.id})">Edit</button>
                <button onclick="deleteProduct(${product.id})">Delete</button>
            </td>
        `;
        productTableBody.appendChild(row);
    });
};

// Edit Product Function
const editProduct = (id) => {
    const product = products.find(p => p.id === id);
    if (product) {
        document.getElementById('name').value = product.name;
        document.getElementById('description').value = product.description;
        document.getElementById('price').value = product.price;
        document.getElementById('category').value = product.category;
        document.getElementById('stock').value = product.stock;

        // Set the editingProductId to track which product we are editing
        editingProductId = id;
    }
};

// Delete Product Function
const deleteProduct = (id) => {
    products = products.filter(p => p.id !== id); // Remove product from array
    renderProductList(); // Re-render the list after deletion
};

// Initialize the product list when the page loads
renderProductList();



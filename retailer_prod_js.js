
let cachedproducts = [];
let conversion = [];



function fetchData(){
    const api_key = localStorage.getItem('api_key');
    const retailerID = localStorage.getItem('userID');
    const requestData = {
        type: 'GetAllRetailerProducts',
        retailerID: retailerID
    };

    fetch('finalAPI.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text) });
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        cachedproducts = data.data; // Same as before
        PopulateProd(cachedproducts);
    })
    .catch(error => {
        console.error("Failed to fetch products:", error);
    });
}

function viewProduct(product) {
    localStorage.setItem("selectedProduct", JSON.stringify(product)); // Store product in localStorage
    window.location.href = "View.php"; // Navigate to View Page
}

function PopulateProd(products) {
    const wishlistContainer = document.querySelector('.wishlist-container');
    
    // Clear existing content if needed
    wishlistContainer.innerHTML = '';
    
    // Loop through each product
    products.forEach(product => {
        // Create product div
        const productDiv = document.createElement('div');
        productDiv.className = 'product';
        productDiv.id = product.productID;
        
        // Create product link and image
        const productLink = document.createElement('a');
        productLink.href = 'View.php' 
        productLink.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default navigation
            viewProduct(product); // Store product details and navigate
        });
        
        const productImg = document.createElement('img');
        productImg.id = 'images';
        productImg.src = product.imageUrl || './img/mouse_dances.jpg'
        productImg.alt = product.name || 'Product image'; 
        
        productLink.appendChild(productImg);
        productDiv.appendChild(productLink);
        
        // Create product name
        const productName = document.createElement('h2');
        productName.textContent = product.productName || 'Product Name';
        productDiv.appendChild(productName);
        
        // Create product description
        const productDesc = document.createElement('p');
        productDesc.textContent = product.description || 'Product description';
        productDiv.appendChild(productDesc);
        
        // Create price list
        const priceList = document.createElement('div');
        priceList.className = 'price-list';
        
        const priceItem = document.createElement('div');
        priceItem.className = 'price-item';
        
        const storeName = document.createElement('span');
        storeName.textContent = product.brandName || 'Store';
        
        const price = document.createElement('span');
        const pricing = parseFloat(product.lowestPrice);
        price.textContent = product.prices ? `R${pricing.toFixed(2)}` : 'R0.00';
        
        priceItem.appendChild(storeName);
        priceItem.appendChild(price);
        priceList.appendChild(priceItem);
        productDiv.appendChild(priceList);
        
        // Create rating
        const rating = document.createElement('div');
        rating.className = 'rating';
        
        // Convert numeric rating to stars (assuming 0-5 scale)
        const fullStars = Math.floor(product.rating || 0);
        const hasHalfStar = (product.rating || 0) % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        
        rating.textContent = '★'.repeat(fullStars) + 
                           (hasHalfStar ? '½' : '') + 
                           '☆'.repeat(emptyStars);
        productDiv.appendChild(rating);
        
        // Create buttons
        const editBtn = document.createElement('button');
        editBtn.type = 'button';
        editBtn.className = 'edit-btn';
        editBtn.textContent = 'Edit Product';
        productDiv.appendChild(editBtn);
        
        const viewBtn = document.createElement('button');
        viewBtn.type = 'button';
        viewBtn.className = 'button';
        viewBtn.textContent = 'View Product';
        productDiv.appendChild(viewBtn);
        
        // Add line break between buttons
        productDiv.appendChild(document.createElement('br'));
        
        // Add product to container
        wishlistContainer.appendChild(productDiv);
    });
}

document.querySelectorAll('input[name="imageSource"]').forEach(radio => {
    radio.addEventListener('change', () => {
      const useUrl = document.getElementById('urlOption').checked;
      document.getElementById('productImageUrl').style.display = useUrl ? 'block' : 'none';
      document.getElementById('productFileInput').style.display = useUrl ? 'none' : 'block';
    });
});

document.getElementById('urlOption').dispatchEvent(new Event('change'));

document.getElementById('productForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const useUrl = document.getElementById('urlOption').checked;
    const imageUrlInput = document.getElementById('productImageUrl');
    const fileInput = document.getElementById('productFileInput');

    let imageUrl = '';

    if (useUrl) {
        imageUrl = imageUrlInput.value.trim();

        if (imageUrl === '') {
            alert("Please provide a valid image URL.");
            return;
        }
    } else {
        // Upload is not yet supported
        alert("Image upload is not yet supported. Please use an image URL.");
        document.getElementById('productForm').style.display = 'none';
        return;
    }
    const retailerID = localStorage.getItem('userID');

    const requestData = {
        type: 'AddProductRequest',
        retailerID: retailerID,
        price: parseFloat(document.getElementById('productPrice').value),
        brandName: document.getElementById('productBrand').value.trim(),
        description: document.getElementById('productDesc').value.trim(),
        imageURL: imageUrl,
        categoryName: document.getElementById('productCategory').value,
        outOfStock: document.getElementById('outOfStock').checked,
    };

    fetch('finalAPI.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text) });
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        alert("Add Product Request submitted successfuly");
        document.getElementById('productForm').reset();
    })
    .catch(error => {
        console.error("Failed to make product request:", error);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const searchButton = document.getElementById("search");

   // Should log the button element

    if (searchButton) {
        searchButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent form submission
            const searchQuery = document.getElementById("searching").value.trim();
            
            if (searchQuery) {
                searchproduct(searchQuery);
            } else {
                alert("Please enter a search term.");
            }
        });
    } else {
        console.error("Search button not found.");
    }
});

function searchproduct(query){
    if (cachedproducts.length === 0) {
        alert("No product data available. Try reloading.");
        return;
    }

    const filtered = cachedproducts.filter(product =>
        product.description.toLowerCase().includes(query.toLowerCase())
    );

    
    PopulateProd(filtered);
}

document.addEventListener("DOMContentLoaded", function () {
    const clear = document.getElementById("clear-search-button");
    
    
    if(clear){
        clear.addEventListener("click", function () {
            const searchQuery = document.getElementById("searching");// Clear the search input
            if(searchQuery){
                searchQuery.value = "";
                fetchData(); // Fetch and display all products again
            }else{
                alert("something is wrong!");
            }
            
        });
    }else{
        console.error("clear element not found:<")
    }
});

document.getElementById('editProductForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const retailerID = localStorage.getItem('userID');

    const useUrl = document.getElementById('editUrlOption').checked;
    const imageUrlInput = document.getElementById('editProductImageUrl');
    const fileInput = document.getElementById('editProductFileInput'); // If later added

    let imageUrl = '';

    if (useUrl) {
        imageUrl = imageUrlInput.value.trim();
        if (imageUrl === '') {
            alert("Please provide a valid image URL.");
            return;
        }
    } else {
        alert("Image upload is not yet supported. Please use an image URL.");
        return;
    }

    const requestData = {
        type: 'UpdateProductRequest',
        retailerID: retailerID,
        productID: document.getElementById('editProductName').value.trim(),
        productName: document.getElementById('editProductName').value.trim(),
        price: parseFloat(document.getElementById('editProductPrice').value),
        description: document.getElementById('editProductDescription').value.trim(),
        imageURL: imageUrl, 
        brandName: document.getElementById('editProductBrand').value.trim(),
        categoryName:  document.getElementById('editProductCategory').value.trim()
    };

    fetch('finalAPI.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text) });
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        alert("Product updated successfully");

        // Hide the modal
        document.getElementById('editProductModal').style.display = 'none';

        // Optional: Reset the form
        document.getElementById('editProductForm').reset();

        // Optionally refresh data
        // PopulateProd(data.data); // or re-fetch product list
    })
    .catch(error => {
        console.error("Failed to update product:", error);
    });
});




window.onload = function(){
    fetchData();
};



document.addEventListener('DOMContentLoaded', function() {
    // Add Product Modal Elements
    const addModal = document.getElementById("addProductModal");
    const addBtn = document.querySelector(".add");
    const closeAddBtn = document.querySelector(".close");
    const cancelAddBtn = document.getElementById("cancelButton");
    const addForm = document.getElementById("productForm");

    // Edit Product Modal Elements
    const editModal = document.getElementById("editProductModal");
    const closeEditBtn = document.querySelector(".close-edit");
    const cancelEditBtn = document.getElementById("cancelEditButton");
    const editForm = document.getElementById("editProductForm");

    // =============================================
    // ADD PRODUCT MODAL FUNCTIONALITY
    // =============================================
    // Add Product Modal - Image Toggle
    const urlOption = document.getElementById('urlOption');
    const uploadOption = document.getElementById('uploadOption');
    const productImageUrlInput = document.getElementById('productImageUrl');
    const productFileInput = document.getElementById('productFileInput');
    const fileUploadInput = document.createElement('input');

    fileUploadInput.type = 'file';
    fileUploadInput.accept = 'image/*';
    fileUploadInput.style.display = 'none';
    document.body.appendChild(fileUploadInput);

    
    if (urlOption && uploadOption) {
        urlOption.addEventListener('change', () => {
            productImageUrlInput.style.display = 'block';
            productFileInput.style.display = 'none';
        });

        uploadOption.addEventListener('change', () => {
            productImageUrlInput.style.display = 'none';
            productFileInput.style.display = 'block';
        });

        productFileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                console.log('Selected file:', file.name);
                // Optionally preview or handle the file
            }
        });
    }


    // =============================================
    // UPDATED EDIT PRODUCT HANDLING USING EVENT DELEGATION
    // =============================================
    
    // Edit Modal Image Upload Handling
    const editUrlOption = document.getElementById('editUrlOption');
    const editUploadOption = document.getElementById('editUploadOption');
    const editImageUrlInput = document.getElementById('editProductImageUrl');
    const editFileUploadInput = document.createElement('input');
    editFileUploadInput.type = 'file';
    editFileUploadInput.accept = 'image/*';
    editFileUploadInput.style.display = 'none';
    document.body.appendChild(editFileUploadInput);

    // Toggle between URL and File Upload in Edit Modal
    if (editUrlOption && editUploadOption) {
        editUrlOption.addEventListener('change', function() {
            editImageUrlInput.style.display = 'block';
        });
        
        editUploadOption.addEventListener('change', function() {
            editImageUrlInput.style.display = 'none';
            editFileUploadInput.click();
        });
        
        editFileUploadInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                console.log('Selected file for edit:', file.name);
                // Handle file upload for edit
            }
        });
    }

    // Event delegation for edit buttons
    document.addEventListener('click', function(e) {
        // Handle edit button clicks
        if (e.target && e.target.classList.contains('edit-btn')) {
            const productCard = e.target.closest('.product');
            
            // Get all product data including image
            const productName = productCard.querySelector('h2').textContent;
            const productDescription = productCard.querySelector('p').textContent;
            const productImage = productCard.querySelector('img').src;
            const productPrice = productCard.querySelector('.price-item span:last-child').textContent.replace('R', '').trim();
            
            // Fill the edit form
            document.getElementById('editProductName').value = productName;
            document.getElementById('editProductDescription').value = productDescription;
            document.getElementById('editProductImageUrl').value = productImage;
            document.getElementById('editProductPrice').value = productPrice;
            
            // Set to URL option by default
            if (editUrlOption) editUrlOption.checked = true;
            if (editImageUrlInput) editImageUrlInput.style.display = 'block';
            
            // Show the modal
            editModal.style.display = "block";
        }

        // Handle view button clicks
        if (e.target && e.target.classList.contains('button')) {
            const productCard = e.target.closest('.product');
            const product = {
                name: productCard.querySelector('h2').textContent,
                description: productCard.querySelector('p').textContent,
                imageUrl: productCard.querySelector('img').src,
                brandName: productCard.querySelector('.price-item span:first-child').textContent,
                lowestPrice: parseFloat(productCard.querySelector('.price-item span:last-child').textContent.replace('R', ''))
            };
            viewProduct(product);
        }
    });

    // Updated Edit Form Submission
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productData = {
                name: document.getElementById('editProductName').value,
                description: document.getElementById('editProductDescription').value,
                price: document.getElementById('editProductPrice').value,
                imageSource: editUrlOption.checked ? 'url' : 'upload',
                imageUrl: editImageUrlInput.value
            };
            
            // Basic validation
            if (!productData.name || !productData.price) {
                alert('Please fill in all required fields');
                return;
            }
            
            if (productData.imageSource === 'url' && !productData.imageUrl) {
                alert('Please enter an image URL');
                return;
            }
            
            if (productData.imageSource === 'upload' && !editFileUploadInput.files[0]) {
                alert('Please select an image to upload');
                return;
            }
            
            console.log('Updated Product Data:', productData);
            alert("Product updated successfully!");
            editModal.style.display = "none";
            
            // Here you would typically send the updated data to your server
            // and then refresh the product list
        });
    }

    // =============================================
    // SHARED MODAL HANDLERS
    // =============================================
    
    // Open Add Modal
    if (addBtn) addBtn.addEventListener('click', () => addModal.style.display = "block");
    
    // Close Handlers
    if (closeAddBtn) closeAddBtn.addEventListener('click', () => addModal.style.display = "none");
    if (cancelAddBtn) cancelAddBtn.addEventListener('click', () => addModal.style.display = "none");
    if (closeEditBtn) closeEditBtn.addEventListener('click', () => editModal.style.display = "none");
    if (cancelEditBtn) cancelEditBtn.addEventListener('click', () => editModal.style.display = "none");

    // Close when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target == addModal) addModal.style.display = "none";
        if (event.target == editModal) editModal.style.display = "none";
    });
});


<?php

?>


<!DOCTYPE html>
<html>
    <head>
        <title>
            Products
        </title>
        <script src="retailer_prod_js.js" defer></script>
    <style>
        @font-face {
            font-family: 'Porkys';
            src:url('FONT/CalSans-Regular.ttf');
        }
        
        .navbar {
            background-color: rgb(17, 61, 42);
            overflow: hidden;
            padding: 10px 10px;
            border-radius: 15px;
            justify-content: space-between;
            display: flex;
            
        }
        .nav-right a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            display: inline-block;
            font-family: "Porkys", sans-serif;
            font-size: 25px;
        }
        .nav-right a:hover {
            background-color: #507541;
            border-radius: 15px;
            
        }

        .navbar-b {
            background-color: rgb(17, 61, 42);
            overflow: hidden;
            padding: 10px 10px;
            border-radius: 15px;
            display: flex;
            margin-top: 10px;
            height: 50px;
            width: 1250px;
            margin-left: auto;
            margin-right: auto;
        }
        
        body{
            background-color: #f0dcbd;
        }

        .text{
            color: white;
            font-size: 45px;
            align-content: center;
            font-family: "Porkys", sans-serif; 
        }

        .wishlist-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start; /* Align products from left to right */
            gap: 20px; /* Space between products */
            padding: 20px;
            margin: 0 auto;
            max-width: 1400px; /* Adjust based on your layout */
        }

        .search-container {
            display: flex;
            justify-content: space-between; /* This will push the add button to the right */
            align-items: center; /* Vertically center all items */
            margin: 20px auto;
            width: 100%;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .search-container input[type="text"] {
            padding: 10px; /* Adds padding inside the input */
            border: 2px solid #ccc; /* Light gray border */
            border-radius: 5px; /* Rounded corners */
            width: 250px;
        }

        #search{
            width: 100px;
            height: 40px;
            margin-inline: 5px;
            background-color: #f0dcbd;
            font-family: 'TanTankiwood', sans-serif;
            transition: background-color 0.3s ease;
            border-radius: 15px;
            font-size: 14px;
            transition: transform 0.2s ease, background-color 0.3s ease;
            color:#2a4aa1;
            border-color: gray;
            
        }

        #clear-search-button{
            width: 110px;
            height: 35px;
            margin-inline: 5px;
            background-color: #f0dcbd;
            font-family: 'TanTankiwood', sans-serif;
            transition: background-color 0.3s ease;
            border-radius: 15px;
            font-size: 14px;
            transition: transform 0.2s ease, background-color 0.3s ease;
            color:#2a4aa1;
            margin-bottom: 10px;
        }

        #clear-search-button:hover{
            background-color: #e4c0a1;
            transform: scale(1.1);
        }

        #search:hover{
            background-color: #e4c0a1;
            transform: scale(1.1);
        }

        .sort-container label{ 
            font-weight: lighter;
            color: #2a4aa1;
            font-family: 'Porkys',sans-serif;
            margin-left: 5px;
        } 


        .sort-container select{
            
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: 'Porkys';
            font-weight:normal;
            font-size: 16px;
            height: 40px;
            width: 170px;
        }  

        .product {
            width: 400px; /* Match your image width */
            padding: 15px;
            box-sizing: border-box;
            text-align: center;
            border: 2px solid #113d2a;
            border-radius: 15px;
            background-color: white;
            margin-bottom: 20px; /* Space between rows */
        }

        .product img{
            width: 4000px;
            height: 350px;
            border-radius: 15px;
            max-width: 100%;
        }

        h2{
            color: #113d2a;
            font-family: "Porkys", sans-serif;
            font-size: 20px;
            font-weight: lighter;
            width: 350px;
        }
        p{
            color: #28805a;
            font-family: "Porkys", sans-serif;
            font-size: 15px;
            font-weight: lighter;
            margin-bottom: 10px;
        }

        .button{
            width: 350px;
            height: 40px;
            margin-inline: 5px;
            background-color: #f0dcbd;
            font-family: 'Porkys', sans-serif;
            transition: background-color 0.3s ease;
            border-radius: 15px;
            font-size: 16px;
            transition: transform 0.2s ease, background-color 0.3s ease;
            color: black;
            margin-bottom: 10px;
        }
        .button:hover{
            background-color: #5bb890;
            transform: scale(1.1);
        }

        .search-group {
            display: flex;
            justify-content: center; /* Centers the search and sort elements */
            align-items: center; /* Vertically aligns them */
            gap: 20px; /* Space between elements */
            flex-grow: 1; /* Takes up available space */
            flex-wrap: nowrap; /* Prevent wrapping */
            margin: 0 auto; /* Center the group */
        }


        .add{
            width: 120px;
            height: 40px;
            margin-inline: 5px;
            background-color: #da754d;
            font-family: 'Porkys', sans-serif;
            transition: background-color 0.3s ease;
            border-radius: 15px;
            font-size: 16px;
            transition: transform 0.2s ease, background-color 0.3s ease;
            color: black;
            margin-bottom: 10px;
            margin-left: auto;
        }
        
        .add:hover{
            transform: scale(1.1);
        }

        span{
            font-family: 'Porkys', sans-serif;
        }

        .price-list{
            margin: 15px 0;
        }

        .price-item{
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 2px solid #eee;
        }

        .rating{
            font-size: 20px;
        }

        
        .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f0dcbd;
            padding: 30px;
            border: 1px solid #113d2a;
            border-radius: 15px;
            width: 400px; /* Fixed width */
            max-width: 90%; /* Responsive */
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            max-height: 90vh; /* NEW: prevent it from going off screen */
            overflow-y: auto;
        }

        .form-group {
        margin-bottom: 20px;
        }

        .form-group label {
        display: block;
        margin-bottom: 8px;
        font-family: 'Porkys', sans-serif;
        color: #113d2a;
        font-size: 16px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #113d2a;
        border-radius: 5px;
        font-family: 'Aileron', sans-serif;
        box-sizing: border-box;
        }

        .image-upload {
        margin-top: 10px;
        }

        .upload-option {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        }

        .upload-option input[type="radio"] {
        margin-right: 5px;
        }

        .upload-option label {
        margin-right: 15px;
        font-family: 'Aileron', sans-serif;
        cursor: pointer;
        }

        .checkbox-group {
        display: flex;
        align-items: center;
        }

        .checkbox-group input[type="checkbox"] {
        margin-right: 10px;
        }

        .modal-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        }

        .modal-buttons button {
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        font-family: 'Porkys', sans-serif;
        cursor: pointer;
        font-size: 16px;
        }

        .modal-buttons button:first-child {
        background-color: #f0dcbd;
        color: #113d2a;
        border: 1px solid #113d2a;
        }

        .modal-buttons button:last-child {
        background-color: #113d2a;
        color: white;
        }

        .close {
        color: #113d2a;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        margin-top: -10px;
        margin-right: -10px;
        }

        .close:hover {
        color: #da754d;
        }

        /* Edit Modal Specific Styles */
        .price-list-edit {
        margin: 15px 0;
        padding: 10px;
        background-color: rgba(17, 61, 42, 0.1);
        border-radius: 10px;
        }

        .price-list-edit h3 {
        color: #113d2a;
        font-family: 'Porkys', sans-serif;
        margin-bottom: 10px;
        font-weight: lighter;
        }

        .price-item-edit {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            font-family: 'Porkys', sans-serif;
        }

        .price-item-edit span {
            color: #113d2a;
            font-size: 16px;
        }

        .price-item-edit input {
            width: 100px;
            padding: 8px;
            border: 1px solid #113d2a;
            border-radius: 5px;
            font-family: 'Aileron', sans-serif;
        }

        .close:hover {
        color: #da754d;
        }

        .edit-btn{
            width: 350px;
            height: 40px;
            margin-inline: 5px;
            background-color: #f0dcbd;
            font-family: 'Porkys', sans-serif;
            transition: background-color 0.3s ease;
            border-radius: 15px;
            font-size: 16px;
            transition: transform 0.2s ease, background-color 0.3s ease;
            color: black;
            margin-bottom: 10px;
        }

        .edit-btn:hover{
            background-color: #5bb890;
            transform: scale(1.1);
        }

        .delete-btn{
            width: 350px;
            height: 40px;
            margin-inline: 5px;
            background-color: #f0dcbd;
            font-family: 'Porkys', sans-serif;
            transition: background-color 0.3s ease;
            border-radius: 15px;
            font-size: 16px;
            transition: transform 0.2s ease, background-color 0.3s ease;
            color: black;
            margin-bottom: 10px;
        }

        .delete-btn:hover{
            background-color: #5bb890;
            transform: scale(1.1);
        }

        
        textarea {
            width: 370px;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            border-color: #113d2a;
            font-size: 1rem;
            font-family: inherit;
            resize: vertical;
            box-shadow: 2px 2px 6px rgba(0,0,0,0.05);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
    </style>
    </head>

    <body>
        <div class="navbar">
            <div class = "text">
                CompareIt!
            </div>
            <div class = "nav-right">
                <a href="https://wheatley.cs.up.ac.za/u24827313/COS216/PA2/deals.html">Best Deals</a>
                <a href="https://wheatley.cs.up.ac.za/u24827313/COS216/PA2/index.html">Products</a>
                <a href="https://wheatley.cs.up.ac.za/u24827313/COS216/PA2/cart.html">Cart</a>
            </div>
        </div>

        <div class="navbar-b">
            <div class = "text">
                Products Page
            </div>
        </div>

        <div class="search-container">
            <div class="search-group">
                <div class="search-bar">
                    <input type="text" id="searching" placeholder="Search..." name="search">
                    <button type="submit" id="search">Search</button>
                    <button id="clear-search-button">Clear Search</button>
                </div>
        
                <div class="sort-container">
                    <label for="sort-by">Sort by:</label>
                    <select id="sort-by">
                        <option value="newest">Newest Arrivals</option>
                        <option value="price">Price</option>
                    </select>
                </div>
            </div>

            <button type="button" class="add">Add product!</button>
        </div>

       

        <div class="wishlist-container">
            
        </div>

        <div id="addProductModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Add New Product</h2>
                <form id="productForm">
                <div class="form-group">
                    <label for="productName">Name:</label>
                    <input type="text" id="productName" required>
                </div>
                
                <div class="form-group">
                    <label for="productPrice">Price (R):</label>
                    <input type="number" id="productPrice" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="productBrand">Brand:</label>
                    <input type="text" id="productBrand" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="productDesc">Description:</label>
                    <textarea id="productDesc" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Product Image:</label>
                    <div class="image-upload">
                    <div class="upload-option">
                        <input type="radio" id="urlOption" name="imageSource" checked>
                        <label for="urlOption">Image URL</label>
                        <input type="radio" id="uploadOption" name="imageSource">
                        <label for="uploadOption">Upload Image</label>
                    </div>
                    <input type="text" id="productImageUrl" placeholder="Enter image URL">
                    <input type="file" id="productFileInput" accept="image/*" style="display: none;">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="productCategory">Category:</label>
                    <select id="productCategory">
                    <option value="books">Books</option>
                    <option value="clothing">Clothing</option>
                    <option value="electronics">Electronics</option>
                    <option value="kitchenware">Fruit&veg</option>
                    <option value="kitchenware">Kitchenware</option>
                    <option value="readyMade">readyMade</option>
                    <option value="Tools">Tools</option>
                    </select>
                </div>
                
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="outOfStock">
                    <label for="outOfStock">Out of Stock</label>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" id="cancelButton">Cancel</button>
                    <button type="submit">Add Product</button>
                </div>
                </form>
            </div>
        </div>

        <!-- Edit Product Modal -->
<div id="editProductModal" class="modal">
    <div class="modal-content">
        <span class="close-edit">&times;</span>
        <h2>Edit Product</h2>
        <form id="editProductForm">
            <div class="form-group">
                <label for="editProductName">Product Name</label>
                <input type="text" id="editProductName" required>
            </div>

            <div class="form-group">
                    <label for="editProductBrand">Brand:</label>
                    <input type="text" id="editProductBrand" step="0.01" required>
            </div>

            
            <div class="form-group">
                <label for="editProductDescription">Description</label>
                <input type = "text" id="editProductDescription" rows="3"></input>
            </div>

            <div class="form-group">
                <label>Product Image:</label>
                <div class="image-upload">
                    <div class="upload-option">
                        <input type="radio" id="editUrlOption" name="editImageSource" checked>
                        <label for="editUrlOption">Image URL</label>
                        <input type="radio" id="editUploadOption" name="editImageSource">
                        <label for="editUploadOption">Upload Image</label>
                    </div>
                    <input type="text" id="editProductImageUrl" placeholder="Enter image URL">
                </div>
            </div>
            
            <div class="form-group">
                <label for="editProductPrice">Price (R)</label>
                <input type="number" id="editProductPrice" step="0.01" required>
            </div>
            <div class="form-group">
                    <label for="editProductCategory">Category:</label>
                    <select id="editProductCategory">
                    <option value="books">Books</option>
                    <option value="clothing">Clothing</option>
                    <option value="electronics">Electronics</option>
                    <option value="kitchenware">Fruit&veg</option>
                    <option value="kitchenware">Kitchenware</option>
                    <option value="readyMade">readyMade</option>
                    <option value="Tools">Tools</option>
                    </select>
            </div>
            
            <div class="modal-buttons">
                <button type="button" id="cancelEditButton">Cancel</button>
                <button type="submit">Update Product</button>
            </div>
        </form>
    </div>
</div>
    </body>
</html>
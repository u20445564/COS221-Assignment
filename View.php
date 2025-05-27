<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Compare It! - Product View</title>
  <script src="view_js.js" defer></script>
  <style>
    @font-face {
          font-family: 'Porkys';
          src:url('FONT/CalSans-Regular.ttf');
    }
    @font-face {
          font-family: 'Aileron';
          src: url('FONT/Aileron-Regular.otf');
    }
    :root {
      --bg-color: #f0dcbd;
      --text-color: #000;
      --card-bg: white;
      --nav-bg: rgb(17, 61, 42);
      --border-color: #113d2a;
      --input-bg: white;
      --shadow-color: rgba(0,0,0,0.1);
      --price-text-color: #666;
    }

    [data-theme="dark"] {
      --bg-color: #1a1a1a;
      --text-color: #e0e0e0;
      --card-bg: #2d2d2d;
      --nav-bg: #0d2818;
      --border-color: #4a6b5a;
      --input-bg: #3a3a3a;
      --shadow-color: rgba(255,255,255,0.1);
      --price-text-color: #b0b0b0;
    }

    * {
      transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: var(--bg-color);
      color: var(--text-color);
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    nav {
      background-color: var(--nav-bg);
      padding: 15px 20px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 15px;
      margin: 10px;
      flex-wrap: wrap;
      gap: 10px;
    }

    nav .brand {
      color: white;
      font-size: 35px;
      font-family: "Porkys", sans-serif;
    }

    .nav-center {
      display: flex;
      align-items: center;
      gap: 15px;
      flex: 1;
      justify-content: center;
    }

    .search-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .search-container input {
      padding: 8px 12px;
      border-radius: 10px;
      border: 2px solid var(--border-color);
      background-color: var(--input-bg);
      color: var(--text-color);
      width: 200px;
    }

    .search-container select {
      padding: 8px 12px;
      border-radius: 10px;
      border: 2px solid var(--border-color);
      background-color: var(--input-bg);
      color: var(--text-color);
    }

    .search-container button {
      padding: 8px 15px;
      border-radius: 10px;
      background-color: var(--bg-color);
      border: 2px solid var(--border-color);
      color: var(--text-color);
      cursor: pointer;
    }

    .search-container button:hover {
      background-color: var(--border-color);
      color: white;
    }

    .nav-links {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .nav-links a, .nav-links button {
      color: white;
      text-decoration: none;
      padding: 10px 15px;
      display: inline-block;
      font-family: "Porkys", sans-serif;
      font-size: 18px;
      background: none;
      border: 1px solid transparent;
      border-radius: 10px;
      cursor: pointer;
    }

    .nav-links a:hover, .nav-links button:hover {
      background-color: #507541;
      border-radius: 10px;
    }

    main {
      flex: 1;
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
      width: 100%;
      box-sizing: border-box;
    }

    .product-view-container {
      background-color: var(--card-bg);
      border-radius: 15px;
      border: 2px solid var(--border-color);
      padding: 30px;
      box-shadow: 0 2px 10px var(--shadow-color);
      margin-bottom: 30px;
    }

    .product-header {
      display: flex;
      gap: 30px;
      margin-bottom: 30px;
      align-items: flex-start;
    }

    .image-section {
      flex: 1;
      max-width: 500px;
    }

    .image-carousel {
      position: relative;
      border-radius: 15px;
      overflow: hidden;
      border: 2px solid var(--border-color);
    }

    .carousel-container {
      position: relative;
      width: 100%;
      height: 400px;
    }

    .carousel-slide {
      display: none;
      width: 100%;
      height: 100%;
    }

    .carousel-slide.active {
      display: block;
    }

    .carousel-slide img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      background-color: white;
    }

    .carousel-nav {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0,0,0,0.5);
      color: white;
      border: none;
      padding: 10px 15px;
      cursor: pointer;
      font-size: 18px;
      border-radius: 5px;
    }

    .carousel-nav:hover {
      background-color: rgba(0,0,0,0.8);
    }

    .carousel-prev {
      left: 10px;
    }

    .carousel-next {
      right: 10px;
    }

    .carousel-indicators {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 15px;
    }

    .indicator {
      width: 60px;
      height: 60px;
      border: 2px solid var(--border-color);
      border-radius: 8px;
      cursor: pointer;
      overflow: hidden;
      opacity: 0.6;
      transition: opacity 0.3s ease;
    }

    .indicator.active {
      opacity: 1;
      border-color: #29805a;
    }

    .indicator img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .product-info {
      flex: 1;
    }

    .product-title {
      font-size: 28px;
      font-weight: bold;
      color: var(--border-color);
      margin-bottom: 15px;
    }

    .product-description {
      font-size: 16px;
      line-height: 1.6;
      color: var(--text-color);
      margin-bottom: 25px;
    }

    .price-comparison {
      margin-bottom: 25px;
    }

    .price-comparison h3 {
      color: var(--border-color);
      margin-bottom: 15px;
      font-size: 20px;
    }

    .price-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
    }

    .price-card {
      background-color: var(--input-bg);
      border: 2px solid var(--border-color);
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      position: relative;
    }
    .price-card-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .card-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }

    

    .price-card.best-price {
      border-color: #29805a;
      background-color: #f8fff9;
    }

    [data-theme="dark"] .price-card.best-price {
      background-color: #1a2d1a;
    }

    .price-card h4 {
      margin: 0 0 10px 0;
      color: var(--border-color);
      font-size: 18px;
    }

    .price-card .price {
      font-size: 24px;
      font-weight: bold;
      color: var(--text-color);
    }

    .best-price-badge {
      position: absolute;
      top: -10px;
      right: -10px;
      background-color: #29805a;
      color: white;
      padding: 5px 10px;
      border-radius: 15px;
      font-size: 12px;
      font-weight: bold;
    }

    .store-link {
      display: block;
      margin-top: 10px;
      padding: 8px 15px;
      background-color: var(--border-color);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-size: 14px;
    }

    .store-link:hover {
      background-color: #0f3326;
    }

    .reviews-section {
      background-color: var(--card-bg);
      border-radius: 15px;
      border: 2px solid var(--border-color);
      padding: 30px;
      box-shadow: 0 2px 10px var(--shadow-color);
      margin: 10px;
    }

    .reviews-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }

    .reviews-header h3 {
      color: var(--border-color);
      font-size: 24px;
      margin: 0;
    }

    .add-response-btn {
      background: none;
      border: 1px solid var(--border-color);
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 12px;
      color: var(--text-color);
    }

    .add-response-btn:hover {
      background-color: var(--input-bg);
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

    .review-form {
      background-color: var(--input-bg);
      border: 2px solid var(--border-color);
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 25px;
      display: none;
    }

    .review-form.active {
      display: block;
    }

    .rating-input {
      margin-bottom: 15px;
    }

    .rating-input label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: var(--text-color);
    }

    .star-rating {
      display: flex;
      gap: 5px;
      margin-bottom: 15px;
    }

    .star-rating .star {
      font-size: 24px;
      cursor: pointer;
      color: #ddd;
      transition: color 0.3s ease;
    }

    .star-rating .star:hover,
    .star-rating .star.active {
      color: #ffcc00;
    }

    .review-form textarea {
      width: 100%;
      min-height: 100px;
      padding: 12px;
      border: 2px solid var(--border-color);
      border-radius: 8px;
      background-color: var(--input-bg);
      color: var(--text-color);
      resize: vertical;
      box-sizing: border-box;
    }

    .review-form-buttons {
      display: flex;
      gap: 10px;
      margin-top: 15px;
    }

    .review-form-buttons button {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    .submit-review {
      background-color: #29805a;
      color: white;
    }

    .cancel-review {
      background-color: #dc3545;
      color: white;
    }

    .review-item {
      border-bottom: 1px solid var(--border-color);
      padding: 20px 0;
    }

    .review-item:last-child {
      border-bottom: none;
    }

    .review-header-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .reviewer-name {
      font-weight: bold;
      color: var(--border-color);
    }

    .review-date {
      color: var(--price-text-color);
      font-size: 14px;
    }

    .review-rating {
      color: #ffcc00;
      font-size: 16px;
      margin-bottom: 10px;
    }

    .review-text {
      color: var(--text-color);
      line-height: 1.6;
      margin-bottom: 10px;
    }

    .review-helpful {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .helpful-btn {
      background: none;
      border: 1px solid var(--border-color);
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 12px;
      color: var(--text-color);
    }

    .helpful-btn:hover {
      background-color: var(--input-bg);
    }

    .submit-response-btn{
      background: none;
      border: 1px solid var(--border-color);
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 12px;
      color: var(--text-color);
    }
    .submit-response-btn:hover {
      background-color: var(--input-bg);
    }
    

    /* Footer styles */
    footer {
      background-color: var(--nav-bg);
      color: white;
      padding: 20px;
      margin: 20px;
      border-radius: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      flex-wrap: wrap;
      gap: 20px;
    }

    .footer-left {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .footer-left h3 {
      font-family: "Porkys", sans-serif;
      font-size: 24px;
      margin: 0;
      color: white;
    }

    .footer-left p {
      margin: 0;
      color: #b0b0b0;
      font-size: 14px;
    }

    .theme-toggle {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .theme-toggle label {
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    .edit-btn{
            width: 550px;
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
            width: 550px;
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

    .toggle-switch {
      position: relative;
      width: 60px;
      height: 30px;
      background-color: #ccc;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .toggle-switch.active {
      background-color: #4CAF50;
    }

    .toggle-slider {
      position: absolute;
      top: 3px;
      left: 3px;
      width: 24px;
      height: 24px;
      background-color: white;
      border-radius: 50%;
      transition: transform 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
    }

    .toggle-switch.active .toggle-slider {
      transform: translateX(30px);
    }

    .copyright {
      text-align: center;
      width: 100%;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid #4a6b5a;
      color: #b0b0b0;
      font-size: 12px;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
      nav {
        flex-direction: column;
        text-align: center;
      }

      nav .brand {
        font-size: 28px;
        margin-bottom: 10px;
      }

      .nav-center {
        flex-direction: column;
        gap: 10px;
        width: 100%;
      }

      .search-container {
        flex-direction: column;
        width: 100%;
      }

      .search-container input {
        width: 100%;
      }

      .header-divider {
         height: 1px;
          background-color: black;
          margin: 0 30px 20px 30px; /* Adjust margins as needed */
          opacity: 0.5;
      }

      .nav-links {
        flex-wrap: wrap;
        justify-content: center;
      }

      .product-header {
        flex-direction: column;
        gap: 20px;
      }

      .price-grid {
        grid-template-columns: 1fr;
      }

      .carousel-container {
        height: 300px;
      }

      .footer-content {
        flex-direction: column;
        text-align: center;
      }
      
      .theme-toggle {
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <nav>
    <div class="brand">Compare It!</div>
    
    <div class="nav-center">
      <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search products...">
        <select id="sortSelect">
          <option value="">Sort By</option>
          <option value="name">Name</option>
          <option value="priceLowHigh">Price: Low to High</option>
          <option value="priceHighLow">Price: High to Low</option>
        </select>
        <button id="searchBtn">Search</button>
      </div>
    </div>

    <div class="nav-links">
      <a href="index.html">Home</a>
      <a href="products.html">Products</a>
      <button class="logout-btn" onclick="logout()">Logout</button>
    </div>
  </nav>

  <main>
  <div class="product-view-container">
    <div class="product-header">
      <div class="image-section">
        <div class="image-carousel">
          <div class="carousel-container">
            <!-- JS will populate .carousel-slide elements here -->
          </div>
          <button class="carousel-nav carousel-prev" onclick="changeSlide(-1)">❮</button>
          <button class="carousel-nav carousel-next" onclick="changeSlide(1)">❯</button>
        </div>
        <div class="carousel-indicators">
          <!-- JS will populate indicators here -->
        </div>
      </div>

      <div class="product-info">
        <h1 class="product-title" id="productTitle"></h1>

        <div class="product-description" id="productDescription">
          <!-- JS will insert description paragraphs -->
        </div>

        <div class="price-comparison">
          <h3>Price</h3>
          <div class="price-grid" id="priceGrid">
            <!-- JS will populate price cards here -->
          </div>
        </div>
      </div>
    </div>
  </div>
</main>


<div class="reviews-section">
  <div class="reviews-header">
    <h3>Customer Reviews</h3>
    <div class="header-divider"></div> <!-- Add this line -->
  </div>

    <div class="review-form" id="reviewForm">
      <div class="rating-input">
        <label>Your Rating:</label>
        <div class="star-rating" id="starRating">
          <span class="star" data-rating="1">★</span>
          <span class="star" data-rating="2">★</span>
          <span class="star" data-rating="3">★</span>
          <span class="star" data-rating="4">★</span>
          <span class="star" data-rating="5">★</span>
        </div>
        <input type="hidden" id="selectedRating" value="0">
      </div>
      <textarea id="reviewText" placeholder="Write your review here..." required></textarea>
      <div class="review-form-buttons">
        <button class="submit-review" onclick="submitReview()">Submit Review</button>
        <button type="button" class="cancel-review" onclick="toggleReviewForm()">Cancel</button>
      </div>
    </div>

    <div class="reviews-list" id="reviewsList">
      <!-- Reviews will be dynamically inserted here by JavaScript -->
      <div class="no-reviews-message" style="display: none;">
        <p>No reviews yet. Be the first to review!</p>
      </div>
    </div>
  </div>
  </main>

  <footer>
    <div class="footer-content">
      <div class="footer-left">
        <h3>Compare It!</h3>
        <p>Your trusted price comparison platform</p>
        <p>Compare prices across major South African retailers</p>
      </div>
      
      <div class="theme-toggle">
        <label for="themeToggle">🌙 Dark Mode</label>
        <div class="toggle-switch" id="themeToggle">
          <div class="toggle-slider">☀</div>
        </div>
      </div>
    </div>
    
    <div class="copyright">
      <p>&copy; 2025 Compare It! All rights reserved. | Helping you find the best deals.</p>
    </div>
  </footer>

</body>
</html>
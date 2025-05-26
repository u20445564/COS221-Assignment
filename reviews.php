<!DOCTYPE html>
<!-- saved from url=(0092)file:///C:/Users/jessi/OneDrive/Desktop/COS221/Assignment%205/Front%20end/reviews%20(2).html -->
<html lang="en" data-theme="dark"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <title>Compare It! - Reviews</title>
  <style>
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
      --border-color: rgba(0,0,0,0.1);
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
      padding: 10px 10px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 15px;
      margin: 10px;
    }

    nav .text {
      color: white;
     font-size: 45px;
      font-family: "Porkys", sans-serif;
    }

    nav .nav-links {
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }

    nav .nav-links a {
      color: white;
      text-decoration: none;
      padding: 14px 20px;
      display: inline-block;
      font-family: "Porkys", sans-serif;
      font-size: 25px;
    }

    nav .nav-links a:hover {
      background-color:rgb(112, 145, 99);
      border-radius: 15px;
    }

    main {
      flex: 1;
    }

    .header-section {
      background-color: var(--nav-bg);
      color: white;
      padding: 20px;
      margin: 20px;
      border-radius: 15px;
    }
    
    .header-section h2 {
      margin: 0;
      font-size: 30px;
      font-family: "Porkys", sans-serif;
      color: white;
      font-weight: lighter;
    }

    .reviews-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .filter-bar {
      background-color: var(--card-bg);
      border-radius: 15px;
      border: 2px solid var(--border-color);
      margin-bottom: 20px;
      padding: 20px;
      display: flex;
      gap: 15px;
      align-items: center;
      flex-wrap: wrap;
    }

    .filter-bar select, .filter-bar input {
      padding: 10px;
      border-radius: 10px;
      border: 2px solid var(--border-color);
      background-color: var(--input-bg);
      color: var(--text-color);
    }

    .filter-bar button {
      padding: 10px 20px;
      border-radius: 10px;
      background-color: var(--border-color);
      color: white;
      border: none;
      cursor: pointer;
      font-weight: bold;
    }

    .filter-bar button:hover {
      background-color: #0f3326;
    }

    .review-card {
      background-color: var(--card-bg);
      border-radius: 15px;
      border: 2px solid var(--border-color);
      margin-bottom: 20px;
      padding: 20px;
      box-shadow: 0 2px 5px var(--shadow-color);
    }

    .review-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      border-bottom: 2px solid #f0dcbd;
      padding-bottom: 10px;
    }

    .product-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .product-image {
      width: 60px;
      height: 60px;
      object-fit: contain;
      border-radius: 10px;
      border: 1px solid #ddd;
    }

    .product-details h3 {
      margin: 0;
      color: var(--border-color);
      font-size: 18px;
    }

    .product-details p {
      margin: 5px 0 0 0;
      color: var(--price-text-color);
      font-size: 14px;
    }

    .review-meta {
      text-align: right;
      color: var(--price-text-color);
    }

    .rating {
      color: #ffcc00;
      font-size: 20px;
      margin: 5px 0;
    }

    .review-date {
      font-size: 12px;
      color: #999;
    }

    .customer-review {
      margin: 15px 0;
    }

    .reviewer-name {
      font-weight: bold;
      color: var(--border-color);
      margin-bottom: 8px;
    }

    .review-text {
      color: var(--text-color);
      line-height: 1.5;
      margin-bottom: 10px;
    }

    .review-helpful {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      color: var(--price-text-color);
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

    .retailer-responses {
      background-color: var(--input-bg);
      border-radius: 10px;
      margin-top: 15px;
      padding: 15px;
      border-left: 4px solid var(--border-color);
    }

    .retailer-response {
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid var(--border-color);
    }

    .retailer-response:last-child {
      margin-bottom: 0;
      padding-bottom: 0;
      border-bottom: none;
    }

    .retailer-name {
      font-weight: bold;
      color: var(--border-color);
      margin-bottom: 5px;
    }

    .retailer-badge {
      display: inline-block;
      background-color: var(--border-color);
      color: white;
      padding: 2px 8px;
      border-radius: 12px;
      font-size: 11px;
      margin-left: 10px;
    }

    .response-text {
      color: var(--text-color);
      line-height: 1.4;
      margin: 8px 0;
    }

    .response-date {
      font-size: 12px;
      color: #999;
    }

    .no-responses {
      color: #999;
      font-style: italic;
      text-align: center;
      padding: 20px;
    }

    /* Your Review Styles */
    .your-review {
      border: 3px rgb(17, 61, 42) !important;
      background: linear-gradient(135deg, #f8fff9 0%, #ffffff 100%);
    }

    [data-theme="dark"] .your-review {
      background: linear-gradient(135deg, #1a2d1a 0%, #2d2d2d 100%);
    }

    .your-review .review-header {
      border-bottom: 2px rgb(17, 61, 42);
    }

    .your-review-badge {
      background-color:rgb(17, 61, 42);
      color: white;
      padding: 4px 12px;
      border-radius: 15px;
      font-size: 12px;
      font-weight: bold;
      margin-left: 10px;
    }

    .no-feedback {
      background-color: #f0f8ff;
      border: 1px dashed rgb(17, 61, 42);
      border-radius: 10px;
      padding: 15px;
      margin-top: 15px;
      text-align: center;
      color: #666;
      font-style: italic;
    }

    [data-theme="dark"] .no-feedback {
      background-color: #1a2d2d;
      color: #b0b0b0;
    }

    .stats-bar {
      background-color: var(--card-bg);
      border-radius: 15px;
      border: 2px solid var(--border-color);
      margin-bottom: 20px;
      padding: 20px;
      display: flex;
      justify-content: space-around;
      text-align: center;
    }

    .stat-item h3 {
      margin: 0;
      color: var(--border-color);
      font-size: 24px;
    }

    .stat-item p {
      margin: 5px 0 0 0;
      color: var(--price-text-color);
      font-size: 14px;
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
      background-color: rgb(17, 61, 42);
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
      border-top: 1px rgb(17, 61, 42);
      color: #b0b0b0;
      font-size: 12px;
    }

    @media (max-width: 768px) {
      .footer-content {
        flex-direction: column;
        text-align: center;
      }
      
      .theme-toggle {
        justify-content: center;
      }
      
      nav {
        flex-direction: column;
        gap: 10px;
      }
      
      nav .text {
        font-size: 35px;
      }
      
      .filter-bar {
        flex-direction: column;
        align-items: stretch;
      }
      
      .review-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
      
      .product-info {
        flex-direction: column;
        text-align: center;
      }
      
      .stats-bar {
        flex-direction: column;
        gap: 15px;
      }
    }
  </style>
</head>
<body>
  <nav>
    <div class="text">Compare It!</div>
    <div class="nav-links">
      <a href="file:///C:/Users/jessi/OneDrive/Desktop/COS221/Assignment%205/Front%20end/products.html">Products</a>
      <a href="file:///C:/Users/jessi/OneDrive/Desktop/COS221/Assignment%205/Front%20end/reviews.html" style="background-color: rgba(255, 255, 255, 0.1); border-radius: 10px;">Reviews</a>
    </div>
  </nav>

  <main>
    <div class="header-section">
      <h2>Customer Reviews &amp; Retailer Responses</h2>
    </div>

    <div class="reviews-container">
      <!-- Filter Bar -->
      <div class="filter-bar">
        <select id="productFilter">
          <option value="">All Products</option>
          <option value="koo-beans">Koo Beans</option>
          <option value="toilet-paper">Two Ply Toilet Paper</option>
          <option value="coca-cola">Coca-Cola</option>
          <option value="doom">Doom Spray</option>
        </select>
        
        <select id="ratingFilter">
          <option value="">All Ratings</option>
          <option value="5">5 Stars</option>
          <option value="4">4 Stars</option>
          <option value="3">3 Stars</option>
          <option value="2">2 Stars</option>
          <option value="1">1 Star</option>
        </select>
        
        <select id="retailerFilter">
          <option value="">All Retailers</option>
          <option value="woolworths">Woolworths</option>
          <option value="checkers">Checkers</option>
          <option value="picknpay">Pick n Pay</option>
        </select>
        
        <input type="text" id="searchReviews" placeholder="Search reviews...">
        <button onclick="filterReviews()">Filter</button>
        <button onclick="clearFilters()">Clear All</button>
      </div>

      <!-- Your Recent Reviews Section -->
      <div class="header-section" style="margin: 20px 0; background-color: rgb(17, 61, 42)";>
        <h2>Your Recent Reviews</h2>
      </div>

      <!-- Your submitted reviews will appear here -->
      <div id="yourReviews">
          <div class="review-card" style="text-align: center; color: var(--price-text-color);">
            <p>You haven't submitted any reviews yet. <a href="file:///C:/Users/jessi/OneDrive/Desktop/COS221/Assignment%205/Front%20end/products.html" style="color: rgb(17, 61, 42); text-decoration: none; font-weight: bold;">Go back to products</a> to leave your first review!</p>
          </div></div>

      <!-- All Reviews Section -->
      <div class="header-section" style="margin: 20px 0;">
        <h2>All Customer Reviews</h2>
      </div>

      <!-- Review Cards -->
      <div class="review-card" data-product="koo-beans" data-rating="5">
        <div class="review-header">
          <div class="product-info">
            <img src="file:///C:/Users/jessi/OneDrive/Desktop/COS221/Assignment%205/Front%20end/images/beans.png" alt="Koo Beans" class="product-image">
            <div class="product-details">
              <h3>Koo Beans</h3>
              <p>Koo Baked Beans in Tomato Sauce 400g</p>
            </div>
          </div>
          <div class="review-meta">
            <div class="rating">★★★★★</div>
            <div class="review-date">2 days ago</div>
          </div>
        </div>
        
        <div class="customer-review">
          <div class="reviewer-name">Sarah M.</div>
          <div class="review-text">
            Amazing quality beans! Perfect for quick family meals. The tomato sauce has great flavor and the beans are cooked perfectly. Pick n Pay had the best price for this product.
          </div>
          <div class="review-helpful">
            <button class="helpful-btn" onclick="markHelpful(this)">👍 Helpful (12)</button>
            <button class="helpful-btn" onclick="markNotHelpful(this)">👎 Not Helpful (1)</button>
          </div>
        </div>
        
        <div class="retailer-responses">
          <div class="retailer-response">
            <div class="retailer-name">
              Pick n Pay <span class="retailer-badge">OFFICIAL</span>
            </div>
            <div class="response-text">
              Thank you for your wonderful review, Sarah! We're delighted you enjoyed our competitive pricing on Koo Beans. We work hard to offer the best value to our customers.
            </div>
            <div class="response-date">1 day ago</div>
          </div>
        </div>
      </div>

      <div class="review-card" data-product="toilet-paper" data-rating="4">
        <div class="review-header">
          <div class="product-info">
            <img src="file:///C:/Users/jessi/OneDrive/Desktop/COS221/Assignment%205/Front%20end/images/tissue.png" alt="Toilet Paper" class="product-image">
            <div class="product-details">
              <h3>Two Ply Toilet Paper</h3>
              <p>Baby Soft Fresh White Two Ply Toilet Paper 18 pk</p>
            </div>
          </div>
          <div class="review-meta">
            <div class="rating">★★★★☆</div>
            <div class="review-date">5 days ago</div>
          </div>
        </div>
        
        <div class="customer-review">
          <div class="reviewer-name">Mike R.</div>
          <div class="review-text">
            Good quality toilet paper, very soft and strong. All retailers had the same price (R169.99) so I went with Woolworths for convenience. Would recommend for families.
          </div>
          <div class="review-helpful">
            <button class="helpful-btn" onclick="markHelpful(this)">👍 Helpful (8)</button>
            <button class="helpful-btn" onclick="markNotHelpful(this)">👎 Not Helpful (0)</button>
          </div>
        </div>
        
        <div class="retailer-responses">
          <div class="retailer-response">
            <div class="retailer-name">
              Woolworths <span class="retailer-badge">OFFICIAL</span>
            </div>
            <div class="response-text">
              Hi Mike, thanks for choosing Woolworths! We're pleased you found our service convenient. We stock Baby Soft products because of their consistent quality and customer satisfaction.
            </div>
            <div class="response-date">4 days ago</div>
          </div>
          
          <div class="retailer-response">
            <div class="retailer-name">
              Checkers <span class="retailer-badge">OFFICIAL</span>
            </div>
            <div class="response-text">
              Thank you for the honest review! We also carry this popular Baby Soft range. Next time, check out our online ordering for even more convenience.
            </div>
            <div class="response-date">3 days ago</div>
          </div>
        </div>
      </div>

      <div class="review-card" data-product="coca-cola" data-rating="3">
        <div class="review-header">
          <div class="product-info">
            <img src="file:///C:/Users/jessi/OneDrive/Desktop/COS221/Assignment%205/Front%20end/images/coke.png" alt="Coca-Cola" class="product-image">
            <div class="product-details">
              <h3>Coca-Cola</h3>
              <p>Coca-Cola Original Soft Drink 2L</p>
            </div>
          </div>
          <div class="review-meta">
            <div class="rating">★★★☆☆</div>
            <div class="review-date">1 week ago</div>
          </div>
        </div>
        
        <div class="customer-review">
          <div class="reviewer-name">Jessica L.</div>
          <div class="review-text">
            Standard Coke taste as expected. Pick n Pay had it slightly cheaper at R26.99 vs R27.99 elsewhere. Wish there were more size options available at better prices.
          </div>
          <div class="review-helpful">
            <button class="helpful-btn" onclick="markHelpful(this)">👍 Helpful (5)</button>
            <button class="helpful-btn" onclick="markNotHelpful(this)">👎 Not Helpful (2)</button>
          </div>
        </div>
        
        <div class="retailer-responses">
          <div class="retailer-response">
            <div class="retailer-name">
              Pick n Pay <span class="retailer-badge">OFFICIAL</span>
            </div>
            <div class="response-text">
              Thanks Jessica! We do our best to offer competitive prices. Keep an eye out for our weekly specials where we often feature different Coca-Cola sizes at great discounts.
            </div>
            <div class="response-date">6 days ago</div>
          </div>
        </div>
      </div>

      <div class="review-card" data-product="doom" data-rating="5">
        <div class="review-header">
          <div class="product-info">
            <img src="file:///C:/Users/jessi/OneDrive/Desktop/COS221/Assignment%205/Front%20end/images/doom.png" alt="Doom Spray" class="product-image">
            <div class="product-details">
              <h3>Doom</h3>
              <p>Doom Super Multi Insect Spray 300ml</p>
            </div>
          </div>
          <div class="review-meta">
            <div class="rating">★★★★★</div>
            <div class="review-date">1 week ago</div>
          </div>
        </div>
        
        <div class="customer-review">
          <div class="reviewer-name">David K.</div>
          <div class="review-text">
            Excellent insect spray! Very effective against flies and mosquitoes. Woolworths had the best price at R47.99. Highly recommend for summer months.
          </div>
          <div class="review-helpful">
            <button class="helpful-btn" onclick="markHelpful(this)">👍 Helpful (15)</button>
            <button class="helpful-btn" onclick="markNotHelpful(this)">👎 Not Helpful (0)</button>
          </div>
        </div>
        
        <div class="retailer-responses">
          <div class="retailer-response">
            <div class="retailer-name">
              Woolworths <span class="retailer-badge">OFFICIAL</span>
            </div>
            <div class="response-text">
              Thank you David! We're glad you found our pricing competitive. Doom products are popular during summer, and we ensure good stock levels for our customers.
            </div>
            <div class="response-date">6 days ago</div>
          </div>
        </div>
      </div>

      <div class="review-card" data-product="koo-beans" data-rating="2">
        <div class="review-header">
          <div class="product-info">
            <img src="file:///C:/Users/jessi/OneDrive/Desktop/COS221/Assignment%205/Front%20end/images/beans.png" alt="Koo Beans" class="product-image">
            <div class="product-details">
              <h3>Koo Beans</h3>
              <p>Koo Baked Beans in Tomato Sauce 400g</p>
            </div>
          </div>
          <div class="review-meta">
            <div class="rating">★★☆☆☆</div>
            <div class="review-date">2 weeks ago</div>
          </div>
        </div>
        
        <div class="customer-review">
          <div class="reviewer-name">Lisa T.</div>
          <div class="review-text">
            The can I bought from Checkers was dented and the beans were too salty. Not sure if it was a quality issue or just bad luck. Price was reasonable though.
          </div>
          <div class="review-helpful">
            <button class="helpful-btn" onclick="markHelpful(this)">👍 Helpful (3)</button>
            <button class="helpful-btn" onclick="markNotHelpful(this)">👎 Not Helpful (1)</button>
          </div>
        </div>
        
        <div class="retailer-responses">
          <div class="retailer-response">
            <div class="retailer-name">
              Checkers <span class="retailer-badge">OFFICIAL</span>
            </div>
            <div class="response-text">
              Hi Lisa, we're sorry to hear about your experience. Dented cans should not be sold. Please bring your receipt to any Checkers store for a full refund or exchange. We take product quality seriously.
            </div>
            <div class="response-date">2 weeks ago</div>
          </div>
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
        <div class="toggle-switch active" id="themeToggle">
          <div class="toggle-slider">🌙</div>
        </div>
      </div>
    </div>
    
    <div class="copyright">
      <p>© 2025 Compare It! All rights reserved. | Helping you find the best deals.</p>
    </div>
  </footer>

  <script>
    // Product data mapping for your reviews
    const productDataMap = {
      'koo-beans': {
        name: 'Koo Beans',
        description: 'Koo Baked Beans in Tomato Sauce 400g',
        image: './images/beans.png'
      },
      'toilet-paper': {
        name: 'Two Ply Toilet Paper',
        description: 'Baby Soft Fresh White Two Ply Toilet Paper 18 pk',
        image: './images/tissue.png'
      },
      'coca-cola': {
        name: 'Coca-Cola',
        description: 'Coca-Cola Original Soft Drink 2L',
        image: './images/coke.png'
      },
      'doom': {
        name: 'Doom',
        description: 'Doom Super Multi Insect Spray 300ml',
        image: './images/doom.png'
      }
    };

    // Theme management functions
    function initializeTheme() {
      const themeToggle = document.getElementById('themeToggle');
      const slider = themeToggle?.querySelector('.toggle-slider');
      
      if (!themeToggle || !slider) return;
      
      // Check for saved theme preference or default to light mode
      const savedTheme = localStorage.getItem('theme') || 'light';
      
      if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        themeToggle.classList.add('active');
        slider.textContent = '🌙';
      } else {
        document.documentElement.setAttribute('data-theme', 'light');
        themeToggle.classList.remove('active');
        slider.textContent = '☀️';
      }
    }

    function toggleTheme() {
      const themeToggle = document.getElementById('themeToggle');
      const slider = themeToggle?.querySelector('.toggle-slider');
      
      if (!themeToggle || !slider) return;
      
      const currentTheme = document.documentElement.getAttribute('data-theme');
      
      if (currentTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
        themeToggle.classList.remove('active');
        slider.textContent = '☀️';
      } else {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        themeToggle.classList.add('active');
        slider.textContent = '🌙';
      }
    }

    // Load your submitted reviews when page loads
    window.addEventListener('DOMContentLoaded', function() {
      loadYourReviews();
      initializeTheme();
      
      // Add click event listener to theme toggle
      document.getElementById('themeToggle').addEventListener('click', toggleTheme);
    });

    function loadYourReviews() {
      const yourReviews = JSON.parse(localStorage.getItem('userReviews') || '[]');
      const yourReviewsContainer = document.getElementById('yourReviews');
      
      if (yourReviews.length === 0) {
        yourReviewsContainer.innerHTML = `
          <div class="review-card" style="text-align: center; color: var(--price-text-color);">
            <p>You haven't submitted any reviews yet. <a href="products.html" style="color: #29805a; text-decoration: none; font-weight: bold;">Go back to products</a> to leave your first review!</p>
          </div>`;
        return;
      }

      yourReviews.forEach((review, index) => {
        const reviewCard = createYourReviewCard(review, index);
        yourReviewsContainer.appendChild(reviewCard);
      });
    }

    function createYourReviewCard(review, index) {
      const card = document.createElement('div');
      card.className = 'review-card your-review';
      card.setAttribute('data-product', review.productId);
      card.setAttribute('data-rating', review.rating);
      
      const productData = productDataMap[review.productId];
      if (!productData) return card;

      const starsDisplay = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
      
      card.innerHTML = `
        <div class="review-header">
          <div class="product-info">
            <img src="${productData.image}" alt="${productData.name}" class="product-image">
            <div class="product-details">
              <h3>${productData.name} <span class="your-review-badge">YOUR REVIEW</span></h3>
              <p>${productData.description}</p>
            </div>
          </div>
          <div class="review-meta">
            <div class="rating">${starsDisplay}</div>
            <div class="review-date">${formatDate(review.date)}</div>
          </div>
        </div>
        
        <div class="customer-review">
          <div class="reviewer-name">You</div>
          <div class="review-text">${review.text}</div>
          <div class="review-helpful">
            <button class="helpful-btn" onclick="markHelpful(this)">👍 Helpful (0)</button>
            <button class="helpful-btn" onclick="markNotHelpful(this)">👎 Not Helpful (0)</button>
          </div>
        </div>
        
        <div class="no-feedback">
          <p>🔔 No retailer responses yet. Retailers typically respond within 2-3 business days.</p>
        </div>
      `;
      
      return card;
    }

    function formatDate(dateString) {
      const date = new Date(dateString);
      const now = new Date();
      const diffTime = Math.abs(now - date);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      
      if (diffDays === 1) return 'Today';
      if (diffDays === 2) return 'Yesterday';
      if (diffDays <= 7) return `${diffDays - 1} days ago`;
      if (diffDays <= 14) return '1 week ago';
      return `${Math.floor(diffDays / 7)} weeks ago`;
    }

    function markHelpful(button) {
      const currentText = button.textContent;
      const match = currentText.match(/\((\d+)\)/);
      if (match) {
        const count = parseInt(match[1]) + 1;
        button.textContent = currentText.replace(/\(\d+\)/, `(${count})`);
        button.style.backgroundColor = '#e8f5e8';
        button.disabled = true;
        setTimeout(() => {
          button.style.backgroundColor = '';
          button.disabled = false;
        }, 2000);
      }
    }

    function markNotHelpful(button) {
      const currentText = button.textContent;
      const match = currentText.match(/\((\d+)\)/);
      if (match) {
        const count = parseInt(match[1]) + 1;
        button.textContent = currentText.replace(/\(\d+\)/, `(${count})`);
        button.style.backgroundColor = '#ffe8e8';
        button.disabled = true;
        setTimeout(() => {
          button.style.backgroundColor = '';
          button.disabled = false;
        }, 2000);
      }
    }

    function filterReviews() {
      const productFilter = document.getElementById('productFilter').value;
      const ratingFilter = document.getElementById('ratingFilter').value;
      const retailerFilter = document.getElementById('retailerFilter').value;
      const searchText = document.getElementById('searchReviews').value.toLowerCase();
      
      const reviewCards = document.querySelectorAll('.review-card');
      let visibleCount = 0;
      
      reviewCards.forEach(card => {
        // Skip the empty state card
        if (card.textContent.includes("You haven't submitted any reviews yet")) {
          return;
        }
        
        let showCard = true;
        
        // Product filter
        if (productFilter && !card.dataset.product.includes(productFilter)) {
          showCard = false;
        }
        
        // Rating filter
        if (ratingFilter && card.dataset.rating !== ratingFilter) {
          showCard = false;
        }
        
        // Search filter
        if (searchText) {
          const reviewTextElement = card.querySelector('.review-text');
          const productNameElement = card.querySelector('.product-details h3');
          
          if (reviewTextElement && productNameElement) {
            const reviewText = reviewTextElement.textContent.toLowerCase();
            const productName = productNameElement.textContent.toLowerCase();
            if (!reviewText.includes(searchText) && !productName.includes(searchText)) {
              showCard = false;
            }
          }
        }
        
        // Show/hide card
        if (showCard) {
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });
      
      // Show results
      if (visibleCount === 0) {
        alert('No reviews found matching your criteria.');
      } else {
        alert(`Found ${visibleCount} review(s) matching your criteria.`);
      }
    }

    function clearFilters() {
      document.getElementById('productFilter').value = '';
      document.getElementById('ratingFilter').value = '';
      document.getElementById('retailerFilter').value = '';
      document.getElementById('searchReviews').value = '';
      
      // Show all reviews
      const reviewCards = document.querySelectorAll('.review-card');
      reviewCards.forEach(card => {
        card.style.display = 'block';
      });
      
      alert('All filters cleared!');
    }

    // Enter key support for search
    document.getElementById('searchReviews').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        filterReviews();
      }
    });
  </script>


</body></html>
// Product data structure for easier manipulation
const productData = [
  {
    name: "Koo Beans",
    description: "Koo Baked Beans in Tomato Sauce 400 g",
    category: "tinned",
    prices: { woolworths: 19.99, checkers: 18.99, picknpay: 16.99 },
    element: null, // Will be populated
    productId: "koo-beans",
    image: "./images/beans.png"
  },
  {
    name: "Two Ply Toilet Paper",
    description: "Baby Soft Fresh White Two Ply Toilet Paper 18 pk",
    category: "toiletries",
    prices: { woolworths: 169.99, checkers: 169.99, picknpay: 169.99 },
    element: null,
    productId: "toilet-paper",
    image: "./images/tissue.png"
  },
  {
    name: "Coca-Cola",
    description: "Coca-Cola Original Soft Drink 2 L",
    category: "beverages",
    prices: { woolworths: 27.99, checkers: 27.99, picknpay: 26.99 },
    element: null,
    productId: "coca-cola",
    image: "./images/coke.png"
  },
  {
    name: "Doom",
    description: "Doom Super Multi Insect Spray 300 ml",
    category: "cleaning",
    prices: { woolworths: 47.99, checkers: 49.99, picknpay: 49.99 },
    element: null,
    productId: "doom",
    image: "./images/doom.png"
  }
];

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

// Initialize the application
function initializeApp() {
  // Link product data to DOM elements
  const productCards = document.querySelectorAll('.product-card');
  productCards.forEach((card, index) => {
    if (productData[index]) {
      productData[index].element = card;
    }
  });
  
  // Set up event listeners
  setupEventListeners();
  
  // Set up review functionality
  setupReviewFunctionality();
  
  // Initialize theme
  initializeTheme();
}

// Set up all event listeners
function setupEventListeners() {
  // Filter toggle
  const toggleFiltersBtn = document.getElementById('toggleFilters');
  if (toggleFiltersBtn) {
    toggleFiltersBtn.addEventListener('click', toggleFilters);
  }
  
  // Search functionality
  const searchBtn = document.getElementById('searchButton');
  const searchInput = document.getElementById('searchInput');
  
  if (searchBtn) {
    searchBtn.addEventListener('click', performSearch);
  }
  
  if (searchInput) {
    searchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        performSearch();
      }
    });
  }
  
  // Sort functionality
  const sortSelect = document.getElementById('sortSelect');
  if (sortSelect) {
    sortSelect.addEventListener('change', performSort);
  }
  
  // Filter checkboxes
  const filterCheckboxes = document.querySelectorAll('.filter-option input[type="checkbox"]');
  filterCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', applyFilters);
  });
  
  // Theme toggle
  const themeToggle = document.getElementById('themeToggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', toggleTheme);
  }
}

// Set up review functionality for each product card
function setupReviewFunctionality() {
  const productCards = document.querySelectorAll('.product-card');
  
  productCards.forEach((card, index) => {
    const reviewButton = card.querySelector('.add-review');
    const reviewInput = card.querySelector('.review-input');
    
    if (reviewButton && reviewInput) {
      // Remove old onclick attribute
      reviewButton.removeAttribute('onclick');
      
      // Add new event listener
      reviewButton.addEventListener('click', function() {
        handleReviewSubmission(card, reviewButton, reviewInput, index);
      });
    }
  });
}

// Handle review submission with rating
function handleReviewSubmission(card, button, input, productIndex) {
  if (input.style.display === 'none' || input.style.display === '') {
    // Show review input with rating selection
    showReviewForm(card, button, input, productIndex);
  } else {
    // Submit the review
    submitReview(card, button, input, productIndex);
  }
}

// Show review form with star rating
function showReviewForm(card, button, input, productIndex) {
  // Create star rating HTML
  const ratingHTML = `
    <div class="rating-selector" style="margin: 10px 0; text-align: center;">
      <p style="margin: 5px 0; font-weight: bold; color: var(--border-color);">Rate this product:</p>
      <div class="stars" style="font-size: 24px; margin: 10px 0;">
        <span class="star" data-rating="1" style="cursor: pointer; color: #ddd;">★</span>
        <span class="star" data-rating="2" style="cursor: pointer; color: #ddd;">★</span>
        <span class="star" data-rating="3" style="cursor: pointer; color: #ddd;">★</span>
        <span class="star" data-rating="4" style="cursor: pointer; color: #ddd;">★</span>
        <span class="star" data-rating="5" style="cursor: pointer; color: #ddd;">★</span>
      </div>
    </div>
  `;
  
  // Insert rating selector before the input
  input.insertAdjacentHTML('beforebegin', ratingHTML);
  
  // Show input and change button text
  input.style.display = 'block';
  input.placeholder = 'Write your detailed review here...';
  button.textContent = 'Submit Review';
  
  // Add star rating functionality
  const stars = card.querySelectorAll('.star');
  let selectedRating = 0;
  
  stars.forEach(star => {
    star.addEventListener('mouseover', function() {
      const rating = parseInt(this.getAttribute('data-rating'));
      highlightStars(stars, rating);
    });
    
    star.addEventListener('mouseout', function() {
      highlightStars(stars, selectedRating);
    });
    
    star.addEventListener('click', function() {
      selectedRating = parseInt(this.getAttribute('data-rating'));
      highlightStars(stars, selectedRating);
    });
  });
  
  // Store selected rating in input's dataset
  input.addEventListener('input', function() {
    input.dataset.rating = selectedRating;
  });
}

// Highlight stars based on rating
function highlightStars(stars, rating) {
  stars.forEach((star, index) => {
    if (index < rating) {
      star.style.color = '#ffcc00';
    } else {
      star.style.color = '#ddd';
    }
  });
}

// Submit the review
function submitReview(card, button, input, productIndex) {
  const reviewText = input.value.trim();
  const rating = parseInt(input.dataset.rating) || 0;
  
  if (!reviewText) {
    alert('Please write a review before submitting.');
    return;
  }
  
  if (rating === 0) {
    alert('Please select a star rating before submitting.');
    return;
  }
  
  // Get product data
  const product = productData[productIndex];
  
  // Create review object
  const review = {
    productId: product.productId,
    productName: product.name,
    productDescription: product.description,
    productImage: product.image,
    text: reviewText,
    rating: rating,
    date: new Date().toISOString(),
    id: Date.now() // Simple ID generation
  };
  
  // Save to localStorage
  saveReview(review);
  
  // Show success message
  alert('Review submitted successfully! You can view it on the Reviews page.');
  
  // Reset the form
  resetReviewForm(card, button, input);
}

// Save review to localStorage
function saveReview(review) {
  let userReviews = JSON.parse(localStorage.getItem('userReviews') || '[]');
  userReviews.unshift(review); // Add to beginning of array (most recent first)
  
  // Keep only last 10 reviews to prevent storage bloat
  if (userReviews.length > 10) {
    userReviews = userReviews.slice(0, 10);
  }
  
  localStorage.setItem('userReviews', JSON.stringify(userReviews));
}

// Reset review form
function resetReviewForm(card, button, input) {
  // Remove rating selector
  const ratingSelector = card.querySelector('.rating-selector');
  if (ratingSelector) {
    ratingSelector.remove();
  }
  
  // Reset input
  input.value = '';
  input.style.display = 'none';
  input.removeAttribute('data-rating');
  
  // Reset button
  button.textContent = 'Add a review...';
}

// Toggle filters section
function toggleFilters() {
  const filtersSection = document.getElementById('filtersSection');
  const toggleButton = document.getElementById('toggleFilters');
  
  if (!filtersSection || !toggleButton) return;
  
  if (filtersSection.style.display === 'none' || filtersSection.style.display === '') {
    filtersSection.style.display = 'block';
    toggleButton.textContent = 'Hide Filters';
  } else {
    filtersSection.style.display = 'none';
    toggleButton.textContent = 'Show Filters';
  }
}

// Get minimum price for a product
function getMinPrice(product) {
  return Math.min(product.prices.woolworths, product.prices.checkers, product.prices.picknpay);
}

// Get maximum price for a product
function getMaxPrice(product) {
  return Math.max(product.prices.woolworths, product.prices.checkers, product.prices.picknpay);
}

// Perform sorting
function performSort() {
  const sortSelect = document.getElementById('sortSelect');
  const container = document.querySelector('.product-container');
  
  if (!sortSelect || !container) return;
  
  const sortOption = sortSelect.value;
  
  if (!sortOption) return;
  
  // Create a copy of productData for sorting
  const sortedProducts = [...productData];
  
  switch (sortOption) {
    case 'priceLowHigh':
      sortedProducts.sort((a, b) => getMinPrice(a) - getMinPrice(b));
      break;
    case 'priceHighLow':
      sortedProducts.sort((a, b) => getMinPrice(b) - getMinPrice(a));
      break;
    case 'name':
      sortedProducts.sort((a, b) => a.name.localeCompare(b.name));
      break;
  }
  
  // Reorder DOM elements
  sortedProducts.forEach(product => {
    if (product.element) {
      container.appendChild(product.element);
    }
  });
}

// Check if product matches category filters
function matchesCategoryFilter(product) {
  const categoryDairy = document.getElementById('categoryDairy');
  const categoryBakery = document.getElementById('categoryBakery');
  const categorySnacks = document.getElementById('categorySnacks');
  const categoryElectronics = document.getElementById('categoryElectronics');
  
  const categoryFilters = {
    tinned: categoryDairy ? categoryDairy.checked : true,
    toiletries: categoryBakery ? categoryBakery.checked : true,
    cleaning: categorySnacks ? categorySnacks.checked : true,
    beverages: categoryElectronics ? categoryElectronics.checked : true
  };
  
  return categoryFilters[product.category];
}

// Check if product matches price filters
function matchesPriceFilter(product) {
  const minPrice = getMinPrice(product);
  
  const price0_100 = document.getElementById('price0-100');
  const price100_500 = document.getElementById('price100-500');
  const price500plus = document.getElementById('price500plus');
  
  const priceFilters = {
    range1: price0_100 ? price0_100.checked && minPrice >= 0 && minPrice <= 100 : false,
    range2: price100_500 ? price100_500.checked && minPrice > 100 && minPrice <= 500 : false,
    range3: price500plus ? price500plus.checked && minPrice > 500 : false
  };
  
  return priceFilters.range1 || priceFilters.range2 || priceFilters.range3;
}

// Apply all filters
function applyFilters() {
  productData.forEach(product => {
    if (product.element) {
      const matchesCategory = matchesCategoryFilter(product);
      const matchesPrice = matchesPriceFilter(product);
      const matchesSearch = checkSearchMatch(product);
      
      // Show product only if it matches all active filters
      if (matchesCategory && matchesPrice && matchesSearch) {
        product.element.style.display = 'block';
      } else {
        product.element.style.display = 'none';
      }
    }
  });
}

// Check if product matches current search
function checkSearchMatch(product) {
  const searchInput = document.getElementById('searchInput');
  
  if (!searchInput) return true;
  
  const searchTerm = searchInput.value.toLowerCase().trim();
  
  if (!searchTerm) return true;
  
  const productName = product.name.toLowerCase();
  const productDesc = product.description.toLowerCase();
  
  return productName.includes(searchTerm) || productDesc.includes(searchTerm);
}

// Perform search
function performSearch() {
  // Apply all filters including search
  applyFilters();
  
  // Show feedback
  const searchInput = document.getElementById('searchInput');
  if (!searchInput) return;
  
  const searchTerm = searchInput.value.trim();
  if (searchTerm) {
    // Count visible products
    const visibleProducts = productData.filter(product => 
      product.element && product.element.style.display !== 'none'
    ).length;
    
    if (visibleProducts === 0) {
      alert('No products found matching your search criteria.');
    } else {
      alert(`Found ${visibleProducts} product(s) matching your search.`);
    }
  }
}

// Clear all filters and search
function clearAllFilters() {
  // Clear search input
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.value = '';
  }
  
  // Reset sort dropdown
  const sortSelect = document.getElementById('sortSelect');
  if (sortSelect) {
    sortSelect.value = '';
  }
  
  // Check all filter checkboxes
  const filterCheckboxes = document.querySelectorAll('.filter-option input[type="checkbox"]');
  filterCheckboxes.forEach(checkbox => {
    checkbox.checked = true;
  });
  
  // Show all products
  productData.forEach(product => {
    if (product.element) {
      product.element.style.display = 'block';
    }
  });
}

// Check for new retailer responses
function checkForNewResponses() {
  const userReviews = JSON.parse(localStorage.getItem('userReviews') || '[]');
  const lastChecked = localStorage.getItem('lastResponseCheck') || '0';
  
  // Get mock responses (simulating retailer responses)
  const mockResponses = JSON.parse(localStorage.getItem('mockResponses') || '[]');
  
  // Count responses that came after last check
  let newResponseCount = 0;
  mockResponses.forEach(response => {
    if (new Date(response.date) > new Date(lastChecked)) {
      newResponseCount++;
    }
  });
  
  // Update notification badge
  updateNotificationBadge(newResponseCount);
}

function updateNotificationBadge(count) {
  const badge = document.getElementById('notificationBadge');
  
  if (badge) {
    if (count > 0) {
      badge.textContent = count;
      badge.style.display = 'flex';
    } else {
      badge.style.display = 'none';
    }
  }
}

// Function to simulate retailer response (for testing)
function simulateRetailerResponse(productId, responseText, retailerName = 'Pick n Pay') {
  const responses = JSON.parse(localStorage.getItem('mockResponses') || '[]');
  
  const newResponse = {
    productId: productId,
    retailer: retailerName,
    text: responseText,
    date: new Date().toISOString(),
    id: Date.now()
  };
  
  responses.push(newResponse);
  localStorage.setItem('mockResponses', JSON.stringify(responses));
  
  // Trigger notification check
  checkForNewResponses();
  
  alert(`New response from ${retailerName}! Check the Reviews page.`);
}

// Legacy function for compatibility (kept in case it's called elsewhere)
function toggleReview(button) {
  // This function is now handled by the new review system
  console.log('Legacy toggleReview called - using new review system instead');
}

// Utility function to add a reset filters button (optional)
function addResetButton() {
  const searchBar = document.querySelector('.search-bar');
  if (!searchBar) return;
  
  const resetButton = document.createElement('button');
  resetButton.textContent = 'Reset All';
  resetButton.style.padding = '10px 20px';
  resetButton.style.borderRadius = '15px';
  resetButton.style.backgroundColor = 'var(--bg-color)';
  resetButton.style.border = '2px solid var(--border-color)';
  resetButton.style.color = 'var(--text-color)';
  resetButton.style.cursor = 'pointer';
  resetButton.addEventListener('click', clearAllFilters);
  
  searchBar.appendChild(resetButton);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  initializeApp();
  
  // Check for new retailer responses
  checkForNewResponses();
  
  // Check every 30 seconds for new responses
  setInterval(checkForNewResponses, 30000);
  
  // Mark responses as read when Reviews link is clicked
  const reviewsLink = document.querySelector('a[href="reviews.html"]');
  if (reviewsLink) {
    reviewsLink.addEventListener('click', function() {
      // Mark current time as last checked
      localStorage.setItem('lastResponseCheck', new Date().toISOString());
      
      // Hide badge after a short delay
      setTimeout(() => {
        updateNotificationBadge(0);
      }, 100);
    });
  }
  
  // Optionally add reset button
  // addResetButton();
});

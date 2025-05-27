// Global variables
let currentSlide = 0;
let selectedRating = 0;
let productData = null;
let allProducts = [];

// Sample product data - replace with actual data from URL parameters or API
const sampleProducts = {
  'koo-beans': {
    id: 'koo-beans',
    name: 'Koo Beans',
    description: 'Koo Baked Beans in Tomato Sauce 400g. These delicious baked beans are perfect for a quick and nutritious meal. Made with high-quality beans and rich tomato sauce, they\'re a family favorite that\'s both convenient and tasty. Perfect for breakfast, lunch, or dinner. Can be enjoyed on toast, as a side dish, or as part of a hearty meal.',
    images: [
      './images/beans.png',
      './images/koo2.png',
      './images/koo3.avif'
    ],
    prices: [
      { store: 'Pick n Pay', price: 16.99, link: 'https://www.pnp.co.za', isBest: true },
      { store: 'Checkers', price: 18.99, link: 'https://www.checkers.co.za', isBest: false },
      { store: 'Woolworths', price: 19.99, link: 'https://www.woolworths.co.za', isBest: false }
    ],
    category: 'tinned',
    brand: 'Koo'
  },
  'toilet-paper': {
    id: 'toilet-paper',
    name: 'Two Ply Toilet Paper',
    description: 'Baby Soft Fresh White Two Ply Toilet Paper 18 pk. Premium quality toilet paper that provides comfort and strength. Perfect for families who want the best combination of softness and durability. Each pack contains 18 rolls of high-quality two-ply tissue that is gentle on skin yet strong enough for everyday use.',
    images: [
      './images/tissue.png',
      './images/toiletpaper2.jpg',
      './images/tissue.png'
    ],
    prices: [
      { store: 'Woolworths', price: 169.99, link: 'https://www.woolworths.co.za', isBest: false },
      { store: 'Checkers', price: 169.99, link: 'https://www.checkers.co.za', isBest: false },
      { store: 'Pick n Pay', price: 169.99, link: 'https://www.pnp.co.za', isBest: false }
    ],
    category: 'toiletries',
    brand: 'Baby Soft'
  },
  'coca-cola': {
    id: 'coca-cola',
    name: 'Coca-Cola',
    description: 'Coca-Cola Original Soft Drink 2L. The classic taste of Coca-Cola in a convenient 2-liter bottle. Perfect for sharing with family and friends, or for keeping in the fridge for whenever you need a refreshing drink. Made with the original Coca-Cola recipe that has been loved worldwide for generations.',
    images: [
      './images/coke.png',
      './images/coke2.jpg',
      './images/coke3.webp'
    ],
    prices: [
      { store: 'Pick n Pay', price: 26.99, link: 'https://www.pnp.co.za', isBest: true },
      { store: 'Woolworths', price: 27.99, link: 'https://www.woolworths.co.za', isBest: false },
      { store: 'Checkers', price: 27.99, link: 'https://www.checkers.co.za', isBest: false }
    ],
    category: 'beverages',
    brand: 'Coca-Cola'
  },
  'doom': {
    id: 'doom',
    name: 'Doom',
    description: 'Doom Super Multi Insect Spray 300ml. Effective protection against flies, mosquitoes, and other flying insects. Fast-acting formula that provides long-lasting protection for your home. Easy-to-use spray bottle that delivers precise application for maximum effectiveness against household pests.',
    images: [
      './images/doom.png',
      './images/doom2.png',
      './images/doom.png'
    ],
    prices: [
      { store: 'Woolworths', price: 47.99, link: 'https://www.woolworths.co.za', isBest: true },
      { store: 'Checkers', price: 49.99, link: 'https://www.checkers.co.za', isBest: false },
      { store: 'Pick n Pay', price: 49.99, link: 'https://www.pnp.co.za', isBest: false }
    ],
    category: 'cleaning',
    brand: 'Doom'
  }
};

// Initialize the page when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  initializeTheme();
  loadProductData();
  setupEventListeners();
  initializeCarousel();
  initializeStarRating();
  loadAllProductsForSearch();
});

// Load all products for search functionality
function loadAllProductsForSearch() {
  allProducts = Object.values(sampleProducts);
}

// Load product data based on URL parameter
function loadProductData() {
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get('product') || 'koo-beans';
  
  productData = sampleProducts[productId];
  
  if (productData) {
    populateProductData();
  } else {
    // Handle product not found
    document.getElementById('productTitle').textContent = 'Product Not Found';
    document.getElementById('productDescription').innerHTML = '<p>Sorry, the requested product could not be found.</p>';
    document.getElementById('priceGrid').innerHTML = '<p>No pricing information available.</p>';
    
    // Hide carousel and reviews section
    document.querySelector('.image-section').style.display = 'none';
    document.querySelector('.reviews-section').style.display = 'none';
  }
}

// Populate the page with product data
function populateProductData() {
  // Update title
  document.getElementById('productTitle').textContent = productData.name;
  document.title = `Compare It! - ${productData.name}`;
  
  // Update description
  document.getElementById('productDescription').innerHTML = `<p>${productData.description}</p>`;
  
  // Update carousel images
  updateCarouselImages();
  
  // Update price comparison
  updatePriceComparison();
}

// Update carousel with product images
function updateCarouselImages() {
  const carouselContainer = document.querySelector('.carousel-container');
  const indicatorsContainer = document.querySelector('.carousel-indicators');
  
  // Clear existing content
  carouselContainer.innerHTML = '';
  indicatorsContainer.innerHTML = '';
  
  // Create slides
  productData.images.forEach((imageSrc, index) => {
    const slide = document.createElement('div');
    slide.className = `carousel-slide ${index === 0 ? 'active' : ''}`;
    slide.innerHTML = `<img src="${imageSrc}" alt="${productData.name}" onerror="this.src='./images/placeholder.png'">`;
    carouselContainer.appendChild(slide);
  });
  
  // Add navigation buttons
  const prevBtn = document.createElement('button');
  prevBtn.className = 'carousel-nav carousel-prev';
  prevBtn.innerHTML = '❮';
  prevBtn.onclick = () => changeSlide(-1);
  carouselContainer.appendChild(prevBtn);
  
  const nextBtn = document.createElement('button');
  nextBtn.className = 'carousel-nav carousel-next';
  nextBtn.innerHTML = '❯';
  nextBtn.onclick = () => changeSlide(1);
  carouselContainer.appendChild(nextBtn);
  
  // Create indicators
  productData.images.forEach((imageSrc, index) => {
    const indicator = document.createElement('div');
    indicator.className = `indicator ${index === 0 ? 'active' : ''}`;
    indicator.innerHTML = `<img src="${imageSrc}" alt="Thumbnail" onerror="this.src='./images/placeholder.png'">`;
    indicator.onclick = () => currentSlideIndex(index + 1);
    indicatorsContainer.appendChild(indicator);
  });
  
  // Reset current slide
  currentSlide = 0;
}

// Update price comparison section
function updatePriceComparison() {
  const priceGrid = document.getElementById('priceGrid');
  priceGrid.innerHTML = '';
  
  // Sort prices to show best price first
  const sortedPrices = [...productData.prices].sort((a, b) => a.price - b.price);
  
  sortedPrices.forEach((priceInfo, index) => {
    const priceCard = document.createElement('div');
    const isBestPrice = index === 0; // First item after sorting is the best price
    priceCard.className = `price-card ${isBestPrice ? 'best-price' : ''}`;
    
    priceCard.innerHTML = `
      ${isBestPrice ? '<div class="best-price-badge">Best Price!</div>' : ''}
      <h4>${priceInfo.store}</h4>
      <div class="price">R${priceInfo.price.toFixed(2)}</div>
      <a href="${priceInfo.link}" target="_blank" class="store-link">Visit Store</a>
    `;
    
    priceGrid.appendChild(priceCard);
  });
}

// Setup event listeners
function setupEventListeners() {
  // Theme toggle
  const themeToggle = document.getElementById('themeToggle');
  if (themeToggle) {
    themeToggle.addEventListener('click', toggleTheme);
  }
  
  // Search functionality
  const searchBtn = document.getElementById('searchBtn');
  if (searchBtn) {
    searchBtn.addEventListener('click', performSearch);
  }
  
  const searchInput = document.getElementById('searchInput');
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
}

// Initialize carousel functionality
function initializeCarousel() {
  currentSlide = 0;
  showSlide(currentSlide);
  
  // Auto-advance carousel every 5 seconds
  setInterval(() => {
    if (productData && productData.images && productData.images.length > 1) {
      changeSlide(1);
    }
  }, 5000);
}

// Show specific slide
function showSlide(slideIndex) {
  const slides = document.querySelectorAll('.carousel-slide');
  const indicators = document.querySelectorAll('.indicator');
  
  if (slides.length === 0) return;
  
  if (slideIndex >= slides.length) currentSlide = 0;
  if (slideIndex < 0) currentSlide = slides.length - 1;
  
  // Hide all slides and indicators
  slides.forEach(slide => slide.classList.remove('active'));
  indicators.forEach(indicator => indicator.classList.remove('active'));
  
  // Show current slide and indicator
  if (slides[currentSlide]) {
    slides[currentSlide].classList.add('active');
  }
  
  if (indicators[currentSlide]) {
    indicators[currentSlide].classList.add('active');
  }
}

// Change slide (next/previous)
function changeSlide(direction) {
  currentSlide += direction;
  showSlide(currentSlide);
}

// Go to specific slide
function currentSlideIndex(slideNumber) {
  currentSlide = slideNumber - 1;
  showSlide(currentSlide);
}

// Initialize star rating functionality
function initializeStarRating() {
  const stars = document.querySelectorAll('.star-rating .star');
  
  stars.forEach(star => {
    star.addEventListener('mouseover', function() {
      const rating = parseInt(this.getAttribute('data-rating'));
      highlightStars(rating);
    });
    
    star.addEventListener('mouseout', function() {
      highlightStars(selectedRating);
    });
    
    star.addEventListener('click', function() {
      selectedRating = parseInt(this.getAttribute('data-rating'));
      highlightStars(selectedRating);
    });
  });
}

// Highlight stars based on rating
function highlightStars(rating) {
  const stars = document.querySelectorAll('.star-rating .star');
  
  stars.forEach((star, index) => {
    if (index < rating) {
      star.classList.add('active');
    } else {
      star.classList.remove('active');
    }
  });
}

// Toggle review form
function toggleReviewForm() {
  const reviewForm = document.getElementById('reviewForm');
  const addReviewBtn = document.querySelector('.add-review-btn');
  
  if (reviewForm.classList.contains('active')) {
    // Hide form
    reviewForm.classList.remove('active');
    addReviewBtn.textContent = 'Add Your Review';
    
    // Reset form
    selectedRating = 0;
    highlightStars(0);
    document.getElementById('reviewText').value = '';
  } else {
    // Show form
    reviewForm.classList.add('active');
    addReviewBtn.textContent = 'Cancel Review';
    
    // Focus on textarea
    setTimeout(() => {
      document.getElementById('reviewText').focus();
    }, 100);
  }
}

// Submit review
function submitReview() {
  const reviewText = document.getElementById('reviewText').value.trim();
  
  if (selectedRating === 0) {
    alert('Please select a star rating.');
    return;
  }
  
  if (reviewText === '') {
    alert('Please write a review.');
    return;
  }
  
  // Create new review element
  const reviewsList = document.getElementById('reviewsList');
  const newReview = document.createElement('div');
  newReview.className = 'review-item';
  
  const starsDisplay = '★'.repeat(selectedRating) + '☆'.repeat(5 - selectedRating);
  const currentDate = new Date().toLocaleDateString();
  
  newReview.innerHTML = `
    <div class="review-header-info">
      <div class="reviewer-name">You</div>
      <div class="review-date">Just now</div>
    </div>
    <div class="review-rating">${starsDisplay}</div>
    <div class="review-text">${reviewText}</div>
    <div class="review-helpful">
      <button class="helpful-btn" onclick="markHelpful(this)">👍 Helpful (0)</button>
      <button class="helpful-btn" onclick="markNotHelpful(this)">👎 Not Helpful (0)</button>
    </div>
  `;
  
  // Add to top of reviews list
  reviewsList.insertBefore(newReview, reviewsList.firstChild);
  
  // Save review to localStorage
  saveReviewToStorage(reviewText, selectedRating);
  
  // Close form and reset
  toggleReviewForm();
  
  // Show success message
  showNotification('Thank you for your review!', 'success');
}

// Save review to localStorage
function saveReviewToStorage(reviewText, rating) {
  const reviews = JSON.parse(localStorage.getItem('userReviews') || '[]');
  
  const newReview = {
    productId: productData.id,
    productName: productData.name,
    text: reviewText,
    rating: rating,
    date: new Date().toISOString(),
    id: Date.now()
  };
  
  reviews.unshift(newReview);
  
  // Keep only last 20 reviews
  if (reviews.length > 20) {
    reviews.splice(20);
  }
  
  localStorage.setItem('userReviews', JSON.stringify(reviews));
}

// Show notification
function showNotification(message, type = 'info') {
  // Create notification element
  const notification = document.createElement('div');
  notification.className = `notification ${type}`;
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: ${type === 'success' ? '#4CAF50' : '#2196F3'};
    color: white;
    padding: 15px 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    z-index: 1000;
    font-weight: bold;
    max-width: 300px;
  `;
  notification.textContent = message;
  
  document.body.appendChild(notification);
  
  // Remove after 3 seconds
  setTimeout(() => {
    notification.remove();
  }, 3000);
}

// Mark review as helpful
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

// Mark review as not helpful
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

// Perform search - intelligent search within view format
function performSearch() {
  const searchTerm = document.getElementById('searchInput').value.trim().toLowerCase();
  const sortBy = document.getElementById('sortSelect').value;
  
  if (!searchTerm) {
    showNotification('Please enter a search term', 'info');
    return;
  }
  
  // Search through all products
  const matchingProducts = allProducts.filter(product => {
    return product.name.toLowerCase().includes(searchTerm) ||
           product.description.toLowerCase().includes(searchTerm) ||
           product.category.toLowerCase().includes(searchTerm) ||
           product.brand.toLowerCase().includes(searchTerm);
  });
  
  if (matchingProducts.length === 0) {
    showNotification('No products found matching your search', 'info');
    return;
  }
  
  // If current product matches, stay on current page
  const currentProductMatches = productData && (
    productData.name.toLowerCase().includes(searchTerm) ||
    productData.description.toLowerCase().includes(searchTerm) ||
    productData.category.toLowerCase().includes(searchTerm) ||
    productData.brand.toLowerCase().includes(searchTerm)
  );
  
  if (currentProductMatches) {
    showNotification(`Current product "${productData.name}" matches your search!`, 'success');
    return;
  }
  
  // Navigate to first matching product
  const firstMatch = matchingProducts[0];
  const newUrl = `view.html?product=${firstMatch.id}`;
  
  if (sortBy) {
    const urlParams = new URLSearchParams();
    urlParams.append('product', firstMatch.id);
    urlParams.append('sort', sortBy);
    window.location.href = `view.html?${urlParams.toString()}`;
  } else {
    window.location.href = newUrl;
  }
}

// Perform sort (for future implementation)
function performSort() {
  const sortBy = document.getElementById('sortSelect').value;
  
  if (!sortBy) return;
  
  // For now, just show notification - can be extended for product comparison
  showNotification(`Sorting by: ${sortBy}`, 'info');
}

// Logout functionality
function logout() {
  if (confirm('Are you sure you want to logout?')) {
    // Clear any stored user data
    localStorage.removeItem('userData');
    localStorage.removeItem('userSession');
    
    showNotification('Logged out successfully', 'success');
    
    // Redirect to home page after a short delay
    setTimeout(() => {
      window.location.href = 'index.html';
    }, 1500);
  }
}

// Theme management functions
function initializeTheme() {
  const themeToggle = document.getElementById('themeToggle');
  const slider = themeToggle?.querySelector('.toggle-slider');
  
  if (!themeToggle || !slider) return;
  
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

// Utility function to get query parameter
function getQueryParameter(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

// Error handling for images
function handleImageError(img) {
  img.src = './images/placeholder.png';
  img.alt = 'Image not available';
}

// Keyboard navigation for carousel
document.addEventListener('keydown', function(e) {
  if (e.key === 'ArrowLeft') {
    changeSlide(-1);
  } else if (e.key === 'ArrowRight') {
    changeSlide(1);
  }
});

// Handle browser back/forward navigation
window.addEventListener('popstate', function(e) {
  loadProductData();
});

// Export functions for global access (if needed)
window.toggleReviewForm = toggleReviewForm;
window.submitReview = submitReview;
window.markHelpful = markHelpful;
window.markNotHelpful = markNotHelpful;
window.logout = logout;
window.changeSlide = changeSlide;
window.currentSlideIndex = currentSlideIndex;
// Product data structure for easier manipulation
const productData = [
  {
    name: "Koo Beans",
    description: "Koo Baked Beans in Tomato Sauce 400 g",
    category: "tinned",
    prices: { woolworths: 19.99, checkers: 18.99, picknpay: 16.99 },
    element: null // Will be populated
  },
  {
    name: "Two Ply Toilet Paper",
    description: "Baby Soft Fresh White Two Ply Toilet Paper 18 pk",
    category: "toiletries",
    prices: { woolworths: 169.99, checkers: 169.99, picknpay: 169.99 },
    element: null
  },
  {
    name: "Coca-Cola",
    description: "Coca-Cola Original Soft Drink 2 L",
    category: "beverages",
    prices: { woolworths: 27.99, checkers: 27.99, picknpay: 26.99 },
    element: null
  },
  {
    name: "Doom",
    description: "Doom Super Multi Insect Spray 300 ml",
    category: "cleaning",
    prices: { woolworths: 47.99, checkers: 49.99, picknpay: 49.99 },
    element: null
  }
];

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
}

// Set up all event listeners
function setupEventListeners() {
  // Filter toggle
  document.getElementById('toggleFilters').addEventListener('click', toggleFilters);
  
  // Search functionality
  document.getElementById('searchButton').addEventListener('click', performSearch);
  document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      performSearch();
    }
  });
  
  // Sort functionality
  document.getElementById('sortSelect').addEventListener('change', performSort);
  
  // Filter checkboxes
  const filterCheckboxes = document.querySelectorAll('.filter-option input[type="checkbox"]');
  filterCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', applyFilters);
  });
}

// Toggle filters section
function toggleFilters() {
  const filtersSection = document.getElementById('filtersSection');
  const toggleButton = document.getElementById('toggleFilters');
  
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
  const sortOption = document.getElementById('sortSelect').value;
  const container = document.querySelector('.product-container');
  
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
  const categoryFilters = {
    tinned: document.getElementById('categoryDairy').checked,
    toiletries: document.getElementById('categoryBakery').checked,
    cleaning: document.getElementById('categorySnacks').checked,
    beverages: document.getElementById('categoryElectronics').checked
  };
  
  return categoryFilters[product.category];
}

// Check if product matches price filters
function matchesPriceFilter(product) {
  const minPrice = getMinPrice(product);
  
  const priceFilters = {
    range1: document.getElementById('price0-100').checked && minPrice >= 0 && minPrice <= 100,
    range2: document.getElementById('price100-500').checked && minPrice > 100 && minPrice <= 500,
    range3: document.getElementById('price500plus').checked && minPrice > 500
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
  const searchInput = document.getElementById('searchInput').value.toLowerCase().trim();
  
  if (!searchInput) return true;
  
  const productName = product.name.toLowerCase();
  const productDesc = product.description.toLowerCase();
  
  return productName.includes(searchInput) || productDesc.includes(searchInput);
}

// Perform search
function performSearch() {
  // Apply all filters including search
  applyFilters();
  
  // Show feedback
  const searchInput = document.getElementById('searchInput').value.trim();
  if (searchInput) {
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
  document.getElementById('searchInput').value = '';
  
  // Reset sort dropdown
  document.getElementById('sortSelect').value = '';
  
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

// Review functionality (keeping existing function)
function toggleReview(button) {
  const input = button.nextElementSibling;
  
  if (input.style.display === 'none' || input.style.display === '') {
    input.style.display = 'block';
    button.textContent = 'Submit Review';
  } else {
    if (input.value.trim()) {
      alert('Review submitted!');
      input.value = '';
    }
    input.style.display = 'none';
    button.textContent = 'Add a review...';
  }
}

// Utility function to add a reset filters button (optional)
function addResetButton() {
  const searchBar = document.querySelector('.search-bar');
  const resetButton = document.createElement('button');
  resetButton.textContent = 'Reset All';
  resetButton.style.padding = '10px 20px';
  resetButton.style.borderRadius = '15px';
  resetButton.style.backgroundColor = '#f0dcbd';
  resetButton.style.border = '2px solid #113d2a';
  resetButton.style.cursor = 'pointer';
  resetButton.addEventListener('click', clearAllFilters);
  
  searchBar.appendChild(resetButton);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  initializeApp();
  
  // Optionally add reset button
  // addResetButton();
});
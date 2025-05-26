// footer.js - Footer Theme Management
// This script handles the dark mode functionality for the footer

(function() {
  'use strict';

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

  // Initialize when DOM is ready
  function initFooter() {
    initializeTheme();
    
    // Add click event listener to theme toggle
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
      themeToggle.addEventListener('click', toggleTheme);
    }
  }

  // Auto-initialize based on document state
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFooter);
  } else {
    // DOM is already loaded, initialize immediately
    initFooter();
  }

  // Expose functions globally for external access if needed
  window.FooterTheme = {
    initialize: initializeTheme,
    toggle: toggleTheme
  };

})();
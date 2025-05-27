document.addEventListener("DOMContentLoaded", function () {
    const product = JSON.parse(localStorage.getItem("selectedProduct"));
    if (!product) {
        document.body.innerHTML = "<p>No product data found.</p>";
        return;
    }
    console.log(product);

    // Populate Title and Description
    document.getElementById("productTitle").textContent = product.productName || "Unnamed Product";
    document.getElementById("productDescription").innerHTML = `
        <p>${product.description || "No description available."}</p>
    `;

    // Image Carousel (only one image in this case)
    const carouselContainer = document.querySelector(".carousel-container");
    const indicatorsContainer = document.querySelector(".carousel-indicators");

    carouselContainer.innerHTML = '';
    indicatorsContainer.innerHTML = '';

    const img = document.createElement("img");
    img.src = product.imageUrl || './img/mouse_dances.jpg';
    img.alt = "Product Image";
    
    const slide = document.createElement("div");
    slide.className = "carousel-slide active";
    slide.appendChild(img);
    carouselContainer.appendChild(slide);

    const thumb = document.createElement("div");
    thumb.className = "indicator active";
    thumb.innerHTML = `<img src="${img.src}" alt="Thumbnail">`;
    indicatorsContainer.appendChild(thumb);

    // Price (single value)
    const priceGrid = document.getElementById("priceGrid");
    // Replace the priceGrid.innerHTML part with this:
    priceGrid.innerHTML = '';
    const cardContainer = document.createElement("div");
    cardContainer.className = "price-card-container";

    const card = document.createElement("div");
    card.className = "price-card";
    card.innerHTML = `
        <h4>${product.brandName || "Retailer"}</h4>
        <div class="price">R${product.prices}</div>
        <a href="#" class="store-link">Visit Store</a>
    `;

    const actions = document.createElement("div");
    actions.className = "card-actions";
    actions.innerHTML = `
        <button class="edit-btn" id="editProductBtn">Edit</button>
        <button class="delete-btn" id="deleteProductBtn">Delete</button>
    `;

    cardContainer.appendChild(card);
    cardContainer.appendChild(actions);
    priceGrid.appendChild(cardContainer);

    

    // Optional: Display specifications if needed
    try {
        const specs = JSON.parse(product.specifications || '{}');
        // You can render them if you want:
        // for (const key in specs) { ... }
    } catch (e) {
        console.warn("Invalid specs format");
    }
});




// Function to fetch and populate reviews
function fetchAndPopulateReviews() {
    const requestData = {
        type: 'GetProductReviews',
        productID: "3" // Replace with actual product ID you want reviews for
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
        console.log("Reviews data:", data);
        populateReviews(data.data);
    })
    .catch(error => {
        console.error("Failed to fetch reviews:", error);
        // Optionally show error message to user
    });
}

// Function to populate reviews in the UI
function populateReviews(reviews) {
    const reviewsList = document.getElementById('reviewsList');
    reviewsList.innerHTML = ''; // Clear existing reviews
    
    if (!reviews || reviews.length === 0) {
        reviewsList.innerHTML = '<p>No reviews yet. Be the first to review!</p>';
        return;
    }

    reviews.forEach(review => {
        const reviewItem = document.createElement('div');
        reviewItem.className = 'review-item';
        
        // Format date (assuming reviewDate is in a standard format)
        const reviewDate = review.reviewDate ? new Date(review.reviewDate).toLocaleDateString() : 'Unknown date';
        
        // Create star rating from rating value (assuming you have a rating column)
        const starRating = '★'.repeat(review.rating || 5) + '☆'.repeat(5 - (review.rating || 5));
        
        reviewItem.innerHTML = `
            <div class="review-header-info">
                <div class="reviewer-name">User ${review.userID}</div>
                <div class="review-date">${reviewDate}</div>
            </div>
            <div class="review-rating">${starRating}</div>
            <div class="review-text">
                ${review.comment || 'No comment provided.'}
            </div>
            ${review.retailerResponse ? `
            <div class="retailer-response">
                <strong>Retailer Response:</strong> ${review.retailerResponse}
            </div>
            ` : ''}
            <div class="review-helpful">
                <button class="helpful-btn" onclick="markHelpful(this)">👍 Helpful (0)</button>
                <button class="helpful-btn" onclick="markNotHelpful(this)">👎 Not Helpful (0)</button>
                <button class="add-response-btn" onclick="addResponse(${review.reviewID}, this)">📝 Add a Response</button>
            </div>
        `;
        
        reviewsList.appendChild(reviewItem);
    });
}

function addResponse(reviewID, buttonElement) {
    // Prevent multiple forms
    if (buttonElement.parentElement.querySelector('.response-form')) return;

    const form = document.createElement('div');
    form.className = 'response-form';
    form.innerHTML = `
        <textarea class="response-textarea" placeholder="Write your response..."></textarea>
        <button class="submit-response-btn">Submit</button>
    `;

    buttonElement.parentElement.appendChild(form);

    form.querySelector('.submit-response-btn').addEventListener('click', () => {
        const responseText = form.querySelector('.response-textarea').value.trim();
        if (!responseText) return;

        const requestData = {
            type: "RespondToReview",
            reviewID: reviewID,
            retailerID: "1",
            response: responseText
        };

        fetch('finalAPI.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text); });
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            form.remove();
            // Find the review text container
            const reviewItem = buttonElement.closest('.review-item');
            const reviewText = reviewItem.querySelector('.review-text');

            // Create and insert response box right after the review text
            const confirmation = document.createElement('div');
            confirmation.className = 'response-confirmation';
            confirmation.innerHTML = `
                <div class="retailer-response-box">
                    <strong>Retailer Response:</strong>
                    <p>${responseText}</p>
                </div>
            `;

            reviewText.insertAdjacentElement('afterend', confirmation);

        })
        .catch(error => {
            console.error("Failed to submit response:", error);
        });
    });
}




// Call this when the page loads or when product changes
document.addEventListener('DOMContentLoaded', function() {
    fetchAndPopulateReviews();
});

// Helper functions for the buttons
function markHelpful(button) {
    const countElement = button.textContent.match(/\((\d+)\)/);
    const currentCount = countElement ? parseInt(countElement[1]) : 0;
    button.textContent = button.textContent.replace(/\(\d+\)/, `(${currentCount + 1})`);
}

function markNotHelpful(button) {
    const countElement = button.textContent.match(/\((\d+)\)/);
    const currentCount = countElement ? parseInt(countElement[1]) : 0;
    button.textContent = button.textContent.replace(/\(\d+\)/, `(${currentCount + 1})`);
}

// Your existing toggle function
function toggleReviewForm() {
    const form = document.getElementById('reviewForm');
    form.classList.toggle('active');
}
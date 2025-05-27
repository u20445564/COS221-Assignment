let cachedproducts = [];
let conversion = [];

function fetchData(){
    const requestData = {
        type: 'GetAllRetailerRequests',
        retailerID: "1"
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
        cachedproducts = data.requests; // Same as before
        PopulateReq(cachedproducts);
    })
    .catch(error => {
        console.error("Failed to fetch products:", error);
    });
}


function PopulateReq(requests) {
    const container = document.querySelector('.requests');
    container.innerHTML = ''; // Clear existing content
    
        requests.forEach(request => {
            const requestItem = document.createElement('div');
            requestItem.className = 'request-item';
    
            requestItem.innerHTML = `
                <div class="request-info">
                    <span class="request-number">Request #${request.requestID}</span>
                    <span class="request-date">Created on: ${request.createdAt}</span>
                </div>
                <div class="request-status">
                    <span class="status ${request.status.toLowerCase()}">${request.status}</span>
                    <div class="request-actions">
                        <button class="view-btn" data-request-id="${request.requestID}">View</button>
                        <button class="edit-btn" data-request-id="${request.requestID}">Edit</button>
                        <button class="delete-btn" data-request-id="${request.requestID}">Delete</button>
                    </div>
                </div>
            `;
    
            container.appendChild(requestItem);
        });
}

window.onload = function(){
    fetchData();
};
    


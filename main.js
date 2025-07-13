document.addEventListener('DOMContentLoaded', function() {
    // Check authentication
    if (!localStorage.getItem('token') && !window.location.pathname.includes('login.html') && !window.location.pathname.includes('register.html')) {
        window.location.href = 'login.html';
        return;
    }

    // Load user orders
    if (window.location.pathname.includes('dashboard.html')) {
        loadUserOrders();
        
        // Search functionality
        const searchBtn = document.getElementById('searchBtn');
        searchBtn.addEventListener('click', performSearch);
        
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
});

function loadUserOrders() {
    fetch('../backend/api/auth.php?action=get_orders', {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const ordersList = document.getElementById('ordersList');
            if (data.orders.length > 0) {
                ordersList.innerHTML = data.orders.map(order => `
                    <div class="order-item">
                        <h3>${order.product}</h3>
                        <p>Date: ${order.date}</p>
                    </div>
                `).join('');
            } else {
                ordersList.innerHTML = '<p>No orders found.</p>';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function performSearch() {
    const query = document.getElementById('searchInput').value.trim();
    if (!query) return;

    fetch(`../backend/api/search.php?query=${encodeURIComponent(query)}`, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        }
    })
    .then(response => response.json())
    .then(data => {
        const searchResults = document.getElementById('searchResults');
        if (data.success && data.results.length > 0) {
            searchResults.innerHTML = `
                <h3>Search Results</h3>
                <ul>
                    ${data.results.map(item => `
                        <li>
                            <h4>${item.name || item.product}</h4>
                            <p>${item.date || item.phone || ''}</p>
                        </li>
                    `).join('')}
                </ul>
            `;
        } else {
            searchResults.innerHTML = '<p>No results found.</p>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('searchResults').innerHTML = '<p>Error performing search.</p>';
    });
}
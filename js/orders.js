$(document).ready(function () {
    // Function to fetch full name from session and display it
    function fetchFullName() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_session', // You need to create this file to fetch full name from session
            dataType: 'json',
            success: function (data) {
                if (data.success && data.role === 'customer') {
                    $('#fullname-display').text(data.fullname);
                    console.log(data.fullname);
                } else {
                    // Alert unauthorized access and redirect to unauthorized page if session is not set or user is not admin
                    alert('You need to login first.');
                    window.location.href = data.success ? 'index.html' : 'index.html';
                }
            },
            error: function (xhr, status, error) {
                $('#fullname-display').text("Error fetching full name");
            }
        });
    }

    // Fetch and display full name on page load
    fetchFullName();


// Function to get status badge class
function getStatusBadgeClass(status) {
    switch (status) {
        case 'Cart':
            return 'badge-info';
        case 'Pending':
            return 'badge-warning';
        case 'Preparing':
            return 'badge-warning';
        case 'Serving':
            return 'badge-primary';
        case 'Completed':
            return 'badge-success';
        default:
            return 'badge-secondary';
    }
}

// Function to fetch and populate cards for orders
function fetchAndPopulateCards() {
    $.ajax({
        type: 'GET',
        url: 'php/fetch_order-list',
        dataType: 'json',
        success: function (data) {
            console.log(data); // Debugging
            data.forEach(function (order) {
                var card = `
                    <div class="col-lg-4 col-md-6 col-sm-10 my-2">
                        <div class="card h-100">
                            <div class="card-header bg-dark text-white">
                                ${order.ordered_time}
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    ${formatItemsList(order.item_names)}
                                </ul>
                                <p>Total Payment: Php ${order.total}</p>
                                <p>Status: <span class="badge ${getStatusBadgeClass(order.status)}">${order.status}</span></p>
                            </div>
                        </div>
                    </div>
                `;
                $('.row').append(card);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error fetching orders:", error);
        }
    });
}

// Function to format items list
function formatItemsList(items) {
    if (!items || items.length === 0) {
        return '<li>No items found</li>';
    }
    var itemList = '';
    items.forEach(function (item) {
        itemList += '<li>' + item + '</li>';
    });
    return itemList;
}


// Call function to fetch and populate cards for orders
fetchAndPopulateCards();

    // Logout functionality
    $('#logout-link').click(function (event) {
        event.preventDefault();
        alert('Logged out successfully.')
        window.location.href = 'php/logout';
    });
});






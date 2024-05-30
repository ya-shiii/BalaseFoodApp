$(document).ready(function () {
    // Fetch and display full name on page load
    fetchFullName();

    fetchAndPopulateCards();
    
    // Event listener for updating order status
    $(document).on('click', '.update-status', function () {
        var customerId = $(this).data('customer-id');
        var orderTime = $(this).data('order-time');
        var newStatus = $(this).data('status');
        updateOrderStatus(customerId, orderTime, newStatus);
    });
    

    // Logout functionality
    $('#logout-link').click(function (event) {
        event.preventDefault();
        alert('Logged out successfully.')
        window.location.href = 'php/logout.php';
    });
});

    // Function to fetch full name from session and display it
    function fetchFullName() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_session.php', // You need to create this file to fetch full name from session
            dataType: 'json',
            success: function (data) {
                if (data.success && data.role === 'admin') {
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

    function fetchAndPopulateCards() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_orders.php',
            dataType: 'json',
            success: function (data) {
                console.log(data); // Debugging: log the data to check its format
                if (Array.isArray(data)) { // Check if data is an array
                    data.forEach(function (order) {
                        var card = `
                    <div class="col-lg-4 col-md-6 col-sm-10 my-2">
                        <div class="card h-100">
                            <div class="card-header bg-dark text-white">
                                ${order.ordered_time}
                            </div>
                            <div class="card-body">
                                <p>Customer: <span class="text-bold">${order.customer_name}</span></p>
                                <ul class="list-unstyled">
                                    ${formatItemsList(order.item_names)}
                                </ul>
                                <p>Total Payment: Php ${order.total}</p>
                                <p>Status: <span class="badge ${getStatusBadgeClass(order.status)}">${order.status}</span></p>
                                <div class="btn-group">
                                    ${renderStatusButton(order.status, order.customer_id, order.ordered_time)}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                        $('.row').append(card);
                    });
                } else {
                    console.error("Data is not an array:", data);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching orders:", error);
            }
        });
    }

// Function to render the appropriate status button based on the order status
    function renderStatusButton(status, customerId, orderTime) {
        switch (status) {
            case 'Pending':
                return `<button type="button" class="btn btn-primary btn-sm update-status" data-status="Preparing" data-customer-id="${customerId}" data-order-time="${orderTime}">Prepare</button>`;
            case 'Preparing':
                return `<button type="button" class="btn btn-info btn-sm update-status" data-status="Serving" data-customer-id="${customerId}" data-order-time="${orderTime}">Serve</button>`;
            case 'Serving':
                return `<button type="button" class="btn btn-success btn-sm update-status" data-status="Completed" data-customer-id="${customerId}" data-order-time="${orderTime}">Mark Completed</button>`;
            case 'Completed':
                return `<button id="deleteOrderButton_${customerId}_${orderTime}" type="button" class="btn btn-danger btn-sm update-status" data-status="Delete" data-customer-id="${customerId}" data-order-time="${orderTime}">Delete</button>
`;
            default:
                return '';
        }
    }

    // Function to format items list
    function formatItemsList(items) {
        // Check if itemNames is already an array
        if (Array.isArray(items)) {
            // Initialize an empty string to store the formatted list
            var itemList = '';

            // Loop through the items array and create list items
            items.forEach(function (item) {
                itemList += `<li>${item}</li>`;
            });

            // Return the formatted list
            return itemList;
        } else {
            // Assume itemNames is a comma-separated string and split it
            var items = items.split(', ');

            // Initialize an empty string to store the formatted list
            var itemList = '';

            // Check if items array is not empty
            if (items && items.length > 0) {
                // Loop through the items array
                items.forEach(function (item) {
                    itemList += `<li>${item}</li>`;
                });
            } else {
                // Handle case where items array is empty
                itemList = '<li>No items found</li>';
            }

            // Return the formatted list
            return itemList;
        }
    }
    
// Function to update the status of an order
function updateOrderStatus(customerId, orderTime, newStatus) {
    // Perform AJAX request to update the status
    $.ajax({
        type: 'POST',
        url: 'php/update_order_status.php', // You need to create this file to handle status updates
        data: { customerId: customerId, orderTime: orderTime, newStatus: newStatus },
        dataType: 'json',
        success: function (data) {
            // Check for success and handle accordingly
            if (data.success) {
                alert('Successfully updated order status.');
                // Refresh the order cards
                $('.row').empty();
                fetchAndPopulateCards();
            } else {
                // Handle error case
                alert('Failed to update order status.');
            }
        },
        error: function (xhr, status, error) {
            console.error("Error updating order status:", error);
            alert('Failed to update order status.');
        }
    });
}
    
    // Function to delete customer order
function deleteCustomerOrder(customerId, orderTime) {
    if (confirm("Are you sure you want to delete this order?")) {
        $.ajax({
            type: 'DELETE',
            url: 'php/delete_order.php',
            data: JSON.stringify({ customer_id: customerId, order_time: orderTime }),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                // Handle success response
                console.log(response.message);
                alert(response.message);
                window.location.reload(); // Reload the page after deletion
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(error);
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    }
}

// Function to add event listener to the delete button
function addDeleteButtonListener(customerId, orderTime) {
    const buttonId = `deleteOrderButton_${customerId}_${orderTime}`;
    const deleteButton = document.getElementById(buttonId);
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            deleteCustomerOrder(customerId, orderTime);
        });
    }
}

// Call the function to add the event listener after the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.update-status[data-status="Delete"]').forEach(button => {
        const customerId = button.getAttribute('data-customer-id');
        const orderTime = button.getAttribute('data-order-time');
        addDeleteButtonListener(customerId, orderTime);
    });
});



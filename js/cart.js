$(document).ready(function () {
    // Function to fetch full name from session and display it
    function fetchFullName() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_session.php', // You need to create this file to fetch full name from session
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

    // Function to fetch and populate cards for items
    function fetchAndPopulateCards() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_cart.php',
            dataType: 'json',
            success: function (data) {
                console.log(data); // Add this line to debug the response
                if (Array.isArray(data)) {
                    // Loop through each item and populate cards
                    data.forEach(function (item) {
                        var card = `
                        <div class="col-lg-4 col-md-6 col-sm-10 my-2">
                            <div class="card h-100 bg-dark text-white">
                                <div class="card-body">
                                    <div class="mb-4 col-12" style="height: 200px; overflow:hidden">
                                        <img src="img/menu/${item.filename}?t=${new Date().getTime()}" alt="${item.name}" class="img-fluid w-full mb-3">
                                    </div>
                        
                                    <h5 class="card-text text-bold">${item.name}</h5>
                                    <p class="card-text">Price each: Php ${item.price}</p>
                                    <p class="card-text">Amount Ordered: <span class="text-bold">${item.amount}</span></p>
                                    <p class="font-italic text-success">Total: Php ${item.total}</p>
                                    <a href="#" class="btn btn-primary w-auto mr-2" onclick="editOrder(${item.order_id})">Edit Amount</a>
                                    <a href="#" class="btn btn-danger w-auto" onclick="deleteOrder(${item.order_id})">Delete</a>
                                </div>
                            </div>
                        </div>
                    `;

                        $('.row').append(card);
                    });
                } else {
                    console.error("Expected an array but got:", data);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching items:", error);
            }
        });
    }

    // Call function to fetch and populate cards for items
    fetchAndPopulateCards();

    // Function to fetch the total amount from the server
    function fetchTotalAmount() {
        $.ajax({
            url: 'php/fetch_total.php', // PHP file to fetch the total amount
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.total_amount == null) {
                    $('#totalToPay').text(`No orders yet`);
                } else if (data.total_amount !== undefined) {
                    const totalAmount = parseFloat(data.total_amount).toFixed(2);
                    $('#totalToPay').text(`Total to-pay: Php ${totalAmount}`);
                }else {
                    alert('Error fetching total amount.');
                }
            },
            error: function() {
                alert('Error fetching total amount.');
            }
        });
    }

    // Call the function to fetch the total amount when the page loads
    fetchTotalAmount();
    
    $('#checkOutButton').on('click', function(event) {
        event.preventDefault(); // Prevent the default behavior

        $.ajax({
            url: 'php/check-out-cart.php', // URL of the PHP file
            type: 'PUT',
            contentType: 'application/json',
            success: function(response) {
                alert('Order checked out successfully!');
                window.location.href = document.referrer;
            },
            error: function(xhr, status, error) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });

    // Logout functionality
    $('#logout-link').click(function (event) {
        event.preventDefault();
        alert('Logged out successfully.')
        window.location.href = 'php/logout.php';
    });
});

function editOrder(order_id) {
    // Fetch the item details using the item ID
    $.ajax({
        url: 'php/fetch_order_info.php', // Replace with your PHP file to fetch item details
        type: 'POST',
        data: { order_id: order_id },
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                alert(data.error);
            } else {
                $('#orderItemName').val(data.item_name);
                $('#orderItemId').val(data.item_id); // Hidden input for item ID
                $('#orderId').val(data.order_id); // Hidden input for order ID
                $('#customerId').val(data.customer_id); // Hidden input for customer ID
                $('#orderItemPrice').val(data.price); // Hidden input for price
                $('#orderItemAmount').val(data.amount); // Set the current amount
                $('#orderItemTotal').val(data.total); // Hidden input for total

                // Show the modal
                $('#editOrderModal').modal('show');
            }
        },
        error: function () {
            alert('Error fetching item details.');
        }
    });
}

function deleteOrder(order_id) {
    if (confirm("Are you sure you want to delete this order?")) {
        $.ajax({
            url: 'php/delete_order.php', // Replace with your PHP file for deleting orders
            type: 'DELETE',
            contentType: 'application/json',
            data: JSON.stringify({ order_id: order_id }),
            success: function(response) {
                alert('Order deleted successfully.');
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('Error deleting order: ' + xhr.responseText);
            }
        });
    }
}





// Calculate total when amount is changed
$('#orderItemAmount').on('input', function () {
    const price = parseFloat($('#orderItemPrice').val());
    const amount = parseInt($('#orderItemAmount').val());
    if (!isNaN(price) && !isNaN(amount)) {
        $('#orderItemTotal').val(price * amount);
    }
});




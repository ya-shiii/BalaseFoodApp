$(document).ready(function () {
    // Function to fetch a cookie by name
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Function to fetch and populate cards for items
    function fetchAndPopulateCards() {
        var user_id = getCookie('user_id'); // Get the user_id from the cookie

        if (user_id) {
            $.ajax({
                type: 'GET',
               url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_cart',
                data: { user_id: user_id }, // Send the user_id as a parameter
                dataType: 'json',
                success: function (data) {
                    console.log(data); // Debug the response
                    if (Array.isArray(data)) {
                        $('.row').empty(); // Clear the previous items
                        data.forEach(function (item) {
                            var card = `
                        <div class="col-lg-4 col-md-6 col-sm-10 my-2">
                            <div class="card h-100 bg-dark text-white">
                                <div class="card-body">
                                    <div class="mb-4 col-12" style="height: 200px; overflow:hidden">
                                        <img src="${item.img_path}?t=${new Date().getTime()}" alt="${item.name}" class="img-fluid w-full mb-3">
                                    </div>
                                    <h5 class="card-text text-bold">${item.name}</h5>
                                    <p class="card-text">Price each: Php ${item.price}</p>
                                    <p class="card-text">Amount Ordered: <span class="text-bold">${item.amount}</span></p>
                                    <p class="font-italic text-success">Total: Php ${item.total}</p>
                                    <a href="#" class="btn btn-primary w-auto mr-2" onclick="editOrder(${item.order_id})">Edit Order</a>
                                    <a href="#" class="btn btn-danger w-auto" onclick="deleteOrder(${item.order_id})">Delete</a>
                                </div>
                            </div>
                        </div>`;
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
        } else {
            console.error("User ID not found in cookies.");
        }
    }

    // Fetch and populate cards on page load
    $(document).ready(function () {
        fetchAndPopulateCards();
    });


    // Function to fetch the total amount from the server
    function fetchTotalAmount() {
        $.ajax({
           url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_total', // PHP file to fetch the total amount
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.total_amount == null) {
                    $('#totalToPay').text(`No orders yet`);
                } else if (data.total_amount !== undefined) {
                    const totalAmount = parseFloat(data.total_amount).toFixed(2);
                    $('#totalToPay').text(`Total to-pay: Php ${totalAmount}`);
                } else {
                    alert('Error fetching total amount.');
                }
            },
            error: function () {
                alert('Error fetching total amount.');
            }
        });
    }

    // Call the function to fetch the total amount when the page loads
    fetchTotalAmount();

    $('#checkOutButton').on('click', function (event) {
        event.preventDefault(); // Prevent the default behavior

        // Get user_id from cookies
        var user_id = getCookie('user_id');

        // Check if user_id is available
        if (!user_id) {
            alert('User ID not found in cookies. Please log in.');
            return;
        }

        // Create data object to send to the server
        var data = {
            user_id: user_id
        };

        $.ajax({
           url: 'https://balasefoodorderingsystem.muccs.host/php/check-out-cart', // URL of the PHP file
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data), // Send the user_id in the request body
            success: function (response) {
                alert('Order checked out successfully!');
                window.location.href = document.referrer;
            },
            error: function (xhr, status, error) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
});

function editOrder(order_id) {
    // Fetch the item details using the item ID
    $.ajax({
       url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_order_info', // Replace with your PHP file to fetch item details
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
           url: 'https://balasefoodorderingsystem.muccs.host/php/delete_order', // Replace with your PHP file for deleting orders
            type: 'DELETE',
            contentType: 'application/json',
            data: JSON.stringify({ order_id: order_id }),
            success: function (response) {
                alert('Order deleted successfully.');
                location.reload();
            },
            error: function (xhr, status, error) {
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




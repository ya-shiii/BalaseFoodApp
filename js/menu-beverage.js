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

    // Function to fetch and populate cards for items
    function fetchAndPopulateCards() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_menu-beverage',
            dataType: 'json',
            success: function (data) {
                console.log(data); // Add this line to debug the response
                if (Array.isArray(data)) {
                    // Loop through each item and populate cards
                    data.forEach(function (item) {
                        var card = `
                            <div class="col-lg-4 col-md-6 col-sm-10 my-2" data-name="${item.name}">
                                <a href="#" class="card h-100 bg-dark text-white w-auto" onclick="orderItem(${item.item_id})">
                                    <div class="card-body">
                                        <div class="mb-4 col-12" style="height: 200px; overflow:hidden">
                                            <img src="img/menu/${item.filename}?t=${new Date().getTime()}" alt="${item.name}" class="img-fluid w-full mb-3">
                                        </div>
                                        
                                        <h5 class="card-text text-bold">${item.name}</h5>
                                        <p class="card-text">${item.description}</p>
                                        <p class="card-text">Price: Php ${item.price}</p>
                                        <p class="font-italic text-success">${item.category}</p>
                                        Order Now!
                                    </div>
                                </a>
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
    }

    // Call function to fetch and populate cards for items
    fetchAndPopulateCards();

    
    
    // Logout functionality
    $('#logout-link').click(function (event) {
        event.preventDefault();
        alert('Logged out successfully.')
        window.location.href = 'php/logout';
    });
});

function orderItem(itemId) {
    // Fetch the item details using the item ID
    $.ajax({
        url: 'php/fetch_item_info', // Replace with your PHP file to fetch item details
        type: 'POST',
        data: { item_id: itemId },
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                alert(data.error);
            } else {
                $('#orderItemName').val(data.name);
                $('#orderItemDescription').val(data.description);
                $('#orderItemPrice').val(data.price);
                $('#orderItemCategory').val(data.category);
                $('#orderItemId').val(data.item_id); // Hidden input for item ID
                $('#orderItemImage').attr('src', 'img/menu/' + data.filename);

                // Show the modal
                $('#orderItemModal').modal('show');
            }
        },
        error: function() {
            alert('Error fetching item details.');
        }
    });
}

function validatePositiveInteger(input) {
    const value = parseInt(input.value, 10);
    
    if (isNaN(value) || value < 1) {
        input.value = '';
    } else {
        input.value = value;
    }
}

function preventInvalidKeys(e) {
    if (e.key === '-' || e.key === '.' || e.key === 'e') {
        e.preventDefault();
    }
}

const itemAmount = document.getElementById('orderItemAmount');

[itemAmount].forEach(input => {
    input.addEventListener('input', function(e) {
        validatePositiveInteger(e.target);
    });

    input.addEventListener('keydown', function(e) {
        preventInvalidKeys(e);
    });
});

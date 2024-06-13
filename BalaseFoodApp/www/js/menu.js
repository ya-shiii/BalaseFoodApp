$(document).ready(function () {
    // Fetch and populate cards on page load
    fetchAndPopulateCards();

    // Event listeners for filter buttons
    $('.category').click(function (event) {
        event.preventDefault();

        // Remove 'active' class from all buttons and add to the clicked one
        $('.btn').removeClass('active');
        $(this).addClass('active');

        // Get the status to filter by
        var status = $(this).text();

        // Fetch and filter the cards based on the status
        fetchAndPopulateCards(status);
    });
});
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
function fetchAndPopulateCards(filterStatus = 'All') {
    $.ajax({
        type: 'GET',
       url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_menu_customer',
        dataType: 'json',
        success: function (data) {
            console.log(data); // Add this line to debug the response
            $('.menu').empty();
            if (Array.isArray(data)) {
                data.forEach(function (item) {
                    if (filterStatus === 'All' || item.category === filterStatus) {
                        var card = `
                        <div class="col-lg-4 col-md-6 col-sm-10 my-2" data-name="${item.name}">
                            <a href="#" class="card h-100 bg-dark text-white w-auto" onclick="orderItem(${item.item_id})">
                                <div class="card-body">
                                    <div class="mb-4 col-12" style="height: 200px; overflow:hidden">
                                        <img src="https://balasefoodorderingsystem.muccs.host/${item.img_path}?t=${new Date().getTime()}" alt="${item.name}" class="img-fluid w-full mb-3">
                                    </div>
                                    <h5 class="card-text text-bold">${item.name}</h5>
                                    <p class="card-text">${item.description}</p>
                                    <p class="card-text">Price: Php ${item.price}</p>
                                    <p class="font-italic text-success">${item.category}</p>
                                    Order Now!
                                </div>
                            </a>
                        </div>`;
                        $('.menu').append(card);
                    }
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

function orderItem(itemId) {
    // Fetch the item details using the item ID
    $.ajax({
       url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_item_info', // Replace with your PHP file to fetch item details
        type: 'POST',
        data: { item_id: itemId },
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                alert(data.error);
            } else {
                $('#orderItemName').val(data.name);
                $('#orderItemDescription').val(data.description);
                $('#orderItemPrice').val(data.price);
                $('#orderItemCategory').val(data.category);
                $('#orderItemId').val(data.item_id); // Hidden input for item ID
                $('#orderItemImage').attr('src', data.filename);

                // Show the modal
                $('#orderItemModal').modal('show');
            }
        },
        error: function () {
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
    input.addEventListener('input', function (e) {
        validatePositiveInteger(e.target);
    });

    input.addEventListener('keydown', function (e) {
        preventInvalidKeys(e);
    });
});



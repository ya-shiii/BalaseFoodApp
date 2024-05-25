$(document).ready(function () {
    // Function to fetch full name from session and display it
    function fetchFullName() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_session.php', // You need to create this file to fetch full name from session
            dataType: 'json',
            success: function (data) {
                if (data.success && data.role === 'admin') {
                    console.log(data.fullname);
                } else {
                    // Alert unauthorized access and redirect to unauthorized page if session is not set or user is not admin
                    alert('Unauthorized access.');
                    window.location.href = data.success ? 'index.html' : 'index.html';
                }
            },
            error: function (xhr, status, error) {
                console.log('Error fetching full name');
            }
        });
    }

    // Fetch and display full name on page load
    fetchFullName();

    // Function to fetch and populate cards for users
    function fetchAndPopulateCards() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_menu.php',
            dataType: 'json',
            success: function (data) {
                console.log(data); // Add this line to debug the response
                if (Array.isArray(data)) {
                    // Loop through each user and populate cards
                    data.forEach(function (user) {
                        var card = `
                            <div class="col-lg-4 col-md-6 col-sm-10 my-2">
                                <div class="card h-100 bg-dark text-white">
                                    <div class="card-body">
                                        <div class="mb-4 col-12" style="height: 200px; overflow:hidden">
                                            <img src="img/menu/${user.filename}?t=${new Date().getTime()}" alt="${user.name}" class="img-fluid w-full mb-3">
                                        </div>
                                        
                                        <h5 class="card-text text-bold">${user.name}</h5>
                                        <p class="card-text">${user.description}</p>
                                        <p class="card-text">Price: ${user.price}</p>
                                        <p class="font-italic text-success">${user.category}</p>
                                        <a href="#" class="btn btn-primary w-auto" onclick="fetchItemInfo(${user.item_id})">Edit</a>
                                        <a href="#" class="btn btn-danger w-auto" onclick="dismissItem(${user.item_id})">Delete</a>
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
                console.error("Error fetching users:", error);
            }
        });
    }

    // Call function to fetch and populate cards for users
    fetchAndPopulateCards();
});

function fetchItemInfo(item_id) {
    $.ajax({
        type: 'POST',
        url: 'php/fetch_menu_info.php',
        data: { item_id: item_id },
        dataType: 'json',
        success: function (response) {
            // Call editItem function with fetched data
            editItem(response);
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

function editItem(ItemInfo) {
    // Populate the edit modal with the fetched data
    $('#item_id').val(ItemInfo.item_id);
    $('#editItemName').val(ItemInfo.name);
    $('#editItemDescription').val(ItemInfo.description);
    $('#editItemPrice').val(ItemInfo.price);
    $('#editItemCategory').val(ItemInfo.category);

    // Generate the image filename
    var imageUrl = 'img/menu/' + ItemInfo.name.replace(/ /g, '_') + '.jpg';
    $('#editItemImage').attr('src', imageUrl);

    // Show the edit modal
    $('#editItemModal').modal('show');
}


function dismissItem(item_id) {
    if (confirm("Are you sure you want to remove this item?")) {
        $.ajax({
            type: 'POST',
            url: 'php/delete_item.php', // Replace with your backend endpoint
            data: { item_id: item_id },
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
            }
        });
    }
}

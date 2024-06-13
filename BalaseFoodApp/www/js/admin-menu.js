$(document).ready(function () {
    


    // Function to fetch and populate cards for users
    function fetchAndPopulateCards() {
        $.ajax({
            type: 'GET',
           url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_menu',
            dataType: 'json',
            success: function (data) {
                console.log(data); // Add this line to debug the response
                if (Array.isArray(data)) {
                    // Loop through each user and populate cards
                    data.forEach(function (user) {
                        if (user.status == 'Available') {
                            var card = `
                                <div class="col-lg-4 col-md-6 col-sm-10 my-2" data-name="${user.name}">
                                    <div class="card h-100 bg-dark text-white">
                                        <div class="card-body">
                                            <div class="mb-4 col-12" style="height: 200px; overflow:hidden">
                                                <img src="${user.img_path}?t=${new Date().getTime()}" alt="${user.name}" class="img-fluid w-full mb-3">
                                            </div>
                                            
                                            <h5 class="card-text text-bold">${user.name}</h5>
                                            <p class="card-text">${user.description}</p>
                                            <p class="card-text">Price: Php ${user.price}</p>
                                            <p class="font-italic text-success">${user.category}</p>
                                            <p class="font-italic text-warning">${user.status}</p>
                                            <a href="#" class="btn btn-primary w-auto" onclick="fetchItemInfo(${user.item_id})">Edit</a>
                                            <a href="#" class="btn btn-warning w-auto" onclick="changeItemStatus(${user.item_id})">Mark Unavailable</a>
                                            <a href="#" class="btn btn-danger w-auto" onclick="deleteItem(${user.item_id})">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            var card = `
                                <div class="col-lg-4 col-md-6 col-sm-10 my-2" data-name="${user.name}">
                                    <div class="card h-100 bg-dark text-white">
                                        <div class="card-body">
                                            <div class="mb-4 col-12" style="height: 200px; overflow:hidden">
                                                <img src="${user.img_path}?t=${new Date().getTime()}" alt="${user.name}" class="img-fluid w-full mb-3">
                                            </div>
                                            
                                            <h5 class="card-text text-bold">${user.name}</h5>
                                            <p class="card-text">${user.description}</p>
                                            <p class="card-text">Price: Php ${user.price}</p>
                                            <p class="font-italic text-success">${user.category}</p>
                                            <p class="font-italic text-danger">${user.status}</p>
                                            <a href="#" class="btn btn-primary w-auto" onclick="fetchItemInfo(${user.item_id})">Edit</a>
                                            <a href="#" class="btn btn-success w-auto" onclick="changeItemStatus(${user.item_id})">Mark Available</a>
                                            <a href="#" class="btn btn-danger w-auto" onclick="deleteItem(${user.item_id})">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }


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

    $('#addItemBtn').click(function (event) {
        event.preventDefault(); // Prevent default form submission

        // Get form data
        var formData = new FormData($('#addItemForm')[0]);

        // Send AJAX request
        $.ajax({
            type: 'POST',
           url: 'https://balasefoodorderingsystem.muccs.host/php/add_item', 
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Handle success response
                console.log(response);
                alert(response.message);

                window.location.reload(); // Reload the page after alert
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });



    $('#editItemBtn').click(function (event) {
        event.preventDefault(); // Prevent default form submission

        // Get form data
        var formData = new FormData($('#editItemForm')[0]);

        // Get item_id from hidden input field
        var itemId = $('#item_id').val();
        formData.append('item_id', itemId); // Add item_id to the formData

        // Send POST request using AJAX
        $.ajax({
            type: 'POST',
           url: 'https://balasefoodorderingsystem.muccs.host/php/edit_item',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Handle success response
                console.log(response);
                alert(response.message);
                // Reload the page after the alert is dismissed
                window.location.reload(); // Reload the page after alert

            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });


});

function fetchItemInfo(item_id) {
    $.ajax({
        type: 'POST',
       url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_menu_info',
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
    $('#editItemImage').attr('src', ItemInfo.img_path);

    // Show the edit modal
    $('#editItemModal').modal('show');
}


function changeItemStatus(item_id) {
    if (confirm("Change item availability?")) {
        $.ajax({
            type: 'PUT',
           url: 'https://balasefoodorderingsystem.muccs.host/php/change_item_availability', 
            data: JSON.stringify({ item_id: item_id }),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                // Handle success response
                console.log(response.message);
                alert(response.message);
                window.location.reload(); // Reload the page after alert
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(error);
            }
        });
    }
}

function deleteItem(item_id) {
    if (confirm("Are you sure you want to remove this item?")) {
        $.ajax({
            type: 'DELETE',
           url: 'https://balasefoodorderingsystem.muccs.host/php/delete_item', 
            data: JSON.stringify({ item_id: item_id }),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                // Handle success response
                console.log(response.message);
                alert(response.message);
                window.location.reload(); // Reload the page after alert
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(error);
            }
        });
    }
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

const itemPrice = document.getElementById('ItemPrice');
const editItemPrice = document.getElementById('editItemPrice');

[itemPrice, editItemPrice].forEach(input => {
    input.addEventListener('input', function (e) {
        validatePositiveInteger(e.target);
    });

    input.addEventListener('keydown', function (e) {
        preventInvalidKeys(e);
    });
});


$(document).ready(function () {
    $('#searchButton').on('click', function () {
        var searchValue = $('#searchInput').val().toLowerCase();
        searchAndDisplayResults(searchValue);
    });
});

function searchAndDisplayResults(searchValue) {
    $.ajax({
        type: 'GET',
       url: 'https://balasefoodorderingsystem.muccs.host/php/search_menu',
        data: { query: searchValue },
        dataType: 'json',
        success: function (data) {
            $('#searchResultsContainer').empty(); // Clear previous search results
            if (Array.isArray(data) && data.length > 0) {
                var propClass;
                var imgSize;
                if (data.length === 1) {
                    propClass = 'col-10';
                    imgSize = '400px';
                } else if (data.length === 2) {
                    propClass = 'col-6';
                    imgSize = '300px';
                } else {
                    propClass = 'col-lg-4 col-md-6 col-sm-10';
                    imgSize = '200px';
                }

                data.forEach(function (item) {
                    var card = `
                        <div class="${propClass} my-2 item-card" data-name="${item.name}">
                            <div class="card bg-dark h-100 text-white">
                                <div class="bg-dark card-body">
                                    <div class="mb-4 col-12" style="height: ${imgSize}; overflow:hidden">
                                        <img src="https://balasefoodorderingsystem.muccs.host/${item.img_path}?t=${new Date().getTime()}" alt="${item.name}" class="img-fluid w-full mb-3">
                                    </div>
                                    <h5 class="card-text text-bold">${item.name}</h5>
                                    <p class="card-text">${item.description}</p>
                                    <p class="card-text">Price: Php ${item.price}</p>
                                    <p class="font-italic text-success">${item.category}</p>
                                    <a href="#" class="btn btn-primary w-auto" onclick="fetchItemInfo(${item.item_id})">Edit</a>
                                    <a href="#" class="btn btn-danger w-auto" onclick="deleteItem(${item.item_id})">Delete</a>
                                </div>
                            </div>
                        </div>
                        
                        `;
                    $('#searchResultsContainer').append(card);
                });
                $('#searchResultsModal').modal('show'); // Show the modal with search results
            } else {
                $('#searchResultsContainer').append('<p class="text-center w-100">No results found.</p>');
                $('#searchResultsModal').modal('show'); // Show the modal even if no results
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching search results:", error);
        }
    });
}



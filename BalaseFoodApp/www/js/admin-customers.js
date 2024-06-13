$(document).ready(function () {
    // Function to fetch and populate cards for users
    function fetchAndPopulateCards() {
        $.ajax({
            type: 'GET',
           url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_customers',
            dataType: 'json',
            success: function (data) {
                // Loop through each user and populate cards
                data.forEach(function (user) {
                    var card = `
                        <div class="col-lg-4 col-md-6 col-sm-10 my-2">
                            <div class="card h-100 bg-dark text-white">
                                <div class="card-body">
                                    <h5 class="card-title text-bold">${user.full_name}</h5>
                                    <p class="card-text">Address: ${user.address}</p>
                                    <p class="card-text">Email: ${user.email}</p>
                                    <p class="card-text">Phone number: ${user.phone}</p>
                                    <p class="card-text">Total Orders: ${user.num_orders}</p>
                                    <a href="#" class="btn btn-primary w-auto" onclick="fetchCustomerInfo(${user.user_id})">Edit</a>
                                    <a href="#" class="btn btn-warning w-auto" onclick="changeType(${user.user_id})">Change Type</a>
                                    <a href="#" class="btn btn-danger w-auto" onclick="deactivateUser(${user.user_id})">Delete</a>
                                </div>
                            </div>
                        </div>`;

                    $('.row').append(card);

                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching users:", error);
            }
        });
    }

    // Call function to fetch and populate cards for users
    fetchAndPopulateCards();


});
function fetchCustomerInfo(u_id) {
    $.ajax({
        type: 'POST',
       url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_customer_info',
        data: { u_id: u_id },
        dataType: 'json',
        success: function (response) {
            // Call editCustomer function with fetched data
            editCustomer(response);
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

function editCustomer(CustomerInfo) {
    // Populate the edit modal with the fetched data
    $('#user_id').val(CustomerInfo.user_id);
    $('#editCustomerName').val(CustomerInfo.full_name);
    $('#editCustomerUsername').val(CustomerInfo.username);
    $('#editCustomerPassword').val(CustomerInfo.password);
    $('#editCustomerEmail').val(CustomerInfo.email);
    $('#editCustomerPhone').val(CustomerInfo.phone);
    $('#editCustomerAddress').val(CustomerInfo.address);

    // Show the edit modal
    $('#editCustomerModal').modal('show');
}

document.getElementById('editCustomerBtn').addEventListener('click', function () {
    // Get form data
    const formData = new FormData(document.getElementById('editCustomerForm'));

    // Get user_id from hidden input field
    const user_id = formData.get('user_id');

    // Create an object to hold the form data
    let data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });

    // Send PUT request using AJAX
    $.ajax({
       url: 'https://balasefoodorderingsystem.muccs.host/php/edit_customer',
        type: 'PUT',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function (response) {
            console.log(response);
            if (response && response.message) {
                alert(response.message);
            } else {
                alert('Unexpected response from server');
            }
            location.reload();
            
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
        }
    });
});


function changeType(u_id) {
    if (confirm("Are you sure you want to change user type?")) {
        $.ajax({
            type: 'POST',
           url: 'https://balasefoodorderingsystem.muccs.host/php/change_customertype', // Replace with your backend endpoint
            data: { u_id: u_id },
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

// Function to deactivate user
function deactivateUser(user_id) {
    // Perform action to deactivate user with given user_id
    console.log(`Deactivating user with ID: ${user_id}`);
    $.ajax({
        type: 'DELETE',
       url: 'https://balasefoodorderingsystem.muccs.host/php/deactivate_user', // Assuming this is the endpoint to handle deactivation
        data: JSON.stringify({ user_id: user_id }), // Send data as JSON string
        contentType: 'application/json', // Set content type to JSON
        dataType: 'json',
        success: function (response) {
            // Handle success response
            console.log(response.message);
            alert(response.message);
            window.location.href = 'admin-customers.html';
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error(error);
            alert('An error occurred: ' + xhr.responseText);
        }
    });
}


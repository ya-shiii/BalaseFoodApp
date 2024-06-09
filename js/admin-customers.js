$(document).ready(function () {
    // Function to fetch full name from session and display it
    function fetchFullName() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_session', // You need to create this file to fetch full name from session
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
            url: 'php/fetch_customers',
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
                                    <a href="#" class="btn btn-warning w-auto" onclick="changeType(${user.user_id})">Change Type</a>
                                    <a href="#" class="btn btn-danger w-auto" onclick="deactivateUser(${user.user_id})">Delete Account</a>
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

function changeType(u_id) {
    if (confirm("Are you sure you want to change user type?")) {
        $.ajax({
            type: 'POST',
            url: 'php/change_customertype', // Replace with your backend endpoint
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
        url: 'php/deactivate_user', // Assuming this is the endpoint to handle deactivation
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


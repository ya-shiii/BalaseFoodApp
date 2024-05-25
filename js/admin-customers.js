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
            url: 'php/fetch_customers.php',
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

// Function to deactivate user
function deactivateUser(user_id) {
    // Perform action to deactivate user with given u_id
    console.log(`Deactivating user with ID: ${user_id}`);
    $.ajax({
        type: 'POST',
        url: 'php/deactivate_user.php', // Assuming this is the endpoint to handle deactivation
        data: { user_id: user_id },
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
        }
    });
}

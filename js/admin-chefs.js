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
            url: 'php/fetch_chef.php',
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
                                        <h5 class="card-title text-bold">${user.full_name}</h5>
                                        <p class="card-text">Address: ${user.address}</p>
                                        <p class="card-text">Email: ${user.email}</p>
                                        <p class="card-text">Phone number: ${user.phone}</p>
                                        <a href="#" class="btn btn-primary w-auto" onclick="fetchChefInfo(${user.u_id})">Edit</a>
                                        <a href="#" class="btn btn-danger w-auto" onclick="dismissChef(${user.u_id})">Delete</a>
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

function fetchChefInfo(u_id) {
    $.ajax({
        type: 'POST',
        url: 'php/fetch_chef_info.php',
        data: { u_id: u_id },
        dataType: 'json',
        success: function (response) {
            // Call editChef function with fetched data
            editChef(response);
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

function editChef(ChefInfo) {
    // Populate the edit modal with the fetched data
    $('#user_id').val(ChefInfo.user_id);
    $('#editChefName').val(ChefInfo.full_name);
    $('#editChefUsername').val(ChefInfo.username);
    $('#editChefPassword').val(ChefInfo.password);
    $('#editChefEmail').val(ChefInfo.email);
    $('#editChefPhone').val(ChefInfo.phone);
    $('#editChefAddress').val(ChefInfo.address);

    // Show the edit modal
    $('#editChefModal').modal('show');
}

function dismissChef(u_id) {
    if (confirm("Are you sure you want to remove this chef?")) {
        $.ajax({
            type: 'POST',
            url: 'php/delete_chef.php', // Replace with your backend endpoint
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
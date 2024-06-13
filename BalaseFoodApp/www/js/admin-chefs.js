$(document).ready(function () {
    // Function to fetch and populate cards for users
    function fetchAndPopulateCards() {
        $.ajax({
            type: 'GET',
           url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_chef',
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
                                        <a href="#" class="btn btn-warning w-auto" onclick="changeType(${user.u_id})">Change Type</a>
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

    $('#account-form').submit(function (event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = {
            user_id: $('#user_id').val(),
            username: $('#username').val(),
            password: $('#password').val(),
            full_name: $('#full_name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            address: $('#address').val(),
            old_password: $('#old_password').val()
        };

        $.ajax({
           url: 'https://balasefoodorderingsystem.muccs.host/php/update_chef_details',
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function (response) {
                alert(response.message);
                window.location.reload(); // Reload the page after alert
            },
            error: function (xhr, status, error) {
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });


});

function fetchChefInfo(u_id) {
    $.ajax({
        type: 'POST',
       url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_chef_info',
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

function changeType(u_id) {
    if (confirm("Are you sure you want to change user type?")) {
        $.ajax({
            type: 'POST',
           url: 'https://balasefoodorderingsystem.muccs.host/php/change_cheftype', // Replace with your backend endpoint
            data: { u_id: u_id },
            dataType: 'json',
            success: function (response) {
                // Handle success response
                console.log(response.message);
                alert(response.message);
                // Reload the page after the alert is dismissed
                    window.location.reload(); // Reload the page after alert
            },
            error: function (xhr, status, error) {
                // Handle error
                console.error(error);
            }
        });
    }
}

function dismissChef(u_id) {
    if (confirm("Are you sure you want to remove this chef?")) {
        $.ajax({
            type: 'DELETE',
           url: 'https://balasefoodorderingsystem.muccs.host/php/delete_chef',
            data: JSON.stringify({ u_id: u_id }),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                alert(response.message);
                // Reload the page after the alert is dismissed

                window.location.reload(); // Reload the page after alert
            },
            error: function (xhr, status, error) {
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    }
}

document.getElementById('editChefBtn').addEventListener('click', function () {
    // Get form data
    const formData = new FormData(document.getElementById('editChefForm'));

    // Get user_id from hidden input field
    const user_id = formData.get('user_id');

    // Create an object to hold the form data
    let data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });

    // Send PUT request using AJAX
    $.ajax({
       url: 'https://balasefoodorderingsystem.muccs.host/php/edit_chef',
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
                window.location.reload(); // Reload the page after alert
            
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
        }
    });
});

$(document).ready(function () {
    $('#addChefForm').submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        // Get form data
        var formData = {
            full_name: $('#ChefName').val(),
            u_name: $('#ChefUsername').val(),
            password: $('#ChefPassword').val(),
            email: $('#ChefEmail').val(),
            phone: $('#ChefPhone').val(),
            address: $('#ChefAddress').val()
        };

        // Send PUT request using AJAX
        $.ajax({
           url: 'https://balasefoodorderingsystem.muccs.host/php/add_chef',
            type: 'PUT',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function (response) {
                // Handle success response
                console.log(response);
                var message = response.message || 'New chef added successfully.'; // Default message if 'message' key is not present
                alert(message);
                window.location.reload(); // Reload the page after submission
            },

            error: function (xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });
});





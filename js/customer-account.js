$(document).ready(function () {
    // Function to fetch full name from session and display it
    function fetchFullName() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_session.php', // You need to create this file to fetch full name from session
            dataType: 'json',
            success: function (data) {
                if (data.success && data.role === 'customer') {
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

    // Function to fetch and populate account details
    function fetchAccountDetails() {
        $.ajax({
            type: 'GET',
            url: 'php/fetch_customer_details.php',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $('#user_id').val(data.user_id);
                    $('#username').val(data.username);
                    $('#password').val(data.password);
                    $('#full_name').val(data.full_name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    $('#address').val(data.address);
                } else {
                    alert('Failed to fetch account details.');
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching account details:", error);
                alert('Failed to fetch account details.');
            }
        });
    }

    // Fetch and populate account details on page load
    fetchAccountDetails();
    
    
    $('#account-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = $(this).serializeArray();
        const data = {};

        formData.forEach(item => {
            data[item.name] = item.value;
        });

        $.ajax({
            url: 'php/update_customer_details.php', // URL of your PHP file
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                alert('Account details updated successfully.');
                window.location.href = document.referrer;
            },
            error: function(xhr, status, error) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
    
    
    // Logout functionality
    $('#logout-link').click(function (event) {
        event.preventDefault();
        alert('Logged out successfully.')
        window.location.href = 'php/logout.php';
    });
});


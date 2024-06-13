$(document).ready(function () {
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

    // Function to fetch and populate account details
    function fetchAccountDetails() {
        var user_id = getCookie('user_id'); // Get user_id from cookies

        $.ajax({
            type: 'GET',
           url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_customer_details',
            data: { user_id: user_id }, // Pass user_id as data
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


    // Submit form and update account details
    $('#account-form').on('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = $(this).serializeArray();
        const data = {};

        formData.forEach(item => {
            data[item.name] = item.value;
        });

        // Get user_id from cookies
        var user_id = getCookie('user_id');

        $.ajax({
           url: 'https://balasefoodorderingsystem.muccs.host/php/update_customer_details', // URL of your PHP file
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify({ ...data, user_id: user_id }), // Include user_id in the data
            success: function (response) {
                alert('Account details updated successfully.');
                window.location.href = document.referrer;
            },
            error: function (xhr, status, error) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });

});


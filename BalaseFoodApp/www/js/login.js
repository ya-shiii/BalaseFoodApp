// Function to set a cookie
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

$(document).ready(function () {
    $('#login-form').on('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the form data
        var formData = {
            username: $('#username').val(),
            password: $('#password').val()
        };

        // Send the AJAX request
        $.ajax({
            type: 'POST',
           url: 'https://balasefoodorderingsystem.muccs.host/php/login', // The URL to your PHP login script
            data: JSON.stringify(formData),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.success) {
                    // Set cookies
                    setCookie('user_id', response.user_id, 7);  // expires in 7 days
                    setCookie('username', response.username, 7);
                    setCookie('role', response.role, 7);
                    setCookie('full_name', response.full_name, 7);

                    // If login is successful, redirect to the desired page
                    window.location.href = response.redirect;
                } else {
                    // If login fails, show the error message
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                // Handle any errors that occurred during the request
                console.error('Error:', error);
                alert('Error logging in. Please try again later.');
            }
        });
    });
});

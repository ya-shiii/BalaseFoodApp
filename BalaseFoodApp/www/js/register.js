$(document).ready(function() {
    $('#register-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const formData = $(this).serializeArray();
        const data = {};

        formData.forEach(item => {
            data[item.name] = item.value;
        });

        $.ajax({
           url: 'https://balasefoodorderingsystem.muccs.host/php/register', // URL of your PHP file
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                alert('Registration successful.');
                window.location.href = 'index.html';
                
            },
            error: function(xhr, status, error) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
});
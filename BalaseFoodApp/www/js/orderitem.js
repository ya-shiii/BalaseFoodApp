$(document).ready(function () {
    $('#orderItemForm').on('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = $(this).serializeArray();
        var data = {};
        $.each(formData, function (index, field) {
            data[field.name] = field.value;
        });

        // Get the user_id and full_name from cookies
        var user_id = getCookie('user_id');
        var full_name = getCookie('full_name');

        // Add user_id and full_name to the data object
        data['user_id'] = user_id;
        data['full_name'] = full_name;

        $.ajax({
           url: 'https://balasefoodorderingsystem.muccs.host/php/order_item',
            type: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function (result) {
                console.log(result); // Log the response for debugging
                alert('Item added to cart successfully.');
                window.location.reload();
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Log the response for debugging
                alert('Error: ' + error);
            }
        });
    });
});

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

$(document).ready(function () {
    $('#orderItemForm').on('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = $(this).serializeArray();
        var data = {};
        $.each(formData, function (index, field) {
            data[field.name] = field.value;
        });

        $.ajax({
            url: 'php/order_item',
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

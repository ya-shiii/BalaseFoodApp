$(document).ready(function () {
    // Function to unset a cookie by setting its expiration date to the past
    function unsetCookie(name) {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }


    function fetchStatistics() {
        $.ajax({
           url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_statistics', // Replace with the actual URL of your PHP file
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Update the counts
                $('#customer-count').text(data.registeredcustomers);
                $('#chefs-count').text(data.chefs);
                $('#orders-count').text(data.orders);
                $('#menu-count').text(data.menu_items);
            },
            error: function (xhr, status, error) {
                console.error("Failed to fetch statistics:", error);
            }
        });
    }

    // Call the fetchStatistics function on page load
    fetchStatistics();
});



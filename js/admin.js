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

    // Logout functionality
    $('#logout-link').click(function (event) {
        event.preventDefault();
        alert('Logged out successfully.')
        window.location.href = 'php/logout.php';
    });

    function fetchStatistics() {
        $.ajax({
            url: 'php/fetch_statistics.php', // Replace with the actual URL of your PHP file
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



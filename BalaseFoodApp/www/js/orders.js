$(document).ready(function () {
    // Function to get status badge class
    function getStatusBadgeClass(status) {
        switch (status) {
            case 'Cart':
                return 'badge-info';
            case 'Pending':
                return 'badge-warning';
            case 'Preparing':
                return 'badge-warning';
            case 'Serving':
                return 'badge-primary';
            case 'Completed':
                return 'badge-success';
            default:
                return 'badge-secondary';
        }
    }

    $(document).ready(function () {
        // Fetch and populate cards on page load
        fetchAndPopulateCards();

        // Event listeners for filter buttons
        $('.category').click(function (event) {
            event.preventDefault();

            // Remove 'active' class from all buttons and add to the clicked one
            $('.btn').removeClass('active');
            $(this).addClass('active');

            // Get the status to filter by
            var status = $(this).text();

            // Fetch and filter the cards based on the status
            fetchAndPopulateCards(status);
        });
    });

    // Function to fetch and populate cards for orders
    function fetchAndPopulateCards(filterStatus = 'All') {
        var user_id = getCookie('user_id'); // Get user_id from cookies

        $.ajax({
            type: 'GET',
           url: 'https://balasefoodorderingsystem.muccs.host/php/fetch_order-list',
            data: { user_id: user_id }, // Pass user_id as data
            dataType: 'json',
            success: function (data) {
                console.log(data); // Debugging
                $('.row').empty(); // Clear previous data

                data.forEach(function (order) {
                    if (filterStatus === 'All' || order.status === filterStatus) {
                        var card = `
                    <div class="col-lg-4 col-md-6 col-sm-10 my-2">
                        <div class="card h-100">
                            <div class="card-header bg-dark text-white">
                                ${order.ordered_time}
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    ${formatItemsList(order.item_names)}
                                </ul>
                                <p>Total Payment: Php ${order.total}</p>
                                <p>Status: <span class="badge ${getStatusBadgeClass(order.status)}">${order.status}</span></p>
                            </div>
                        </div>
                    </div>
                    `;
                        $('.row').append(card);
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching orders:", error);
            }
        });
    }

    // Utility functions for formatting and status badges
    function formatItemsList(items) {
        return items.map(item => `<li>${item}</li>`).join('');
    }

    function getStatusBadgeClass(status) {
        switch (status) {
            case 'Cart': return 'badge-secondary';
            case 'Pending': return 'badge-warning';
            case 'Preparing': return 'badge-info';
            case 'Serving': return 'badge-primary';
            case 'Completed': return 'badge-success';
            default: return 'badge-secondary';
        }
    }



    // Function to format items list
    function formatItemsList(items) {
        if (!items || items.length === 0) {
            return '<li>No items found</li>';
        }
        var itemList = '';
        items.forEach(function (item) {
            itemList += '<li>' + item + '</li>';
        });
        return itemList;
    }


    // Call function to fetch and populate cards for orders
    fetchAndPopulateCards();


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






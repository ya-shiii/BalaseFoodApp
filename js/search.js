$(document).ready(function() {
    $('#searchButton').on('click', function() {
        var searchValue = $('#searchInput').val().toLowerCase();
        searchAndDisplayResults(searchValue);
    });
});

function searchAndDisplayResults(searchValue) {
    $.ajax({
        type: 'GET',
        url: 'php/search_menu',
        data: { query: searchValue },
        dataType: 'json',
        success: function (data) {
            $('#searchResultsContainer').empty(); // Clear previous search results
            if (Array.isArray(data) && data.length > 0) {
                var propClass;
                var imgSize;
                if (data.length === 1) {
                    propClass = 'col-10';
                    imgSize = '400px';
                } else if (data.length === 2) {
                    propClass = 'col-6';
                    imgSize = '300px';
                } else {
                    propClass = 'col-lg-4 col-md-6 col-sm-10';
                    imgSize = '200px';
                }

                data.forEach(function (item) {
                    var card = `
                        <div class="${propClass} my-2 item-card" data-name="${item.name}">
                            <a href="#" class="card h-100 bg-dark text-white w-auto" onclick="orderItem(${item.item_id})">
                                <div class="card-body">
                                    <div class="mb-4 col-12" style="height: ${imgSize}; overflow:hidden">
                                        <img src="${item.img_path}?t=${new Date().getTime()}" alt="${item.name}" class="img-fluid w-full mb-3">
                                    </div>
                                    <h5 class="card-text text-bold">${item.name}</h5>
                                    <p class="card-text">${item.description}</p>
                                    <p class="card-text">Price: Php ${item.price}</p>
                                    <p class="font-italic text-success">${item.category}</p>
                                    Order Now!
                                </div>
                            </a>
                        </div>`;
                    $('#searchResultsContainer').append(card);
                });
                $('#searchResultsModal').modal('show'); // Show the modal with search results
            } else {
                $('#searchResultsContainer').append('<p class="text-center w-100">No results found.</p>');
                $('#searchResultsModal').modal('show'); // Show the modal even if no results
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching search results:", error);
        }
    });
}

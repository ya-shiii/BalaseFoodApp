// Logout functionality
$('#logout-link').click(function (event) {
    event.preventDefault();

    // Unset cookies
    unsetCookie('user_id');
    unsetCookie('username');
    unsetCookie('role');
    unsetCookie('full_name');

    window.location.href = 'https://balasefoodorderingsystem.muccs.host/php/logout';
});

// Function to unset a cookie by setting its expiration date to the past
function unsetCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}
// Function to fetch role from cookies and perform actions based on role
function fetchRoleAndFullName() {
    var role = getCookie('role');
    var fullName = getCookie('full_name');
    var session_id = getCookie('user_id');

    if (role && fullName) {
        console.log('Session ID:', session_id);
        console.log('Role:', role);
        console.log('Full Name:', fullName);

        // Perform actions based on role
        if (role === 'customer') {
            console.log('Access granted for customer.');
            // Proceed with admin-specific actions
        }
    } else {
        // Alert unauthorized access and redirect to unauthorized page if cookies are not set
        alert('You must login as customer.');
        window.location.href = 'index.html';
    }
}
fetchRoleAndFullName() ;

// Function to get a cookie value by name
function getCookie(name) {
    var nameEQ = name + "=";
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1, cookie.length);
        }
        if (cookie.indexOf(nameEQ) == 0) {
            return cookie.substring(nameEQ.length, cookie.length);
        }
    }
    return null;
}

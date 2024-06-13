// Function to fetch role from cookies and perform actions based on role
function fetchRoleAndFullName() {
    var role = getCookie('role');
    var fullName = getCookie('full_name');

    if (role && fullName) {
        console.log('Role:', role);
        console.log('Full Name:', fullName);

        // Perform actions based on role
        if (role === 'admin') {
            console.log('Access granted for admin.');
            // Proceed with admin-specific actions
        }
    } else {
        // Alert unauthorized access and redirect to unauthorized page if cookies are not set
        alert('You must login as admin.');
        window.location.href = 'index.html';
    }
}

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

fetchRoleAndFullName() ;
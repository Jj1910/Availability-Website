window.onload = function() {
    let cookies = document.cookie.split('; ').reduce((acc, cookie) => {
        let [key, value] = cookie.split('=');
        acc[key] = value;
        return acc;
    }, {});

    if (!cookies.user) {
        window.location.href = 'auth.html';
    }
};

document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        let startInput = document.getElementById(this.id + 'Start');
        let endInput = document.getElementById(this.id + 'End');
        startInput.disabled = !this.checked;
        endInput.disabled = !this.checked;
    });
});

document.getElementById('availabilityForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let availability = {};
    let cookies = document.cookie.split('; ').reduce((acc, cookie) => {
        let [key, value] = cookie.split('=');
        acc[key] = value;
        return acc;
    }, {});
    let user = cookies.user || "Unknown User";
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        if (checkbox.checked) {
            let day = checkbox.id;
            let start = document.getElementById(day + 'Start').value;
            let end = document.getElementById(day + 'End').value;
            availability[day] = { start, end };
        }
    });
    console.log("User:", user, "User Availability:", availability);
    //alert("Availability submitted! Check the console for details.");
});

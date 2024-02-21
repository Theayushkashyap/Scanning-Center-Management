// main.js
$(document).ready(function () {
    // Set the logged-in username in the navbar
    document.getElementById('loggedInUsername').innerText = '<?php echo $loggedInUsername; ?>';
});

function changeMonth(month) {
    // Reload the page with the selected month as a query parameter
    window.location.href = '/archive.php?month=' + month;
}

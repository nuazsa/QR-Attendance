document.addEventListener('DOMContentLoaded', function() {
    const dateElement = document.getElementById('current-date');
    const options = {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    };
    const currentDate = new Date().toLocaleDateString('en-ID', options);
    dateElement.textContent = currentDate;
});
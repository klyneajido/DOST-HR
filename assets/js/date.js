// Ensure DOM is fully loaded before running script
document.addEventListener("DOMContentLoaded", function(){
    function formatDate(date){
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const dayName = days[date.getDay()];
        const day = date.getDate();
        const monthName = months[date.getMonth()];
        const year = date.getFullYear();

        return `${dayName}, ${day} ${monthName} ${year}`;
    }

    const today = new Date();
    const formattedDate = formatDate(today);
    document.getElementById("current-date").textContent = formattedDate;
});

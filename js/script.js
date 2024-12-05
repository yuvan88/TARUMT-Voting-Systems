let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.navbar');

menu.onclick = () =>{
    menu.classList.toggle('fa-times');
    navbar.classList.toggle('active');
}

window.onscroll = () =>{
    menu.classList.remove('fa-times');
    navbar.classList.remove('active');
}

const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});


 // Disable past dates in the date input field
 document.addEventListener('DOMContentLoaded', function () {
    var dateInput = document.getElementById('dateInput');
    var today = new Date();

    // Format the date to YYYY-MM-DD
    var dd = today.getDate();
    var mm = today.getMonth() + 1; // Months are zero-based
    var yyyy = today.getFullYear();

    // Add leading zero to day and month if necessary
    if (dd < 10) dd = '0' + dd;
    if (mm < 10) mm = '0' + mm;

    // Today's date in YYYY-MM-DD format
    var todayFormatted = yyyy + '-' + mm + '-' + dd;

    // Set the 'min' attribute of the date input to today's date
    dateInput.setAttribute('min', todayFormatted);
});
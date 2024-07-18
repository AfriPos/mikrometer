// import './bootstrap';

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();


document.addEventListener('DOMContentLoaded', function () {
    var button = document.querySelector('[data-bs-target="#customerTable"]');
    var icon = button.querySelector('.chevron-icon');
    var customerTable = document.getElementById('customerTable');

    customerTable.addEventListener('hidden.bs.collapse', function () {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    });

    customerTable.addEventListener('shown.bs.collapse', function () {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    });
});



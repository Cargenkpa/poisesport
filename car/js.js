const menu = document.querySelector('#mobile-menu');
const menuLinks = document.querySelector('#nav-links');

menu.addEventListener('click', function() {
    // Toggle the menu
    menuLinks.classList.toggle('active');
    
    // Aesthetic Animation for Hamburger
    menu.classList.toggle('is-active');
    
    // Optional: Fade in effect
    if(menuLinks.classList.contains('active')) {
        menuLinks.style.opacity = "0";
        setTimeout(() => {
            menuLinks.style.transition = "opacity 0.5s ease";
            menuLinks.style.opacity = "1";
        }, 10);
    }
});
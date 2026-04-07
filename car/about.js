document.addEventListener('DOMContentLoaded', () => {
    const infoBoxes = document.querySelectorAll('.paragraph-box');

    infoBoxes.forEach(box => {
        // Slide In on Mouse Enter
        box.addEventListener('mouseenter', () => {
            box.classList.add('active');
        });

        // Slide Out on Mouse Leave
        box.addEventListener('mouseleave', () => {
            box.classList.remove('active');
        });
    });
const menuToggle = document.getElementById('mobile-menu');
const navList = document.getElementById('nav-list');

menuToggle.addEventListener('click', () => {
    navList.classList.toggle('active');
});

// Close menu when a link is clicked
document.querySelectorAll('.navbar nav ul li a').forEach(link => {
    link.addEventListener('click', () => {
        navList.classList.remove('active');
    });
});
    // Optional: Trigger them once on scroll for mobile users
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // You can choose to leave them active or keep it hover-only
                // entry.target.classList.add('active'); 
            }
        });
    }, { threshold: 0.5 });

    infoBoxes.forEach(box => observer.observe(box));
});
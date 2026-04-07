document.addEventListener('DOMContentLoaded', () => {
    // Hero Slideshow logic (Fade in/out)
    const slides = document.querySelectorAll('.slide');
    let current = 0;

    setInterval(() => {
        slides[current].classList.remove('active');
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
    }, 4500);

    // Form Handling
    const form = document.getElementById('contactForm');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const btn = form.querySelector('.send-btn');
        btn.innerText = 'SENDING...';
        
        // Mock submission
        setTimeout(() => {
            alert('Message Sent Successfully!');
            btn.innerText = 'SEND MESSAGE';
            form.reset();
        }, 1500);
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
});
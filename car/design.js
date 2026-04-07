document.addEventListener("DOMContentLoaded", () => {
    
    // --- 1. Slideshow Logic ---
    const slides = document.querySelectorAll(".slide");
    let currentSlide = 0;

    function nextSlide() {
        if(slides.length > 0) {
            slides[currentSlide].classList.remove("active");
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add("active");
        }
    }

    // Change slide every 5 seconds
    setInterval(nextSlide, 5000);


    // --- 2. Interactive Scroll Animation (The slide-in effect) ---
    const hiddenElements = document.querySelectorAll(".hidden-element");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                // Add the class that triggers CSS animation
                entry.target.classList.add("show-element");
            }
        });
    }, {
        threshold: 0.15 // Trigger when 15% of the element is visible
    });

    hiddenElements.forEach((el) => observer.observe(el));


    // --- 3. Scroll Effects (Header Background & Hero Fade) ---
    const header = document.querySelector(".navbar");
    const heroSection = document.getElementById("hero-section");

    window.addEventListener("scroll", () => {
        const scrollPos = window.scrollY;

        // Navbar: Add dark background when scrolled past 50px
        if (scrollPos > 50) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }

        // Hero: Fade out the images as you scroll down
        if (heroSection) {
            // Calculate opacity: 0 scroll = 1 opacity. 600 scroll = 0 opacity.
            const opacity = 1 - (scrollPos / 600); 
            
            if (opacity >= 0) {
                heroSection.style.opacity = opacity;
                heroSection.style.visibility = 'visible';
            } else {
                heroSection.style.opacity = 0;
                heroSection.style.visibility = 'hidden'; // Hide cleanly
            }
        }
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
});
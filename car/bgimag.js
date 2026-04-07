document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;
    const slideTime = 4000; // 4 seconds
    const backgroundPosition = 'center 20%';
    function changeSlide() {
        // Remove 'active' from the current slide
        slides[currentSlide].classList.remove('active');

        // Move to the next slide, or back to the first one
        currentSlide = (currentSlide + 1) % slides.length;

        // Add 'active' to the new slide
        slides[currentSlide].classList.add('active');
    }

    // Initialize the interval
    setInterval(changeSlide, slideTime);
});
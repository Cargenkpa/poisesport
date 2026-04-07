document.addEventListener('DOMContentLoaded', () => {
    const logoElement = document.getElementById('animated-logo');
    const text = "POISE";
    
    // Clear the HTML text and replace with spans for each letter
    logoElement.innerHTML = '';
    const letters = [];

    text.split('').forEach(char => {
        const span = document.createElement('span');
        span.innerText = char;
        logoElement.appendChild(span);
        letters.push(span);
    });

    // Configuration for timing (in milliseconds)
    const letterDelay = 400;  // Time between each letter appearing
    const holdTime = 2000;    // Time to wait after word is complete
    const fadeOutTime = 1000; // Time for the fade out

    function startAnimation() {
        // Step 1: Make letters appear one by one
        letters.forEach((letter, index) => {
            setTimeout(() => {
                letter.classList.add('visible');
            }, index * letterDelay);
        });

        // Step 2: Calculate when the word is fully finished
        const animationCompleteTime = (letters.length * letterDelay) + holdTime;

        // Step 3: Remove class to fade out all at once
        setTimeout(() => {
            letters.forEach(letter => {
                letter.classList.remove('visible');
            });

            // Step 4: Restart the loop after fade out is done
            setTimeout(startAnimation, fadeOutTime);
            
        }, animationCompleteTime);
    }
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

    // Start the loop
    startAnimation();
});
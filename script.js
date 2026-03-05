document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            // Animate icon transformation if needed
        });
    }

    // Breaking News Animation Stop/Start on Hover
    const breakingNews = document.querySelector('.breaking-label');
    if (breakingNews) {
        breakingNews.addEventListener('mouseenter', () => {
            breakingNews.style.animationPlayState = 'paused';
        });
        breakingNews.addEventListener('mouseleave', () => {
            breakingNews.style.animationPlayState = 'running';
        });
    }
});

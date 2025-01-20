document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('backToTopButton');

    // Show or hide the button when scrolling
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopButton.classList.add('show');
        } else {
            backToTopButton.classList.remove('show');
        }
    });

    // Scroll to the top when the button is clicked
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 380,
            behavior: 'smooth'
        });
    });
});
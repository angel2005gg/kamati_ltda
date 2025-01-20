document.addEventListener('input', function (event) {
    if (event.target.tagName.toLowerCase() === 'textarea') {
        autoResize(event.target);
    }
});

function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}
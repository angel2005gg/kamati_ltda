(function() {
    resetSelectsToDefault('mi-clase-select');
})();

function resetSelectsToDefault(className) {
    const selects = document.querySelectorAll(`select.${className}`);
    selects.forEach(select => {
        select.selectedIndex = -1;
    });
}


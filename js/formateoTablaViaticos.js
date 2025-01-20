document.addEventListener('DOMContentLoaded', function () {
    const valorInputs = document.querySelectorAll('.valor-input');

    valorInputs.forEach(input => {
        formatInputValue(input); // Formatear al cargar la página

        input.addEventListener('input', function (event) {
            const cursorPosition = input.selectionStart;
            const originalLength = input.value.length;

            // Eliminar cualquier carácter no numérico
            let cleanValue = input.value.replace(/[^0-9]/g, '');
            let formattedValue = '';

            // Añadir puntos de miles
            for (let i = cleanValue.length; i > 0; i -= 3) {
                formattedValue = cleanValue.substring(i - 3, i) + (i !== cleanValue.length ? '.' : '') + formattedValue;
            }

            input.value = formattedValue;

            // Ajustar la posición del cursor
            const newLength = formattedValue.length;
            const newCursorPosition = cursorPosition + (newLength - originalLength);
            input.setSelectionRange(newCursorPosition, newCursorPosition);
        });

        input.addEventListener('focusout', function () {
            formatInputValue(input); // Formatear cuando se pierde el enfoque
        });
    });

    function formatInputValue(input) {
        let cleanValue = input.value.replace(/[^0-9]/g, '');
        let formattedValue = '';

        for (let i = cleanValue.length; i > 0; i -= 3) {
            formattedValue = cleanValue.substring(i - 3, i) + (i !== cleanValue.length ? '.' : '') + formattedValue;
        }

        input.value = formattedValue;
    }
});
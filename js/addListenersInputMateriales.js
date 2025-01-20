export function addInputListener(textarea) {
    textarea.addEventListener('input', function() {
        const value = this.value;
        let number = value.replace(/\D/g, ''); // Eliminar caracteres no numéricos

        // Formatear el número con puntos cada tres dígitos
        let formattedNumber = number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Actualizar el valor del textarea con el número formateado
        this.value = formattedNumber;
        // Guardar el número sin formato para su uso interno
        this.dataset.rawValue = number;
    });
}
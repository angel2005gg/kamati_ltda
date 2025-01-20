export function obtenerValorSinFormato(input) {
    if (!input || !input.value) {
        return '0';
    }
    // Eliminar todos los puntos y caracteres no numéricos excepto comas, puntos y guiones
    const valorSinFormato = input.value.replace(/[^\d,.-]/g, '').replace(/\./g, '').replace(',', '.');
    
    return valorSinFormato;
}
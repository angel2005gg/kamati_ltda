import { calculateCostoKamatiUnitario } from './calculateCostoKamatiUnitarioSx.js';

export function setupToggleButtonCosto(container, tbody) {
    if (container) {
        container.querySelectorAll('.toggle-readonly-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = button.closest('tr'); // Encuentra la fila actual
                const targetClass = button.getAttribute('data-target');
                const input = row.querySelector(`.${targetClass}`); // Busca dentro de la fila actual
                let hiddenInput = null;
                
                // Determina cuál hidden input debe cambiarse dentro de la fila
                if (targetClass === 'costo-alimentacion') {
                    hiddenInput = row.querySelector('.hidden_alimentacion');
                } else if (targetClass === 'costo-transporte') {
                    hiddenInput = row.querySelector('.hidden_transporte');
                }

                if (input && hiddenInput) {
                    if (input.hasAttribute('readonly')) {
                        // Quitar readonly y restaurar el valor original (si lo hay)
                        input.removeAttribute('readonly');
                        hiddenInput.value = '1';
                        
                        // Restaurar valor original si lo hay
                        if (input.dataset.originalValue) {
                            
                            delete input.dataset.originalValue; // Limpia el valor original almacenado
                            // Eliminar los estilos aplicados anteriormente
                            input.style.backgroundColor = '';
                            input.style.color = '';
                            input.style.border = '';
                        }
                    } else {
                        // Guardar el valor actual como el valor original y limpiar el campo
                        if (!input.dataset.originalValue) {
                            input.dataset.originalValue = input.value; // Guarda el valor original
                        }
                        hiddenInput.value = '0';
                        input.setAttribute('readonly', true);
                        input.value = 'No aplica';
                        
                        // Aplicar estilos para "No aplica"
                        input.style.backgroundColor = '#f8d7da'; // Color de fondo
                        input.style.color = '#721c24'; // Color del texto
                        input.style.border = '1px solid #f5c6cb'; // Color del borde
                        
                       // Asegura que el campo sea obligatorio
                    }
                    
                    // Configura el flag para ejecutar calculateViaticos y luego llama a calculateCostoKamatiUnitario
                    calculateCostoKamatiUnitario(tbody, container);
                } else {
                    console.error('No se encontró el input o el hidden input correspondiente:', targetClass);
                }
            });
        });
    } else {
        console.error('El contenedor proporcionado no es válido.');
    }
}
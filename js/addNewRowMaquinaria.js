import { calculateTotalsMaquinaria } from "./calculateTotalsMaquinaria.js";
import { updateCostoKamatiUnitarioMaquinaria } from "./updateCostoKamatiUnitarioMaquinaria.js";
import { showDeleteModalMaquinaria } from "./showDeleteMOdalMaquinaria.js";
import { applyNumberFormatting } from "./applyNumberFormattingMateriales.js";
import { addInputListener } from "./addListenersInputMateriales.js";
import { toggleSelectInputMaquinaria } from "./toggleSelectInputMaquinaria.js";
import { updateCurrencyFormatting } from "./updateCurrencyFormatingMateriales.js";
import { setupCheckboxToggleMaquinaria } from "./toggleInputFactorMaquinaria.js";
export function addNewRowMaquinaria(tbody, baseRow, deleteModal, divContainer) {
    // Clonar la fila base para agregar una nueva

    const nuevaFila = baseRow.cloneNode(true);
    nuevaFila.style.display = "";  // Asegúrate de que la nueva fila esté visible
    nuevaFila.id = "";  // Eliminar el ID para evitar duplicados

    const checkbox = nuevaFila.querySelector('.checkBoxk_newFactorMaquinariaClass');
    const inputNewFactor = nuevaFila.querySelector('.inputNewFactorMaquinariaClas');
    const inputNewFactorHidden = nuevaFila.querySelector('.inputHiddenNewFactorMaquinariaClas');
    if(checkbox && inputNewFactor){
        checkbox.checked = false;
        inputNewFactor.setAttribute('disabled', true);
        inputNewFactor.value = '';
        inputNewFactorHidden.value = 'false';
    }
    // Limpiar valores de los campos de textarea y establecer el tamaño
    nuevaFila.querySelectorAll("textarea").forEach(textarea => {
        textarea.value = ""; // Dejar el textarea vacío
        textarea.rows = 1;   // Establecer la altura del textarea a una línea
        textarea.style.resize = 'none'; // Deshabilitar redimensionamiento
        textarea.style.overflow = 'hidden';
    });
    nuevaFila.querySelectorAll(".span_trm").forEach(span => {
        span.textContent = "$";
    });
    // Limpiar los valores de los selectores en la nueva fila
    nuevaFila.querySelectorAll(".select_reset").forEach(select => select.selectedIndex = -1);
    // Añadir la nueva fila al tbody de la tabla actual
    tbody.appendChild(nuevaFila);
    // Agregar event listener al botón de eliminar fila
    const deleteButton = nuevaFila.querySelector('.delete-btn');
    deleteButton.addEventListener('click', () => showDeleteModalMaquinaria(nuevaFila, deleteModal, divContainer, tbody));
    // Agregar event listeners a los inputs relevantes para recalcular los totales cuando cambien
    nuevaFila.querySelectorAll('.cost-kamati-input, .value-total-input').forEach(input => {
        input.addEventListener('input', calculateTotalsMaquinaria(divContainer, tbody));
    });
    // Asignar el event listener para los campos de precio y descuento
    nuevaFila.querySelectorAll('.cost-kamati-input, .value-total-input, .precio-lista-input, .descuento-input, .descuento-adicional-input, .cantidad-material, .abreviatura-lista, .select_trm_nu, .date_input_entrega, .inputNewFactorMaquinariaClas, .inputHiddenNewFactorMaquinariaClas').forEach(element => {
        if (element.tagName.toLowerCase() === 'select') {
            element.addEventListener('change', () => updateCostoKamatiUnitarioMaquinaria(nuevaFila, divContainer, tbody));
        } else {
            element.addEventListener('input', () => updateCostoKamatiUnitarioMaquinaria(nuevaFila, divContainer, tbody));
        }
    });
    
    nuevaFila.querySelectorAll('.numberInput').forEach(addInputListener);
    // Agregar event listener para la fecha de entrega
    const fechaEntregaInput = nuevaFila.querySelector('#id_fecha_tiempo_entrega');
    const valorTiempoEntregaTextarea = nuevaFila.querySelector('#valor_tiempo_entrega');
    if (fechaEntregaInput && valorTiempoEntregaTextarea) {
        fechaEntregaInput.addEventListener('change', function() {
            const fechaActual = new Date();
            const fechaSeleccionada = new Date(this.value);
            const diferenciaTiempo = fechaSeleccionada - fechaActual;
            const diferenciaDias = Math.floor(diferenciaTiempo / (1000 * 60 * 60 * 24));

            if (diferenciaDias > 7) {
                const semanas = Math.floor(diferenciaDias / 7);
                valorTiempoEntregaTextarea.value = `${semanas} semana(s)`;
            } else {
                valorTiempoEntregaTextarea.value = `${diferenciaDias} día(s)`;
            }
        });
    }

    setupCheckboxToggleMaquinaria(nuevaFila,divContainer, tbody);
        document.querySelectorAll('.toggle-btn').forEach(button => {
            button.addEventListener('click', toggleSelectInputMaquinaria);
        });
        document.querySelectorAll('.option-input-maquinaria').forEach(input => {
            input.addEventListener('input', function() {
                const select = this.previousElementSibling;
                const newValue = this.value;
    
                if (newValue) {
                    let optionExists = false;
                    for (let i = 0; i < select.options.length; i++) {
                        if (select.options[i].value === newValue) {
                            optionExists = true;
                            select.selectedIndex = i;
                            break;
                        }
                    }
                    if (!optionExists) {
                        const newOption = new Option(newValue, newValue, true, true);
                        select.add(newOption);
                        select.selectedIndex = select.options.length - 1;
                    }
                }
            });
        });
        document.querySelectorAll('.select_trm_nu').forEach(select => {
            select.addEventListener('change', function() {
                const newSelectedCurrency = this.value;
                const row = this.closest('tr');
                const currencySymbol = row.querySelector('.span_trm');
                const numberInput = row.querySelector('.numberInput1');
                // Actualizar el símbolo de la moneda
                switch (newSelectedCurrency) {
                    case 'USD':
                        currencySymbol.textContent = 'US$';
                        break;
                    case 'EUR':
                        currencySymbol.textContent = '€';
                        break;
                    default:
                        currencySymbol.textContent = '$';
                        break;
                }
    
                // Aplicar formateo adecuado
                applyNumberFormatting(numberInput, newSelectedCurrency);
            });
        });
        // Asignar formateo de moneda a los selects de la nueva tabla
        nuevaFila.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', () => {
                updateCurrencyFormatting(select);
                calculateTotalsMaquinaria(divContainer, tbody);
            });
        });
        // Asignar formateo de número a los inputs de la nueva tabla
        nuevaFila.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => {
                calculateTotalsMaquinaria(divContainer, tbody);
            });
        });
        // Actualizar el costo Kamati Unitario para cada fila después de clonar y guardar la tabla
        nuevaFila.querySelectorAll('tbody tr').forEach(row => {
            updateCostoKamatiUnitarioMaquinaria(row, divContainer, tbody);
        });

        return nuevaFila;
    }
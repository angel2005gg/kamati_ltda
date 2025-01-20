document.addEventListener('DOMContentLoaded', function() {

    
    const addRowButton = document.querySelector('button[name="button_nueva_fila_materiales"]');
    const saveTableButton = document.querySelector('button[name="button_guardar_tabla_materiales"]');
    const tableBody = document.getElementById('tableBody');
    const baseRow = document.getElementById('baseRow');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {});
    const confirmDeleteButton = document.getElementById('confirmDelete');
    let rowToDelete = null;
    // Obtener el valor del input
    addRowButton.addEventListener("click", function () {
        addNewRow(tableBody);
        calculateTotals();
    });
    saveTableButton.addEventListener('click', saveTable);
    confirmDeleteButton.addEventListener('click', function() {
        if (rowToDelete) {
            rowToDelete.remove();
            rowToDelete = null;
            deleteModal.hide();
            calculateTotals();
        }
    });
    tableBody.querySelectorAll('.select_reset').forEach(select => select.selectedIndex = -1);



    // Asignar event listeners a los botones de eliminación existentes al cargar la página
    assignDeleteEventListeners();
    // Función para formatear los números en los textarea en tiempo real
    function addInputListener(textarea) {
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

function addNewRow(tbody) {

    // Clonar la fila base para agregar una nueva
    const nuevaFila = baseRow.cloneNode(true);
    nuevaFila.style.display = "";  // Asegúrate de que la nueva fila esté visible
    nuevaFila.id = "";  // Eliminar el ID para evitar duplicados

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
    nuevaFila.querySelectorAll('.select_reset').forEach(select => select.selectedIndex = -1);
    // Añadir la nueva fila al tbody de la tabla actual
    tbody.appendChild(nuevaFila);
    // Agregar event listener al botón de eliminar fila
    const deleteButton = nuevaFila.querySelector('.delete-btn');
    deleteButton.addEventListener('click', () => showDeleteModal(nuevaFila));
    // Agregar event listeners a los inputs relevantes para recalcular los totales cuando cambien
    nuevaFila.querySelectorAll('.cost-kamati-input, .value-total-input').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });
    // Asignar el event listener para los campos de precio y descuento
    nuevaFila.querySelectorAll('.cost-kamati-input, .value-total-input, .precio-lista-input, .descuento-input, .descuento-adicional-input, .cantidad-material, .abreviatura-lista, .select_trm_nu, .date_input_entrega').forEach(element => {
        if (element.tagName.toLowerCase() === 'select') {
            element.addEventListener('change', () => updateCostoKamatiUnitario(nuevaFila));
        } else {
            element.addEventListener('input', () => updateCostoKamatiUnitario(nuevaFila));
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
        document.querySelectorAll('.toggle-btn').forEach(button => {
            button.addEventListener('click', toggleSelectInput);
        });
        document.querySelectorAll('.option-input').forEach(input => {
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
                calculateTotals();
            });
        });
        // Asignar formateo de número a los inputs de la nueva tabla
        nuevaFila.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => {
                calculateTotals();
            });
        });
        // Actualizar el costo Kamati Unitario para cada fila después de clonar y guardar la tabla
        nuevaFila.querySelectorAll('tbody tr').forEach(row => {
            updateCostoKamatiUnitario(row);
        });

    }

    function toggleSelectInput(event) {
        const container = event.target.closest('.select-input-container');
        const select = container.querySelector('.option-select');
        const input = container.querySelector('.option-input');
        const isSelectVisible = !select.classList.contains('hidden');
    
        if (isSelectVisible) {
            select.classList.add('hidden');
            input.classList.remove('hidden');
        } else {
            select.classList.remove('hidden');
            input.classList.add('hidden');
        }
    }

    function showDeleteModal(row) {
        rowToDelete = row;
        deleteModal.show();
    }

    
    function calculateTotals() {
        let totalKamati = 0;
        let totalCliente = 0;
    
        tableBody.querySelectorAll('tr').forEach(row => {
            const precioListaInput = row.querySelector('.precio-lista-input');
            const precioListaSinFormato = obtenerValorSinFormato(precioListaInput);
            
            const costKamatiTotal = row.querySelector('.cost-kamati-total');
            const costKamatiTotalSinFormato = obtenerValorSinFormato(costKamatiTotal);
            const valueTotalInput = row.querySelector('.value-total-input');
            const valueTotalInputSinFormato = obtenerValorSinFormato(valueTotalInput);
    
            // Verifica que el campo de precio lista tenga un valor antes de acumular
            if (precioListaSinFormato && parseFloat(precioListaSinFormato)) {
                if (costKamatiTotalSinFormato) {
                    totalKamati += parseFloat(costKamatiTotalSinFormato) || 0;
                }
    
                if (valueTotalInputSinFormato) {
                    totalCliente += parseFloat(valueTotalInputSinFormato) || 0;
                }
            }
        });
    
        document.querySelector('input[name="txt_total_kamati"]').value = `$ ${formatNumberWithDots(toFixedWithComma(totalKamati, 2, ''))}`;
        document.querySelector('input[name="txt_total_cliente"]').value = `$ ${formatNumberWithDots(toFixedWithComma(totalCliente, 2, ''))}`;
    }

    function obtenerValorSinFormato(input) {
        if (!input || !input.value) {
            return '0';
        }
        
        // Eliminar todos los puntos y caracteres no numéricos excepto comas, puntos y guiones
        const valorSinFormato = input.value.replace(/[^\d,.-]/g, '').replace(/\./g, '').replace(',', '.');
        
        return valorSinFormato;
    }

    function formatNumberWithDots(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function toFixedWithComma(num, digits, symbol) {
        num = parseFloat(num).toFixed(digits);
        num = num.replace('.', ',');
        return symbol + num;
    }
    
    
    function applyNumberFormatting(textarea, currency) {
        textarea.addEventListener('input', function() {
            let value = this.value;
            let cursorPosition = this.selectionStart;
            let formattedValue;

            if (currency === 'COP') {
                // Formateo para COP (puntos cada 3 dígitos, sin coma al final)
                let number = value.replace(/[^\d]/g, ''); // Eliminar caracteres no numéricos
                formattedValue = number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            } else {
                // Formateo para USD y EUR (puntos para miles y coma para decimales)
                let [integerPart, decimalPart] = value.split(',');
                integerPart = integerPart ? integerPart.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';
                formattedValue = integerPart;

                if (decimalPart !== undefined) {
                    formattedValue += ',' + decimalPart.replace(/[^\d]/g, ''); // Solo permitir dígitos en la parte decimal
                } else if (value.endsWith(',')) {
                    formattedValue += ','; // Mantener la coma si está al final
                }
            }

            // Actualizar el valor del textarea
            this.value = formattedValue;

            // Restaurar la posición del cursor
            let newCursorPosition = cursorPosition;
            if (value.length > formattedValue.length) {
                newCursorPosition -= (value.length - formattedValue.length);
            } else if (value.length < formattedValue.length) {
                newCursorPosition += (formattedValue.length - value.length);
            }
            this.setSelectionRange(newCursorPosition, newCursorPosition);
        });

        // Aplicar formato inicial al cargar
        let initialValue = textarea.value;
        textarea.value = applyInitialFormatting(initialValue, currency);
    }

    // Función para aplicar el formato inicial
    function applyInitialFormatting(value, currency) {
        if (currency === 'COP') {
            let number = value.replace(/[^\d]/g, '');
            return number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        } else {
            let [integerPart, decimalPart] = value.split(',');
            integerPart = integerPart ? integerPart.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';
            let formattedValue = integerPart;

            if (decimalPart !== undefined) {
                formattedValue += ',' + decimalPart.replace(/[^\d]/g, '');
            }
            return formattedValue;
        }
    }

    function saveTable() {
        // Recoger los valores de los inputs
        const tableName = document.querySelector('input[name="nombre_materiales"]').value;
        const factorMo = parseFloat(document.querySelector('input[name="txt_factor_mo1"]').value.replace(/[^\d,.-]/g, '').replace(',', '.')) || 0;
        const factorO = parseFloat(document.querySelector('input[name="txt_factor_o1"]').value.replace(/[^\d,.-]/g, '').replace(',', '.')) || 0;
        const poliza = parseFloat(document.querySelector('input[name="txt_poliza1"]').value.replace(/[^\d,.-]/g, '').replace(',', '.')) || 0;
        const siemens = parseFloat(document.querySelector('input[name="txt_siemens1"]').value.replace(/[^\d,.-]/g, '').replace(',', '.')) || 0;
        const pilz = parseFloat(document.querySelector('input[name="txt_pilz1"]').value.replace(/[^\d,.-]/g, '').replace(',', '.')) || 0;
        const rittal = parseFloat(document.querySelector('input[name="txt_rittal1"]').value.replace(/[^\d,.-]/g, '').replace(',', '.')) || 0;
        const phoenix = parseFloat(document.querySelector('input[name="txt_phoenix1"]').value.replace(/[^\d,.-]/g, '').replace(',', '.')) || 0;
        const totalKamati = document.querySelector('input[name="txt_total_kamati"]').value;
        const totalCliente = document.querySelector('input[name="txt_total_cliente"]').value;
    
        const contenedorTablaTotal = document.createElement('div');
        const divH2 = document.createElement('div');
        divH2.classList.add('h2_separado');
        const divNombreTabla = document.createElement('div');
        divNombreTabla.classList.add('h2_separado');
    
        const contenedorFlex = document.createElement('div');
        contenedorFlex.style.display = "flex";
        contenedorFlex.setAttribute('name', 'contenedorFlex');
    
        const tableContainer = document.createElement('div');
        tableContainer.classList.add('tabla_solicitudes');
        tableContainer.setAttribute('name', 'tableContainer');
    
        const divContenedor = document.createElement('div');
        const divFlex = document.createElement('div');
        divFlex.classList.add('div_nuevo');
    
        divH2.innerHTML = `<h2>Materiales</h2>`;
        divNombreTabla.innerHTML = `
            <h4>Nombre de la tabla</h4>
            <input type="text" class="form-control" name="nombre_materiales" id="nombre_materiales" style="width: 30%;" value="${tableName}">
        `;
    
        // Crear la nueva tabla y clonar las filas, incluyendo el valor seleccionado en los select
        const table = document.createElement('table');
        table.classList.add('table-responsive');
        const thead = document.createElement('thead');
        thead.innerHTML = document.querySelector('thead').innerHTML;
        const tbody = document.createElement('tbody');
    
        document.querySelector('tbody').querySelectorAll('tr').forEach(row => {
            const newRow = row.cloneNode(true);
            newRow.querySelectorAll('input').forEach((input, index) => {
                input.value = row.querySelectorAll('input')[index].value;
            });
            newRow.querySelectorAll('select').forEach((select, index) => {
                select.value = row.querySelectorAll('select')[index].value;
            });
    
            // Actualizar el costo Kamati Unitario para la nueva fila
            const precioLista = parseFloat(newRow.querySelector('textarea[name="precio_lista"]').value.replace(/[^\d,.-]/g, '').replace(',', '.')) || 0;
            const costoKamatiUnitario = precioLista * factorMo * factorO * poliza + siemens + pilz + rittal + phoenix;
            newRow.querySelector('textarea[name="costo_kamati_unitario"]').value = costoKamatiUnitario.toFixed(2);
    
            const deleteButton = newRow.querySelector('.delete-btn');
            deleteButton.addEventListener('click', () => showDeleteModal(newRow));
            tbody.appendChild(newRow);
        });
    
        table.appendChild(thead);
        table.appendChild(tbody);
    
        tableContainer.appendChild(table);
        contenedorFlex.appendChild(tableContainer);
    
        divContenedor.innerHTML = `
            <div class="stacked-form tm">
                <h5>Factores</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_factor_mox" id="txt_factor_mo1" placeholder="" value="${factorMo}">
                            <label for="segundoApellido">Factor Mo</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_factor_ox" id="txt_factor_o1" placeholder="" value="${factorO}">
                            <label for="numeroIdentificacion">Factor O</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_polizax" id="txt_poliza1" placeholder="" value="${poliza}">
                            <label for="correoElectronico">Póliza</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_siemensx" id="txt_siemens1" placeholder="" value="${siemens}">
                            <label for="correoElectronico">Siemens</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_pilzx" id="txt_pilz1" placeholder="" value="${pilz}">
                            <label for="correoElectronico">Pilz</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_rittalx" id="txt_rittal1" placeholder="" value="${rittal}">
                            <label for="correoElectronico">Rittal</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar" name="txt_phoenixx" id="txt_phoenix1" placeholder="" value="${phoenix}">
                            <label for="correoElectronico">Phoenix Contact</label>
                        </div>
                    </div>
                </div>
            </div>
        `;
    
        divFlex.innerHTML = `
            <div class="separacion">
                <div class="form-floating">
                    <button type="button" name="button_nueva_fila_materiales" class="nueva_fila">Agregar Fila</button>
                </div>
               <div class="separacion">
                    <label for="Total Kamati">Total Kamati</label>
                    <div class="form-floating">
                        <div class="span_div">
                            <input type="text" name="txt_total_kamati" value="${totalKamati}" readonly>
                            <span class="span_cop">COP</span>
                        </div>
                    </div>
                </div>
                <div class="separacion">
                    <label for="Total Cliente">Total Cliente</label>
                    <div class="form-floating">
                        <div class="span_div">
                            <input type="text" name="txt_total_cliente" value="${totalCliente}" readonly>
                            <span class="span_cop">COP</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    
        contenedorFlex.appendChild(divContenedor);
    
        contenedorTablaTotal.appendChild(divH2);
        contenedorTablaTotal.appendChild(divNombreTabla);
        contenedorTablaTotal.appendChild(contenedorFlex);
        contenedorTablaTotal.appendChild(divFlex);
    
        document.body.appendChild(contenedorTablaTotal);
    
        // Asignar event listeners al botón de agregar filas de la nueva tabla
        const newAddRowButton = divFlex.querySelector('button[name="button_nueva_fila_materiales"]');
        newAddRowButton.addEventListener('click', () => addNewRow(tbody));
    
        // Asignar funcionalidad de arrastrar y soltar a la nueva tabla
        new Sortable(tbody, {
            animation: 150,
            ghostClass: 'sortable-ghost'
        });
    
        // Calcular totales después de guardar la tabla
        calculateTotals();
    
        // Asignar formateo de moneda a los selects de la nueva tabla
        tbody.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', () => {
                updateCurrencyFormatting(select);
                calculateTotals();
            });
        });
    
        // Asignar event listeners a los inputs de la nueva tabla
        tbody.querySelectorAll('textarea, input').forEach(input => {
            input.addEventListener('input', () => {
                const row = input.closest('tr');
                updateCostoKamatiUnitarioxx(row);
                calculateTotals();
            });
        });
    
        // Asignar event listeners a los inputs de factores
        const factorInputs = ['txt_factor_mo1', 'txt_factor_o1', 'txt_poliza1', 'txt_siemens1', 'txt_pilz1', 'txt_rittal1', 'txt_phoenix1'];
        factorInputs.forEach(name => {
            document.querySelector(`input[name="${name}"]`).addEventListener('input', () => {
                tbody.querySelectorAll('tr').forEach(row => {
                    updateCostoKamatiUnitarioxx(row);
                    calculateTotals();
                });
            });
        });
    
        // Limpiar los inputs después de guardar
        document.querySelector('input[name="nombre_materiales"]').value = '';
        document.querySelector('input[name="txt_factor_mo1"]').value = '1.4';
        document.querySelector('input[name="txt_factor_o1"]').value = '1.25';
        document.querySelector('input[name="txt_poliza1"]').value = '1.01';
        document.querySelector('input[name="txt_siemens1"]').value = '';
        document.querySelector('input[name="txt_pilz1"]').value = '';
        document.querySelector('input[name="txt_rittal1"]').value = '';
        document.querySelector('input[name="txt_phoenix1"]').value = '';
        document.querySelector('input[name="txt_total_kamati"]').value = '';
        document.querySelector('input[name="txt_total_cliente"]').value = '';
    }


    // Función para actualizar el formato de la moneda en todas las filas
    function updateCurrencyFormatting() {
        document.querySelectorAll('.select_trm_nu').forEach(select => {
            const row = select.closest('tr');
            const currencySymbol = row.querySelector('.span_trm');
            const numberInput = row.querySelector('.numberInput1');

            // Obtener la moneda seleccionada inicialmente
            const selectedCurrency = select.value;

            // Actualizar el símbolo de la moneda
            switch (selectedCurrency) {
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

            // Aplicar formateo adecuado al cargar la página
            applyNumberFormatting(numberInput, selectedCurrency);
        });
    }

    // Llamar a updateCurrencyFormatting al cargar la página
    updateCurrencyFormatting();

    // Añadir event listener para cuando se cambie la moneda
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

    function updateCostoKamatiUnitario(row = null) {
        // Si se proporciona una fila, usa los valores de esa fila
        if (row) {
            
            // Añadir el event listener al input de tipo date
            document.querySelectorAll('#id_fecha_tiempo_entrega').forEach(input => {
                input.addEventListener('change', function() {
                    const fechaActual = new Date();
                    const fechaSeleccionada = new Date(this.value);
    
                    const diferenciaTiempo = fechaSeleccionada - fechaActual;
                    const diferenciaDias = Math.floor(diferenciaTiempo / (1000 * 60 * 60 * 24)) + 1;
    
                    const textarea = this.closest('tr').querySelector('#valor_tiempo_entrega');
    
                    if (diferenciaDias > 6) {
                        const semanas = Math.floor(diferenciaDias / 6);
                        textarea.value = `${semanas} semana(s)`;
                    } else {
                        textarea.value = `${diferenciaDias} día(s)`;
                    }
                });
            });
            
           
            
            const listaAbreviatura = row.querySelector('.abreviatura-lista');
            const precioListaInput = row.querySelector('.precio-lista-input');
            const selectTrm = row.querySelector('.select_trm_nu');
            const costKamatiInput = row.querySelector('.cost-kamati-input');
    
            const costKamatiTotalElement = row.querySelector('.cost-kamati-total');
            const valorUtilidad = row.querySelector('.valor-utilidad');
            const valorUtilidadTotal = row.querySelector('.value-total-input');
            const descuentoInput = row.querySelector('.descuento-input');
            const descuentoAdicionalInput = row.querySelector('.descuento-adicional-input');
            const cantidadMaterial = row.querySelector('.cantidad-material');
            const cantidadMaterialSinFormato = obtenerValorSinFormato(cantidadMaterial);
    
            const poliza = document.querySelector('#txt_poliza');
            const factorO = document.querySelector('#txt_factor_o');
            const factorMO = document.querySelector('#txt_factor_mo');
            const siemens = document.querySelector('#txt_siemens');
            const rittal = document.querySelector('#txt_rittal');
            const pc = document.querySelector('#txt_phoenix');
            const pilz = document.querySelector('#txt_pilz');
    
            const identificacionUsd = document.querySelector('#txt_identificacion_usd');
            const identificacionUsdSinFormato = obtenerValorSinFormato(identificacionUsd);
            
            const identificacionEur = document.querySelector('#txt_identificacion_eur');
            const identificacionEurSinFormato = obtenerValorSinFormato(identificacionEur);
    
            if (precioListaInput && descuentoInput && descuentoAdicionalInput && costKamatiInput && cantidadMaterialSinFormato) {
                let cantidad = parseFloat(cantidadMaterialSinFormato);
                let precioLista = parseFloat(obtenerValorSinFormato(precioListaInput)) || 0;
                let descuento = parseFloat(descuentoInput.value) || 0;
                let descuentoAdicional = parseFloat(descuentoAdicionalInput.value) || 0;
                let moneda = selectTrm ? selectTrm.value : 'COP';
    
                if (moneda === 'USD' && identificacionUsdSinFormato) {
                    let usdValue = parseFloat(identificacionUsdSinFormato) || 1;
                    precioLista *= usdValue;
                } else if (moneda === 'EUR' && identificacionEurSinFormato) {
                    let eurValue = parseFloat(identificacionEurSinFormato) || 1;
                    precioLista *= eurValue;
                }
                

               
                
                
    
                let precioConDescuento = precioLista - (precioLista * (descuento / 100));
    
                if (descuentoAdicional > 0) {
                    precioConDescuento -= (precioConDescuento * (descuentoAdicional / 100));
                }
    
                // Aplicar el formato al valor antes de asignarlo al input
                costKamatiInput.value = `$ ${formatNumberWithDots(toFixedWithComma(precioConDescuento, 2, ''))}`;
    
                let costoKamati = parseFloat(obtenerValorSinFormato(costKamatiInput)) || 0;
    
                if (cantidad > 0) {
                    let valorFinal = costoKamati * cantidad;
    
                    if (costKamatiTotalElement) {
                        costKamatiTotalElement.value = `$ ${formatNumberWithDots(toFixedWithComma(valorFinal, 2, ''))}`;
                    }
                }
    
                let valorUtilidadFinal = 0;
                let factorPoliza = poliza ? parseFloat(poliza.value) || 0 : 0;
                let factorO1 = factorO ? parseFloat(factorO.value) || 0 : 0;
                let factorMO1 = factorMO ? parseFloat(factorMO.value) || 0 : 0;
                let siemens1 = factorMO ? parseFloat(siemens.value) || 0 : 0;
                let rittal1 = factorMO ? parseFloat(rittal.value) || 0 : 0;
                let pilz1 = factorMO ? parseFloat(pilz.value) || 0 : 0;
                let pc1 = factorMO ? parseFloat(pc.value) || 0 : 0;
                let abreviatura = listaAbreviatura ? parseInt(listaAbreviatura.value, 10) : 0;
    
                if (abreviatura === 8) { // O otros
                    valorUtilidadFinal = costoKamati * factorO1 * factorPoliza;
                } else if (abreviatura === 13) { // MO mano de obra electrica
                    valorUtilidadFinal = costoKamati * factorMO1 * factorPoliza;
                } else if (abreviatura === 1) { // AUTO automatización SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 2) { // COM comunicaciones obra electrica SIEMENS 
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 3) { // CE maniobra CE SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 4) { // VAR variadores SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 5) { // SOFT software SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 6) { // REP repuestos SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 7) { // LP maniobra LO SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 10) { // PC PHOENIX CONTACT
                    valorUtilidadFinal = costoKamati * pc1 * factorPoliza;
                } else if (abreviatura === 9) { // PILZ
                    valorUtilidadFinal = costoKamati * pilz1 * factorPoliza;
                } else if (abreviatura === 11) { // R Rittal
                    valorUtilidadFinal = costoKamati * rittal1 * factorPoliza;
                } else if (abreviatura === 14) { // SUP supervisor MO
                    valorUtilidadFinal = costoKamati * factorMO1 * factorPoliza;
                } else if (abreviatura === 17) { // SISO MO
                    valorUtilidadFinal = costoKamati * factorMO1 * factorPoliza;
                } else if (abreviatura === 15) { // ING ingeniero MO
                    valorUtilidadFinal = costoKamati * factorMO1 * factorPoliza;
                } else if (abreviatura === 16) { // PM project manager MO
                    valorUtilidadFinal = costoKamati * factorMO1 * factorPoliza;
                }
    
                if (valorUtilidad) {
                    valorUtilidad.value = `$ ${formatNumberWithDots(toFixedWithComma(valorUtilidadFinal, 2,''))}`;
                }
    
                let valorUtilidadTo = cantidad * valorUtilidadFinal;
                if (valorUtilidadTotal) {
                    valorUtilidadTotal.value = `$ ${formatNumberWithDots(toFixedWithComma(valorUtilidadTo, 2,''))}`;
                }
            } else {
                console.error('Uno o más elementos necesarios no se encontraron en la fila.');
            }
        } else {
            // Si no hay fila específica, recorre todas las filas
            const rows = document.querySelectorAll('table tbody tr');
            rows.forEach(row => updateCostoKamatiUnitario(row));
        }
    
        calculateTotals();
    }
    // Función para asignar event listeners a los botones de eliminación existentes
    function assignDeleteEventListeners() {
        // Asegúrate de que tableBody esté definido
        const tableBody = document.querySelector('table tbody'); // Ajusta el selector según tu estructura de HTML
    
        if (!tableBody) {
            console.error('No se encontró tableBody.');
            return;
        }
    
        // Asignar event listeners a los botones de eliminar
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            const row = button.closest('tr');
            button.addEventListener('click', () => showDeleteModal(row));
        });
        
        const buttonPencil = document.querySelectorAll('.toggle-btn');
            buttonPencil.forEach(button => {
            button.addEventListener('click', toggleSelectInput);
        });
    
        // Asignar event listeners a los inputs relevantes dentro de la tabla
        tableBody.querySelectorAll('.cost-kamati-input, .value-total-input, .precio-lista-input, .descuento-input, .descuento-adicional-input, .cantidad-material, .abreviatura-lista, .select_trm_nu, .date_input_entrega').forEach(input => {
            input.addEventListener('input', () => {
                const row = input.closest('tr');
                updateCostoKamatiUnitario(row);
            });
        });
    
        // Asignar event listeners a los inputs fuera de la tabla
        const names = ['txt_factor_mo1', 'txt_factor_o1', 'txt_poliza1', 'txt_siemens1', 'txt_pilz1', 'txt_rittal1', 'txt_phoenix1'];
        names.forEach(name => {
            const inputs = document.getElementsByName(name);
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    // Llamar a updateCostoKamatiUnitario para cada fila si es necesario actualizar todos los cálculos
                    const rows = document.querySelectorAll('table tbody tr');
                    rows.forEach(row => updateCostoKamatiUnitario(row));
                });
            });
        });
        tableBody.querySelectorAll('.cost-kamati-input, .value-total-input, .precio-lista-input, .descuento-input, .descuento-adicional-input, .cantidad-material, .abreviatura-lista, .select_trm_nu, .date_input_entrega').forEach(input => {
            input.addEventListener('input', () => {
                const row = input.closest('tr');
                updateCostoKamatiUnitarioxx(row);
            });
        });
    
        // Asignar event listeners a los inputs fuera de la tabla
        const namess = ['txt_factor_mox', 'txt_factor_ox', 'txt_polizax', 'txt_siemensx', 'txt_pilzx', 'txt_rittalx', 'txt_phoenixx'];
        namess.forEach(name => {
            const inputs = document.getElementsByName(name);
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    // Llamar a updateCostoKamatiUnitario para cada fila si es necesario actualizar todos los cálculos
                    const rows = document.querySelectorAll('table tbody tr');
                    rows.forEach(row => updateCostoKamatiUnitarioxx(row));
                });
            });
        });
    }

    // Inicializar funcionalidad de arrastrar y soltar en la tabla principal
    new Sortable(tableBody, {
        animation: 150,
        ghostClass: 'sortable-ghost'
    });

    // Calcular los totales al cargar la página
    calculateTotals();




    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    
    function updateCostoKamatiUnitarioxx(row = null) {
        // Si se proporciona una fila, usa los valores de esa fila
        if (row) {
            
            // Añadir el event listener al input de tipo date
            document.querySelectorAll('#id_fecha_tiempo_entrega').forEach(input => {
                input.addEventListener('change', function() {
                    const fechaActual = new Date();
                    const fechaSeleccionada = new Date(this.value);
    
                    const diferenciaTiempo = fechaSeleccionada - fechaActual;
                    const diferenciaDias = Math.floor(diferenciaTiempo / (1000 * 60 * 60 * 24)) + 1;
    
                    const textarea = this.closest('tr').querySelector('#valor_tiempo_entrega');
    
                    if (diferenciaDias > 6) {
                        const semanas = Math.floor(diferenciaDias / 6);
                        textarea.value = `${semanas} semana(s)`;
                    } else {
                        textarea.value = `${diferenciaDias} día(s)`;
                    }
                });
            });
            
           
            
            const listaAbreviatura = row.querySelector('.abreviatura-lista');
            const precioListaInput = row.querySelector('.precio-lista-input');
            const selectTrm = row.querySelector('.select_trm_nu');
            const costKamatiInput = row.querySelector('.cost-kamati-input');
    
            const costKamatiTotalElement = row.querySelector('.cost-kamati-total');
            const valorUtilidad = row.querySelector('.valor-utilidad');
            const valorUtilidadTotal = row.querySelector('.value-total-input');
            const descuentoInput = row.querySelector('.descuento-input');
            const descuentoAdicionalInput = row.querySelector('.descuento-adicional-input');
            const cantidadMaterial = row.querySelector('.cantidad-material');
            const cantidadMaterialSinFormato = obtenerValorSinFormato(cantidadMaterial);
    
            const poliza = document.querySelector('#txt_poliza1');
            const factorO = document.querySelector('#txt_factor_o1');
            const factorMO = document.querySelector('#txt_factor_mo1');
            const siemens = document.querySelector('#txt_siemens1');
            const rittal = document.querySelector('#txt_rittal1');
            const pc = document.querySelector('#txt_phoenix1');
            const pilz = document.querySelector('#txt_pilz1');
    
            const identificacionUsd = document.querySelector('#txt_identificacion_usd');
            const identificacionUsdSinFormato = obtenerValorSinFormato(identificacionUsd);
            
            const identificacionEur = document.querySelector('#txt_identificacion_eur');
            const identificacionEurSinFormato = obtenerValorSinFormato(identificacionEur);
    
            if (precioListaInput && descuentoInput && descuentoAdicionalInput && cantidadMaterialSinFormato) {
                let cantidad = parseFloat(cantidadMaterialSinFormato);
                let precioLista = parseFloat(obtenerValorSinFormato(precioListaInput)) || 0;
                let descuento = parseFloat(descuentoInput.value) || 0;
                let descuentoAdicional = parseFloat(descuentoAdicionalInput.value) || 0;
                let moneda = selectTrm ? selectTrm.value : 'COP';
    
                if (moneda === 'USD' && identificacionUsdSinFormato) {
                    let usdValue = parseFloat(identificacionUsdSinFormato) || 1;
                    precioLista *= usdValue;
                } else if (moneda === 'EUR' && identificacionEurSinFormato) {
                    let eurValue = parseFloat(identificacionEurSinFormato) || 1;
                    precioLista *= eurValue;
                }
                
                let precioConDescuento = precioLista - (precioLista * (descuento / 100));
    
                if (descuentoAdicional > 0) {
                    precioConDescuento -= (precioConDescuento * (descuentoAdicional / 100));
                }
    
                // Aplicar el formato al valor antes de asignarlo al input
                costKamatiInput.value = `$ ${formatNumberWithDots(toFixedWithComma(precioConDescuento, 2, ''))}`;
    
                let costoKamati = parseFloat(obtenerValorSinFormato(costKamatiInput)) || 0;
    
                if (cantidad > 0) {
                    let valorFinal = costoKamati * cantidad;
    
                    if (costKamatiTotalElement) {
                        costKamatiTotalElement.value = `$ ${formatNumberWithDots(toFixedWithComma(valorFinal, 2, ''))}`;
                    }
                }
    
                let valorUtilidadFinal = 0;
                let factorPoliza = poliza ? parseFloat(poliza.value) || 0 : 0;
                let factorO1 = factorO ? parseFloat(factorO.value) || 0 : 0;
                let factorMO1 = factorMO ? parseFloat(factorMO.value) || 0 : 0;
                let siemens1 = factorMO ? parseFloat(siemens.value) || 0 : 0;
                let rittal1 = factorMO ? parseFloat(rittal.value) || 0 : 0;
                let pilz1 = factorMO ? parseFloat(pilz.value) || 0 : 0;
                let pc1 = factorMO ? parseFloat(pc.value) || 0 : 0;
                let abreviatura = listaAbreviatura ? parseInt(listaAbreviatura.value, 10) : 0;
    
                if (abreviatura === 8) { // O otros
                    valorUtilidadFinal = costoKamati * factorO1 * factorPoliza;
                } else if (abreviatura === 13) { // MO mano de obra electrica
                    valorUtilidadFinal = costoKamati * factorMO1 * factorPoliza;
                } else if (abreviatura === 1) { // AUTO automatización SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 2) { // COM comunicaciones obra electrica SIEMENS 
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 3) { // CE maniobra CE SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 4) { // VAR variadores SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 5) { // SOFT software SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 6) { // REP repuestos SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 7) { // LP maniobra LO SIEMENS
                    valorUtilidadFinal = costoKamati * siemens1 * factorPoliza;
                } else if (abreviatura === 10) { // PC PHOENIX CONTACT
                    valorUtilidadFinal = costoKamati * pc1 * factorPoliza;
                } else if (abreviatura === 9) { // PILZ
                    valorUtilidadFinal = costoKamati * pilz1 * factorPoliza;
                } else if (abreviatura === 11) { // R Rittal
                    valorUtilidadFinal = costoKamati * rittal1 * factorPoliza;
                } else if (abreviatura === 14) { // SUP supervisor MO
                    valorUtilidadFinal = costoKamati * factorMO1 * factorPoliza;
                } else if (abreviatura === 17) { // SISO MO
                    valorUtilidadFinal = costoKamati * factorMO1 * factorPoliza;
                } else if (abreviatura === 15) { // ING ingeniero MO
                    valorUtilidadFinal = costoKamati * factorMO1 * factorPoliza;
                } else if (abreviatura === 16) { // PM project manager MO
                    valorUtilidadFinal = costoKamati * factorMO1 * factorPoliza;
                }
    
                if (valorUtilidad) {
                    valorUtilidad.value = `$ ${formatNumberWithDots(toFixedWithComma(valorUtilidadFinal, 2,''))}`;
                }
    
                let valorUtilidadTo = cantidad * valorUtilidadFinal;
                if (valorUtilidadTotal) {
                    valorUtilidadTotal.value = `$ ${formatNumberWithDots(toFixedWithComma(valorUtilidadTo, 2,''))}`;
                }
            } else {
                console.error('Uno o más elementos necesarios no se encontraron en la fila.');
            }
        } else {
            // Si no hay fila específica, recorre todas las filas
            const rows = document.querySelectorAll('table tbody tr');
            rows.forEach(row => updateCostoKamatiUnitarioxx(row));
        }
    
        calculateTotals();
    }



    function addNewRow(tbody) {

        // Clonar la fila base para agregar una nueva
        const nuevaFila = baseRow.cloneNode(true);
        nuevaFila.style.display = "";  // Asegúrate de que la nueva fila esté visible
        nuevaFila.id = "";  // Eliminar el ID para evitar duplicados
    
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
        nuevaFila.querySelectorAll('.select_reset').forEach(select => select.selectedIndex = -1);
        // Añadir la nueva fila al tbody de la tabla actual
        tbody.appendChild(nuevaFila);
        // Agregar event listener al botón de eliminar fila
        const deleteButton = nuevaFila.querySelector('.delete-btn');
        deleteButton.addEventListener('click', () => showDeleteModal(nuevaFila));
        // Agregar event listeners a los inputs relevantes para recalcular los totales cuando cambien
        nuevaFila.querySelectorAll('.cost-kamati-input, .value-total-input').forEach(input => {
            input.addEventListener('input', calculateTotals);
        });
        // Asignar el event listener para los campos de precio y descuento
        nuevaFila.querySelectorAll('.cost-kamati-input, .value-total-input, .precio-lista-input, .descuento-input, .descuento-adicional-input, .cantidad-material, .abreviatura-lista, .select_trm_nu, .date_input_entrega').forEach(element => {
            if (element.tagName.toLowerCase() === 'select') {
                element.addEventListener('change', () => updateCostoKamatiUnitarioxx(nuevaFila));
            } else {
                element.addEventListener('input', () => updateCostoKamatiUnitarioxx(nuevaFila));
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
            document.querySelectorAll('.toggle-btn').forEach(button => {
                button.addEventListener('click', toggleSelectInput);
            });
            document.querySelectorAll('.option-input').forEach(input => {
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
                    calculateTotals();
                });
            });
            // Asignar formateo de número a los inputs de la nueva tabla
            nuevaFila.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', () => {
                    calculateTotals();
                });
            });
            // Actualizar el costo Kamati Unitario para cada fila después de clonar y guardar la tabla
            nuevaFila.querySelectorAll('tbody tr').forEach(row => {
                updateCostoKamatiUnitarioxx(row);
            });
    
        }

});

document.querySelectorAll('.numberInput').forEach(textarea => {
    textarea.addEventListener('input', function() {
        const value = this.value;
        let number = value.replace(/\D/g, ''); // Eliminar caracteres no numéricos

        // Formatear el número con puntos cada tres dígitos
        let formattedNumber = number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Actualizar el valor del textarea con el número formateado
        this.value = formattedNumber;
    });
});

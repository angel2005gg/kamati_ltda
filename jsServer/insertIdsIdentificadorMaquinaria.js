// Importa la función desde el archivo correspondiente
import { checkFactoresIndependientesMaquinaria } from "../js/checkFactoresIndependientesMaquinaria.js";
import { addNewRowMaquinaria } from "../js/addNewRowMaquinaria.js";
import { updateCostoKamatiUnitarioMaquinaria } from "../js/updateCostoKamatiUnitarioMaquinaria.js";
import { setupCheckboxToggleMaquinaria } from "../js/toggleInputFactorMaquinaria.js";
import { updateCurrencyFormatting } from "../js/updateCurrencyFormatingMateriales.js"


// Función para esperar que un elemento esté disponible en el DOM
function waitForElement(selector, timeout = 200) {
    return new Promise((resolve, reject) => {
        const intervalTime = 100; // Tiempo entre cada verificación (en ms)
        const endTime = Date.now() + timeout;

        const checkExist = () => {
            const element = document.querySelector(selector);
            if (element) {
                resolve(element); // Si el elemento está en el DOM, resolvemos la promesa
            } else if (Date.now() > endTime) {
                reject(new Error(`El elemento con el selector "${selector}" no apareció en ${timeout / 1000} segundos.`));
            } else {
                setTimeout(checkExist, intervalTime); // Reintentar cada intervalo
            }
        };

        checkExist(); // Iniciar la verificación
    });
}

function formatCurrency(value) {
    const number = parseFloat(value);
    if (isNaN(number)) return '$ 0'; // Si no es un número válido, devuelve 0
    return '$ ' + number.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
function formatCurrencys(value) {
    const number = parseFloat(value);
    if (isNaN(number)) return '$ 0'; // Si no es un número válido, devuelve 0
    return number.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// Función para insertar los datos del material, trabajando con el ID del contenedor
export async function insertarIdsDatosMaquinaria(ids, baseRow, deleteModal) {
    try {

        console.log(ids);
        if (!ids) {
            console.error('El ID del contenedor no es válido.');
            return;
        }

        const validIdSelector = `id_Maq${ids}`;
        const tablaMaquinaria = document.getElementById(validIdSelector);
        tablaMaquinaria.setAttribute("style", "display: block; margin-bottom: 100px;");

        if (!tablaMaquinaria) {
            console.error(`No se encontró el contenedor con ID: ${ids}`);
            return;
        }

        const insertId = ids; // Usamos el ID proporcionado

        const txtHiddenIdIdentificador = tablaMaquinaria.querySelector('.txt_id_identificador_Maquinaria');
        if (txtHiddenIdIdentificador) {
            txtHiddenIdIdentificador.value = insertId; // Asigna el insertId como valor
            txtHiddenIdIdentificador.id = `txt_id_identificador_Maquinaria_${Date.now()}`;
        }

        const response = await fetch('../phpServer/insertIdsIdentificadorMaquinaria.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids })
        });

        if (response.ok) {
            const data = await response.json(); // Obtenemos los datos en formato JSON

            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    const numeroId = item.id_IdentificadorMaquinaria;
                    const validIdSelector = `#id_Maq${numeroId}`;
                    const contenedores = document.querySelectorAll(validIdSelector);

                    if (contenedores.length === 1) {
                        const contenedor = contenedores[0];
                        const nombreFields = contenedor.querySelectorAll('.nombre_table-maquinariaClass');
                        nombreFields.forEach(field => {
                            field.value = item.nombreTablaMaquinaria || '';
                        });

                        const checkFactoresFields = contenedor.querySelectorAll('.hiddenInputFactoresIndependientesClasMaquinariaClassUnique');
                        checkFactoresFields.forEach(async field => {
                            field.value = item.checkFactoresMaquinaria || '';

                            // Si el valor del input hidden es "1", llamamos la función que maneja todo
                            if (field.value === "1") {
                                console.log('El valor es 1, llamando a la función para activar checkbox');

                                // Esperamos que el contenedor se haya generado dinámicamente
                                const validIdSelector = `#id_Maq${item.id_IdentificadorMaquinaria}`;
                                try {
                                    const contenedor = await waitForElement(validIdSelector, 500); // Esperamos que el contenedor esté en el DO

                                    // Ahora buscamos el tbody y llamamos a la función
                                    const divContainer = contenedor;
                                    const tableBody = contenedor.querySelector('tbody');

                                    // Llamamos a la función que ahora maneja todo
                                    checkFactoresIndependientesMaquinaria(baseRow, divContainer, tableBody);

                                } catch (error) {
                                    console.error('Error al esperar el contenedor:', error);
                                }
                            }
                        });

                        const totalKamatiFields = contenedor.querySelectorAll('.txtTotalKamatiMaquinaria');
                        totalKamatiFields.forEach(field => {
                            field.value = formatCurrency(item.totalKamati);
                        });

                        const totalClienteFields = contenedor.querySelectorAll('.txtTotalClienteMaquinaria');
                        totalClienteFields.forEach(field => {
                            field.value = formatCurrency(item.totalCliente);
                        });




                    } else {
                        console.warn(`Se encontraron múltiples contenedores con el mismo ID: ${validIdSelector}`);
                    }
                });
            } else {
                console.error('No se encontraron datos en la respuesta:', data);
            }
        } else {
            console.error('Error en la respuesta de la API:', response.statusText);
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //CONTINUAMOS CON LA FUNCION DE TRAER LOS FACTORES INDEPENDIENTES Y ADICIONALES DE LA TABLA MAQUINARIA
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        const responses = await fetch('../phpServer/updateFactoresIndependientesMaquinaria.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids })
        });

        if (responses.ok) {
            const data = await responses.json(); // Obtenemos los datos en formato JSON

            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    const numeroId = item.id_IdentificadorMaquinaria_FK;
                    const validIdSelector = `#id_Maq${numeroId}`;
                    const contenedores = document.querySelectorAll(validIdSelector);

                    if (contenedores.length === 1) {
                        const contenedor = contenedores[0];

                        const [factorMo, factorO, factorPo, factorVi] = item.valores_factores;
                        const [idMo, idO, idPo, idVi] = item.ids_factores;

                        // Asignar valores a los campos visibles
                        if (contenedor.querySelector('.factor_MoClassMaquinariaUnique')) {
                            contenedor.querySelector('.factor_MoClassMaquinariaUnique').value = factorMo || '0.00';
                        }
                        if (contenedor.querySelector('.factor_OClassMaquinariaUnique')) {
                            contenedor.querySelector('.factor_OClassMaquinariaUnique').value = factorO || '0.00';
                        }
                        if (contenedor.querySelector('.factor_polizaClassMaquinariaUnique')) {
                            contenedor.querySelector('.factor_polizaClassMaquinariaUnique').value = factorPo || '0.00';
                        }
                        if (contenedor.querySelector('.factor_VClassMaquinariaUnique')) {
                            contenedor.querySelector('.factor_VClassMaquinariaUnique').value = factorVi || '0.00';
                        }

                        // Asignar valores a los campos ocultos
                        const hiddenFactorMoField = contenedor.querySelector('.class_hidden_factorMo_maquinaria');
                        const hiddenFactorOField = contenedor.querySelector('.class_hidden_factorO_maquinaria');
                        const hiddenFactorPoField = contenedor.querySelector('.class_hidden_factorPo_maquinaria');
                        const hiddenFactorViField = contenedor.querySelector('.class_hidden_factorVi_maquinaria');

                        if (hiddenFactorMoField) hiddenFactorMoField.value = idMo || '';
                        if (hiddenFactorOField) hiddenFactorOField.value = idO || '';
                        if (hiddenFactorPoField) hiddenFactorPoField.value = idPo || '';
                        if (hiddenFactorViField) hiddenFactorViField.value = idVi || '';
                    } else {
                        console.warn(`Se encontraron múltiples contenedores con el mismo ID: ${validIdSelector}`);
                    }
                });
            } else {
                console.error('No se encontraron datos en la respuesta:', data);
            }
        } else {
            console.error('Error en la respuesta de la API:', responses.statusText);
        }
        const responsesx = await fetch('../phpServer/updateFactoresAdicionalesMaquinaria.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids })
        });

        if (responsesx.ok) {
            const data = await responsesx.json(); // Obtenemos los datos en formato JSON

            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(item => {
                    const numeroId = item.id_IdentificadorMaquinaria_fk;
                    const validIdSelector = `#id_Maq${numeroId}`;
                    const contenedores = document.querySelectorAll(validIdSelector);

                    if (contenedores.length === 1) {
                        const contenedor = contenedores[0];

                        const [siemensFc, pilzFc, rittalFc, pcFc] = item.valores_factores;
                        const [idSm, iPz, idRt, idPc] = item.ids_factores;

                        // Asignar valores a los campos visibles
                        if (contenedor.querySelector('.factor_siemensClassMaquinariaUnique')) {
                            contenedor.querySelector('.factor_siemensClassMaquinariaUnique').value = siemensFc || '0.00';
                        }
                        if (contenedor.querySelector('.factor_pilzClassMaquinariaUnique')) {
                            contenedor.querySelector('.factor_pilzClassMaquinariaUnique').value = pilzFc || '0.00';
                        }
                        if (contenedor.querySelector('.factor_rittalClassMaquinariaUnique')) {
                            contenedor.querySelector('.factor_rittalClassMaquinariaUnique').value = rittalFc || '0.00';
                        }
                        if (contenedor.querySelector('.factor_phoenixcontactClassMaquinariaUnique')) {
                            contenedor.querySelector('.factor_phoenixcontactClassMaquinariaUnique').value = pcFc || '0.00';
                        }

                        // Asignar valores a los campos ocultos
                        const hiddenFactorSiemensField = contenedor.querySelector('.class_hidden_Factor_Simaquinaria');
                        const hiddenFactorPilzField = contenedor.querySelector('.class_hidden_Factor_Pimaquinaria');
                        const hiddenFactorRittalField = contenedor.querySelector('.class_hidden_Factor_Rimaquinaria');
                        const hiddenFactorPcField = contenedor.querySelector('.class_hidden_Factor_Pcmaquinaria');

                        if (hiddenFactorSiemensField) hiddenFactorSiemensField.value = idSm || '';
                        if (hiddenFactorPilzField) hiddenFactorPilzField.value = iPz || '';
                        if (hiddenFactorRittalField) hiddenFactorRittalField.value = idRt || '';
                        if (hiddenFactorPcField) hiddenFactorPcField.value = idPc || '';
                    } else {
                        console.warn(`Se encontraron múltiples contenedores con el mismo ID: ${validIdSelector}`);
                    }
                });
            } else {
                console.error('No se encontraron datos en la respuesta:', data);
            }
        } else {
            console.error('Error en la respuesta de la API:', responsesx.statusText);
        }

        const columnToDataKeyMap = {
            cantidadMateriales: "cantidades",
            id_identificadorMaquinaria_fk_maq: "id_identificadorMaquinaria_fk_maq",
            id_TablaMateriales: "ids_Tablas",
            unidadesMateriales: "unidades",
            abreviaturaMateriales: "abreviaturas",
            referenciaMateriales: "referencias",
            material: "materiales",
            descripcionMaterial: "descripciones",
            proveedorMateriales: "proveedores",
            estadoButton: "estados_botones",
            notaMateriales: "notas",
            tipoMoneda: "monedas",
            preciolistaMateriales: "precios_lista",
            costoUnitarioKamatiMateriales: "costos_unitarios",
            costoTotalKamatiMateriales: "costos_totales",
            valorUtilidadMateriales: "valores_utilidad",
            valorTotalUtilidadMateriales: "valores_totales_utilidad",
            tiempoEntregaMateriales: "tiempos_entrega",
            descuentoMateriales: "descuentos",
            descuentoAdicional: "descuentos_adicionales",
            fechaTiempoEntregaMateriales: "fechas_entrega",
            revisionMateriales: "revisiones",
            checkEstadoMateriales: "estados_check",
            factorAdicionalMateriales: "factores_adicionales"
        };

        const columnToClassMap = {
            cantidadMateriales: 'materialescantidadTableMaquinaria',
            id_identificadorMaquinaria_fk_maq: 'id_fila_MaquinariaTable_class',
            id_TablaMateriales: 'id_fila_MaquinariaTable_class_clonedUpdate',
            unidadesMateriales: 'select_unidades_maquinaria_table_class',
            abreviaturaMateriales: 'select_abreviatura_maquinaria_class',
            referenciaMateriales: 'textarea_referencia_maquinaria_class',
            material: 'textarea_maquinariaMaterial_class',
            descripcionMaterial: 'textarea_descripcionmaquinaria_class',
            proveedorMateriales: 'select_proveedor_Maquinaria_class',
            estadoButton: 'input_proveedor_Maquinaria_uniqueHidden',
            notaMateriales: 'textarea_nota_Maquinaria_class',
            tipoMoneda: 'selet_trm_Maquinaria_class',
            preciolistaMateriales: 'precio_lista_input_class_Maquinaria',
            costoUnitarioKamatiMateriales: 'cost_kamati_input_class_Maquinaria',
            costoTotalKamatiMateriales: 'cost_kamati_total_class_Maquinaria',
            valorUtilidadMateriales: 'valor_utilidad_class_Maquinaria',
            valorTotalUtilidadMateriales: 'value_total_input_class_Maquinaria',
            tiempoEntregaMateriales: 'valor_tiempo_entrega_class_Maquinaria',
            descuentoMateriales: 'descuento_input_Maquinaria_class',
            descuentoAdicional: 'descuento_adicional_input_Maquinaria_class',
            fechaTiempoEntregaMateriales: 'date_input_entrega_class_Maquinaria',
            revisionMateriales: 'select_rep_classMaquinaria',
            checkEstadoMateriales: 'check_estado_class_Maquinaria',
            factorAdicionalMateriales: 'factor_adicional_class_Maquinaria',
        };

        const assignValuesToTable = (contenedor, baseRow, deleteModal, tbody, data) => {
            const rows = Array.from(tbody.querySelectorAll("tr")); // Trabajar únicamente con las filas del tbody actual

            Object.keys(columnToDataKeyMap).forEach((columnKey) => {
                const dataKey = columnToDataKeyMap[columnKey]; // Clave asociada al dato
                const rawValue = data[dataKey]; // Valor en los datos
                const values = typeof rawValue === "string"
                    ? rawValue.split(",") // Dividir si es cadena
                    : [String(rawValue || "")]; // Convertir a string si no lo es

                values.forEach((value, idx) => {
                    let currentRow;

                    if (idx < rows.length) {
                        // Usar filas existentes
                        currentRow = rows[idx];
                        updateCurrencyFormatting();
                    } else {
                        // Crear nueva fila si no hay suficientes
                        addNewRowMaquinaria(tbody, baseRow, deleteModal, contenedor);
                        updateCurrencyFormatting();
                        currentRow = tbody.querySelector("tbody tr:last-child"); // Obtener última fila agregada dentro del tbody actual
                        rows.push(currentRow); // Agregar a la lista de filas
                    }

                    // Buscar la clase asociada
                    const className = columnToClassMap[columnKey];
                    if (className) {
                        const selector = `.${className.split(" ").join(".")}`; // Asegurar selector CSS
                        const field = currentRow.querySelector(selector);

                        if (field) {
                            if (field.type === "date") {
                                // Manejo de campos de tipo fecha
                                const dateValue = new Date(value.trim());
                                if (!isNaN(dateValue.getTime())) {
                                    const formattedDate = dateValue.toISOString().split("T")[0];
                                    field.value = formattedDate;
                                }
                            } else {
                                // Asignar valores normales
                                field.value = value.trim();
                                if (field.tagName === "SELECT") {
                                    field.value = value.trim(); // Manejo de selects
                                }

                                // Formatear valores si es el campo de precio lista
                                if (className.includes("precio_lista_input_class_Maquinaria")) {
                                    field.value = formatCurrencys(value.trim());
                                }

                                if (className.includes("input_proveedor_Maquinaria_uniqueHidden") && field.value === "1") {
                                    // Asignar el valor de proveedorMateriales al input correspondiente
                                    const container = field.closest('.select-input-container_maquinaria');
                                    const select = container.querySelector('.select_proveedor_Maquinaria_class');
                                    const input = container.querySelector('.input_proveedor_Maquinaria_class');
                                    const isSelectVisible = !select.classList.contains('hidden');
                                    // Extraer el valor específico para esta fila usando el índice
                                    const proveedores = (data.proveedores || "").split(","); // Dividir valores separados por comas
                                    const proveedor = proveedores[idx]?.trim() || ""; // Tomar el valor correspondiente al índice actual

                                    if (isSelectVisible) {
                                        select.classList.add('hidden');
                                        input.classList.remove('hidden');
                                        input.value = proveedor;

                                    } else {
                                        console.warn('No se encontraron los elementos select/input en el contenedor.');
                                    }

                                }

                                if (className.includes('check_estado_class_Maquinaria')) {
                                    // Encontramos el campo hidden correspondiente dentro de la fila
                                    const hiddenField = currentRow.querySelector('.check_estado_class_Maquinaria');

                                    // Verificamos si el valor de ese campo es "1"
                                    if (hiddenField && hiddenField.value === "1") {
                                        // Ejecutar setupCheckboxToggleMaquinaria solo cuando el valor sea "1"
                                        setupCheckboxToggleMaquinaria(currentRow, contenedor, tbody);
                                    }
                                }
                                if (className.includes('id_fila_MaquinariaTable_class')) {
                                    // Encontramos el campo hidden correspondiente dentro de la fila
                                    const hiddenField = currentRow.querySelector('.id_fila_MaquinariaTable_class');

                                    // Verificamos si el valor de ese campo es "1"
                                    if (hiddenField && hiddenField.value !== null) {
                                        // Ejecutar setupCheckboxToggleMaquinaria solo cuando el valor sea "1"
                                        const idcontenedor = contenedor.id;
                                        hiddenField.value = idcontenedor.replace('id_Maq', ''); // Removemos la parte 'id_'
                                    }
                                }

                            }
                        }
                    } else {
                        console.warn(`No se encontró clase para columna: ${columnKey}`);
                    }

                    // Ejecutar la función `updateCostoKamatiUnitario` para la primera fila
                    if (idx === 0) {
                        updateCostoKamatiUnitarioMaquinaria(currentRow, contenedor, tbody);
                        // Ejecuta la función para la primera fila
                    }

                    // Ejecutar la función `updateCostoKamatiUnitarioMaquinaria` para las filas nuevas
                    if (idx >= rows.length - values.length) {
                        updateCostoKamatiUnitarioMaquinaria(currentRow, contenedor, tbody);
                        // Ejecuta la función para cada fila nueva
                    }
                });
            });
        };
        // Lógica principal para procesar datos y asignarlos
        try {
            const responsess = await fetch('../phpServer/obtenerDatosTablaMaquinaria.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ ids }), // IDs enviados al servidor
            });

            if (responsess.ok) {
                const data = await responsess.json();

                if (data && typeof data === 'object' && Object.keys(data).length > 0) {
                    // Agrupar datos por ID
                    const groupedData = Object.values(data).reduce((groups, item) => {
                        const id = item.id_identificadorMaquinaria_fk_maq;
                        if (!groups[id]) groups[id] = [];
                        groups[id].push(item);
                        return groups;
                    }, {});

                    // Procesar cada grupo de datos
                    Object.entries(groupedData).forEach(([id, items]) => {
                        // Selector para el contenedor basado en el ID
                        const tableSelector = `#id_Maq${id}`; // Ajusta el prefijo según tu HTML
                        const contenedor = document.querySelector(tableSelector);

                        if (!contenedor) {
                            console.error(`No se encontró una tabla para ID ${id}. Saltando...`);
                            return;
                        }


                        // Obtener elementos base de la tabla
                        const baseRow = contenedor.querySelector('tbody tr:first-child');
                        const tbody = contenedor.querySelector('.tbody_maquinariaClas');

                        // Procesar elementos del grupo y asignarlos solo a su tabla
                        items.forEach((item) => {
                            assignValuesToTable(contenedor, baseRow, deleteModal, tbody, item);

                        });
                    });
                } else {
                    console.error('No se encontraron datos válidos en la respuesta:', data);
                }
            } else {
                console.error(`Error en la respuesta del servidor: ${responsess.status} ${responsess.statusText}`);
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }

        return insertId;
    } catch (error) {
        console.error('Error en la función insertarIdsDatosMateriales:', error);
    }
}
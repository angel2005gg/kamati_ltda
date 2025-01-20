// Importa la función desde el archivo correspondiente
import { checkFactoresIndependent } from "../js/checkFactoresIndependientes.js";
import { addTurnRow, addNormalRow } from '../js/addRowsAzxs.js';
import { calculateCostoKamatiUnitario } from '../js/calculateCostoKamatiUnitarioSx.js';
import { initializeTable } from '../js/calculateSumColumns.js';
import { setupCheckboxToggle } from '../js/toggleInputFactor.js';



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

// Función para insertar los datos del material, trabajando con el ID del contenedor
export async function insertarIdsDatosActividades(ids, baseRow, deleteModal, deleteModalTurno) {
    try {

        console.log(ids);
        if (!ids) {
            console.error('El ID del contenedor no es válido.');
            return;
        }

        const validIdSelector = `id_Ac${ids}`;
        const tablaMaquinaria = document.getElementById(validIdSelector);
        tablaMaquinaria.setAttribute("style", "display: block; margin-bottom: 100px;");

        if (!tablaMaquinaria) {
            console.error(`No se encontró el contenedor con ID: ${ids}`);
            return;
        }

        const insertId = ids; // Usamos el ID proporcionado

        const txtHiddenIdIdentificador = tablaMaquinaria.querySelector('.txt_id_identificador_Actividades');
        if (txtHiddenIdIdentificador) {
            txtHiddenIdIdentificador.value = insertId; // Asigna el insertId como valor
            txtHiddenIdIdentificador.id = `txt_id_identificador_Actividades_${Date.now()}`;
        }

        const response = await fetch('../phpServer/insertIdsIdentificadorAvtividades.php', {
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
                    const numeroId = item.id_IdentificadorActividades;
                    const validIdSelector = `#id_Ac${numeroId}`;
                    const contenedores = document.querySelectorAll(validIdSelector);

                    if (contenedores.length === 1) {
                        const contenedor = contenedores[0];
                        const nombreFields = contenedor.querySelectorAll('.nombre_table-actividadesClass');
                        nombreFields.forEach(field => {
                            field.value = item.nombreTablaActividades || '';
                        });

                        const checkFactoresFields = contenedor.querySelectorAll('.hiddenTableInput_actividades_unqueVal');
                        checkFactoresFields.forEach(async field => {
                            field.value = item.checkFactoresIndependientes || '';

                            // Si el valor del input hidden es "1", llamamos la función que maneja todo
                            if (field.value === "1") {
                                console.log('El valor es 1, llamando a la función para activar checkbox');

                                // Esperamos que el contenedor se haya generado dinámicamente
                                const validIdSelector = `#id_Ac${item.id_IdentificadorActividades}`;
                                try {
                                    const contenedor = await waitForElement(validIdSelector, 500); // Esperamos que el contenedor esté en el DO

                                    // Ahora buscamos el tbody y llamamos a la función
                                    const divContainer = contenedor;
                                    const tableBody = contenedor.querySelector('tbody');

                                    // Llamamos a la función que ahora maneja todo
                                    checkFactoresIndependent(baseRow, divContainer, tableBody);

                                } catch (error) {
                                    console.error('Error al esperar el contenedor:', error);
                                }
                            }
                        });

                        const totalKamatiFields = contenedor.querySelectorAll('.txt_total_kamatiActividadesClass');
                        totalKamatiFields.forEach(field => {
                            field.value = formatCurrency(item.totalKamatiActividades);
                        });

                        const totalClienteFields = contenedor.querySelectorAll('.txt_total_clienteActividadesClass');
                        totalClienteFields.forEach(field => {
                            field.value = formatCurrency(item.totalClienteActividades);
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

        // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // //CONTINUAMOS CON LA FUNCION DE TRAER LOS FACTORES INDEPENDIENTES Y ADICIONALES DE LA TABLA MAQUINARIA
        // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        const responses = await fetch('../phpServer/updateFactoresIndependientesActividades.php', {
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
                    const numeroId = item.id_IdentificadorActividades_fk;
                    const validIdSelector = `#id_Ac${numeroId}`;
                    const contenedores = document.querySelectorAll(validIdSelector);

                    if (contenedores.length === 1) {
                        const contenedor = contenedores[0];

                        const [factorMo, factorO, factorVi, factorPo] = item.valores_factores;
                        const [idMo, idO, idVi, idPo] = item.ids_factores;

                        // Asignar valores a los campos visibles
                        if (contenedor.querySelector('.factorMoHiddenClass')) {
                            contenedor.querySelector('.factorMoHiddenClass').value = factorMo || '0.00';
                        }
                        if (contenedor.querySelector('.factorOHiddenClass')) {
                            contenedor.querySelector('.factorOHiddenClass').value = factorO || '0.00';
                        }
                        if (contenedor.querySelector('.viaticosHiddenClass')) {
                            contenedor.querySelector('.viaticosHiddenClass').value = factorVi || '0.00';
                        }
                        if (contenedor.querySelector('.polizaHiddenClaas')) {
                            contenedor.querySelector('.polizaHiddenClaas').value = factorPo || '0.00';
                        }

                        // Asignar valores a los campos ocultos
                        const hiddenFactorMoField = contenedor.querySelector('.class_hidden_factorMo_ActividadesUnique');
                        const hiddenFactorOField = contenedor.querySelector('.class_hidden_factorO_ActividadesUnique');
                        const hiddenFactorViField = contenedor.querySelector('.class_hidden_factorVi_ActividadesUnique');
                        const hiddenFactorPoField = contenedor.querySelector('.class_hidden_factorPo_ActividadesUnique');

                        if (hiddenFactorMoField) hiddenFactorMoField.value = idMo || '';
                        if (hiddenFactorOField) hiddenFactorOField.value = idO || '';
                        if (hiddenFactorViField) hiddenFactorViField.value = idVi || '';
                        if (hiddenFactorPoField) hiddenFactorPoField.value = idPo || '';
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



        // Hacer la solicitud al servidor
        const responsesViaticos = await fetch('../phpServer/updateSelectViaticosActividades.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids }), // Envía los IDs que necesitas al servidor
        });

        if (responsesViaticos.ok) {
            const data = await responsesViaticos.json(); // Obtenemos los datos en formato JSON
            console.log('Datos recibidos:', data); // Verifica la respuesta completa

            if (data && Array.isArray(data.data) && data.data.length > 0) {
                let count = 0;  // Variable para contar las iteraciones

                data.data.forEach((item, index) => {
                    // Reiniciar el índice cuando llegue a 2
                    index = count % 2;

                    const idIdentificador = item.id_IdentificadorActividades_fk2; // Verifica el nombre de las propiedades
                    // Seleccionar el contenedor correspondiente
                    const validIdSelector = `#id_Ac${idIdentificador}`;
                    const contenedores = document.querySelectorAll(validIdSelector);

                    if (contenedores.length === 1) {
                        const contenedor = contenedores[0];

                        // Buscar la fila usando el índice
                        const hiddenInputIdViaticos = contenedor.querySelector(`.hidden_inputId_viaticos_unique_${index}`);
                        if (hiddenInputIdViaticos) {
                            const filaViatico = hiddenInputIdViaticos.closest('tr');

                            if (filaViatico) {
                                const idViatico = item.id_ViaticosActividadesIndependiente;
                                const valorViatico = item.valorViaticoActividadesIndependiente;

                                // Actualiza el campo hidden_inputId_viaticos_unique
                                hiddenInputIdViaticos.value = idViatico || '';

                                // Actualiza el campo hidden_identificador_tabla_unique
                                const hiddenIdentificadorTabla = filaViatico.querySelector(`.hidden_identificador_tabla_unique_${index}`);
                                if (hiddenIdentificadorTabla) {
                                    hiddenIdentificadorTabla.value = idIdentificador || '';
                                }

                                // Actualiza el campo valorActividadesViaticosUnique
                                const valorInput = filaViatico.querySelector('.valorActividadesViaticosUnique');
                                if (valorInput) {
                                    valorInput.value = Number(valorViatico).toLocaleString('es-CO');
                                }
                            } else {
                                console.warn(`No se encontró la fila correspondiente para el índice: ${index}`);
                            }
                        } else {
                            console.warn(`No se encontró el input con clase .hidden_inputId_viaticos_unique_${index}`);
                        }
                    } else if (contenedores.length > 1) {
                        console.warn(`Se encontraron múltiples contenedores con el mismo ID: ${validIdSelector}`);
                    } else {
                        console.warn(`No se encontró ningún contenedor con el ID: ${validIdSelector}`);
                    }

                    count++;  // Incrementar el contador para la siguiente iteración
                });
            } else {
                console.error('No se encontraron datos en la respuesta:', data);
            }
        } else {
            console.error('Error en la respuesta de la API:', responsesViaticos.statusText);
        }
        // Hacer la solicitud al servidor
        const responsesCargos = await fetch('../phpServer/updateSelectCargosActividades.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ids }), // Envía los IDs que necesitas al servidor
        });

        if (responsesCargos.ok) {
            const data = await responsesCargos.json(); // Obtenemos los datos en formato JSON
            console.log('Datos recibidos:', data); // Verifica la respuesta completa

            if (data && Array.isArray(data.data) && data.data.length > 0) {
                let count = 0;  // Variable para contar las iteraciones

                data.data.forEach((item, index) => {
                    // Reiniciar el índice cuando llegue a 2
                    index = count % 10;

                    const idIdentificador = item.id_IdentificadorActividades_fk3; // Verifica el nombre de las propiedades
                    // Seleccionar el contenedor correspondiente
                    const validIdSelector = `#id_Ac${idIdentificador}`;
                    const contenedores = document.querySelectorAll(validIdSelector);

                    if (contenedores.length === 1) {
                        const contenedor = contenedores[0];

                        // Buscar la fila usando el índice
                        const hiddenInputIdViaticos = contenedor.querySelector(`.hidden_input_unique_tarifaCargoId_${index}`);
                        if (hiddenInputIdViaticos) {
                            const filaViatico = hiddenInputIdViaticos.closest('tr');

                            if (filaViatico) {
                                const idViatico = item.id_CargosIndependientesActividades;
                                const valorViatico = item.valorCargoActividadesIndependiente;

                                // Actualiza el campo hidden_inputId_viaticos_unique
                                hiddenInputIdViaticos.value = idViatico || '';

                                // Actualiza el campo hidden_identificador_tabla_unique
                                const hiddenIdentificadorTabla = filaViatico.querySelector(`.hidden_input_unique_tarifaCargo_${index}`);
                                if (hiddenIdentificadorTabla) {
                                    hiddenIdentificadorTabla.value = idIdentificador || '';
                                }

                                // Actualiza el campo valorActividadesViaticosUnique
                                const valorInput = filaViatico.querySelector('.valorTarigaCargoUniqueClass');
                                if (valorInput) {
                                    valorInput.value = Number(valorViatico).toLocaleString('es-CO');
                                }
                            } else {
                                console.warn(`No se encontró la fila correspondiente para el índice: ${index}`);
                            }
                        } else {
                            console.warn(`No se encontró el input con clase .hidden_inputId_viaticos_unique_${index}`);
                        }
                    } else if (contenedores.length > 1) {
                        console.warn(`Se encontraron múltiples contenedores con el mismo ID: ${validIdSelector}`);
                    } else {
                        console.warn(`No se encontró ningún contenedor con el ID: ${validIdSelector}`);
                    }

                    count++;  // Incrementar el contador para la siguiente iteración
                });
            } else {
                console.error('No se encontraron datos en la respuesta:', data);
            }
        } else {
            console.error('Error en la respuesta de la API:', responsesCargos.statusText);
        }



        try {
            // Obtener datos de turnos
            const responseTurnos = await fetch('../phpServer/updateSelectTurnosActividades.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({}),
            });

            if (!responseTurnos.ok) {
                throw new Error(`Error al obtener turnos: ${responseTurnos.statusText}`);
            }

            const turnosData = await responseTurnos.json();
            const turnosAgrupados = agruparPor(turnosData.data, 'id_identificadorActividades_fk');
            console.log('Turnos Agrupados:', turnosAgrupados);

            // Obtener datos de actividades
            const responseActividades = await fetch('../phpServer/obtenerFilasActividades.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({}),
            });

            if (!responseActividades.ok) {
                throw new Error(`Error al obtener actividades: ${responseActividades.statusText}`);
            }

            const actividadesData = await responseActividades.json();
            const actividadesAgrupadas = agruparPor(actividadesData, 'id_TurnoActividaes_fk');
            console.log('Actividades Agrupadas:', actividadesAgrupadas);

            // Procesar turnos y asignar actividades
            Object.entries(turnosAgrupados).forEach(([idIdentificador, turnos]) => {
                const contenedor = document.querySelector(`#id_Ac${idIdentificador}`);
                if (!contenedor) {
                    console.error(`Contenedor no encontrado para ID: ${idIdentificador}`);
                    return;
                }

                const tbody = contenedor.querySelector('.tbodyActividades_Clas');
                const table = contenedor.querySelector('#miTablaMovible1');
                if (!tbody) {
                    console.error(`Tbody no encontrado en contenedor con ID: ${idIdentificador}`);
                    return;
                }

                // Eliminar filas de actividades sin atributo data-turno-id
                const filasSinTurno = Array.from(tbody.querySelectorAll('.filaclonableunica_actividades_Class:not([data-turno-id])'));
                filasSinTurno.forEach((fila) => fila.remove());

                // Eliminar filas de turnos sin atributo data-turno-id
                const filasSinDataTurnoId = Array.from(tbody.querySelectorAll('.tr_new_tbody_turnounique_Class:not([data-turno-id])'));
                filasSinDataTurnoId.forEach((fila) => fila.remove());

                // Procesar cada turno y sus actividades asociadas
                turnos.forEach((turno) => {
                    // Agregar fila del turno si no existe
                    const existingTurnoRow = Array.from(tbody.querySelectorAll(`.tr_new_tbody_turnounique_Class[data-turno-id="${turno.id_TurnoActividades}"]`))[0];
                    let turnoRow;

                    if (!existingTurnoRow) {
                        turnoRow = addTurnRow(tbody, deleteModalTurno, contenedor);
                        turnoRow.setAttribute('data-turno-id', turno.id_TurnoActividades);
                    } else {
                        turnoRow = existingTurnoRow;
                    }

                    asignarDatosTurno(turnoRow, turno);

                    // Obtener las actividades asociadas
                    const actividades = actividadesAgrupadas[turno.id_TurnoActividades] || [];
                    actividades.sort((a, b) => a.id_Actividad - b.id_Actividad); // Ordenar actividades

                    // Filas de actividades existentes
                    const filasExistentesActividades = Array.from(
                        tbody.querySelectorAll(`.filaclonableunica_actividades_Class[data-turno-id="${turno.id_TurnoActividades}"]`)
                    );

                    const filasFaltantes = actividades.length - filasExistentesActividades.length;

                    // Clonar filas faltantes
                    for (let i = 0; i < filasFaltantes; i++) {
                        const nuevaFila = addNormalRow(baseRow, tbody, deleteModal, contenedor);
                        nuevaFila.setAttribute('data-turno-id', turno.id_TurnoActividades);
                    }

                    // Asignar actividades a las filas correspondientes debajo del turno
                    const filasActualizadasActividades = Array.from(
                        tbody.querySelectorAll(`.filaclonableunica_actividades_Class[data-turno-id="${turno.id_TurnoActividades}"]`)
                    );

                    actividades.forEach((actividad, index) => {
                        const filaActividad = filasActualizadasActividades[index];
                        if (filaActividad) {
                            asignarDatosActividad(filaActividad, actividad, contenedor);
                        }
                    });
                });
                calculateCostoKamatiUnitario(tbody, contenedor);
                initializeTable(table, contenedor);

            });
        } catch (error) {
            console.error('Error al procesar turnos y actividades:', error);
        }

        // Función para agrupar datos por una clave específica
        function agruparPor(array, clave) {
            return array.reduce((acc, item) => {
                const key = item[clave];
                if (!key) return acc;
                if (!acc[key]) acc[key] = [];
                acc[key].push(item);
                return acc;
            }, {});
        }

        // Asignar datos a una fila de turno
        function asignarDatosTurno(fila, turno) {
            if (!fila || !turno) return;
            fila.querySelector('.starTimeClassActividades').value = turno.horaInicioTurno || '';
            fila.querySelector('.endTimeClassActividades').value = turno.horaFinTurno || '';
            fila.querySelector('.tipoDia-classActividades').value = turno.tipoTurno || '';
            fila.querySelector('.hidden_idIdentificadorActividadeUnique_CLASS').value = turno.id_identificadorActividades_fk || '';
            fila.querySelector('.hidden_idId_turno_ActividadeUnique_CLASS').value = turno.id_TurnoActividades || '';
        }

        // Asignar datos a una fila de actividad
        function asignarDatosActividad(fila, actividad, contenedor) {
            if (!fila || !actividad) return;

            fila.querySelector('.class_hidden_identificador_uniqueAc').value = actividad.id_TurnoActividaes_fk || '';
            fila.querySelector('.class_hidden_id_uniqueAc').value = actividad.id_TablaActividades || '';
            fila.querySelector('.cantidad_actividades_unique').value = actividad.cantidad || '';
            fila.querySelector('.selectUnidadesActividadesClass').value = actividad.unidad || '';
            fila.querySelector('.abreviaturas_nomClass').value = actividad.abreviaturaLinea || '';
            fila.querySelector('.descripcion_breve_classUnique').value = actividad.descripcionBreve || '';
            fila.querySelector('.cantidad_persona_class_unique').value = actividad.cantidadPersonas || '';
            fila.querySelector('.nota_actividades_unique_class').value = actividad.nota || '';
            fila.querySelector('.costo-externio-unitario-input').value = actividad.costoExternoUnitario || '';
            fila.querySelector('.costoAlimentacion_input_actividades_unique_class').value = actividad.costoAlimentacion || '';
            fila.querySelector('.class_transporteInput_unique').value = actividad.costoTransporte || '';
            fila.querySelector('.valor_Dia_kamati-class').value = actividad.costoDiaKamati || '';
            fila.querySelector('.valorDiasKamatiClass').value = actividad.costoTotalDiasKamati || '';
            fila.querySelector('.valor-dia-utilidadClass').value = actividad.valorDiaUtilidad || '';
            fila.querySelector('.valorDiasClienteUtilidadClass').value = actividad.valorTotalUtilidad || '';
            fila.querySelector('.select_resp_unique_actividades').value = actividad.rep || '';
            fila.querySelector('.input_hidden_check_unique_class_ac').value = actividad.checkActividades;
            const tbody = contenedor.querySelector('.tbodyActividades_Clas');
            const inputHiddenAc = fila.querySelector('.input_hidden_check_unique_class_ac').value;
            if (inputHiddenAc) {
                setupCheckboxToggle(fila, contenedor, tbody);
            }

            fila.querySelector('.input-new-factor-Actividades-class').value = actividad.factorAdicional || '0';
            fila.querySelector('.inputValor-optionActividadesClass').value = actividad.estadoButtonPersonal || '';
            fila.querySelector('.costoAlimentacion_hidden_uniqueclass_estadoButton').value =
                actividad.estadoButtonAlimentacion === 0 ? '0' : (actividad.estadoButtonAlimentacion ?? '');

            fila.querySelector('.class_transporteHidden_unique').value =
                actividad.estadoButtonTransporte === 0 ? '0' : (actividad.estadoButtonTransporte ?? '');

            const alimetacion = fila.querySelector('.costoAlimentacion_hidden_uniqueclass_estadoButton').value;
            const transporte = fila.querySelector('.class_transporteHidden_unique').value;
            const alimentacion1 = fila.querySelector('.costo-alimentacion');
            const transporte1 = fila.querySelector('.costo-transporte');

            if (alimetacion === '0') {
                if (!alimentacion1.dataset.originalValue) {
                    alimentacion1.dataset.originalValue = alimentacion1.value; // Guarda el valor original
                }
                alimentacion1.setAttribute('readonly', true);
                alimentacion1.value = 'No aplica';

                // Aplicar estilos para "No aplica"
                alimentacion1.style.backgroundColor = '#f8d7da'; // Color de fondo
                alimentacion1.style.color = '#721c24'; // Color del texto
                alimentacion1.style.border = '1px solid #f5c6cb'; // Color del borde

                // Establecer el campo como obligatorio y añadir un texto
                alimentacion1.setAttribute('required', true);

            }
            if (transporte === '0') {
                if (!transporte1.dataset.originalValue) {
                    transporte1.dataset.originalValue = transporte1.value; // Guarda el valor original
                }
                transporte1.setAttribute('readonly', true);
                transporte1.value = 'No aplica';

                // Aplicar estilos para "No aplica"
                transporte1.style.backgroundColor = '#f8d7da'; // Color de fondo
                transporte1.style.color = '#721c24'; // Color del texto
                transporte1.style.border = '1px solid #f5c6cb'; // Color del borde

                // Establecer el campo como obligatorio y añadir un texto
                transporte1.setAttribute('required', true);

            }


            const inputValorOption = fila.querySelector('.inputValor-optionActividadesClass');
            const selectNombreCotizaciones = fila.querySelector('.select-nombreCotizacionesActividades-Class');
            const proveedorInput = fila.querySelector('.proveedor_input_classUnique');
            const costoExterno = fila.querySelector('.costo-externio-unitario-input');


            if (inputValorOption?.value === 'true') {
                proveedorInput.value = actividad.descripcionPersonal || '';
                selectNombreCotizaciones.value = ''; // Limpiar el select si es necesario
                selectNombreCotizaciones.classList.add('hidden');
                proveedorInput.classList.remove('hidden');
                costoExterno.disabled = false;
            } else {
                selectNombreCotizaciones.value = actividad.descripcionPersonal || '';
                proveedorInput.value = ''; // Limpiar el input si es necesario
            }



            fila.classList.add('clonedValoresKamatiFila');

            // Selecciona todos los campos de costo kamati y abreviatura dentro de la fila

            const abreviaturaElements = fila.querySelectorAll('.abreviaturas_nomClass');

            const alimetacionAc = fila.querySelectorAll('.hidden_valor_total_alimentacion_class_uniqueAc');
            const transporteAc = fila.querySelectorAll('.hidden_valor_total_transporte_class_uniqueAc');
            const alimentacionAcHidden = fila.querySelectorAll('.costoAlimentacion_hidden_uniqueclass_estadoButton');
            const transporteAcHidden = fila.querySelectorAll('.class_transporteHidden_unique');
            const transporteAcUtilidad = fila.querySelectorAll('.hidden_valor_total_transporte_class_uniqueAc_utilidad');
            const alimentacionAcUtilidad = fila.querySelectorAll('.hidden_valor_total_alimentacion_class_uniqueAc_utilidad');
            const diasKamatiValorSin = fila.querySelectorAll('.valor_diasKamati_classHiddenValorAc_unique_for_clon');
            const diasClienteValorSin = fila.querySelectorAll('.valor_diasCliente_classHiddenValorAc_unique_for_clon');

            diasKamatiValorSin.forEach((element) => {
                element.classList.add('clonedValoresKamati');
            });
            diasClienteValorSin.forEach((element) => {
                element.classList.add('clonedValoresCliente');
            });
            alimentacionAcHidden.forEach((element) => {
                element.classList.add('clonedAlimentacionClassUniqueHiddenn');
            });
            transporteAcHidden.forEach((element) => {
                element.classList.add('clonedTransporteClassUniqueHiddenn');
            });
            alimetacionAc.forEach((element) => {
                element.classList.add('clonedAlimentacionClassUnique');
            });
            transporteAc.forEach((element) => {
                element.classList.add('clonedTransporteClassUnique');
            });
            transporteAcUtilidad.forEach((element) => {
                element.classList.add('clonedTransporteClassUniqueUtilidad');
            });
            alimentacionAcUtilidad.forEach((element) => {
                element.classList.add('clonedAlimentacionClassUniqueUtilidad');
            });
            abreviaturaElements.forEach((element) => {
                element.classList.add('clonedValoresKamatiAbreviations');
            });




        }

        return insertId;
    } catch (error) {
        console.error('Error en la función insertarIdsDatosMateriales:', error);
    }
}
import { entreSemanaTurn } from './calculateDiasYUtilidadEntreSemanaTurn.js';
import { entreSemanaTurnDominical } from './calculateDiasDominicalTurn.js';
import { calculateDiaEntresemanas } from './calculateDiasEntreSemana.js';
import { calculateDiasDominical } from './calculateDiasDominicals.js';
import { calculateHorasDiaSemana } from './calculateHorasEntreSemana.js';
import { calculateHorasDominical } from './calculateHorasDomincal.js';
import { calculateDiassemanaTurnButton } from './calculateDiasemanaTurnButton.js';
import { calculateDiaDomicalTurnButons } from './calculateDiaDomincalTurnButton.js';
import { calculateDiasSemanaButtons } from './calculateDiasSemanaButton.js';
import { calculateDiasDominicalButtons } from './calculateDiasDominicalButton.js';
import { calculateHorasSemanaButtons } from './calculateHorasEntreSemanaButton.js';
import { calculateHorasDomiinicalButtons } from './calculateHorasDominicalButton.js';
import { checkJornada } from './checkJornadas.js';
import { calculateButtonNa } from './calculateButtonNas.js';
import { horasCalculadasDobleInput } from './dobleInputTiempo.js';
import { calculateViaticos } from './calculateViaticos.js';
import { findClosestNoMoverRow } from './findClosestNoMover.js';
import { initializeTable } from './calculateSumColumns.js';
export function calculateCostoKamatiUnitario(container ,divContainerActividades) {

    const tableBody = container ;
    const otherTable = document.querySelector("#table_cargos_acGlobal #cargos_table_acGlobal #cargo_table_acsGlobal #tbody_ac_tableGlobal");
    const tableViaticos = document.querySelector("#id_content_viaticosGlobal #id_new_tabla_viaticosGlobal #table_viaticos_acGlobal #tbody_viaticos_acGlobal");
    const factorOAc = document.querySelector("#factorOGlobal");
    const polizaAc = document.querySelector("#polizaGlobal");
    const viaticosAc = document.querySelector("#viaticosGlobal");
    const factorMoAc = document.querySelector("#factorMoGlobal");

    //FUNCIÓN QUE LLAMA LOS CALCULOS DE LOS TIEMPOS Y DE LOS VIÁTICOS PARA LA TABLA
    function calculateAndUpdate(rows, divContainerActividades) {
        const cambio = rows.querySelector('.inputValor-optionActividadesClass').value;
        const cantPersonas = rows.querySelector('td:nth-child(6) textarea').value.replace(/\./g, '');

        // Encuentra la fila con la clase 'no-mover' más cercana a la fila actual
        let noMoverRow = null;
        tableBody.querySelectorAll('tr.no-mover').forEach(row => {
            if (!noMoverRow || Math.abs(row.rowIndex - rows.rowIndex) < Math.abs(noMoverRow.rowIndex - rows.rowIndex)) {
                noMoverRow = row;
            }
        });

        // Verifica si se encontró una fila con la clase 'no-mover'
        if (!noMoverRow) {
            console.error("No se encontró una fila con la clase 'no-mover'.");
            return;
        }

        // Encuentra la fila más cercana que no tenga la clase 'no-mover'
        let closestRow = null;
        let minDistance = Infinity;

        tableBody.querySelectorAll('tr:not(.no-mover)').forEach(row => {
            const distance = Math.abs(row.rowIndex - noMoverRow.rowIndex);
            if (distance < minDistance) {
                minDistance = distance;
                closestRow = row;
            }
        });

        // Verifica si se encontró una fila válida
        if (!closestRow) {
            console.error("No se encontró una fila válida para realizar los cálculos.");
            return;
        }

        // (El resto de la función utiliza closestRow para los cálculos)
        if (cambio === 'false') {
            const descPersonal = rows.querySelector('.select-nombreCotizacionesActividades-Class').value;
            otherTable.querySelectorAll('tr').forEach(otherRow => {
                const cargo = otherRow.querySelector('td:first-child').textContent.trim();
                if (cargo === descPersonal) {
                    calculateDiaYDiasKamati(rows, cantPersonas, divContainerActividades);
                    calculateViaticos(rows, tableViaticos, divContainerActividades);
                    initializeTable(tableBody, divContainerActividades);
                }
            });

        } else if (cambio === 'true') {
            const descPersonal = rows.querySelector('.option-input_Ac').value;
            if (descPersonal !== null) {
                calculateDiaYDiasKamati(rows, cantPersonas, divContainerActividades);
                calculateViaticos(rows, tableViaticos, divContainerActividades);
                initializeTable(tableBody, divContainerActividades);
            }
        }
    }



    function calculateDiaYDiasKamati(rows, cantPersonas, divContainerActividades) {

        const noMoverRow = findClosestNoMoverRow(rows);

        if (!noMoverRow) {
            console.error("Fila .no-mover no encontrada.");
            return;
        }

        let cambio = rows.querySelector('.inputValor-optionActividadesClass').value;

        const costoExterno = rows.querySelector('.costo-externio-unitario-input')?.value.replace(/\./g, '') || '0';
        const diaKamati = rows.querySelector('.valor_Dia_kamati-class');
        const diasKamati = rows.querySelector('.valorDiasKamatiClass');
        const diasKamatiHidden = rows.querySelector('.valor_diasKamati_classHiddenValorAc_unique_for_clon');
        const alimenatacion = rows.querySelector('.hidden_valor_total_alimentacion_class_uniqueAc');
        const transporte = rows.querySelector('.hidden_valor_total_transporte_class_uniqueAc');
        const alimenatacionUtilidad = rows.querySelector('.hidden_valor_total_alimentacion_class_uniqueAc_utilidad');
        const transporteUtilidad = rows.querySelector('.hidden_valor_total_transporte_class_uniqueAc_utilidad');
        const tipoDiaNoMover = noMoverRow.querySelector('select[name="tipo_dia"]').value;
        const abrvLinea = rows.querySelector('.abreviaturas_nomClass').value;
        const unidad = rows.querySelector('.selectUnidadesActividadesClass').value;
        const startTimeValue = noMoverRow.querySelector('.starTimeClassActividades').value;
        const endTimeValue = noMoverRow.querySelector('.endTimeClassActividades').value;
        const cant = rows.querySelector('.cant-input').value.replace(/\./g, '');
        const { costoApl, costoTrs } = calculateButtonNa(rows);

        const factorOAcValue = parseFloat(factorOAc.value) || 1;
        const factorMoValue = parseFloat(factorMoAc.value) || 1;
        const polizaAcValue = parseFloat(polizaAc.value) || 1;
        const viaticosValue = parseFloat(viaticosAc.value) || 1;

        const extraDiurna = document.getElementById('recargos1Id');
        const recargoNocturno = document.getElementById('recargos2Id');
        const recargoNocturnoExtra = document.getElementById('recargos3Id');
        const recargoFestivoDominical = document.getElementById('recargos4Id');
        const recargoExtraDominicalDiurna = document.getElementById('recargos5Id');
        const recargoNocturnoDominical = document.getElementById('recargos6Id');
        const recargoNocturnoExtraDominical = document.getElementById('recargos7Id');

        const extraDiurnaValue = parseFloat(extraDiurna.value) || 1;
        const recargoNocturnoValue = parseFloat(recargoNocturno.value) || 1;
        const recargoNocturnoExtraValue = parseFloat(recargoNocturnoExtra.value) || 1;
        const recargoFestivoDominicalValue = parseFloat(recargoFestivoDominical.value) || 1;
        const recargoExtraDominicalDiurnaValue = parseFloat(recargoExtraDominicalDiurna.value) || 1;
        const recargoNocturnoDominicalValue = parseFloat(recargoNocturnoDominical.value) || 1;
        const recargoNocturnoExtraDominicalValue = parseFloat(recargoNocturnoExtraDominical.value) || 1;

        let costoFinalAlimentacion = parseFloat(costoApl) * viaticosValue * polizaAcValue;
        let costoFinalTransporte = parseFloat(costoTrs) * viaticosValue * polizaAcValue;
        const totalTiempo = horasCalculadasDobleInput(noMoverRow);

        const { jornada, horasDiurnas, horasNocturnas } = checkJornada(tableBody, startTimeValue, endTimeValue);

        console.log(jornada);

        if (!diaKamati || !diasKamati) {
            console.error("Elementos diaKamati o diasKamati no encontrados.");
            return;
        }

        if (cambio === 'true') {
            if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Turn' && cant) {
                entreSemanaTurn(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden,divContainerActividades, rows, costoExterno, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, recargoNocturnoValue, extraDiurnaValue, recargoNocturnoExtraValue, costoFinalAlimentacion, costoFinalTransporte, totalTiempo, jornada, horasDiurnas, horasNocturnas, cantPersonas);

            } else if (tipoDiaNoMover === 'Dominical' && unidad === 'Turn' && cant) {
                entreSemanaTurnDominical(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, tableBody, rows, costoExterno, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, recargoNocturnoValue, recargoNocturnoExtraValue, costoFinalAlimentacion, costoFinalTransporte, totalTiempo, jornada, horasDiurnas, horasNocturnas, cantPersonas, recargoFestivoDominicalValue, recargoExtraDominicalDiurnaValue, recargoNocturnoDominicalValue, recargoNocturnoExtraDominicalValue);
            }
            //DIAS - ENTRE SEMANA
            else if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Dias' && cant) {
                calculateDiaEntresemanas(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, rows, costoExterno, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas);
            }
            //DÍAS - DOMINICAL
            else if (tipoDiaNoMover === 'Dominical' && unidad === 'Dias' && cant) {
                calculateDiasDominical(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, rows, costoExterno, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, recargoFestivoDominicalValue);
            }
            //Horas - Entre semana 
            else if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Horas' && cant) {
                calculateHorasDiaSemana(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, rows, costoExterno, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, extraDiurnaValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas);
            }
            //Horas - Dominical 
            else if (tipoDiaNoMover === 'Dominical' && unidad === 'Horas' && cant) {
                calculateHorasDominical(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, rows, costoExterno, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, recargoFestivoDominicalValue, recargoExtraDominicalDiurnaValue);
            }

        } else if (cambio === 'false') {
            const totalTiempo = horasCalculadasDobleInput(noMoverRow);
            if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Turn' && cant) {
                calculateDiassemanaTurnButton(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, otherTable, rows, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, recargoNocturnoValue, extraDiurnaValue, recargoNocturnoExtraValue, costoFinalAlimentacion, costoFinalTransporte, totalTiempo, jornada, horasDiurnas, horasNocturnas, cantPersonas);
            }
            else if (tipoDiaNoMover === 'Dominical' && unidad === 'Turn' && cant) {
                calculateDiaDomicalTurnButons(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, tableBody, otherTable, rows, costoExterno, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, recargoNocturnoValue, recargoNocturnoExtraValue, costoFinalAlimentacion, costoFinalTransporte, totalTiempo, jornada, horasDiurnas, horasNocturnas, cantPersonas, recargoFestivoDominicalValue, recargoExtraDominicalDiurnaValue, recargoNocturnoDominicalValue, recargoNocturnoExtraDominicalValue)
            }
            //DIAS - ENTRE SEMANA
            else if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Dias' && cant) {
                calculateDiasSemanaButtons(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, otherTable, rows, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas);
            }
            //DÍAS - DOMINICAL
            else if (tipoDiaNoMover === 'Dominical' && unidad === 'Dias' && cant) {
                calculateDiasDominicalButtons(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, otherTable, rows, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, recargoFestivoDominicalValue);
            }
            //HORAS - ENTRE SEMANA 
            else if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Horas' && cant) {
                calculateHorasSemanaButtons(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, otherTable, rows, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, extraDiurnaValue);
            }
            //HORA - DOMINICAL
            else if (tipoDiaNoMover === 'Dominical' && unidad === 'Horas' && cant) {
                calculateHorasDomiinicalButtons(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, otherTable, rows, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, recargoFestivoDominicalValue, recargoExtraDominicalDiurnaValue);
            }
        }
    }

    // Función para configurar el MutationObserver y los event listeners en los campos de costo

    function observeAndAttachListenersToRow(rows, rowTurn, tableViaticos, divContainerActividades) {
        // Función para ejecutar el cálculo
        const triggerCalculations = () => {
            calculateAndUpdate(rows, divContainerActividades);
            calculateViaticos(rows, tableViaticos, divContainerActividades);
        };
    
        // Evitar bucles infinitos con debounce
        let isUpdating = false;
        const debounceCalculation = () => {
            if (!isUpdating) {
                isUpdating = true;
                triggerCalculations();
                setTimeout(() => { isUpdating = false; }, 300); // Evita que se ejecute repetidamente en 300ms
            }
        };
    
        // Función para agregar listeners a los elementos relevantes
        const attachListeners = (element) => {
            if (!element) return;
    
            // Evento para inputs, selects, textareas
            element.addEventListener('input', debounceCalculation);
            element.addEventListener('change', debounceCalculation);
    
            // Evento para botones
            if (element.tagName === 'BUTTON') {
                element.addEventListener('click', debounceCalculation);
            }
    
            // Agregar MutationObserver para cambios programáticos en inputs, selects, etc.
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(element.tagName)) {
                const observerElement = new MutationObserver(mutations => {
                    mutations.forEach(mutation => {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                            debounceCalculation();
                        }
                    });
                });
                observerElement.observe(element, { attributes: true, attributeFilter: ['value'] });
            }
        };
    
        // Adjuntar listeners a los elementos de la fila
        const elements = rows.querySelectorAll(`
            .costo-alimentacion, .costo-transporte, .cantidad_actividades_unique, .inputValor-optionActividadesClass, 
            .abreviaturas_nomClass, .tipoDia-classActividades, .selectUnidadesActividadesClass, .starTimeClassActividades, 
            .endTimeClassActividades, .cant-input, .select-nombreCotizacionesActividades-Class, textarea, 
            .costo-externio-unitario-input, .option-input_Ac, .valor_Dia_kamati-class, #JornadaDiurnaInicioId, 
            #JornadaDiurnaFinId, .hidden_alimentacion, .hidden_transporte, button, .check_new_Factor-ClassActividades, 
            .input-new-factor-Actividades-class
        `);
    
        elements.forEach(element => attachListeners(element));
       
        rows.addEventListener('textarea', debounceCalculation);
        // Escuchar cambios en la fila principal
        rowTurn.addEventListener('change', debounceCalculation);
    
        // Observar si se agregan nuevos nodos o elementos al contenedor
        const observerConfig = { childList: true, subtree: true }; // Detecta elementos hijos añadidos
        const rowObserver = new MutationObserver(() => {
            const newElements = rows.querySelectorAll(`
                .costo-alimentacion, .costo-transporte, .cantidad_actividades_unique, .inputValor-optionActividadesClass, 
                .abreviaturas_nomClass, .tipoDia-classActividades, .selectUnidadesActividadesClass, .starTimeClassActividades, 
                .endTimeClassActividades, .cant-input, .select-nombreCotizacionesActividades-Class, textarea, 
                .costo-externio-unitario-input, .option-input_Ac, .valor_Dia_kamati-class, #JornadaDiurnaInicioId, 
                #JornadaDiurnaFinId, .hidden_alimentacion, .hidden_transporte, button, .check_new_Factor-ClassActividades, 
                .input-new-factor-Actividades-class
            `);
    
            newElements.forEach(newElement => attachListeners(newElement));
        });
    
        rowObserver.observe(rows, observerConfig);
    }

    tableBody.querySelectorAll('tr:not(.no-mover)').forEach(row => {
        tableBody.querySelectorAll('.tr_new_tbody_turnounique_Class').forEach(rowTurn => {
            observeAndAttachListenersToRow(row, rowTurn, tableViaticos, divContainerActividades);
            calculateAndUpdate(row, divContainerActividades);
            calculateViaticos(row, tableViaticos, divContainerActividades);
        });
    });

    [factorOAc, polizaAc, viaticosAc, factorMoAc].forEach(factor => {
        factor.addEventListener('input', () => {
            tableBody.querySelectorAll('tr:not(.no-mover)').forEach(rows => {
                observeAndAttachListenersToRow(rows, tableViaticos, divContainerActividades);

                calculateAndUpdate(rows, divContainerActividades);
                calculateViaticos(rows, tableViaticos, divContainerActividades);
                initializeTable(tableBody, divContainerActividades);
            });
        });
    });
}
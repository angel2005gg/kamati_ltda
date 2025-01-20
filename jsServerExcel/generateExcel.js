function obtenerDatos() {
    // Obtener el valor del h2
    const titulo = document.querySelector('.h2_separado h2').innerText;

    // Obtener los valores de los campos solicitados
    const campo1 = document.querySelector('#fechaActual').value;
    const campo2 = document.querySelector('#nombreClienteCotizacionId').value;
    const campo3 = document.querySelector('#nombreProyectoCotizacionId').value;
    const campo4 = document.querySelector('#codigoProyectoCotizacionId').value;
    const totalAbKamati = document.querySelector('#txtIdTotalKamatiLineasAb').value;
    const totalAbCliente = document.querySelector('#txtIdTotalClienteLineasAb').value;

    // Obtener los valores de los campos horizontales
    const txtNombre = document.querySelector('#txt_nombre').value;
    const txtIdentificacion = document.querySelector('#txt_identificacion').value;
    const txtIdentificacionUsd = document.querySelector('#txt_identificacion_usd').value;
    const txtIdentificacionEur = document.querySelector('#txt_identificacion_eur').value;

    // Obtener los nuevos valores de los inputs
    const factorMoGlobal = document.querySelector('#factorMoGlobal').value;
    const factorOGlobal = document.querySelector('#factorOGlobal').value;
    const viaticosGlobal = document.querySelector('#viaticosGlobal').value;
    const polizaGlobal = document.querySelector('#polizaGlobal').value;
    const siemensGlobal = document.querySelector('#siemensGlobal').value;
    const pilzGlobal = document.querySelector('#pilzGlobal').value;
    const rittalGlobal = document.querySelector('#rittalGlobal').value;
    const phoenixGlobal = document.querySelector('#phoenixGlobal').value;

    const totalUnique_kamati = Array.from(document.querySelectorAll('.totalUnique_kamati_class_cloned')).map(input => input.value.trim());
    // Paso 1: Obtener todos los valores de los inputs
    const totalUnique_cliente = Array.from(document.querySelectorAll('.totalUnique_cliente_class_cloned'))
        .map(input => input.value.trim());

    // Depuración: Verifica los valores originales obtenidos
    console.log("Valores originales:", totalUnique_cliente);

    // Paso 2: Limpia los números eliminando puntos (separador de miles), comas (separador decimal) y cualquier otro símbolo no numérico
    const cleanedNumbers = totalUnique_cliente.map(num => {
        // Eliminamos todos los caracteres que no son números ni comas (manteniendo la coma como separador decimal)
        const cleaned = num.replace(/[^0-9,]+/g, '');
        console.log(`Número limpiado: ${num} => ${cleaned}`); // Depuración: muestra cada número limpiado
        return cleaned;
    });

    // Paso 3: Convierte los valores a números flotantes, convirtiendo la coma a punto para la operación
    const numericValues = cleanedNumbers.map(num => {
        // Convertimos la coma en un punto solo cuando hagamos el parseo para que se pueda convertir a flotante
        const cleanedForParse = num.replace(',', '.');
        const parsed = parseFloat(cleanedForParse);
        console.log(`Convertido a número: ${num} => ${parsed}`); // Depuración: muestra cada valor convertido
        return parsed;
    });

    // Paso 4: Calcula la suma total
    const totalSum = numericValues.reduce((sum, current) => sum + current, 0);

    // Depuración: Verifica el resultado de la suma
    console.log("Suma total:", totalSum);

    // Paso 5: Convierte el resultado a un número con formato y decimales, manteniendo la coma como separador decimal
    const formattedTotal = totalSum.toLocaleString('de-DE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    // Muestra el resultado final formateado
    console.log("Total formateado:", formattedTotal);
    const totalUnique_kamati_maquinaria = Array.from(document.querySelectorAll('.txtTotalKamatiMaquinaria_cloned')).map(input => input.value.trim());
    const totalKamatiAcs = Array.from(document.querySelectorAll('.txt_total_kamatiActividadesClass_CLONED')).map(input => input.value.trim());
    // Para totalUnique_cliente_maquinaria
    const totalUnique_cliente_maquinaria = Array.from(document.querySelectorAll('.txtTotalClienteMaquinaria_cloned'))
        .map(input => input.value.trim());

    // Obtención de los valores de los inputs
    const totalClienteAcs = Array.from(document.querySelectorAll('.txt_total_clienteActividadesClass_CLONED'))
        .map(input => input.value.trim());

    // Función para limpiar los números
    const cleanNumbersAcs = (arr) => {
        return arr.map(num => {
            // Paso 1: Reemplazamos las comas por puntos, pero solo las de miles
            // Paso 2: Reemplazamos el punto decimal por coma
            const cleaned = num.replace(/,/g, '').replace(/\./g, ',');
            console.log(`Número limpiado: ${num} => ${cleaned}`); // Depuración: muestra cada número limpiado
            return cleaned;
        });
    };

    // Limpiar los valores de totalKamatiAcs
    const cleanedNumbersAcs = cleanNumbersAcs(totalClienteAcs);

    // Función para convertir los números a valores flotantes
    const convertToNumericValuesAcs = (arr) => {
        return arr.map(num => {
            // Convertimos la coma en un punto solo cuando hagamos el parseo para que se pueda convertir a flotante
            const cleanedForParse = num.replace(',', '.');
            const parsed = parseFloat(cleanedForParse);
            console.log(`Convertido a número: ${num} => ${parsed}`); // Depuración: muestra cada valor convertido
            return parsed;
        });
    };

    // Convertir los números para totalKamatiAcs
    const numericValuesAcs = convertToNumericValuesAcs(cleanedNumbersAcs);

    // Calcular la suma total
    const totalSumAcs = numericValuesAcs.reduce((sum, current) => sum + current, 0);

    // Depuración: Verifica los resultados de la suma
    console.log("Suma total Actividades:", totalSumAcs);

    // Función para formatear el total en el formato adecuado
    const formatTotalAc = (total) => {
        return total.toLocaleString('de-DE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    };

    // Formatear el total
    const formattedTotalAcs = formatTotalAc(totalSumAcs);

    // Función para limpiar los números (eliminar puntos y símbolos)
    const cleanNumbers = (arr) => {
        return arr.map(num => {
            // Eliminamos todos los caracteres que no son números ni comas
            const cleaned = num.replace(/[^0-9,]+/g, '');
            console.log(`Número limpiado: ${num} => ${cleaned}`); // Depuración: muestra cada número limpiado
            return cleaned;
        });
    };

    // Limpiar los valores para totalUnique_cliente_maquinaria
    const cleanedNumbers_maquinaria = cleanNumbers(totalUnique_cliente_maquinaria);



    // Función para convertir los números a valores flotantes
    const convertToNumericValues = (arr) => {
        return arr.map(num => {
            // Convertimos la coma en un punto solo cuando hagamos el parseo para que se pueda convertir a flotante
            const cleanedForParse = num.replace(',', '.');
            const parsed = parseFloat(cleanedForParse);
            console.log(`Convertido a número: ${num} => ${parsed}`); // Depuración: muestra cada valor convertido
            return parsed;
        });
    };

    // Convertir los números para totalUnique_cliente_maquinaria
    const numericValues_maquinaria = convertToNumericValues(cleanedNumbers_maquinaria);

    const totalSum_maquinaria = numericValues_maquinaria.reduce((sum, current) => sum + current, 0);


    // Depuración: Verifica los resultados de la suma
    console.log("Suma total Maquinaria:", totalSum_maquinaria);

    // Función para formatear el total en el formato adecuado
    const formatTotal = (total) => {
        return total.toLocaleString('de-DE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    };

    // Formatear los totales
    const formattedTotal_maquinaria = formatTotal(totalSum_maquinaria);


    // Función para limpiar los valores
    const cleanValues = (value) => {
        // Paso 1: Eliminar el símbolo de $
        // Paso 2: Eliminar los puntos de miles
        // Paso 3: Dejar la coma como separador decimal
        const cleanedValue = value.replace(/[^0-9,]+/g, '');  // Elimina símbolos no numéricos excepto la coma
        return cleanedValue;
    };

    // Limpiar los valores
    const cleanedTotal = cleanValues(formattedTotal);
    const cleanedTotalMaquinaria = cleanValues(formattedTotal_maquinaria);
    const cleanedTotalAcs = cleanValues(formattedTotalAcs);

    // Convertir los valores limpiados a números flotantes, reemplazando la coma decimal por punto
    const convertToNumeric = (value) => {
        return parseFloat(value.replace(',', '.'));
    };

    // Convertir los valores a flotantes
    const numericTotal = convertToNumeric(cleanedTotal);
    const numericTotalMaquinaria = convertToNumeric(cleanedTotalMaquinaria);
    const numericTotalAcs = convertToNumeric(cleanedTotalAcs);

    // Sumar los valores
    const totalSumFinal = numericTotal + numericTotalMaquinaria + numericTotalAcs;

    // Depuración: Verificar el total de la suma
    console.log("Suma total:", totalSumFinal);

    // Formatear la suma final
    const formattedFinalTotal = totalSumFinal.toLocaleString('de-DE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });




    // Obtener los datos de la tabla de Viáticos
    const viaticos = [];
    const viaticosRows = document.querySelectorAll('#tbody_viaticos_acGlobal tr');
    viaticosRows.forEach(row => {
        const nombreViatico = row.querySelector('td:nth-child(1)').innerText.trim();
        const valorViatico = row.querySelector('td:nth-child(2) input').value.trim().replace(/\./g, ''); // Quitar los puntos del formato de miles
        viaticos.push({ nombre: nombreViatico, valor: valorViatico });
    });
    // Obtener los datos de la tabla de Viáticos
    const contenedores = {};

    // Obtener los contenedores de viáticos
    const viaticosContainers = document.querySelectorAll('.viaticos_tbody_unique_cloned'); // Asegúrate de que cada contenedor tenga esta clase

    viaticosContainers.forEach(container => {
        const viaticosAc = [];

        // Obtener el identificador único para este contenedor de viáticos
        const containerId = container.querySelector('.identificadorUpdateacUnique')?.value.trim(); // Obtener el valor del campo de entrada

        if (containerId) {
            const viaticosRowsAc = container.querySelectorAll('tr');

            viaticosRowsAc.forEach(row => {
                const nombreViatico = row.querySelector('td:nth-child(1)').innerText.trim();
                const valorViatico = row.querySelector('td:nth-child(2) input').value.trim().replace(/\./g, ''); // Quitar los puntos del formato de miles
                viaticosAc.push({ nombre: nombreViatico, valor: valorViatico });
            });

            // Almacenar los viáticos por contenedor en el objeto principal
            contenedores[containerId] = {
                viaticos: viaticosAc,
                cargos: [] // Inicializamos cargos como un array vacío por ahora
            };
        }
    });

    // Obtener los contenedores de cargos
    const cargosContainers = document.querySelectorAll('.cargos_tbody_unique_cloned'); // Asegúrate de que cada contenedor tenga esta clase

    cargosContainers.forEach(container => {
        const cargosAc = [];

        // Obtener el identificador único para este contenedor de cargos
        const containerId = container.querySelector('.class_inputHidden_unique_identificadorCa')?.value.trim(); // Obtener el valor del campo de entrada

        if (containerId) {
            const cargosRowsAc = container.querySelectorAll('tr');

            cargosRowsAc.forEach(row => {
                const nombreCargo = row.querySelector('td:nth-child(1)').innerText.trim();
                const valorCargo = row.querySelector('td:nth-child(2) input').value.trim().replace(/\./g, ''); // Quitar los puntos del formato de miles
                cargosAc.push({ nombre: nombreCargo, valor: valorCargo });
            });

            // Almacenar los cargos por contenedor en el objeto principal
            if (contenedores[containerId]) {
                contenedores[containerId].cargos = cargosAc;
            } else {
                contenedores[containerId] = {
                    viaticos: [], // Si no tiene viáticos, los inicializamos como un array vacío
                    cargos: cargosAc
                };
            }
        }
    });

    console.log(contenedores);
    // Obtener los datos de la tabla de Cargos
    const cargos = [];
    const cargosRows = document.querySelectorAll('#tbody_ac_tableGlobal tr');
    cargosRows.forEach(row => {
        const nombreCargo = row.querySelector('td:nth-child(1)').innerText.trim();
        const valorCargo = row.querySelector('td:nth-child(2) input').value.trim().replace(/\./g, ''); // Quitar los puntos del formato de miles
        cargos.push({ nombre: nombreCargo, valor: valorCargo });
    });
    // Obtener los datos de la tabla de Cargos actividades

    // Obtener los valores de los campos con la clase nombre_tabla_unique_cloned_class
    const nombreTablaMateriales = [];
    const materialesInputs = document.querySelectorAll('.nombre_tabla_unique_cloned_class');
    materialesInputs.forEach(input => {
        nombreTablaMateriales.push(input.value.trim());
    });

    const nombreTablaMaquinaria = [];
    const nombreTablas = document.querySelectorAll('.nombre_table_maquinariaClass_clonedUnique');
    nombreTablas.forEach(input => {
        nombreTablaMaquinaria.push(input.value.trim());
    });
    const nombreTablaActividades = [];
    const nombreTablaActivid = document.querySelectorAll('.nombreTablaActividades_unique_class_cloned');
    nombreTablaActivid.forEach(input => {
        nombreTablaActividades.push(input.value.trim());
    });

    //Factores de materiales
    const factorMo = Array.from(document.querySelectorAll('.factor_Mo_unique_cloned_class')).map(input => input.value.trim());
    const factorO = Array.from(document.querySelectorAll('.factor_O_unique_cloned_class')).map(input => input.value.trim());
    const factorv = Array.from(document.querySelectorAll('.factor_V_unique_cloned_class')).map(input => input.value.trim());
    const factorPo = Array.from(document.querySelectorAll('.factor_Po_unique_cloned_class')).map(input => input.value.trim());
    const factorSm = Array.from(document.querySelectorAll('.factor_Sm_unique_cloned_class')).map(input => input.value.trim());
    const factorPilz = Array.from(document.querySelectorAll('.factorPilz_unique_cloned_class')).map(input => input.value.trim());
    const factorRt = Array.from(document.querySelectorAll('.factor_Rt_unique_cloned_class')).map(input => input.value.trim());
    const factorPx = Array.from(document.querySelectorAll('.factor_Px_unique_cloned_class')).map(input => input.value.trim());
    //Factores de maquinaria 
    const factorMoMaquinaria = Array.from(document.querySelectorAll('.factor_MoClassMaquinariaUnique_cloned')).map(input => input.value.trim());
    const factorOMaquinaria = Array.from(document.querySelectorAll('.factor_OClassMaquinariaUnique_cloned')).map(input => input.value.trim());
    const factorvMaquinaria = Array.from(document.querySelectorAll('.factor_VClassMaquinariaUnique_cloned')).map(input => input.value.trim());
    const factorPoMaquinaria = Array.from(document.querySelectorAll('.factor_polizaClassMaquinariaUnique_cloned')).map(input => input.value.trim());
    const factorSmMaquinaria = Array.from(document.querySelectorAll('.factor_siemensClassMaquinariaUnique_cloned')).map(input => input.value.trim());
    const factorPilzMaquinaria = Array.from(document.querySelectorAll('.factor_pilzClassMaquinariaUnique_cloned')).map(input => input.value.trim());
    const factorRtMaquinaria = Array.from(document.querySelectorAll('.factor_rittalClassMaquinariaUnique_cloned')).map(input => input.value.trim());
    const factorPxMaquinaria = Array.from(document.querySelectorAll('.factor_phoenixcontactClassMaquinariaUnique_cloned')).map(input => input.value.trim());
    //Factores de actividades
    const factorMoAc = Array.from(document.querySelectorAll('.factorMoHiddenClass_unique_class_cloned')).map(input => input.value.trim());
    const factorOAc = Array.from(document.querySelectorAll('.factorOHiddenClass_unique_class_cloned')).map(input => input.value.trim());
    const factorvAc = Array.from(document.querySelectorAll('.viaticosHiddenClass_unique_class_cloned')).map(input => input.value.trim());
    const factorPoAc = Array.from(document.querySelectorAll('.polizaHiddenClaas_unique_class_cloned')).map(input => input.value.trim());

    const automatizacion = document.getElementById('id_AutoKamatiValor').value.trim();
    const comunicacion = document.getElementById('id_ComKamatiValor').value.trim();
    const maniobrace = document.getElementById('id_CeKamatiValor').value.trim();
    const variadores = document.getElementById('id_VarKamatiValor').value.trim();
    const software = document.getElementById('id_SoftKamatiValor').value.trim();
    const repuestos = document.getElementById('id_RepKamatiValor').value.trim();
    const maniobralp = document.getElementById('id_LpKamatiValor').value.trim();
    const otros = document.getElementById('id_OKamatiValor').value.trim();
    const pilz = document.getElementById('id_PilzKamatiValor').value.trim();
    const phoenix = document.getElementById('id_PcKamatiValor').value.trim();
    const rittal = document.getElementById('id_RKamatiValor').value.trim();
    const viaticosAv = document.getElementById('id_VKamatiValor').value.trim();
    const mano = document.getElementById('id_MoKamatiValor').value.trim();
    const supervisor = document.getElementById('id_SupKamatiValor').value.trim();
    const ingenieria = document.getElementById('id_IngKamatiValor').value.trim();
    const project = document.getElementById('id_PmKamatiValor').value.trim();
    const siso = document.getElementById('id_SisoKamatiValor').value.trim();

    const automatizacionCliente = document.getElementById('id_AutoClienteValor').value.trim();
    const comunicacionCliente = document.getElementById('id_ComClienteValor').value.trim();
    const maniobraceCliente = document.getElementById('id_CeClienteValor').value.trim();
    const variadoresCliente = document.getElementById('id_VarClienteValor').value.trim();
    const softwareCliente = document.getElementById('id_SoftClienteValor').value.trim();
    const repuestosCliente = document.getElementById('id_RepClienteValor').value.trim();
    const maniobralpCliente = document.getElementById('id_LpClienteValor').value.trim();
    const otrosCliente = document.getElementById('id_OClienteValor').value.trim();
    const pilzCliente = document.getElementById('id_PilzClienteValor').value.trim();
    const phoenixCliente = document.getElementById('id_PcClienteValor').value.trim();
    const rittalCliente = document.getElementById('id_RClienteValor').value.trim();
    const viaticosAvCliente = document.getElementById('id_VClienteValor').value.trim();
    const manoCliente = document.getElementById('id_MoClienteValor').value.trim();
    const supervisorCliente = document.getElementById('id_SupClienteValor').value.trim();
    const ingenieriaCliente = document.getElementById('id_IngClienteValor').value.trim();
    const projectCliente = document.getElementById('id_PmClienteValor').value.trim();
    const sisoCliente = document.getElementById('id_SisoClienteValor').value.trim();

    const automatizacionClientePorcentaje = document.getElementById('id_AutoClientePorcentaje').value.trim();
    const comunicacionClientePorcentaje = document.getElementById('id_ComClientePorcentaje').value.trim();
    const maniobraceClientePorcentaje = document.getElementById('id_CeClientePorcentaje').value.trim();
    const variadoresClientePorcentaje = document.getElementById('id_VarClientePorcentaje').value.trim();
    const softwareClientePorcentaje = document.getElementById('id_SoftClientePorcentaje').value.trim();
    const repuestosClientePorcentaje = document.getElementById('id_RepClientePorcentaje').value.trim();
    const maniobralpClientePorcentaje = document.getElementById('id_LpClientePorcentaje').value.trim();
    const otrosClientePorcentaje = document.getElementById('id_OClientePorcentaje').value.trim();
    const pilzClientePorcentaje = document.getElementById('id_PilzClientePorcentaje').value.trim();
    const phoenixClientePorcentaje = document.getElementById('id_PcClientePorcentaje').value.trim();
    const rittalClientePorcentaje = document.getElementById('id_RClientePorcentaje').value.trim();
    const viaticosAvClientePorcentaje = document.getElementById('id_VClientePorcentaje').value.trim();
    const manoClientePorcentaje = document.getElementById('id_MoClientePorcentaje').value.trim();
    const supervisorClientePorcentaje = document.getElementById('id_SupClientePorcentaje').value.trim();
    const ingenieriaClientePorcentaje = document.getElementById('id_IngClientePorcentaje').value.trim();
    const projectClientePorcentaje = document.getElementById('id_PmClientePorcentaje').value.trim();
    const sisoClientePorcentaje = document.getElementById('id_SisoClientePorcentaje').value.trim();

    const inputFactorIndepedienteMaquinaria = Array.from(
        document.querySelectorAll('.hiddenInputFactoresIndependientesUniqueCloned')
    ).map(input => {
        const valor = input.value.trim();
        if (valor === '0' || valor === '') {
            return 'No aplica factores independientes';
        } else if (valor === '1') {
            return 'Si aplica factores independientes';
        }
        return valor; // Retorna el valor original si no cumple ninguna condición
    });

    const inputFactorIndepedienteMateriales = Array.from(
        document.querySelectorAll('.hiddenInputFactoresIndependientesUniqueCloned')
    ).map(input => {
        const valor = input.value.trim();
        if (valor === '0' || valor === '') {
            return 'No aplica factores independientes';
        } else if (valor === '1') {
            return 'Si aplica factores independientes';
        }
        return valor; // Retorna el valor original si no cumple ninguna condición
    });
    const inputFactorIndepedienteActividades = Array.from(
        document.querySelectorAll('.checkNActividades_unique_class_cloned')
    ).map(input => {
        const valor = input.value.trim();
        if (valor === '0' || valor === '') {
            return 'No aplica factores independientes';
        } else if (valor === '1') {
            return 'Si aplica factores independientes';
        }
        return valor; // Retorna el valor original si no cumple ninguna condición
    });



    const materialesDatos = {};
    const tablas = document.querySelectorAll('.tabla_tbody_unique_cloned');

    tablas.forEach((tabla, index) => {
        if (!materialesDatos[index]) {
            materialesDatos[index] = [];
        }

        const filasMateriales = tabla.querySelectorAll('.class_Cloned_trMateriales_unique');

        filasMateriales.forEach((row, rowIndex) => {
            const proveedorSelectText = row.querySelector('.select_proveedor_materiales_class')?.selectedOptions[0]?.text.trim();
            const proveedor = row.querySelector('.input_proveedor_materiales_class')?.classList.contains('hidden')
                ? proveedorSelectText || ""
                : row.querySelector('.input_proveedor_materiales_class').value || "";
            const cantidad = row.querySelector('.materialescantidadTable').value.trim();
            const unidad = row.querySelector('.select_unidades_materiales_table_class').value.trim();
            const abreviatura = row.querySelector('.select_abreviatura_materiales_class')?.selectedOptions[0]?.text.trim();
            const referencia = row.querySelector('.textarea_referencia_materiales_class').value.trim();
            const material = row.querySelector('.textarea_material_class').value.trim();
            const descripcionMaterial = row.querySelector('.textarea_descripcionMaterial_class').value.trim();
            const nota = row.querySelector('.textarea_nota_materiales_class').value.trim();
            const trm = row.querySelector('.selet_trm_materiales_class').value.trim();
            const precioLista = row.querySelector('.precio_lista_input_class_materiales').value.trim();
            const costoUnitarioKamati = row.querySelector('.cost-kamati-input_class_materiales').value.trim();
            const costoTotalKamati = row.querySelector('.cost-kamati-total_class_materiales').value.trim();
            const valorUtilidad = row.querySelector('.valor-utilidad_class_materiales').value.trim();
            const valorTotal = row.querySelector('.value-total-input_class_materiales').value.trim();
            const tiempoEntrega = row.querySelector('.valor_tiempo_entrega_class_materialesa').value.trim();
            const descuento = row.querySelector('.descuento-input_materiales_class').value.trim();
            const descuentoAdicional = row.querySelector('.descuento-adicional-input_materiales_class').value.trim();
            const fechaEntrega = row.querySelector('.date_input_entrega_class_materiales').value.trim();
            const rep = row.querySelector('.select_rep_classMateriales').value.trim();
            const checkEstado = row.querySelector('.check_estado_class_materiales').checked;
            const factorAdicional = row.querySelector('.factor_adicional_class_materiales').value.trim();



            materialesDatos[index].push({
                rowIndex: rowIndex,  // Este es el contador que se incrementa por fila
                cantidad: cantidad,
                unidad: unidad,
                abreviatura: abreviatura,
                referencia: referencia,
                material: material,
                descripcionMaterial: descripcionMaterial,
                proveedor: proveedor,
                nota: nota,
                trm: trm,
                precioLista: precioLista,
                costoUnitarioKamati: costoUnitarioKamati,
                costoTotalKamati: costoTotalKamati,
                valorUtilidad: valorUtilidad,
                valorTotal: valorTotal,
                tiempoEntrega: tiempoEntrega,
                descuento: descuento,
                descuentoAdicional: descuentoAdicional,
                fechaEntrega: fechaEntrega,
                rep: rep,
                checkEstado: checkEstado,
                factorAdicional: factorAdicional
            });
        });
    });
    const maquinariaDatos = {};
    const tablasMaquinaria = document.querySelectorAll('.table_original_maquinaria_clonedUnique');

    tablasMaquinaria.forEach((tabla, index) => {
        if (!maquinariaDatos[index]) {
            maquinariaDatos[index] = [];
        }

        const filasMaquinaria = tabla.querySelectorAll('.trClassMquinaria_unique_cloned');

        filasMaquinaria.forEach((row, rowIndex) => {
            const cantidad = row.querySelector('.materialescantidadTableMaquinaria').value.trim();
            const unidad = row.querySelector('.select_unidades_maquinaria_table_class').value.trim();
            const abreviatura = row.querySelector('.select_abreviatura_maquinaria_class').value.trim();
            const referencia = row.querySelector('.textarea_referencia_maquinaria_class').value.trim();
            const material = row.querySelector('.textarea_maquinariaMaterial_class').value.trim();
            const descripcionMaterial = row.querySelector('.textarea_descripcionmaquinaria_class').value.trim();
            const proveedor = row.querySelector('.select_proveedor_Maquinaria_class').value.trim();
            const nota = row.querySelector('.nota_maquinaria_uniqueclass').value.trim();
            const trm = row.querySelector('.selet_trm_Maquinaria_class').value.trim();
            const precioLista = row.querySelector('.precio_lista_input_class_Maquinaria').value.trim();
            const costoUnitarioKamati = row.querySelector('.cost_kamati_input_class_Maquinaria').value.trim();
            const costoTotalKamati = row.querySelector('.cost_kamati_total_class_Maquinaria').value.trim();
            const valorUtilidad = row.querySelector('.valor_utilidad_class_Maquinaria').value.trim();
            const valorTotal = row.querySelector('.value_total_input_class_Maquinaria').value.trim();
            const tiempoEntrega = row.querySelector('.valor_tiempo_entrega_class_Maquinaria').value.trim();
            const descuento = row.querySelector('.descuento_input_Maquinaria_class').value.trim();
            const descuentoAdicional = row.querySelector('.descuento_adicional_input_Maquinaria_class').value.trim();
            const fechaEntrega = row.querySelector('.date_input_entrega_class_Maquinaria').value.trim();
            const rep = row.querySelector('.select_rep_classMaquinaria').value.trim();
            const checkEstado = row.querySelector('.checkBoxk_newFactorMaquinariaClass').checked;
            const factorAdicional = row.querySelector('.factor_adicional_class_Maquinaria').value.trim();



            maquinariaDatos[index].push({
                rowIndex: rowIndex,  // Este es el contador que se incrementa por fila
                cantidad: cantidad,
                unidad: unidad,
                abreviatura: abreviatura,
                referencia: referencia,
                material: material,
                descripcionMaterial: descripcionMaterial,
                proveedor: proveedor,
                nota: nota,
                trm: trm,
                precioLista: precioLista,
                costoUnitarioKamati: costoUnitarioKamati,
                costoTotalKamati: costoTotalKamati,
                valorUtilidad: valorUtilidad,
                valorTotal: valorTotal,
                tiempoEntrega: tiempoEntrega,
                descuento: descuento,
                descuentoAdicional: descuentoAdicional,
                fechaEntrega: fechaEntrega,
                rep: rep,
                checkEstado: checkEstado,
                factorAdicional: factorAdicional
            });
        });
    });




    const actividadesArray = {};  // Este objeto almacenará los turnos y sus actividades

    // Recorremos las tablas de turnos
    const tablasAc = document.querySelectorAll('.tbodyAc_cloned_tbody_ac_trs');
    tablasAc.forEach((tabla) => {
        // Recorremos las filas de los turnos
        const filasMateriales = tabla.querySelectorAll('.tr_new_tbody_turnounique_Class');
        filasMateriales.forEach((row) => {
            const turnoId = row.getAttribute('data-turno-id'); // Obtenemos el ID del turno
            const contenedorId = row.querySelector('.hidden_idIdentificadorActividadeUnique_CLASS')?.value; // Obtenemos el identificador del contenedor

            if (!actividadesArray[turnoId]) {
                actividadesArray[turnoId] = {  // Creamos un objeto para cada turno
                    turno: [],
                    actividades: []
                };
            }

            const starTime = row.querySelector('.starTimeClassActividades').value.trim();
            const endTime = row.querySelector('.endTimeClassActividades').value.trim();
            const tipoTiempo = row.querySelector('.tipoDia-classActividades')?.selectedOptions[0]?.text.trim();

            // Guardamos los datos del turno, asociando con el contenedor
            actividadesArray[turnoId].turno.push({
                contenedorId: contenedorId, // Guardamos el contenedor donde pertenece el turno
                starTime: starTime,
                endTime: endTime,
                tipoTiempo: tipoTiempo
            });
        });

        // Recorremos las filas de actividades que están debajo de los turnos
        const filasActividades = tabla.querySelectorAll('.filaclonableunica_actividades_Class');
        filasActividades.forEach((row) => {
            const turnoId = row.getAttribute('data-turno-id'); // Obtenemos el ID del turno relacionado
            if (!actividadesArray[turnoId]) {
                // Si por alguna razón no existe el turno relacionado, saltamos esta actividad
                return;
            }

            const proveedorInput = row.querySelector('.proveedor_input_classUnique');
            const selectCotizacion = row.querySelector('.select-nombreCotizacionesActividades-Class');

            // Asegúrate de que el select existe y tiene opciones
            const material = selectCotizacion && selectCotizacion.options.length > 0
                ? selectCotizacion.classList.contains('hidden')
                    ? proveedorInput.value.trim() || ""
                    : selectCotizacion.options[selectCotizacion.selectedIndex]?.text.trim() || ""
                : "";  // Si no hay opciones en el select, asigna un valor vacío
            const cantidad = row.querySelector('.cantidad_actividades_unique').value.trim();
            const unidad = row.querySelector('.selectUnidadesActividadesClass').value.trim();
            const abreviaturaSelect = row.querySelector('.abreviaturas_nomClass');

            // Asegúrate de que el select exista y tenga opciones seleccionadas
            const abreviatura = abreviaturaSelect && abreviaturaSelect.selectedIndex >= 0
                ? abreviaturaSelect.options[abreviaturaSelect.selectedIndex]?.text.trim() || ""
                : "";  // Si no hay opciones o no está seleccionado, asigna un valor vacío
            const referencia = row.querySelector('.descripcion_breve_classUnique').value.trim();

            const descripcionMaterial = row.querySelector('.cantidad_persona_class_unique').value.trim();
            const proveedor = row.querySelector('.nota_actividades_unique_class').value.trim();
            const nota = row.querySelector('.costo-externio-unitario-input').value.trim();
            const trm = row.querySelector('.costoAlimentacion_input_actividades_unique_class').value.trim();
            const precioLista = row.querySelector('.class_transporteInput_unique').value.trim();
            const costoUnitarioKamati = row.querySelector('.valor_Dia_kamati-class').value.trim();
            const costoTotalKamati = row.querySelector('.valorDiasKamatiClass').value.trim();
            const valorUtilidad = row.querySelector('.valor-dia-utilidadClass').value.trim();
            const valorTotal = row.querySelector('.valorDiasClienteUtilidadClass').value.trim();
            const tiempoEntrega = row.querySelector('.select_resp_unique_actividades').value.trim();
            const checkEstado = row.querySelector('.check_new_Factor-ClassActividades').checked;
            const factorAdicional = row.querySelector('.input-new-factor-Actividades-class').value.trim();

            // Asociamos las actividades al turno correspondiente
            actividadesArray[turnoId].actividades.push({
                cantidad: cantidad,
                unidad: unidad,
                abreviatura: abreviatura,
                referencia: referencia,
                material: material,
                descripcionMaterial: descripcionMaterial,
                proveedor: proveedor,
                nota: nota,
                trm: trm,
                precioLista: precioLista,
                costoUnitarioKamati: costoUnitarioKamati,
                costoTotalKamati: costoTotalKamati,
                valorUtilidad: valorUtilidad,
                valorTotal: valorTotal,
                tiempoEntrega: tiempoEntrega,
                checkEstado: checkEstado,
                factorAdicional: factorAdicional
            });
        });
    });

    const contenedoresArray = {};

    // Recorremos el objeto `actividadesArray`
    Object.entries(actividadesArray).forEach(([turnoId, data]) => {
        const contenedorId = data.turno[0]?.contenedorId; // Obtenemos el `contenedorId` del primer turno

        if (!contenedorId) return; // Saltamos si no hay `contenedorId`

        // Si el contenedor no existe en `contenedoresArray`, lo inicializamos como un array vacío
        if (!contenedoresArray[contenedorId]) {
            contenedoresArray[contenedorId] = []; // Cada contenedor será un array secuencial de turnos
        }

        // Añadimos el turno completo (incluyendo actividades) al contenedor correspondiente
        contenedoresArray[contenedorId].push({
            turnoId: turnoId,
            turno: data.turno,
            actividades: data.actividades
        });

    });

    // Ahora `contenedoresArray` tendrá índices numéricos secuenciales para cada contenedor
    console.log(contenedoresArray);




    // Aquí es donde se agrega todo el objeto `datos`, incluyendo los datos de las tablas
    const datos = {
        titulo: titulo,
        campo1: campo1,
        campo2: campo2,
        campo3: campo3,
        campo4: campo4,
        txtNombre: txtNombre,
        txtIdentificacion: txtIdentificacion,
        txtIdentificacionUsd: txtIdentificacionUsd,
        txtIdentificacionEur: txtIdentificacionEur,
        factorMoGlobal: factorMoGlobal,
        factorOGlobal: factorOGlobal,
        viaticosGlobal: viaticosGlobal,
        polizaGlobal: polizaGlobal,
        siemensGlobal: siemensGlobal,
        pilzGlobal: pilzGlobal,
        rittalGlobal: rittalGlobal,
        phoenixGlobal: phoenixGlobal,
        viaticos: viaticos,
        cargos: cargos,
        nombreTablaMateriales: nombreTablaMateriales,
        factorMo: factorMo,
        factorO: factorO,
        factorv: factorv,
        factorPo: factorPo,
        factorSm: factorSm,
        factorPilz: factorPilz,
        factorRt: factorRt,
        factorPx: factorPx,
        materialesDatos: materialesDatos,
        totalUnique_kamati,
        totalUnique_cliente,
        nombreTablaMaquinaria,
        factorMoMaquinaria,
        factorOMaquinaria,
        factorvMaquinaria,
        factorPoMaquinaria,
        factorSmMaquinaria,
        factorPilzMaquinaria,
        factorRtMaquinaria,
        factorPxMaquinaria,
        inputFactorIndepedienteMaquinaria,
        inputFactorIndepedienteMateriales,
        maquinariaDatos,
        totalUnique_kamati_maquinaria,
        totalUnique_cliente_maquinaria,
        automatizacion,
        comunicacion,
        maniobrace,
        variadores,
        software,
        repuestos,
        maniobralp,
        otros,
        pilz,
        phoenix,
        rittal,
        viaticosAv,
        mano,
        supervisor,
        ingenieria,
        project,
        siso,
        automatizacionCliente,
        comunicacionCliente,
        maniobraceCliente,
        variadoresCliente,
        softwareCliente,
        repuestosCliente,
        maniobralpCliente,
        otrosCliente,
        pilzCliente,
        phoenixCliente,
        rittalCliente,
        viaticosAvCliente,
        manoCliente,
        supervisorCliente,
        ingenieriaCliente,
        projectCliente,
        sisoCliente,
        automatizacionClientePorcentaje,
        comunicacionClientePorcentaje,
        maniobraceClientePorcentaje,
        variadoresClientePorcentaje,
        softwareClientePorcentaje,
        repuestosClientePorcentaje,
        maniobralpClientePorcentaje,
        otrosClientePorcentaje,
        pilzClientePorcentaje,
        phoenixClientePorcentaje,
        rittalClientePorcentaje,
        viaticosAvClientePorcentaje,
        manoClientePorcentaje,
        supervisorClientePorcentaje,
        ingenieriaClientePorcentaje,
        projectClientePorcentaje,
        sisoClientePorcentaje,
        nombreTablaActividades,
        inputFactorIndepedienteActividades,
        factorMoAc,
        factorOAc,
        factorvAc,
        factorPoAc,
        contenedoresArray,
        totalKamatiAcs,
        totalClienteAcs,
        totalAbKamati,
        totalAbCliente,
        formattedTotal,
        formattedTotal_maquinaria,
        formattedTotalAcs,
        formattedFinalTotal// Aquí es donde agregamos los datos de las filas
    };
    // Fetch para enviar los datos al servidor
    fetch('../phpServerExcel/generateExcel.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(datos) // Convertir los datos en formato JSON
    })
        .then(response => response.blob()) // Recibir el archivo generado como un blob
        .then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'Cotizacion_' + campo4 + '.xlsx';
            link.click();
        })
        .catch(error => {
            console.error('Error al enviar los datos:', error);
        });
}
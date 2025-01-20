export async function insertarFactoresIndependientes(insertId, tablaMateriales) {
    try {
        // Obtener valores de los campos ocultos
        const factorMo = document.getElementById('txt_factor_moHidden').value;
        const factorO = document.getElementById('txt_factor_oHidden').value;
        const poliza = document.getElementById('txt_polizaHidden').value;
        const viaticos = document.getElementById('txt_factor_VHidden').value;

        const response = await fetch('../phpServer/insertDataIFactoresIndependientes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id_factoresIndependientes: insertId,
                factors: [
                    { name: 'FactorMo', value: factorMo },
                    { name: 'FactorO', value: factorO },
                    { name: 'Poliza', value: poliza },
                    { name: 'Viaticos', value: viaticos }
                ]
            }),
        });

        const result = await response.json();

        if (result.success) {
            const factorToClassMap = {
                'FactorMo': 'class_hidden_factorMo_materiales',
                'FactorO': 'class_hidden_factorO_materiales',
                'Poliza': 'class_hidden_factorPo_materiales',
                'Viaticos': 'class_hidden_factorVi_materiales'
            };

            result.results.forEach((insertedId, index) => {
                const factorName = result.factorNames[index];
                const inputClass = factorToClassMap[factorName];
                if (inputClass) {
                    const inputHiddenElements = tablaMateriales.getElementsByClassName(inputClass);
                    if (inputHiddenElements.length > 0) {
                        const inputHidden = inputHiddenElements[0];
                        inputHidden.value = insertedId;
                        inputHidden.id = `${inputHidden.id}_${Date.now()}`;
                    }
                }
            });
        } else {
            console.error('Error en la inserción:', result.message);
        }
    } catch (error) {
        console.error('Error al hacer la solicitud:', error);
    }
}
export async function insertarFactoresIndependientesActividades(insertId, tablaActividades) {
    try {
        // Obtener valores de los campos ocultos
        const factorMoActividades = document.getElementById('factorMoAcHidden').value;
        const factorOActividades = document.getElementById('factorOAcHidden').value;
        const polizaActividades = document.getElementById('polizaAcHidden').value;
        const viaticosActividades = document.getElementById('viaticosAcHidden').value;

        const response = await fetch('../phpServer/insertDataIFactoresIndependientesActividades.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id_factoresIndependientesActividades: insertId,
                factors: [
                    { name: 'FactorMo', value: factorMoActividades },
                    { name: 'FactorO', value: factorOActividades },
                    { name: 'Viaticos', value: viaticosActividades },
                    { name: 'Poliza', value: polizaActividades }
                ]
            }),
        });

        const result = await response.json();

        if (result.success) {
            const factorToClassMap = {
                'FactorMo': 'class_hidden_factorMo_ActividadesUnique',
                'FactorO': 'class_hidden_factorO_ActividadesUnique',
                'Viaticos': 'class_hidden_factorVi_ActividadesUnique',
                'Poliza': 'class_hidden_factorPo_ActividadesUnique'
            };

            result.results.forEach((insertedId, index) => {
                const factorName = result.factorNames[index];
                const inputClass = factorToClassMap[factorName];
                if (inputClass) {
                    const inputHiddenElements = tablaActividades.getElementsByClassName(inputClass);
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
export async function insertarFactoresIndependientesMaquinaria(insertId, tablaMateriales) {
    try {
        // Obtener valores de los campos ocultos
        const factorMoMaquinaria = document.getElementById('txt_factor_moHiddenMaquinaria').value;
        const factorOMaquinaria = document.getElementById('txt_factor_oHiddenMaquinaria').value;
        const polizaMaquinaria = document.getElementById('txt_polizaHiddenMaquinaria').value;
        const viaticosMaquinaria = document.getElementById('txt_factor_VHiddenMaquinaria').value;

        const response = await fetch('../phpServer/insertDataIFactoresIndependientesMaquinaria.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id_factoresIndependientesMaquinaria: insertId,
                factors: [
                    { name: 'FactorMo', value: factorMoMaquinaria },
                    { name: 'FactorO', value: factorOMaquinaria },
                    { name: 'Poliza', value: polizaMaquinaria },
                    { name: 'Viaticos', value: viaticosMaquinaria }
                ]
            }),
        });

        const result = await response.json();

        if (result.success) {
            const factorToClassMap = {
                'FactorMo': 'class_hidden_factorMo_maquinaria',
                'FactorO': 'class_hidden_factorO_maquinaria',
                'Poliza': 'class_hidden_factorPo_maquinaria',
                'Viaticos': 'class_hidden_factorVi_maquinaria'
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
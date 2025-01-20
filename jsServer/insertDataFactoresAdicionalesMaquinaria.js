export async function insertarFactoresAdicionalesMaquinaria(insertId, tablaMateriales) {
    try {
        // Obtener valores de los campos ocultos
        const siemensMaquinaria = document.getElementById('txt_siemensHiddenMaquinaria').value || '1.25';
        const pilzMaquinaria = document.getElementById('txt_pilzHiddenMaquinaria').value || '1.25';
        const rittalMaquinaria = document.getElementById('txt_rittalHiddenMaquinaria').value || '1.25';
        const phxcntMaquinaria = document.getElementById('txt_phoenixHiddenMaquinaria').value || '1.25';

        const response = await fetch('../phpServer/insertDataIFactoresAdicionalesMaquinaria.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id_factoresAdicionalesMaquinaria: insertId,
                factors: [
                    { name: 'Siemens', value: siemensMaquinaria },
                    { name: 'Pilz', value: pilzMaquinaria },
                    { name: 'Rittal', value: rittalMaquinaria },
                    { name: 'Phx Cnt', value: phxcntMaquinaria }
                ]
            }),
        });

        const result = await response.json();

        if (result.success) {
            const factorToClassMap = {
                'Siemens': 'class_hidden_Factor_Simaquinaria',
                'Pilz': 'class_hidden_Factor_Pimaquinaria',
                'Rittal': 'class_hidden_Factor_Rimaquinaria',
                'Phx Cnt': 'class_hidden_Factor_Pcmaquinaria'
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
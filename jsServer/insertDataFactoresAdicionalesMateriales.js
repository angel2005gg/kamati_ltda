export async function insertarFactoresAdicionalesMat(insertId, tablaMateriales) {
    try {
        // Obtener valores de los campos ocultos
        const siemens = document.getElementById('txt_siemensHidden').value || '1.25';
        const pilz = document.getElementById('txt_pilzHidden').value || '1.25';
        const rittal = document.getElementById('txt_rittalHidden').value || '1.25';
        const phxcnt = document.getElementById('txt_phoenixHidden').value || '1.25';

        const response = await fetch('../phpServer/insertDataIFactoresAdicionalesMateriales.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id_factoresAdicionalesMateriales: insertId,
                factors: [
                    { name: 'Siemens', value: siemens },
                    { name: 'Pilz', value: pilz },
                    { name: 'Rittal', value: rittal },
                    { name: 'Phx Cnt', value: phxcnt }
                ]
            }),
        });

        const result = await response.json();

        if (result.success) {
            const factorToClassMap = {
                'Siemens': 'class_hidden_Factor_Simateriales',
                'Pilz': 'class_hidden_Factor_Pimateriales',
                'Rittal': 'class_hidden_Factor_Rimateriales',
                'Phx Cnt': 'class_hidden_Factor_Pcmateriales'
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
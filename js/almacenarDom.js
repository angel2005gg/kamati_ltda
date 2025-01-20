// // Función para guardar el estado del DOM
// function saveDOMState() {
//     let domState = [];
    
//     // Recorremos todos los elementos input, textarea, select y elementos que necesites
//     document.querySelectorAll('input, textarea, select').forEach((element, index) => {
//         let elementState = {
//             tag: element.tagName,
//             type: element.type,
//             id: element.id || index,  // Guardar el ID o usar el índice como identificación
//             classList: [...element.classList],
//             value: element.value,
//             checked: element.checked // Si es un checkbox o radio
//         };
//         domState.push(elementState);  // Añadir al array
//     });

//     // Guardamos el estado en sessionStorage o localStorage
//     localStorage.setItem('domState', JSON.stringify(domState));
// }

// // Función para restaurar el estado del DOM
// function restoreDOMState() {
//     const savedState = localStorage.getItem('domState');

//     if (savedState) {
//         const domState = JSON.parse(savedState);

//         domState.forEach((savedElement) => {
//             let element = document.getElementById(savedElement.id);

//             // Si no hay un ID (por ejemplo en clones), buscar por posición en el DOM
//             if (!element) {
//                 element = document.querySelectorAll(savedElement.tag)[savedElement.index];
//             }

//             if (element) {
//                 // Restaurar el valor
//                 element.value = savedElement.value;

//                 // Si es un checkbox o radio, restaurar el estado de "checked"
//                 if (savedElement.type === 'checkbox' || savedElement.type === 'radio') {
//                     element.checked = savedElement.checked;
//                 }
//             }
//         });
//     }
// }

// // Guardar el estado antes de salir o recargar la página
// window.addEventListener('beforeunload', function() {
//     saveDOMState();
// });

// // Restaurar el estado cuando se cargue la página
// window.onload = function() {
//     restoreDOMState();
// };
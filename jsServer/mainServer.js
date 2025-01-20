// main.js

// Importamos las funciones desde el archivo 'funciones.js'
import { obtenerDatosCotizacion, guardarDatosEnServidor, agregarEventListeners } from './updateSelectCotizaciones.js';
import { obtenerDatosCotizacionFac, updateCotizacionJsdFac, agregarEventListenersFactores } from './updateSelectFactorCotizacion.js';
import { obtenerDatosCotizacionFacAd, updateCotizacionJsdFacAd, agregarEventListenersFactoresAd } from './updateSelectFactorAdicional.js';
import { obtenerDatosViaticos, updateViaticos, agregarEventListenesViaticos } from './updateSelectViaticos.js';
import { obtenerDatosCargos, updateCargos, agregarEventListenesCargos } from './updateSelectCargos.js';
import { fetchAndSaveTableData } from './updateSelectIdentificadorMateriales.js';
import { fetchAndSaveTableDataMaquinaria } from './updateSelectIdentificadorMaquinaria.js';
import { fetchAndSaveTableDataActividades } from './updateSelectIdentificadorActividades.js';

import { updateMaterialesDesdeInput, agregarEventListenersMateriales } from './updateIdentificadorMateriales.js';
import { updateMaterialesDesdeInputMaquinaria, agregarEventListenersMaquinaria }from './updateIdentificadorMaquinaria.js';
import { updateMaterialesDesdeInputActividades, agregarEventListenersActividades }from './updateIdentificadorActividades.js';

import { updateFactoresIndependientesMaterialesDesdeInput, agregarEventListenersFactorMateriales } from './updateFactoresIndependientesMat.js';
import { updateFactoresIndependientesMaquinariaDesdeInput, agregarEventListenersFactorMaquinaria } from './updateFactoresIndependientesMaquinaria.js';
import { updateFactoresIndependientesActividadesDesdeInput, agregarEventListenersFactorActividades } from './updateFactoresIndependientesActividades.js';

import { updateFactoresAdicionalesMaterialesDesdeInput, agregarEventListenersFactorAdicionalesMateriales } from './updateFactoresAdicionalesMat.js';
import { updateFactoresAdicionalesMaquinarias, agregarEventListenersFactorAdicionalesMaq } from './updateFactoresAdicionalesMaq.js';

import { addListenersToRowFields } from './updateFilasTablaMateriales.js';
import { addListenersToRowFieldsMaquinaria } from './updateFilasTablaMaquinaria.js';
import { addListenersToRowFieldsViaticosActividades } from './updateFiasViaticos.js';
import { addListenersToRowFieldsCargosActividades } from './updateFilasCargos.js';
import { addListenersToRowFieldsTurnoActividades } from './updateFilasTurnosActividades.js';
import { addListenersToRowFieldsActividadesNormales } from './updateFilasActividadesNormales.js';



// Hacer que la función 'guardarDatosEnServidor' esté disponible globalmente
window.guardarDatosEnServidor = guardarDatosEnServidor;
window.updateCotizacionJsdFacAd = updateCotizacionJsdFacAd;
window.updateCotizacionJsdFac = updateCotizacionJsdFac;
window.updateCargos = updateCargos;
window.updateViaticos = updateViaticos;
window.updateFactoresAdicionalesMaterialesDesdeInput = updateFactoresAdicionalesMaterialesDesdeInput;
window.updateFactoresAdicionalesMaquinarias = updateFactoresAdicionalesMaquinarias;
window.updateFactoresIndependientesMaterialesDesdeInput = updateFactoresIndependientesMaterialesDesdeInput;
window.updateFactoresIndependientesMaquinariaDesdeInput = updateFactoresIndependientesMaquinariaDesdeInput;
window.updateFactoresIndependientesActividadesDesdeInput = updateFactoresIndependientesActividadesDesdeInput;
window.updateMaterialesDesdeInput = updateMaterialesDesdeInput;
window.updateMaterialesDesdeInputMaquinaria = updateMaterialesDesdeInputMaquinaria;
window.updateMaterialesDesdeInputActividades = updateMaterialesDesdeInputActividades;


// Llamar a las funciones al cargar la página
window.onload = () => {
   obtenerDatosCotizacion();  // Cargar los datos desde el servidor al cargar la página
   obtenerDatosCotizacionFac();  // Cargar los datos desde el servidor al cargar la página
   obtenerDatosCotizacionFacAd();  // Cargar los datos desde el servidor al cargar la página
   obtenerDatosViaticos();  // Cargar los datos desde el servidor al cargar la página
   obtenerDatosCargos();  // Cargar los datos desde el servidor al cargar la página
   fetchAndSaveTableData();
   fetchAndSaveTableDataMaquinaria();
   fetchAndSaveTableDataActividades();
   agregarEventListeners();  // Agregar los event listeners a los campos
   agregarEventListenersFactores();  // Agregar los event listeners a los campos
   agregarEventListenersFactoresAd();  // Agregar los event listeners a los campos
   agregarEventListenesViaticos();  // Agregar los event listeners a los campos
   agregarEventListenesCargos();

   setTimeout(() => {
      agregarEventListenersMateriales(); // Mover aquí si es necesario
      agregarEventListenersMaquinaria(); // Mover aquí si es necesario
      agregarEventListenersActividades(); // Mover aquí si es necesario
      agregarEventListenersFactorMateriales(); // Mover aquí si es necesario
      agregarEventListenersFactorMaquinaria(); // Mover aquí si es necesario
      agregarEventListenersFactorActividades(); // Mover aquí si es necesario
      agregarEventListenersFactorAdicionalesMateriales(); // Mover aquí si es necesario
      agregarEventListenersFactorAdicionalesMaq(); // Mover aquí si es necesario
      addListenersToRowFields(); // Mover aquí si es necesario
      addListenersToRowFieldsMaquinaria(); // Mover aquí si es necesario
      addListenersToRowFieldsViaticosActividades(); // Mover aquí si es necesario
      addListenersToRowFieldsCargosActividades(); // Mover aquí si es necesario
      addListenersToRowFieldsTurnoActividades(); // Mover aquí si es necesario
      addListenersToRowFieldsActividadesNormales(); // Mover aquí si es necesario
   }, 250);

};
// Al cargar la página, restaurar valores desde localStorage
window.onload = function() {
    document.getElementById('nombreProyectoCotizacionId').value = sessionStorage.getItem('nombreProyecto') || '';
    document.getElementById('codigoProyectoCotizacionId').value = sessionStorage.getItem('codigoProyecto') || '';
    document.getElementById('fechaActual').value = sessionStorage.getItem('fechaActual') || '';
    document.getElementById('nombreClienteCotizacionId').value = sessionStorage.getItem('nombreCliente') || '';
    document.getElementById('txt_identificacion_usd').value = sessionStorage.getItem('dolarParaElProyecto') || '';
    document.getElementById('txt_identificacion_eur').value = sessionStorage.getItem('euroParaElProyecto') || '';
    document.getElementById('siemensGlobal').value = sessionStorage.getItem('siemenesProyecto') || '1.0';
    document.getElementById('pilzGlobal').value = sessionStorage.getItem('pilzProyecto') || '1.0';
    document.getElementById('rittalGlobal').value = sessionStorage.getItem('rittalProyecto') || '1.0';
    document.getElementById('phoenixGlobal').value = sessionStorage.getItem('phoenixContactProyecto') || '1.0';
    document.getElementById('factorMoGlobal').value = sessionStorage.getItem('factorMO') || document.getElementById('factorMoGlobal').value;
    document.getElementById('factorOGlobal').value = sessionStorage.getItem('factorO') || document.getElementById('factorOGlobal').value;
    document.getElementById('viaticosGlobal').value = sessionStorage.getItem('factorV') || document.getElementById('viaticosGlobal').value;
    document.getElementById('polizaGlobal').value = sessionStorage.getItem('factorP') || document.getElementById('polizaGlobal').value;

};

// Función para actualizar los valores en sessionStorage cuando se cambien los inputs
function updatesessionStorage() {
    const nombreProyecto = document.getElementById('nombreProyectoCotizacionId').value;
    const codigoProyecto = document.getElementById('codigoProyectoCotizacionId').value;
    const fechaActual = document.getElementById('fechaActual').value;
    const nombreCliente = document.getElementById('nombreClienteCotizacionId').value;
    const dolarCotizacion = document.getElementById('txt_identificacion_usd').value;
    const euroCotizacion = document.getElementById('txt_identificacion_eur').value;
    const siemensGlobalCotizacion = document.getElementById('siemensGlobal').value;
    const pilzGlobalCotizacion = document.getElementById('pilzGlobal').value;
    const rittalGlobalCotizacion = document.getElementById('rittalGlobal').value;
    const phoenixGlobalCotizacion = document.getElementById('phoenixGlobal').value;
    const factorMo = document.getElementById('factorMoGlobal').value;
    const factorO = document.getElementById('factorOGlobal').value;
    const factorV = document.getElementById('viaticosGlobal').value;
    const factorP = document.getElementById('polizaGlobal').value;

    // Guardar los valores en sessionStorage
    sessionStorage.setItem('factorMO', factorMo);
    sessionStorage.setItem('factorO', factorO);
    sessionStorage.setItem('factorV', factorV);
    sessionStorage.setItem('factorP', factorP);
    sessionStorage.setItem('siemenesProyecto', siemensGlobalCotizacion);
    sessionStorage.setItem('pilzProyecto', pilzGlobalCotizacion);
    sessionStorage.setItem('rittalProyecto', rittalGlobalCotizacion);
    sessionStorage.setItem('phoenixContactProyecto', phoenixGlobalCotizacion);
    // Guardar los valores en sessionStorage
    sessionStorage.setItem('nombreProyecto', nombreProyecto);
    sessionStorage.setItem('codigoProyecto', codigoProyecto);
    sessionStorage.setItem('fechaActual', fechaActual);
    sessionStorage.setItem('nombreCliente', nombreCliente);
    sessionStorage.setItem('dolarParaElProyecto', dolarCotizacion);
    sessionStorage.setItem('euroParaElProyecto', euroCotizacion);
    
}

// Agregar event listeners para actualizar sessionStorage cuando los inputs cambien
document.getElementById('nombreProyectoCotizacionId').addEventListener('input', updatesessionStorage);
document.getElementById('codigoProyectoCotizacionId').addEventListener('input', updatesessionStorage);
document.getElementById('fechaActual').addEventListener('input', updatesessionStorage);
document.getElementById('nombreClienteCotizacionId').addEventListener('input', updatesessionStorage);
document.getElementById('txt_identificacion_usd').addEventListener('input', updatesessionStorage);
document.getElementById('txt_identificacion_eur').addEventListener('input', updatesessionStorage);
document.getElementById('siemensGlobal').addEventListener('input', updatesessionStorage);
document.getElementById('pilzGlobal').addEventListener('input', updatesessionStorage);
document.getElementById('rittalGlobal').addEventListener('input', updatesessionStorage);
document.getElementById('phoenixGlobal').addEventListener('input', updatesessionStorage);
document.getElementById('factorMoGlobal').addEventListener('input', updatesessionStorage);
document.getElementById('factorOGlobal').addEventListener('input', updatesessionStorage);
document.getElementById('viaticosGlobal').addEventListener('input', updatesessionStorage);
document.getElementById('polizaGlobal').addEventListener('input', updatesessionStorage);

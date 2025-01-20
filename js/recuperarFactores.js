
    // Recuperar valores desde localStorage y asignarlos a los inputs ocultos
    document.getElementById('hidden_InputFactorGlobalNameMo').value = sessionStorage.getItem('factorGlobalNameMo') || '';
    document.getElementById('hidden_InputFactorGlobalNameO').value = sessionStorage.getItem('factorGlobalNameO') || '';
    document.getElementById('hidden_InputFactorGlobalNameV').value = sessionStorage.getItem('factorGlobalNameV') || '';
    document.getElementById('hidden_InputFactorGlobalNameP').value = sessionStorage.getItem('factorGlobalNameP') || '';
   
    document.getElementById('id_inputHidden_FactoresAdicionalesSiemens').value = sessionStorage.getItem('siemensFactor') || '';
    document.getElementById('id_inputHidden_FactoresAdicionalesPilz').value = sessionStorage.getItem('pilzFactor') || '';
    document.getElementById('id_inputHidden_FactoresAdicionalesRittal').value = sessionStorage.getItem('rittalFactor') || '';
    document.getElementById('id_inputHidden_FactoresAdicionalesPhxContact').value = sessionStorage.getItem('pcntFactor') || '';
    
    document.getElementById('id_hidden_viaticos_0').value = sessionStorage.getItem('idAlimentacion') || '';
    document.getElementById('id_hidden_viaticos_1').value = sessionStorage.getItem('idTransporte') || '';

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('id_hidden_cargo_0').value = sessionStorage.getItem('id_cargoValorCotizacion_0') || '';
        document.getElementById('id_hidden_cargo_1').value = sessionStorage.getItem('id_cargoValorCotizacion_1') || '';
        document.getElementById('id_hidden_cargo_2').value = sessionStorage.getItem('id_cargoValorCotizacion_2') || '';
        document.getElementById('id_hidden_cargo_3').value = sessionStorage.getItem('id_cargoValorCotizacion_3') || '';
        document.getElementById('id_hidden_cargo_4').value = sessionStorage.getItem('id_cargoValorCotizacion_4') || '';
        document.getElementById('id_hidden_cargo_5').value = sessionStorage.getItem('id_cargoValorCotizacion_5') || '';
        document.getElementById('id_hidden_cargo_6').value = sessionStorage.getItem('id_cargoValorCotizacion_6') || '';
        document.getElementById('id_hidden_cargo_7').value = sessionStorage.getItem('id_cargoValorCotizacion_7') || '';
        document.getElementById('id_hidden_cargo_8').value = sessionStorage.getItem('id_cargoValorCotizacion_8') || '';
        document.getElementById('id_hidden_cargo_9').value = sessionStorage.getItem('id_cargoValorCotizacion_9') || '';
    });
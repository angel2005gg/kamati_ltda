export function calculateButtonNa(row) {
    const costoAlimentacion = row.querySelector('#textarea_costo_alt')?.value.replace(/\./g, '') || '0';
    const costoTransporte = row.querySelector('#textarea_costo_trs')?.value.replace(/\./g, '') || '0';
    const hiddenAli = row.querySelector('#hidden_alimentacionId');
    const hiddenTrs = row.querySelector('#hidden_transporteId');
    let costoApl1 = 0;
    let costoTrs1 = 0;
    if (hiddenAli.value === '0') {
        costoApl1 = 0;
    } else {
        costoApl1 = costoAlimentacion;
    }

    if (hiddenTrs.value === '0') {
        costoTrs1 = 0;
    } else {
        costoTrs1 = costoTransporte;
    }

    return {
        costoApl: costoApl1,
        costoTrs: costoTrs1
    }
}
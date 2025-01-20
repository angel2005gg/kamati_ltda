jQuery(document).ready(function($) {
    $('#consulta_filtro_cotizacion_codigo_uniqueId').on('input', function() {
        var codigoCotizacion = $(this).val();

        $.ajax({
            url: '../phpServerFiltroCotizacion/cotizacionFiltroServer.php', // Archivo PHP que maneja la consulta
            type: 'POST',
            data: { codigo_cotizacion: codigoCotizacion },
            dataType: 'json',
            success: function(data) {
                var tableContainer = $('#tableContainer');
                tableContainer.empty();

                if (data.length > 0) {
                    var htmlContent = '<div class="cotizacion-container">';
                    data.forEach(function(cotizacion) {
                        htmlContent += `
                            <div class="cotizacion-card" onclick="setCotizacionID(${cotizacion.id_Cotizacion})">
                                <div class="cotizacion-header">
                                    <h4>ID Cotización: ${cotizacion.id_Cotizacion}</h4>
                                </div>
                                <div class="cotizacion-body">
                                    <p><strong>Nombre Cotización:</strong> ${cotizacion.nombre_cotizacion}</p>
                                    <p><strong>Código Cotización:</strong> ${cotizacion.codigo_cotizacion}</p>
                                    <p><strong>Fecha Creación:</strong> ${cotizacion.fecha_creacion}</p>
                                </div>
                            </div>`;
                    });
                    htmlContent += '</div>';
                    tableContainer.html(htmlContent);
                } else {
                    tableContainer.html('<p>No se encontraron cotizaciones.</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la consulta: ', error);
            }
        });
    });
});
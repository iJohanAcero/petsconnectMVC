// informe.js - Versión mejorada
window.cargarInforme = function () {
    fetch("view/informe/informeView.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;
                
                // Cargar scripts en el orden correcto
                const scripts = [
                    "https://code.jquery.com/jquery-3.7.0.min.js",
                    "https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js",
                    "https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js",
                    // PDF dependencies first
                    "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js",
                    "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js",
                    // Other export dependencies
                    "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js",
                    // Buttons last
                    "https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js",
                    "https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js",
                    "https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js",
                    "https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"
                ];

                // Función para cargar scripts secuencialmente
                const loadScriptsSequentially = async (scripts) => {
                    for (const src of scripts) {
                        await new Promise((resolve, reject) => {
                            // Verificar si el script ya está cargado
                            if (document.querySelector(`script[src="${src}"]`)) {
                                resolve();
                                return;
                            }
                            
                            const script = document.createElement('script');
                            script.src = src;
                            script.onload = resolve;
                            script.onerror = reject;
                            document.body.appendChild(script);
                        });
                    }
                };

                // Cargar scripts y luego inicializar DataTable
                loadScriptsSequentially(scripts)
                    .then(() => {
                        // Pequeña pausa para asegurar que todo esté listo
                        setTimeout(initializeDataTable, 100);
                    })
                    .catch(error => {
                        console.error("Error loading scripts:", error);
                    });
            }
        })
        .catch(error => {
            console.error("⚠ Error al cargar PHP:", error);
        });
};

function initializeDataTable() {
    const buttons = [
        {
            extend: 'copy',
            className: 'btn btn-sm btn-outline-secondary',
            text: '<i class="bi bi-clipboard me-1"></i> Copiar'
        },
        {
            extend: 'csv',
            className: 'btn btn-sm btn-outline-primary',
            text: '<i class="bi bi-file-earmark-text me-1"></i> CSV'
        },
        {
            extend: 'excel',
            className: 'btn btn-sm btn-outline-success',
            text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel'
        },
        {
            extend: 'pdf',
            className: 'btn btn-sm btn-outline-danger',
            text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF'
        },
        {
            extend: 'print',
            className: 'btn btn-sm btn-outline-info',
            text: '<i class="bi bi-printer me-1"></i> Imprimir'
        }
    ];

    // Inicializar DataTable con configuración mejorada
    const table = $('#tablaAdultas').DataTable({
        ajax: {
            url: '/petsconnectMVC/Controller/informe/informeController.php?accion=mascotas_adultas',
            dataSrc: 'data'
        },
        columns: [
            { 
                data: 'id_mascota',
                className: 'text-center fw-bold'
            },
            { 
                data: 'mascota',
                render: function(data) {
                    return '<span class="text-capitalize">' + data + '</span>';
                }
            },
            { 
                data: 'especie',
                render: function(data) {
                    const icon = data.toLowerCase() === 'felino' ? 'bi bi-bug' : 'bi bi-heart';
                    return '<i class="' + icon + ' me-1"></i>' + data;
                }
            },
            { 
                data: 'sexo',
                className: 'text-center',
                render: function(data) {
                    const badgeClass = data.toLowerCase() === 'macho' ? 'bg-primary' : 'bg-pink';
                    return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                }
            },
            { 
                data: 'edad_meses',
                className: 'text-center',
                render: function(data) {
                    return '<span class="badge bg-info">' + data + ' meses</span>';
                }
            },
            { 
                data: 'fundacion',
                render: function(data) {
                    return '<span class="text-capitalize">' + data + '</span>';
                }
            },
            { 
                data: 'estado_adopcion',
                className: 'text-center',
                render: function(data) {
                    const badgeClass = data === 'EN ADOPCION' ? 'bg-success' : 'bg-warning';
                    return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                }
            }
        ],
        dom: "<'row'<'col-md-6'B><'col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: {
            dom: {
                container: {
                    className: 'btn-group btn-group-sm'
                },
                button: {
                    className: 'btn'
                }
            },
            buttons: buttons
        },
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        initComplete: function() {
            // Actualizar contador de mascotas
            const count = this.api().data().count();
            $('#contadorMascotas').text(count);
        },
        drawCallback: function() {
            // Ajustar estilos después de dibujar la tabla
            $('.dt-buttons').addClass('btn-group btn-group-sm');
        }
    });

    const tablePopulares = $('#tablaPopulares').DataTable({
    ajax: {
        url: '/petsconnectMVC/Controller/informe/informeController.php?accion=mascotas_populares',
        dataSrc: 'data'
    },
    columns: [
        { 
            data: 'id_mascota',
            className: 'text-center fw-bold'
        },
        { 
            data: 'mascota',
            render: function(data) {
                return '<span class="text-capitalize fw-semibold">' + data + '</span>';
            }
        },
        { 
            data: 'especie',
            render: function(data) {
                const icon = data.toLowerCase() === 'felino' ? 'bi bi-bug' : 'bi bi-heart';
                return '<i class="' + icon + ' me-1"></i>' + data;
            }
        },
        { 
            data: 'fundacion',
            render: function(data) {
                return '<span class="text-capitalize">' + data + '</span>';
            }
        },
        { 
            data: 'total_solicitudes',
            className: 'text-center',
            render: function(data) {
                // Badge con color según la cantidad de solicitudes
                let badgeClass = 'bg-secondary';
                if (data >= 10) badgeClass = 'bg-danger';
                else if (data >= 5) badgeClass = 'bg-warning';
                else if (data >= 1) badgeClass = 'bg-success';
                
                return '<span class="badge ' + badgeClass + ' fs-6">' + data + ' solicitudes</span>';
            }
        }
    ],
    dom: "<'row'<'col-md-6'B><'col-md-6'f>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    buttons: {
        dom: {
            container: {
                className: 'btn-group btn-group-sm'
            },
            button: {
                className: 'btn'
            }
        },
        buttons: buttons
    },
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    responsive: true,
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    order: [[4, 'desc']], // Ordenar por solicitudes descendente
    initComplete: function() {
        // Actualizar contador de mascotas populares
        const count = this.api().data().count();
        $('#contadorMascotasPopulares').text(count);
        
        // Añadir título personalizado
        this.api().columns().every(function() {
            if (this.index() === 4) {
                const column = this;
                const title = $('#tituloSolicitudes');
                if (title.length) {
                    title.text('Top ' + count + ' Mascotas más Solicitadas');
                }
            }
        });
    },
    drawCallback: function() {
        // Ajustar estilos después de dibujar la tabla
        $('.dt-buttons').addClass('btn-group btn-group-sm');
        
        // Resaltar las mascotas con más solicitudes
        this.api().rows().every(function() {
            const data = this.data();
            if (data.total_solicitudes >= 10) {
                $(this.node()).addClass('table-danger');
            } else if (data.total_solicitudes >= 5) {
                $(this.node()).addClass('table-warning');
            }
        });
    }
});
}
// ===================== INICIALIZACIÓN ===================== //
document.addEventListener("DOMContentLoaded", function () {
    const btnInforme = document.getElementById("btn-cargar-informe");
    if (btnInforme) {
        btnInforme.addEventListener("click", function (e) {
            e.preventDefault();
            cargarInforme();
        });
    }
});

window.cargarInforme = cargarInforme;
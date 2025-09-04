// informe.js - Versión mejorada
window.cargarInformeAdmin = function () {
    fetch("view/informe/informeAdminView.php")
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
    const tableFundaciones = $('#tablaDonacionesFundacion').DataTable({
    ajax: {
        url: '/petsconnectMVC/Controller/informe/informeController.php?accion=donacion_fundacion',
        dataSrc: 'data'
    },
    columns: [
        { 
            data: 'fundacion',
            render: function(data) {
                return '<span class="text-capitalize fw-semibold">' + data + '</span>';
            }
        },
        { 
            data: 'total_donaciones',
            className: 'text-center fw-bold',
            render: function(data) {
                return '<span class="badge bg-primary">' + data + '</span>';
            }
        },
        { 
            data: 'total_recaudado',
            className: 'text-end fw-bold text-success',
            render: function(data) {
                return '$' + new Intl.NumberFormat('es-CO').format(data);
            }
        },
        { 
            data: 'promedio_donacion',
            className: 'text-end text-muted',
            render: function(data) {
                return '$' + new Intl.NumberFormat('es-CO').format(data);
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
        buttons: buttons // usa tu config de botones (csv, excel, pdf, print)
    },
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    responsive: true,
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    initComplete: function() {
        // Contador de fundaciones listadas
        const count = this.api().data().count();
        $('#contadorFundaciones').text(count);
    },
    drawCallback: function() {
        $('.dt-buttons').addClass('btn-group btn-group-sm');
    }
    });


    const tableAdopcionesEspecie = $('#tablaAdopcionesEspecie').DataTable({
    ajax: {
        url: '/petsconnectMVC/Controller/informe/informeController.php?accion=adopcion_especie',
        dataSrc: 'data'
    },
    columns: [
        { 
            data: 'especie',
            render: function(data) {
                return '<span class="text-capitalize fw-semibold">' + data + '</span>';
            }
        },
        { 
            data: 'adopciones_completadas',
            className: 'text-center',
            render: function(data) {
                return '<span class="badge bg-success">' + data + '</span>';
            }
        },
        { 
            data: 'adopciones_pendientes',
            className: 'text-center',
            render: function(data) {
                return '<span class="badge bg-warning">' + data + '</span>';
            }
        },
        { 
            data: 'en_adopcion',
            className: 'text-center',
            render: function(data) {
                return '<span class="badge bg-info">' + data + '</span>';
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
        buttons: buttons // usa tu config de botones (csv, excel, pdf, print)
    },
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    responsive: true,
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    initComplete: function() {
        // Contador de especies listadas
        const count = this.api().data().count();
        $('#contadorAdopcionesEspecie').text(count);
    },
    drawCallback: function() {
        $('.dt-buttons').addClass('btn-group btn-group-sm');
    }
});

const tablePublicacionesFundacion = $('#tablaPublicacionesFundacion').DataTable({
    ajax: {
        url: '/petsconnectMVC/Controller/informe/informeController.php?accion=publicaciones_fundacion',
        dataSrc: 'data'
    },
    columns: [
        { 
            data: 'fundacion',
            render: function(data) {
                return '<span class="fw-semibold text-capitalize">' + data + '</span>';
            }
        },
        { 
            data: 'total_publicaciones',
            className: 'text-center',
            render: function(data) {
                return '<span class="badge bg-primary">' + data + '</span>';
            }
        },
        { 
            data: 'ultima_publicacion',
            className: 'text-center',
            render: function(data) {
                return data === 'Sin publicaciones' 
                    ? '<span class="text-muted fst-italic">' + data + '</span>'
                    : '<span class="badge bg-info">' + data + '</span>';
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
        buttons: buttons // usa tu config de botones (csv, excel, pdf, print)
    },
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
    },
    responsive: true,
    pageLength: 10,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    initComplete: function() {
        // Contador de fundaciones listadas
        const count = this.api().data().count();
        $('#contadorPublicacionesFundacion').text(count);
    },
    drawCallback: function() {
        $('.dt-buttons').addClass('btn-group btn-group-sm');
    }
});




    
}
// ===================== INICIALIZACIÓN ===================== //
document.addEventListener("DOMContentLoaded", function () {
    const btnInforme = document.getElementById("btn-cargar-informeAdmin");
    if (btnInforme) {
        btnInforme.addEventListener("click", function (e) {
            e.preventDefault();
            cargarInformeAdmin();
        });
    }
});

window.cargarInformeAdmin = cargarInformeAdmin;
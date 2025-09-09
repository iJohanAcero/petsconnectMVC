window.cargarDashboardFundacion = function () {
    fetch("view/dashboard/dashboardFundacion.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;
                
                // Inicializar gráficos después de cargar el contenido
                setTimeout(initializeCharts, 100);
            }
        })
        .catch(error => {
            console.error("⚠ Error al cargar PHP:", error);
        });
};

// Función para inicializar gráficos
function initializeCharts() {

    // Gráfico de barras: Mascotas por estado
fetch('/petsconnectMVC/Controller/dashboard/dashboard.php?accion=mascotasPorEstado')
    .then(res => res.json())
    .then(json => {
        const labels = json.data.map(row => row.tipo_estado); // ['ADOPTADO', 'EN ADOPCION', 'EN TRAMITE']
        const values = json.data.map(row => row.total);

        new Chart(document.getElementById('chartMascotasEstado'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Cantidad de Mascotas',
                    data: values,
                    backgroundColor: [
                        '#28a745', // ADOPTADO = verde
                        '#007bff', // EN ADOPCION = azul
                        '#ffc107'  // EN TRAMITE = amarillo
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    })
    .catch(err => console.error("Error cargando mascotas por estado:", err));

//  Gráfico de barras: Donaciones por causa
fetch('/petsconnectMVC/Controller/dashboard/dashboardFundacion.php?accion=donacionesPorCausa')
    .then(res => res.json())
    .then(json => {
        if (!json.success) throw new Error(json.message);

        const labels = json.data.map(row => row.causa);
        const recaudado = json.data.map(row => row.total_recaudado);
        const meta = json.data.map(row => row.meta);

        new Chart(document.getElementById('chartDonacionesCausa'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Meta',
                        data: meta,
                        backgroundColor: '#e0e0e0'
                    },
                    {
                        label: 'Recaudado',
                        data: recaudado,
                        backgroundColor: '#36a2eb'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: { mode: 'index', intersect: false },
                    legend: { position: 'bottom' }
                },
                scales: {
                    x: { stacked: false },
                    y: { beginAtZero: true, stacked: false }
                }
            }
        });
    })
    .catch(err => console.error("Error cargando donaciones por causa:", err));

    // Gráfico de líneas: Publicaciones por mes
    fetch('/petsconnectMVC/Controller/dashboard/dashboardFundacion.php?accion=publicacionesPorMes')
    .then(res => res.json())
    .then(json => {
        if (!json.success) throw new Error(json.message);

        const labels = json.data.map(row => row.mes);
        const values = json.data.map(row => row.total); 

        new Chart(document.getElementById('chartPublicacionesMes'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Publicaciones',
                    data: values,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: '#36a2eb',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: true }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    })
    .catch(err => console.error("Error cargando publicaciones por mes:", err));



// Gráfico de dona: Adopciones por especie
fetch('/petsconnectMVC/Controller/dashboard/dashboardFundacion.php?accion=adopcionesPorEspecie')
    .then(res => res.json())
    .then(json => {
        if (!json.success) throw new Error(json.message);

        const labels = json.data.map(row => row.especie);
        const values = json.data.map(row => row.total_adopciones);

        new Chart(document.getElementById('chartAdopcionesEspecie'), {
            type: 'doughnut',
            data: {
                labels: labels, // ['Canino', 'Felino']
                datasets: [{
                    label: 'Adopciones',
                    data: values,
                    backgroundColor: ['#36a2eb', '#ff6384']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: true }
                }
            }
        });
    })
    .catch(err => console.error("Error cargando adopciones por especie:", err));

    // Gráfico de dona: Causas activas por tipo
    fetch('/petsconnectMVC/Controller/dashboard/dashboardFundacion.php?accion=causasActivasPorTipo')
    .then(res => res.json())
    .then(json => {
        if (!json.success) throw new Error(json.message);

        const labels = json.data.map(row => row.tipo_causa);
        const values = json.data.map(row => row.total_causas);

        new Chart(document.getElementById('chartCausasTipo'), {
            type: 'doughnut',
            data: {
                labels: labels, // ['alimentación', 'esterilización', 'vacunación']
                datasets: [{
                    label: 'Causas Activas',
                    data: values,
                    backgroundColor: ['#ff6384', '#36a2eb', '#ffce56']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: true }
                }
            }
        });
    })
    .catch(err => console.error("Error cargando causas activas por tipo:", err));

    // Gráfico de líneas: Mascotas adoptadas por mes
   fetch('/petsconnectMVC/Controller/dashboard/dashboardFundacion.php?accion=mascotasAdoptadasPorMes')
    .then(res => res.json())
    .then(json => {
        if (!json.success) throw new Error(json.message);

        const labels = json.data.map(row => row.mes);
        const values = json.data.map(row => row.total_adoptadas);

        new Chart(document.getElementById('chartMascotasAdoptadasMes'), {
            type: 'bar',
            data: {
                labels: labels, // ['2025-01', '2025-02', ...]
                datasets: [{
                    label: 'Mascotas adoptadas',
                    data: values,
                    backgroundColor: '#36a2eb',
                    borderColor: '#1e88e5',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    })
    .catch(err => console.error("Error cargando mascotas adoptadas por mes:", err));

    // Gráfico de barras: Donaciones por mes
fetch('/petsconnectMVC/Controller/dashboard/dashboardFundacion.php?accion=donacionesPorMes')
    .then(res => res.json())
    .then(json => {
        if (!json.success) throw new Error(json.message);

        const labels = json.data.map(row => row.mes);
        const values = json.data.map(row => row.total_recaudado);

        new Chart(document.getElementById('chartDonacionesMes'), {
            type: 'bar',
            data: {
                labels: labels, // ['2025-01', '2025-02', ...]
                datasets: [{
                    label: 'Donaciones recibidas (COP)',
                    data: values,
                    backgroundColor: '#4caf50',
                    borderColor: '#2e7d32',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return '$' + ctx.raw.toLocaleString('es-CO');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString('es-CO');
                            }
                        }
                    }
                }
            }
        });
    })
    .catch(err => console.error("Error cargando donaciones por mes:", err));

}

// ===================== INICIALIZACIÓN ===================== //
document.addEventListener("DOMContentLoaded", function () {
    const btnInforme = document.getElementById("btn-cargar-dashboardFundacion");
    if (btnInforme) {
        btnInforme.addEventListener("click", function (e) {
            e.preventDefault();
            cargarDashboardFundacion();
        });
    }
});

window.cargarDashboardFundacion = cargarDashboardFundacion;
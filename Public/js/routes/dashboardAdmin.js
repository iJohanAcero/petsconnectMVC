window.cargarDashboardAdmin = function () {
    fetch("view/dashboard/dashboardAdmin.php")
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
    // Donaciones por mes
    fetch('/petsconnectMVC/Controller/dashboard/dashboardAdminController.php?accion=donacionesPorMes')
        .then(res => res.json())
        .then(json => {
            const labels = json.data.map(row => row.mes);
            const values = json.data.map(row => row.total);
            new Chart(document.getElementById('chartDonacionesMes'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Donaciones (COP)',
                        data: values,
                        backgroundColor: '#28a745'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true },
                        tooltip: { enabled: true }
                    }
                }
            });
        })
        .catch(err => console.error("Error cargando donaciones por mes:", err));
//  Guardianes registrados por mes
        fetch('/petsconnectMVC/Controller/dashboard/dashboardAdminController.php?accion=guardianesPorMes')
    .then(res => res.json())
    .then(json => {
        const labels = json.data.map(row => row.mes);
        const values = json.data.map(row => row.total_guardianes);

        new Chart(document.getElementById('chartGuardianesMes'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nuevos Guardianes por Mes',
                    data: values,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0,123,255,0.3)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    tooltip: { enabled: true }
                }
            }
        });
    })
    .catch(err => console.error("Error cargando guardianes por mes:", err));

    // Mascotas registradas por especie (felinas vs caninas)
    fetch('/petsconnectMVC/Controller/dashboard/dashboardAdminController.php?accion=mascotasFelinasCaninas')
    .then(res => res.json())
    .then(json => {
        const labels = json.data.map(row => row.especie);
        const values = json.data.map(row => row.total);

        new Chart(document.getElementById('chartMascotasEspecie'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Mascotas registradas',
                    data: values,
                    backgroundColor: ['#fdaac4', '#a3bced'], // felino = rosado, canino = azul
                    borderWidth: 1
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
    .catch(err => console.error("Error cargando mascotas por especie:", err));

    // Publicaciones por mes
    fetch('/petsconnectMVC/Controller/dashboard/dashboardAdminController.php?accion=publicacionesPorMes')
    .then(res => res.json())
    .then(json => {
        const labels = json.data.map(row => row.mes);
        const values = json.data.map(row => row.total);

        new Chart(document.getElementById('chartPublicacionesMes'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Publicaciones por mes',
                    data: values,
                    backgroundColor: '#20c997'
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
    .catch(err => console.error("Error cargando publicaciones por mes:", err));

// Mascotas por estado (adoptado, en adopción, en trámite)
    fetch('/petsconnectMVC/Controller/dashboard/dashboardAdminController.php?accion=mascotasPorEstado')
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
                    legend: { display: false }, // no es necesario para barras simples
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

    // Ranking de fundaciones por total recaudado
    fetch('/petsconnectMVC/Controller/dashboard/dashboardAdminController.php?accion=rankingFundaciones')
    .then(res => res.json())
    .then(json => {
        const labels = json.data.map(row => row.fundacion);
        const values = json.data.map(row => row.total_recaudado);

        new Chart(document.getElementById('chartRankingFundaciones'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total recaudado (COP)',
                    data: values,
                    backgroundColor: '#ff6384'
                }]
            },
            options: {
                indexAxis: 'y', // 🔥 barras horizontales
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                return new Intl.NumberFormat('es-CO', {
                                    style: 'currency',
                                    currency: 'COP'
                                }).format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => {
                                return new Intl.NumberFormat('es-CO', {
                                    style: 'currency',
                                    currency: 'COP',
                                    maximumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    }
                }
            }
        });
    })
    .catch(err => console.error("Error cargando ranking de fundaciones:", err));

    // Tipos de causas de adopción
    fetch('/petsconnectMVC/Controller/dashboard/dashboardAdminController.php?accion=tiposCausas')
    .then(res => res.json())
    .then(json => {
        const labels = json.data.map(row => row.tipo_causa);
        const values = json.data.map(row => row.total_causas);

        new Chart(document.getElementById('chartTiposCausas'), {
            type: 'doughnut',
            data: {
                labels: labels, // ["alimentación", "esterilización", "vacunación"]
                datasets: [{
                    label: 'Causas registradas',
                    data: values,
                    backgroundColor: [
                        '#a3bced', // alimentación
                        'hsl(252, 30%, 17%)', // esterilización
                        '#fdaac4'  // vacunación
                    ],
                    borderWidth: 1
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
    .catch(err => console.error("Error cargando tipos de causas:", err));

// Usuarios registrados por tipo (guardian vs fundación)
    fetch('/petsconnectMVC/Controller/dashboard/dashboardAdminController.php?accion=usuariosRegistrados')
    .then(res => res.json())
    .then(json => {
        const labels = json.data.map(row => row.tipo_usuario);
        const values = json.data.map(row => row.total);

        new Chart(document.getElementById('chartUsuariosRegistrados'), {
            type: 'bar',
            data: {
                labels: labels, // ['GUARDIAN', 'FUNDACION']
                datasets: [{
                    label: 'Usuarios registrados',
                    data: values,
                    backgroundColor: ['#fdaac4', 'hsl(252, 30%, 17%)'] // guardian = rosado, fundación = azul
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
    .catch(err => console.error("Error cargando usuarios registrados:", err));
}

// ===================== INICIALIZACIÓN ===================== //
document.addEventListener("DOMContentLoaded", function () {
    const btnInforme = document.getElementById("btn-cargar-dashboardAdmin");
    if (btnInforme) {
        btnInforme.addEventListener("click", function (e) {
            e.preventDefault();
            cargarDashboardAdmin();
        });
    }
});

window.cargarDashboardAdmin = cargarDashboardAdmin;
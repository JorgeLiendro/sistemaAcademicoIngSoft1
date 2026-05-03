<?php require_once 'views/layouts/header.php'; ?>

<style>
    .reporte-header {
        background: linear-gradient(135deg, #3c50e0 0%, #80caee 100%);
        color: white;
        padding: 30px 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .reporte-title {
        font-size: 24px;
        font-weight: 700;
    }

    .btn-volver {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 2px solid white;
        padding: 10px 20px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-volver:hover {
        background: white;
        color: #3c50e0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-left: 4px solid #3c50e0;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #3c50e0;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #888;
        font-weight: 500;
    }

    .carrera-container {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .carrera-title {
        font-size: 20px;
        font-weight: 700;
        color: #1c2434;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .estudiantes-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .estudiante-item {
        padding: 12px;
        border: 1px solid #f0f0f0;
        border-radius: 6px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .estudiante-item:hover {
        background: #f9f9f9;
    }

    .tabla-stats {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .tabla-stats th {
        background: #f8f9fa;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #ddd;
        font-size: 13px;
    }

    .tabla-stats td {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .tabla-stats tr:hover {
        background: #f9f9f9;
    }
</style>

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="reporte-header">
        <div class="reporte-title">
            <i class="fas fa-graduation-cap me-2"></i> Desempeño por Carrera
        </div>
        <a href="index.php?controller=Admin&action=reportes" class="btn-volver">
            <i class="fas fa-chevron-left me-2"></i> Volver
        </a>
    </div>

    <!-- Grid de Estadísticas Generales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= count($reporteData); ?></div>
            <div class="stat-label">Carreras</div>
        </div>
        <div class="stat-card" style="border-left-color: #10b981;">
            <div class="stat-value" style="color: #10b981;">
                <?= array_sum(array_map(function($c) { return $c['total_estudiantes']; }, $reporteData)); ?>
            </div>
            <div class="stat-label">Total Estudiantes</div>
        </div>
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <div class="stat-value" style="color: #f59e0b;">
                <?= number_format(
                    array_sum(array_map(function($c) { return $c['promedio_general']; }, $reporteData)) / count($reporteData),
                    2
                ); ?>
            </div>
            <div class="stat-label">Promedio General</div>
        </div>
    </div>

    <!-- Detalles por Carrera -->
    <?php foreach ($reporteData as $nombre_carrera => $stats): ?>
        <div class="carrera-container">
            <div class="carrera-title"><?= htmlspecialchars($nombre_carrera); ?></div>

            <table class="tabla-stats">
                <tr>
                    <th>Métrica</th>
                    <th>Valor</th>
                </tr>
                <tr>
                    <td>Total Estudiantes</td>
                    <td><strong><?= $stats['total_estudiantes']; ?></strong></td>
                </tr>
                <tr>
                    <td>Promedio General</td>
                    <td>
                        <strong><?= $stats['promedio_general']; ?></strong>
                        <span style="color: #888; font-size: 12px; margin-left: 10px;">
                            <?php if ($stats['promedio_general'] >= 80): ?>
                                <i class="fas fa-check-circle" style="color: #10b981;"></i> Excelente
                            <?php elseif ($stats['promedio_general'] >= 70): ?>
                                <i class="fas fa-check-circle" style="color: #f59e0b;"></i> Bueno
                            <?php else: ?>
                                <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i> Bajo
                            <?php endif; ?>
                        </span>
                    </td>
                </tr>
            </table>

            <?php if (!empty($stats['mejores_estudiantes'])): ?>
                <div style="margin-top: 20px;">
                    <h6 style="font-weight: 700; color: #1c2434; margin-bottom: 12px;">
                        <i class="fas fa-medal me-2" style="color: #f59e0b;"></i> Top 5 Mejores Estudiantes
                    </h6>
                    <ul class="estudiantes-list">
                        <?php foreach ($stats['mejores_estudiantes'] as $index => $est): ?>
                            <li class="estudiante-item">
                                <span>
                                    <strong style="margin-right: 10px; color: #f59e0b;">#<?= $index + 1; ?></strong>
                                    <?= htmlspecialchars($est['nombre_completo']); ?>
                                </span>
                                <span style="background: #f0f7ff; color: #3c50e0; padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 13px;">
                                    <?= number_format($est['promedio'], 2); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- GRÁFICO: Comparación de Promedios por Carrera -->
<div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 30px;">
    <h5 style="font-weight: 700; color: #1c2434; margin-bottom: 20px;">
        <i class="fas fa-chart-bar me-2" style="color: #3c50e0;"></i> Promedio General por Carrera
    </h5>
    <div style="height: 400px; position: relative;">
        <canvas id="chartPromedios"></canvas>
    </div>
</div>

<!-- GRÁFICO: Distribución de Estudiantes -->
<div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 30px;">
    <h5 style="font-weight: 700; color: #1c2434; margin-bottom: 20px;">
        <i class="fas fa-pie-chart me-2" style="color: #10b981;"></i> Distribución de Estudiantes por Carrera
    </h5>
    <div style="height: 400px; position: relative; max-width: 500px;">
        <canvas id="chartEstudiantes"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para gráfico de promedios
    const datosCarreras = <?= json_encode(array_keys($reporteData)); ?>;
    const promedios = <?= json_encode(array_map(function($c) { return $c['promedio_general']; }, $reporteData)); ?>;
    
    const ctxPromedios = document.getElementById('chartPromedios').getContext('2d');
    new Chart(ctxPromedios, {
        type: 'bar',
        data: {
            labels: datosCarreras,
            datasets: [{
                label: 'Promedio General',
                data: promedios,
                backgroundColor: [
                    '#3c50e0',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6',
                    '#ec4899'
                ],
                borderColor: 'rgba(0,0,0,0.1)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: function(value) { return value + ' pts'; } }
                }
            }
        }
    });

    // Datos para gráfico de estudiantes
    const estudiantes = <?= json_encode(array_map(function($c) { return $c['total_estudiantes']; }, $reporteData)); ?>;
    
    const colores = ['#3c50e0', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
    
    const ctxEstudiantes = document.getElementById('chartEstudiantes').getContext('2d');
    new Chart(ctxEstudiantes, {
        type: 'doughnut',
        data: {
            labels: datosCarreras,
            datasets: [{
                data: estudiantes,
                backgroundColor: colores.slice(0, datosCarreras.length),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, font: { size: 12 } }
                }
            }
        }
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>

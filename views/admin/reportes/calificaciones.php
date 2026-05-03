<?php require_once 'views/layouts/header.php'; ?>

<style>
    .reporte-header {
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
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
        color: #ef4444;
    }

    .tabla-reportes {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-collapse: collapse;
    }

    .tabla-reportes th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #e0e0e0;
        font-size: 13px;
        text-transform: uppercase;
    }

    .tabla-reportes td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .tabla-reportes tr:hover {
        background: #f9f9f9;
    }

    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #dcfce7;
        color: #166534;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-info {
        background: #dbeafe;
        color: #0c2340;
    }
</style>

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="reporte-header">
        <div class="reporte-title">
            <i class="fas fa-chart-bar me-2"></i> Entregas y Calificaciones
        </div>
        <a href="index.php?controller=Admin&action=reportes" class="btn-volver">
            <i class="fas fa-chevron-left me-2"></i> Volver
        </a>
    </div>

    <!-- Tabla de Calificaciones -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow-x: auto;">
        <table class="tabla-reportes">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Entregas</th>
                    <th>Promedio</th>
                    <th>Mín - Máx</th>
                    <th>Excelente</th>
                    <th>Bueno</th>
                    <th>Regular</th>
                    <th>Insuficiente</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($calificaciones)): ?>
                    <?php foreach ($calificaciones as $row): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($row['materia']); ?></strong></td>
                            <td><span class="badge badge-info"><?= $row['total_entregas']; ?></span></td>
                            <td>
                                <strong style="color: #ef4444; font-size: 16px;">
                                    <?= number_format($row['promedio'], 2); ?>
                                </strong>
                            </td>
                            <td>
                                <small style="color: #888;">
                                    <?= number_format($row['minima'], 1); ?> - <?= number_format($row['maxima'], 1); ?>
                                </small>
                            </td>
                            <td><span class="badge badge-success"><?= $row['excelente']; ?></span></td>
                            <td><span class="badge badge-warning"><?= $row['bueno']; ?></span></td>
                            <td><span class="badge badge-warning"><?= $row['regular']; ?></span></td>
                            <td><span class="badge badge-danger"><?= $row['insuficiente']; ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px; color: #999;">
                            <i class="fas fa-inbox me-2"></i> No hay registros de calificaciones
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- GRÁFICO: Distribución de Calificaciones -->
<div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 30px;">
    <h5 style="font-weight: 700; color: #1c2434; margin-bottom: 20px;">
        <i class="fas fa-chart-pie me-2" style="color: #ef4444;"></i> Distribución General de Calificaciones
    </h5>
    <div style="height: 350px; position: relative; max-width: 500px;">
        <canvas id="chartDistribucion"></canvas>
    </div>
</div>

<!-- GRÁFICO: Promedio por Materia -->
<div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 30px;">
    <h5 style="font-weight: 700; color: #1c2434; margin-bottom: 20px;">
        <i class="fas fa-chart-bar me-2" style="color: #8b5cf6;"></i> Promedio de Calificaciones por Materia
    </h5>
    <div style="height: 400px; position: relative;">
        <canvas id="chartPromedios"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calificacionesData = <?= json_encode($calificaciones); ?>;
    
    // Sumar totales de distribución
    let totalExcelente = 0, totalBueno = 0, totalRegular = 0, totalInsuficiente = 0;
    const materias = [];
    const promedios = [];
    
    calificacionesData.forEach(row => {
        totalExcelente += parseInt(row.excelente);
        totalBueno += parseInt(row.bueno);
        totalRegular += parseInt(row.regular);
        totalInsuficiente += parseInt(row.insuficiente);
        materias.push(row.materia);
        promedios.push(parseFloat(row.promedio));
    });

    // Gráfico de distribución
    const ctxDistribucion = document.getElementById('chartDistribucion').getContext('2d');
    new Chart(ctxDistribucion, {
        type: 'doughnut',
        data: {
            labels: ['Excelente (90-100)', 'Bueno (80-89)', 'Regular (70-79)', 'Insuficiente (<70)'],
            datasets: [{
                data: [totalExcelente, totalBueno, totalRegular, totalInsuficiente],
                backgroundColor: ['#10b981', '#f59e0b', '#f97316', '#ef4444'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15, font: { size: 12 } } }
            }
        }
    });

    // Gráfico de promedios por materia
    const ctxPromedios = document.getElementById('chartPromedios').getContext('2d');
    new Chart(ctxPromedios, {
        type: 'bar',
        data: {
            labels: materias,
            datasets: [{
                label: 'Promedio',
                data: promedios,
                backgroundColor: promedios.map(p => {
                    if (p >= 90) return '#10b981';
                    if (p >= 80) return '#f59e0b';
                    if (p >= 70) return '#f97316';
                    return '#ef4444';
                }),
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { callback: function(v) { return v + ' pts'; } } }
            }
        }
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>

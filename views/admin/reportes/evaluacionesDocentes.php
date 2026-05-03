<?php require_once 'views/layouts/header.php'; ?>

<style>
    .reporte-header {
        background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
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
        color: #ec4899;
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

    .badge-primary {
        background: #f0f7ff;
        color: #3c50e0;
    }

    .stars {
        color: #f59e0b;
        font-size: 14px;
    }

    .stars-low {
        color: #ef4444;
    }
</style>

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="reporte-header">
        <div class="reporte-title">
            <i class="fas fa-star me-2"></i> Evaluaciones de Docentes
        </div>
        <a href="index.php?controller=Admin&action=reportes" class="btn-volver">
            <i class="fas fa-chevron-left me-2"></i> Volver
        </a>
    </div>

    <!-- Tabla de Evaluaciones -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow-x: auto;">
        <table class="tabla-reportes">
            <thead>
                <tr>
                    <th>Docente</th>
                    <th>Total Evaluaciones</th>
                    <th>Promedio</th>
                    <th>⭐⭐⭐⭐⭐</th>
                    <th>⭐⭐⭐⭐</th>
                    <th>⭐⭐⭐</th>
                    <th>⭐⭐ o menos</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($evaluaciones)): ?>
                    <?php foreach ($evaluaciones as $row): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($row['nombre_completo']); ?></strong></td>
                            <td><span class="badge badge-primary"><?= $row['total_evaluaciones']; ?></span></td>
                            <td>
                                <strong class="stars">
                                    <?= number_format($row['promedio'], 1); ?> 
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <i class="fas fa-star <?= $i < round($row['promedio']) ? '' : 'text-muted'; ?>"></i>
                                    <?php endfor; ?>
                                </strong>
                            </td>
                            <td><?= $row['cinco_estrellas']; ?></td>
                            <td><?= $row['cuatro_estrellas']; ?></td>
                            <td><?= $row['tres_estrellas']; ?></td>
                            <td><span style="color: #ef4444; font-weight: 600;"><?= $row['dos_estrellas_menos']; ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: #999;">
                            <i class="fas fa-inbox me-2"></i> No hay evaluaciones registradas
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- GRÁFICO: Ranking de Docentes -->
<div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 30px;">
    <h5 style="font-weight: 700; color: #1c2434; margin-bottom: 20px;">
        <i class="fas fa-ranking-star me-2" style="color: #f59e0b;"></i> Top Docentes por Evaluación
    </h5>
    <div style="height: 400px; position: relative;">
        <canvas id="chartRanking"></canvas>
    </div>
</div>

<!-- GRÁFICO: Distribución de Calificaciones -->
<div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 30px;">
    <h5 style="font-weight: 700; color: #1c2434; margin-bottom: 20px;">
        <i class="fas fa-chart-pie me-2" style="color: #ec4899;"></i> Distribución General de Evaluaciones
    </h5>
    <div style="height: 350px; position: relative; max-width: 500px;">
        <canvas id="chartDistribucion"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const evaluacionesData = <?= json_encode($evaluaciones); ?>;
    
    // Preparar datos para ranking
    const top5 = evaluacionesData.sort((a, b) => parseFloat(b.promedio) - parseFloat(a.promedio)).slice(0, 5);
    const docentes = top5.map(e => e.nombre_completo);
    const puntajes = top5.map(e => parseFloat(e.promedio));
    
    // Sumar totales de distribución
    let total5 = 0, total4 = 0, total3 = 0, total2 = 0;
    evaluacionesData.forEach(row => {
        total5 += parseInt(row.cinco_estrellas);
        total4 += parseInt(row.cuatro_estrellas);
        total3 += parseInt(row.tres_estrellas);
        total2 += parseInt(row.dos_estrellas_menos);
    });

    // Gráfico de ranking
    const ctxRanking = document.getElementById('chartRanking').getContext('2d');
    new Chart(ctxRanking, {
        type: 'bar',
        data: {
            labels: docentes,
            datasets: [{
                label: 'Puntuación Promedio',
                data: puntajes,
                backgroundColor: '#ec4899',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, max: 5, ticks: { callback: function(v) { return v + ' ⭐'; } } }
            }
        }
    });

    // Gráfico de distribución
    const ctxDistribucion = document.getElementById('chartDistribucion').getContext('2d');
    new Chart(ctxDistribucion, {
        type: 'doughnut',
        data: {
            labels: ['⭐⭐⭐⭐⭐ (5 estrellas)', '⭐⭐⭐⭐ (4 estrellas)', '⭐⭐⭐ (3 estrellas)', '⭐⭐ o menos'],
            datasets: [{
                data: [total5, total4, total3, total2],
                backgroundColor: ['#10b981', '#3c50e0', '#f59e0b', '#ef4444'],
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
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>

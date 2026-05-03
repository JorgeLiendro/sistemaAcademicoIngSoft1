<?php require_once 'views/layouts/header.php'; ?>

<style>
    .reporte-header {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
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
        color: #f59e0b;
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

    .barra-progreso {
        width: 100%;
        height: 24px;
        background: #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
    }

    .barra-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #34d399);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: 600;
    }

    .stat-box {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-presente {
        background: #dcfce7;
        color: #166534;
    }

    .badge-ausente {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-licencia {
        background: #dbeafe;
        color: #0c2340;
    }
</style>

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="reporte-header">
        <div class="reporte-title">
            <i class="fas fa-clipboard-check me-2"></i> Control de Asistencia
        </div>
        <a href="index.php?controller=Admin&action=reportes" class="btn-volver">
            <i class="fas fa-chevron-left me-2"></i> Volver
        </a>
    </div>

    <!-- Tabla de Asistencia -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow-x: auto;">
        <table class="tabla-reportes">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Presentes</th>
                    <th>Ausentes</th>
                    <th>Licencias</th>
                    <th>Retrasos</th>
                    <th>% Asistencia</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($asistencia)): ?>
                    <?php foreach ($asistencia as $row): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($row['materia']); ?></strong></td>
                            <td><span class="badge badge-presente"><?= $row['presentes']; ?></span></td>
                            <td><span class="badge badge-ausente"><?= $row['ausentes']; ?></span></td>
                            <td><span class="badge badge-licencia"><?= $row['licencias']; ?></span></td>
                            <td><span class="badge"><?= $row['retrasos']; ?></span></td>
                            <td>
                                <div class="barra-progreso">
                                    <div class="barra-fill" style="width: <?= $row['porcentaje_asistencia']; ?>%;">
                                        <?= $row['porcentaje_asistencia']; ?>%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
                            <i class="fas fa-inbox me-2"></i> No hay registros de asistencia
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- GRÁFICO: Asistencia General -->
<div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 30px;">
    <h5 style="font-weight: 700; color: #1c2434; margin-bottom: 20px;">
        <i class="fas fa-chart-pie me-2" style="color: #f59e0b;"></i> Resumen General de Asistencia
    </h5>
    <div style="height: 350px; position: relative; max-width: 500px;">
        <canvas id="chartAsistenciaGeneral"></canvas>
    </div>
</div>

<!-- GRÁFICO: Asistencia por Materia -->
<div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 30px;">
    <h5 style="font-weight: 700; color: #1c2434; margin-bottom: 20px;">
        <i class="fas fa-chart-line me-2" style="color: #10b981;"></i> % Asistencia por Materia
    </h5>
    <div style="height: 400px; position: relative;">
        <canvas id="chartAsistenciaMateria"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sumar totales de asistencia
    let totalPresentes = 0, totalAusentes = 0, totalLicencias = 0, totalRetrasos = 0;
    const asistenciaData = <?= json_encode($asistencia); ?>;
    const materiasNombres = [];
    const porcentajesAsistencia = [];

    asistenciaData.forEach(row => {
        totalPresentes += parseInt(row.presentes);
        totalAusentes += parseInt(row.ausentes);
        totalLicencias += parseInt(row.licencias);
        totalRetrasos += parseInt(row.retrasos);
        materiasNombres.push(row.materia);
        porcentajesAsistencia.push(parseFloat(row.porcentaje_asistencia));
    });

    // Gráfico de torta general
    const ctxGeneral = document.getElementById('chartAsistenciaGeneral').getContext('2d');
    new Chart(ctxGeneral, {
        type: 'doughnut',
        data: {
            labels: ['Presentes', 'Ausentes', 'Licencias', 'Retrasos'],
            datasets: [{
                data: [totalPresentes, totalAusentes, totalLicencias, totalRetrasos],
                backgroundColor: ['#10b981', '#ef4444', '#3c50e0', '#f59e0b'],
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

    // Gráfico de línea por materia
    const ctxMateria = document.getElementById('chartAsistenciaMateria').getContext('2d');
    new Chart(ctxMateria, {
        type: 'line',
        data: {
            labels: materiasNombres,
            datasets: [{
                label: '% Asistencia',
                data: porcentajesAsistencia,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { callback: function(v) { return v + '%'; } } }
            },
            plugins: { legend: { labels: { usePointStyle: true, padding: 15 } } }
        }
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>

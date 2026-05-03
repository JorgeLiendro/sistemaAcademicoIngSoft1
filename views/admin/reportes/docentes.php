<?php require_once 'views/layouts/header.php'; ?>

<style>
    .reporte-header {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
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
        color: #10b981;
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

    .badge-success {
        background: #dcfce7;
        color: #166534;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }
</style>

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="reporte-header">
        <div class="reporte-title">
            <i class="fas fa-chalkboard-user me-2"></i> Gestión de Docentes
        </div>
        <a href="index.php?controller=Admin&action=reportes" class="btn-volver">
            <i class="fas fa-chevron-left me-2"></i> Volver
        </a>
    </div>

    <!-- Tabla de Docentes -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <table class="tabla-reportes">
            <thead>
                <tr>
                    <th>Docente</th>
                    <th>Materias</th>
                    <th>Estudiantes</th>
                    <th>Evaluación Promedio</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($docentes)): ?>
                    <?php foreach ($docentes as $doc): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($doc['nombre_completo']); ?></strong>
                            </td>
                            <td>
                                <span class="badge badge-primary"><?= $doc['materias']; ?></span>
                            </td>
                            <td>
                                <span class="badge badge-success"><?= $doc['estudiantes_total'] ?? 0; ?></span>
                            </td>
                            <td>
                                <?php if (!empty($doc['promedio_evaluacion'])): ?>
                                    <strong style="color: #f59e0b;"><?= number_format($doc['promedio_evaluacion'], 1); ?> ⭐</strong>
                                <?php else: ?>
                                    <span style="color: #ccc;">Sin evaluaciones</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 30px; color: #999;">
                            <i class="fas fa-inbox me-2"></i> No hay docentes registrados
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

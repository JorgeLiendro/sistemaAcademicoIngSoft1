<?php require_once 'views/layouts/header.php'; ?>

<style>
    .reporte-header {
        background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
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
        color: #8b5cf6;
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
</style>

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="reporte-header">
        <div class="reporte-title">
            <i class="fas fa-users me-2"></i> Inscripciones
        </div>
        <a href="index.php?controller=Admin&action=reportes" class="btn-volver">
            <i class="fas fa-chevron-left me-2"></i> Volver
        </a>
    </div>

    <!-- Tabla de Inscripciones -->
    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); overflow-x: auto;">
        <table class="tabla-reportes">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Inscritos</th>
                    <th>Activos</th>
                    <th>Retirados</th>
                    <th>Docente</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($inscripciones)): ?>
                    <?php foreach ($inscripciones as $row): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($row['materia']); ?></strong></td>
                            <td><span class="badge badge-primary"><?= $row['estudiantes_inscritos']; ?></span></td>
                            <td><span class="badge badge-success"><?= $row['activos']; ?></span></td>
                            <td><span class="badge"><?= $row['retirados']; ?></span></td>
                            <td><?= htmlspecialchars($row['docente'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px; color: #999;">
                            <i class="fas fa-inbox me-2"></i> No hay inscripciones registradas
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

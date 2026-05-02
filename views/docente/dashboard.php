<?php require_once 'views/layouts/header.php'; ?>

<style>
    .card-stat {
        background: #fff;
        padding: 25px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .icon-box {
        width: 45px;
        height: 45px;
        background: #eff4fb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3c50e0;
        font-size: 20px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1c2434;
        margin-bottom: 5px;
    }

    .stat-title {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }

    .stat-trend {
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .trend-up { color: #10b981; }

    .subject-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: 0.3s;
        height: 100%;
        overflow: hidden;
    }

    .subject-card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .subject-image {
        height: 160px;
        background: linear-gradient(135deg, #3c50e0 0%, #80caee 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }

    .subject-content {
        padding: 25px;
    }

    .subject-title {
        font-size: 18px;
        font-weight: 700;
        color: #1c2434;
        margin-bottom: 15px;
    }

    .subject-info {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #64748b;
    }

    .btn-manage {
        background: #3c50e0;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 14px;
        width: 100%;
        transition: 0.3s;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .btn-manage:hover {
        background: #2a3bb7;
        color: white;
    }
</style>

<!-- FILA DE ESTADÍSTICAS DOCENTE -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card-stat">
            <div>
                <div class="stat-value"><?php echo count($materias); ?></div>
                <div class="stat-title">Materias Asignadas</div>
                <div class="stat-trend trend-up mt-2">
                    Activas <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="icon-box">
                <i class="fas fa-book"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-stat">
            <div>
                <?php
                    $total_estudiantes = 0;
                    foreach($materias as $m) $total_estudiantes += ($m['estudiantes_inscritos'] ?? 0);
                ?>
                <div class="stat-value"><?php echo $total_estudiantes; ?></div>
                <div class="stat-title">Total Estudiantes</div>
                <div class="stat-trend trend-up mt-2">
                    En curso <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <div class="icon-box">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-stat">
            <div>
                <div class="stat-value">85%</div>
                <div class="stat-title">Progreso Semestral</div>
                <div class="stat-trend trend-up mt-2">
                    Semana <?php echo date('W'); ?> <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div class="icon-box">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
</div>

<h4 class="mb-4 fw-bold text-dark">Mis Materias Asignadas</h4>

<div class="row g-4">
    <?php if (count($materias) > 0): ?>
        <?php foreach ($materias as $materia): ?>
            <div class="col-lg-4 col-md-6">
                <div class="subject-card">
                    <div class="subject-image">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="subject-content">
                        <div class="subject-title"><?= htmlspecialchars($materia['nombre']); ?></div>
                        <div class="subject-info">
                            <span><i class="fas fa-users me-1"></i> <?= $materia['estudiantes_inscritos']; ?> Alumnos</span>
                            <span><i class="fas fa-hashtag me-1"></i> ID: <?= $materia['id_materia']; ?></span>
                        </div>
                        <a href="index.php?controller=Docente&action=gestionTareas&id_materia=<?= $materia['id_materia']; ?>" class="btn-manage">
                            <i class="fas fa-cog me-2"></i> Gestionar Clase
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="bg-white p-5 text-center border rounded">
                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                <p class="text-muted">No tienes materias asignadas actualmente.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
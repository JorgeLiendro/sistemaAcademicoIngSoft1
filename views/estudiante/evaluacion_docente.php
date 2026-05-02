<?php require_once 'views/layouts/header.php'; ?>

<style>
    .eval-container {
        padding: 40px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    }

    .eval-header {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border-left: 6px solid var(--stadum-red);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .eval-card {
        border: 1px solid #e1e5eb;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        transition: 0.3s;
        background: #f8f9fc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .eval-card:hover {
        border-color: var(--stadum-red);
        background: white;
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }

    .eval-info h5 {
        font-weight: 700;
        color: var(--stadum-dark);
        margin-bottom: 8px;
    }

    .eval-info p {
        margin-bottom: 0;
        color: #666;
        font-size: 0.95rem;
    }

    .docente-tag {
        color: var(--stadum-red);
        font-weight: 600;
    }

    .btn-eval {
        background: var(--stadum-dark);
        color: white;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: 0.3s;
        border: none;
    }

    .btn-eval:hover {
        background: var(--stadum-red);
        color: white;
        transform: scale(1.05);
    }

    .empty-eval {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-eval i {
        font-size: 4rem;
        color: #eee;
        margin-bottom: 20px;
    }
</style>

<div class="eval-view animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="eval-header">
        <div>
            <h2 class="serif fw-bold mb-1">Evaluación de Desempeño Docente</h2>
            <p class="text-muted mb-0">Su opinión es vital para mantener la excelencia académica. Califique a sus docentes actuales.</p>
        </div>
        <a href="index.php?controller=Estudiante&action=dashboard" class="btn btn-outline-secondary rounded-pill px-4 fw-bold small">
            <i class="fas fa-chevron-left me-2"></i> Volver
        </a>
    </div>

    <!-- Contenido -->
    <div class="eval-container">
        <?php if (count($materias) > 0): ?>
            <div class="row">
                <?php foreach ($materias as $materia): ?>
                    <div class="col-12">
                        <div class="eval-card">
                            <div class="eval-info">
                                <h5><?= htmlspecialchars($materia['nombre']); ?></h5>
                                <p>
                                    <i class="fas fa-chalkboard-teacher me-2"></i> 
                                    Docente: <span class="docente-tag"><?= htmlspecialchars($materia['docente']); ?></span>
                                </p>
                                <div class="mt-2 small text-muted">
                                    <span class="me-3"><i class="fas fa-layer-group me-1"></i> <?= $materia['nivel_semestre'] ?? 'N/A'; ?>º Semestre</span>
                                    <span><i class="fas fa-users me-1"></i> Grupo <?= htmlspecialchars($materia['grupo'] ?? 'A'); ?></span>
                                </div>
                            </div>
                            <div>
                                <a href="#" class="btn-eval">
                                    <i class="fas fa-star me-2"></i> Evaluar Docente
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-eval">
                <i class="fas fa-user-clock"></i>
                <h4 class="fw-bold">No tiene materias inscritas para evaluar</h4>
                <p class="text-muted">Una vez que se inscriba en materias, podrá evaluar a sus docentes aquí.</p>
                <a href="index.php?controller=Estudiante&action=inscribirMateria" class="btn btn-dark rounded-pill px-5 mt-3">Inscribir Materias Ahora</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

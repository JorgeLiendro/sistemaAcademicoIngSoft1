<?php require_once 'views/layouts/header.php'; ?>

<style>
    .subject-header-banner {
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

    .card-pro {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
        margin-bottom: 30px;
        height: 100%;
    }

    .card-header-pro {
        padding: 20px 25px;
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .bg-pro-dark { background: linear-gradient(135deg, var(--stadum-dark) 0%, var(--stadum-blue) 100%); }
    .bg-pro-red { background: linear-gradient(135deg, #8e142f 0%, var(--stadum-red) 100%); }
    .bg-pro-info { background: linear-gradient(135deg, #0dcaf0 0%, #0aa2bd 100%); }
    .bg-pro-success { background: linear-gradient(135deg, #198754 0%, #146c43 100%); }

    .form-label-pro {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }

    .form-control-pro {
        border-radius: 10px;
        padding: 12px 20px;
        border: 1px solid #e1e5eb;
        font-size: 0.95rem;
        background-color: #f8f9fc;
        transition: 0.3s;
    }

    .form-control-pro:focus {
        background-color: #fff;
        border-color: var(--stadum-red);
        box-shadow: 0 0 0 0.25rem rgba(211, 26, 67, 0.1);
        outline: none;
    }

    .btn-action-pro {
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border: none;
        transition: 0.3s;
        width: 100%;
    }

    .material-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: 0.3s;
    }

    .material-item:hover {
        background-color: #f8f9fc;
    }

    .material-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 15px;
    }

    .bg-light-red { background: rgba(211, 26, 67, 0.1); color: var(--stadum-red); }
</style>

<div class="docente-management-view">
    <!-- Banner de Materia -->
    <div class="subject-header-banner animate__animated animate__fadeIn">
        <div>
            <h2 class="serif fw-bold mb-1 text-dark"><?= htmlspecialchars($materia['nombre']); ?></h2>
            <p class="text-muted mb-0"><i class="fas fa-chalkboard-teacher me-2 text-danger"></i> Panel de Gestión de Contenidos</p>
        </div>
        <a href="index.php?controller=Docente&action=dashboard" class="btn btn-outline-secondary rounded-pill px-4 fw-bold small">
            <i class="fas fa-chevron-left me-2"></i> Volver al Dashboard
        </a>
    </div>

    <!-- Alertas -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 py-3">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['mensaje']; ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <!-- SECCIÓN: SUBIR MATERIAL -->
        <div class="col-lg-6">
            <div class="card-pro">
                <div class="card-header-pro bg-pro-info">
                    <i class="fas fa-cloud-upload-alt fa-lg"></i>
                    <h5 class="mb-0 fw-bold">Subir Material de Estudio</h5>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?controller=Docente&action=subirMaterial" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_materia" value="<?= $materia['id_materia']; ?>">
                        <div class="mb-4">
                            <label class="form-label-pro">Título del Documento</label>
                            <input type="text" name="titulo" class="form-control form-control-pro" placeholder="Ej: Guía de Estudio - Unidad 1" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label-pro">Archivo (PDF, Word, PPT)</label>
                            <input type="file" name="archivo" class="form-control form-control-pro" accept=".pdf,.doc,.docx,.ppt,.pptx" required>
                            <small class="text-muted mt-2 d-block">Formatos permitidos: PDF, Word, PowerPoint (Máx 10MB).</small>
                        </div>
                        <button type="submit" class="btn btn-action-pro shadow-sm" style="background: #0dcaf0; color: white;">
                            <i class="fas fa-upload me-2"></i> Publicar Material
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: CREAR TAREA -->
        <div class="col-lg-6">
            <div class="card-pro">
                <div class="card-header-pro bg-pro-red">
                    <i class="fas fa-tasks fa-lg"></i>
                    <h5 class="mb-0 fw-bold">Asignar Nueva Tarea</h5>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?controller=Docente&action=crearTarea" method="POST">
                        <input type="hidden" name="id_materia" value="<?= $materia['id_materia']; ?>">
                        <div class="mb-3">
                            <label class="form-label-pro">Nombre de la Actividad</label>
                            <input type="text" name="titulo" class="form-control form-control-pro" placeholder="Ej: Investigación de Campo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-pro">Instrucciones</label>
                            <textarea name="descripcion" class="form-control form-control-pro" rows="3" placeholder="Indique los requisitos..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label-pro">Fecha Límite</label>
                            <input type="date" name="fecha_entrega" class="form-control form-control-pro" required>
                        </div>
                        <button type="submit" class="btn btn-action-pro shadow-sm" style="background: var(--stadum-red); color: white;">
                            <i class="fas fa-plus-circle me-2"></i> Crear Tarea para la Clase
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: MATERIALES DISPONIBLES -->
        <div class="col-lg-6">
            <div class="card-pro">
                <div class="card-header-pro bg-pro-dark">
                    <i class="fas fa-book-open fa-lg"></i>
                    <h5 class="mb-0 fw-bold">Recursos Publicados</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (count($materiales) > 0): ?>
                        <div class="materials-list">
                            <?php foreach ($materiales as $material): ?>
                                <div class="material-item">
                                    <div class="d-flex align-items-center">
                                        <div class="material-icon-box bg-light-red">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($material['titulo']); ?></h6>
                                            <small class="text-muted">Documento disponible</small>
                                        </div>
                                    </div>
                                    <a href="uploads/<?= htmlspecialchars($material['archivo']); ?>" target="_blank" class="btn btn-sm btn-dark rounded-pill px-3">
                                        <i class="fas fa-download me-1"></i> Bajar
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">No hay materiales publicados.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SECCIÓN: TAREAS ASIGNADAS -->
        <div class="col-lg-6">
            <div class="card-pro">
                <div class="card-header-pro bg-pro-success">
                    <i class="fas fa-clipboard-check fa-lg"></i>
                    <h5 class="mb-0 fw-bold">Tareas Activas</h5>
                </div>
                <div class="card-body p-4">
                    <?php if (count($tareas) > 0): ?>
                        <div class="accordion accordion-flush" id="tareasAccordion">
                            <?php foreach ($tareas as $index => $tarea): ?>
                                <div class="accordion-item border mb-3 rounded-3 overflow-hidden">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index; ?>">
                                            <i class="fas fa-tasks me-2 text-success"></i>
                                            <?= htmlspecialchars($tarea['titulo']); ?>
                                            <span class="ms-auto badge bg-light text-muted small border">
                                                Vence: <?= date('d/m/Y', strtotime($tarea['fecha_entrega'])); ?>
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $index; ?>" class="accordion-collapse collapse" data-bs-parent="#tareasAccordion">
                                        <div class="accordion-body text-muted small">
                                            <?= nl2br(htmlspecialchars($tarea['descripcion'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">No hay tareas programadas.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
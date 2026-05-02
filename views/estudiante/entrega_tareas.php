<?php require_once 'views/layouts/header.php'; ?>

<style>
    .section-header-banner {
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
    .bg-pro-blue { background: linear-gradient(135deg, #0b1c39 0%, #4e73df 100%); }

    .custom-table-pro thead {
        background: #f8f9fa;
        border-bottom: 2px solid #eee;
    }

    .custom-table-pro th {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #888;
        padding: 15px 20px;
        border: none;
    }

    .custom-table-pro td {
        padding: 20px;
        vertical-align: middle;
        border-color: #f8f9fa;
    }

    .task-title {
        font-weight: 700;
        color: var(--stadum-dark);
        margin-bottom: 2px;
        display: block;
    }

    .materia-badge {
        font-size: 11px;
        font-weight: 600;
        color: var(--stadum-red);
        background: #fdf0f2;
        padding: 4px 10px;
        border-radius: 5px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .btn-action-pro {
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        transition: 0.3s;
    }

    .modal-content-pro {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }

    .modal-header-pro {
        background: var(--stadum-dark);
        color: white;
        padding: 25px 30px;
        border: none;
    }

    .form-control-pro {
        border-radius: 10px;
        padding: 12px 20px;
        border: 1px solid #e1e5eb;
        background-color: #f8f9fc;
    }

    .score-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
    }

    .bg-score-high { background: #e6fffa; color: #008672; }
    .bg-score-mid { background: #fffaf0; color: #b7791f; }
    .bg-score-low { background: #fff5f5; color: #c53030; }
</style>

<div class="estudiante-tasks-view animate__animated animate__fadeIn">
    <!-- Header de Sección -->
    <div class="section-header-banner">
        <div>
            <h2 class="serif fw-bold mb-1 text-dark">Centro de Actividades</h2>
            <p class="text-muted mb-0"><i class="fas fa-tasks me-2 text-danger"></i> Seguimiento de tareas pendientes y calificaciones recibidas.</p>
        </div>
        <a href="index.php?controller=Estudiante&action=dashboard" class="btn btn-outline-secondary rounded-pill px-4 fw-bold small">
            <i class="fas fa-chevron-left me-2"></i> Volver al Panel
        </a>
    </div>

    <!-- Alertas -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 py-3">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 py-3">
            <i class="fas fa-exclamation-triangle me-2"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- TAREAS PENDIENTES -->
    <div class="card-pro">
        <div class="card-header-pro bg-pro-dark">
            <i class="fas fa-clock fa-lg"></i>
            <h5 class="mb-0 fw-bold">Tareas Pendientes y por Entregar</h5>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($tareas_pendientes)): ?>
                <div class="table-responsive">
                    <table class="table custom-table-pro table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Actividad / Materia</th>
                                <th>Fecha Límite</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tareas_pendientes as $tarea): ?>
                                <tr>
                                    <td>
                                        <span class="task-title"><?= htmlspecialchars($tarea['titulo']); ?></span>
                                        <span class="materia-badge"><?= htmlspecialchars($tarea['materia']); ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark fw-bold small"><?= date('d M, Y', strtotime($tarea['fecha_entrega'])); ?></span>
                                            <span class="text-muted smaller"><?= date('H:i', strtotime($tarea['fecha_entrega'])); ?></span>
                                            <?php if (strtotime($tarea['fecha_entrega']) < time()): ?>
                                                <span class="text-danger fw-bold" style="font-size: 10px;">VENCIDA</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($tarea['entregada']): ?>
                                            <span class="status-badge bg-success bg-opacity-10 text-success">
                                                <i class="fas fa-check-double me-1"></i> Entregada
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge bg-warning bg-opacity-10 text-warning">
                                                <i class="fas fa-hourglass-half me-1"></i> Pendiente
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <?php if (!empty($tarea['archivo_tarea'])): ?>
                                                <a href="uploads/<?= htmlspecialchars($tarea['archivo_tarea']); ?>" class="btn btn-sm btn-outline-dark rounded-circle" title="Descargar Guía">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            <?php endif; ?>
                                            <button class="btn btn-action-pro <?= $tarea['entregada'] ? 'btn-dark' : 'btn-red'; ?>" data-bs-toggle="modal" data-bs-target="#modalEntrega<?= $tarea['id_tarea']; ?>">
                                                <?= $tarea['entregada'] ? 'Revisar Envío' : 'Entregar Tarea'; ?>
                                            </button>
                                        </div>

                                        <!-- Modal de Entrega Rediseñado -->
                                        <div class="modal fade" id="modalEntrega<?= $tarea['id_tarea']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content modal-content-pro shadow-lg">
                                                    <div class="modal-header modal-header-pro">
                                                        <h5 class="modal-title serif fw-bold">Envío de Actividad</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="index.php?controller=Estudiante&action=entregarTarea" method="post" enctype="multipart/form-data">
                                                        <div class="modal-body p-4">
                                                            <input type="hidden" name="id_tarea" value="<?= $tarea['id_tarea']; ?>">
                                                            <div class="text-center mb-4">
                                                                <div class="bg-light p-3 rounded-3 border">
                                                                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($tarea['titulo']); ?></h6>
                                                                    <p class="small text-muted mb-0"><?= htmlspecialchars($tarea['materia']); ?></p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-4 text-start">
                                                                <label class="form-label-pro">Seleccionar Archivo</label>
                                                                <input type="file" name="archivo" class="form-control form-control-pro" accept=".pdf,.doc,.docx,.zip,.rar" required>
                                                                <small class="text-muted mt-2 d-block">Formatos: PDF, Word, ZIP (Máx 10MB)</small>
                                                                <?php if ($tarea['entregada'] && !empty($tarea['archivo_entrega'])): ?>
                                                                    <div class="alert alert-info mt-3 py-2 small">
                                                                        <i class="fas fa-file-alt me-2"></i> Actual: <?= basename($tarea['archivo_entrega']); ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            
                                                            <div class="mb-0 text-start">
                                                                <label class="form-label-pro">Comentarios Adicionales</label>
                                                                <textarea name="observaciones" class="form-control form-control-pro" rows="3" placeholder="Opcional..."><?= $tarea['observaciones_entrega'] ?? ''; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light border-0">
                                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-red rounded-pill px-4 fw-bold">PROCESAR ENTREGA</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">¡Excelente! No tienes tareas pendientes por el momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- TAREAS CALIFICADAS -->
    <div class="card-pro">
        <div class="card-header-pro bg-pro-red">
            <i class="fas fa-award fa-lg"></i>
            <h5 class="mb-0 fw-bold">Calificaciones y Retroalimentación</h5>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($tareas_calificadas)): ?>
                <div class="table-responsive">
                    <table class="table custom-table-pro align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Actividad</th>
                                <th>Calificación</th>
                                <th>Comentarios del Docente</th>
                                <th class="text-end">Recursos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tareas_calificadas as $tarea): ?>
                                <tr>
                                    <td>
                                        <span class="task-title"><?= htmlspecialchars($tarea['titulo']); ?></span>
                                        <span class="materia-badge"><?= htmlspecialchars($tarea['materia']); ?></span>
                                    </td>
                                    <td>
                                        <?php 
                                            $grade = $tarea['calificacion'];
                                            $gClass = ($grade >= 85) ? 'high' : (($grade >= 60) ? 'mid' : 'low');
                                        ?>
                                        <div class="score-circle bg-score-<?= $gClass; ?>">
                                            <?= $grade; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="bg-light p-2 rounded-3 small text-muted" style="min-width: 200px;">
                                            <i class="fas fa-quote-left me-2 text-danger opacity-25"></i>
                                            <?= !empty($tarea['retroalimentacion']) ? htmlspecialchars($tarea['retroalimentacion']) : 'Sin comentarios registrados.'; ?>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <?php if (!empty($tarea['archivo_entrega'])): ?>
                                            <a href="uploads/<?= htmlspecialchars($tarea['archivo_entrega']); ?>" target="_blank" class="btn btn-sm btn-dark rounded-pill px-3">
                                                <i class="fas fa-eye me-1"></i> Mi Trabajo
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-star-half-alt fa-3x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">Aún no tienes tareas calificadas.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
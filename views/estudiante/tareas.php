<?php require_once 'views/layouts/header.php'; ?>

<style>
    .subject-banner {
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

    .nav-pills-custom {
        background: white;
        padding: 8px;
        border-radius: 50px;
        display: inline-flex;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 35px;
    }

    .nav-pills-custom .nav-link {
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        font-size: 0.85rem;
        transition: 0.3s;
    }

    .nav-pills-custom .nav-link.active {
        background-color: var(--stadum-red);
        color: white;
        box-shadow: 0 4px 12px rgba(211, 26, 67, 0.3);
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
    .bg-pro-info { background: linear-gradient(135deg, #0dcaf0 0%, #0aa2bd 100%); }

    .resource-card {
        border: 1px solid #f0f0f0;
        border-radius: 15px;
        padding: 25px;
        transition: 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .resource-card:hover {
        border-color: var(--stadum-red);
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    }

    .icon-box-res {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .bg-soft-red { background: rgba(211, 26, 67, 0.1); color: var(--stadum-red); }

    .task-item-pro {
        border: 1px solid #f0f0f0;
        border-radius: 15px;
        margin-bottom: 15px;
        overflow: hidden;
        transition: 0.3s;
    }

    .task-trigger {
        width: 100%;
        text-align: left;
        background: white;
        border: none;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .task-trigger:hover {
        background-color: #f8f9fc;
    }

    .btn-action-pro {
        border-radius: 50px;
        padding: 14px 35px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        border: none;
        transition: 0.3s;
    }

    .btn-red { background: var(--stadum-red); color: white; }
    .btn-dark { background: var(--stadum-dark); color: white; }

    .stat-mini-card {
        text-align: center;
        padding: 25px;
        border-radius: 15px;
        background: white;
        border: 1px solid #eee;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
    }

    .stat-label-mini {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #888;
        margin-bottom: 5px;
    }

    .stat-value-mini {
        font-size: 2rem;
        font-weight: 800;
        color: var(--stadum-dark);
        font-family: 'Crimson Text', serif;
    }

    .grade-badge {
        padding: 8px 15px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
    }
</style>

<div class="estudiante-materia-view animate__animated animate__fadeIn">
    <!-- Header de la Materia -->
    <div class="subject-banner">
        <div>
            <h2 class="serif fw-bold mb-1 text-dark"><?= htmlspecialchars($materia['nombre']); ?></h2>
            <p class="text-muted mb-0"><i class="fas fa-chalkboard-teacher me-2 text-danger"></i> Docente: <?= htmlspecialchars($materia['docente']); ?></p>
        </div>
        <a href="index.php?controller=Estudiante&action=dashboard" class="btn btn-outline-secondary rounded-pill px-4 fw-bold small">
            <i class="fas fa-chevron-left me-2"></i> Volver al Panel
        </a>
    </div>

    <!-- Navegación por Pestañas -->
    <div class="text-center">
        <ul class="nav nav-pills nav-pills-custom" id="materiaTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-materiales" type="button">
                    <i class="fas fa-book-open me-2"></i> Recursos
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-tareas" type="button">
                    <i class="fas fa-tasks me-2"></i> Actividades
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-calificaciones" type="button">
                    <i class="fas fa-star me-2"></i> Calificaciones
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-asistencia" type="button">
                    <i class="fas fa-calendar-check me-2"></i> Mi Asistencia
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-evaluacion" type="button">
                    <i class="fas fa-comment-dots me-2"></i> Evaluar Docente
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <!-- SECCIÓN 1: RECURSOS -->
        <div class="tab-pane fade show active" id="tab-materiales">
            <div class="card-pro">
                <div class="card-header-pro bg-pro-dark">
                    <i class="fas fa-folder-open fa-lg"></i>
                    <h5 class="mb-0 fw-bold">Materiales de Estudio</h5>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($materiales)): ?>
                        <div class="row g-4">
                            <?php foreach ($materiales as $material): ?>
                                <div class="col-md-6 col-lg-4">
                                    <div class="resource-card shadow-sm">
                                        <div class="icon-box-res bg-soft-red">
                                            <i class="fas <?= $material['tipo'] === 'Video' ? 'fa-play-circle' : ($material['tipo'] === 'Enlace' ? 'fa-link' : 'fa-file-pdf'); ?>"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2"><?= htmlspecialchars($material['titulo']); ?></h6>
                                        <p class="text-muted small mb-4 flex-grow-1"><?= htmlspecialchars($material['descripcion'] ?? 'Sin descripción adicional.'); ?></p>
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <span class="text-muted smaller" style="font-size: 11px;"><i class="far fa-calendar-alt me-1"></i> <?= date('d M, Y', strtotime($material['fecha_publicacion'])); ?></span>
                                            <a href="<?= $material['tipo'] === 'Enlace' ? htmlspecialchars($material['ruta']) : 'uploads/'.htmlspecialchars($material['ruta']); ?>" 
                                               target="_blank" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">ACCEDER</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">El docente aún no ha publicado recursos para esta materia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: ACTIVIDADES -->
        <div class="tab-pane fade" id="tab-tareas">
            <div class="card-pro">
                <div class="card-header-pro bg-pro-red">
                    <i class="fas fa-list-check fa-lg"></i>
                    <h5 class="mb-0 fw-bold">Cronograma de Tareas</h5>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($tareas)): ?>
                        <?php foreach ($tareas as $index => $tarea): ?>
                            <div class="task-item-pro shadow-sm">
                                <button class="task-trigger" type="button" data-bs-toggle="collapse" data-bs-target="#task-<?= $index; ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light p-2 rounded-3 me-3 text-danger"><i class="fas fa-file-signature"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($tarea['titulo']); ?></h6>
                                            <small class="text-muted">Fecha límite: <?= date('d M, Y - H:i', strtotime($tarea['fecha_entrega'])); ?></small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if ($tarea['fecha_entrega_estudiante']): ?>
                                            <span class="badge rounded-pill bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> ENTREGADO</span>
                                        <?php elseif (strtotime($tarea['fecha_entrega']) > time()): ?>
                                            <span class="badge rounded-pill bg-danger px-3 py-2"><i class="fas fa-clock me-1"></i> PENDIENTE</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-secondary px-3 py-2">CERRADO</span>
                                        <?php endif; ?>
                                        <i class="fas fa-chevron-down text-muted small"></i>
                                    </div>
                                </button>
                                <div id="task-<?= $index; ?>" class="collapse">
                                    <div class="p-4 border-top bg-light">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6 class="fw-bold text-dark mb-2">Instrucciones:</h6>
                                                <p class="text-muted small mb-0"><?= nl2br(htmlspecialchars($tarea['descripcion'])); ?></p>
                                            </div>
                                            <div class="col-md-4 border-start">
                                                <?php if ($tarea['fecha_entrega_estudiante']): ?>
                                                    <div class="bg-white p-3 rounded-3 border border-success">
                                                        <div class="text-success fw-bold small mb-2"><i class="fas fa-check-circle me-2"></i> ¡Tarea entregada!</div>
                                                        <div class="smaller text-muted">Fecha: <?= date('d/m/Y H:i', strtotime($tarea['fecha_entrega_estudiante'])); ?></div>
                                                        
                                                        <?php if ($tarea['calificacion'] !== null): ?>
                                                            <div class="mt-3 pt-3 border-top">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <span class="small fw-bold text-dark">Calificación:</span>
                                                                    <span class="badge bg-<?= $tarea['calificacion'] >= 70 ? 'success' : 'danger' ?>"><?= $tarea['calificacion'] ?>/100</span>
                                                                </div>
                                                                <?php if (!empty($tarea['retroalimentacion'])): ?>
                                                                    <div class="smaller text-muted mt-2 p-2 bg-light rounded italic" style="font-size: 11px;">
                                                                        <i class="fas fa-quote-left me-1"></i> <?= htmlspecialchars($tarea['retroalimentacion']) ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="mt-2 text-muted smaller italic"><i class="fas fa-hourglass-half me-1"></i> Pendiente de calificar</div>
                                                        <?php endif; ?>
                                                        
                                                        <hr class="my-2">
                                                        <a href="<?= htmlspecialchars($tarea['archivo_estudiante'] ?? '#') ?>" target="_blank" class="btn btn-sm btn-link text-success fw-bold p-0">Ver mi trabajo enviado</a>
                                                    </div>
                                                <?php elseif (strtotime($tarea['fecha_entrega']) > time()): ?>
                                                    <h6 class="fw-bold text-dark mb-3">Realizar Entrega:</h6>
                                                    <form action="index.php?controller=Estudiante&action=entregarTarea" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="id_tarea" value="<?= $tarea['id_tarea']; ?>">
                                                        <input type="hidden" name="id_materia" value="<?= $materia['id_materia']; ?>">
                                                        <div class="mb-3">
                                                            <input type="file" class="form-control form-control-sm" name="archivo" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-red w-100 btn-sm fw-bold py-2">SUBIR ARCHIVO</button>
                                                    </form>
                                                <?php else: ?>
                                                    <div class="alert alert-warning py-2 small mb-0">El periodo de entrega ha finalizado.</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-tasks fa-4x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">No hay actividades programadas.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3: CALIFICACIONES -->
        <div class="tab-pane fade" id="tab-calificaciones">
            <div class="card-pro">
                <div class="card-header-pro bg-pro-info">
                    <i class="fas fa-award fa-lg"></i>
                    <h5 class="mb-0 fw-bold">Desempeño Académico</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Resumen de Notas -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="stat-mini-card">
                                <div class="stat-label-mini">Promedio General</div>
                                <div class="stat-value-mini text-primary"><?= number_format($resumen_calificaciones['promedio_calificaciones'] ?? 0, 1); ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-mini-card">
                                <div class="stat-label-mini">Tareas Entregadas</div>
                                <div class="stat-value-mini text-success"><?= $resumen_calificaciones['tareas_entregadas'] ?? 0; ?> / <?= $resumen_calificaciones['total_tareas'] ?? 0; ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-mini-card">
                                <div class="stat-label-mini">Puntaje Máximo</div>
                                <div class="stat-value-mini text-danger"><?= $resumen_calificaciones['mejor_calificacion'] ?? 0; ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Detalle -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr class="small text-uppercase text-muted">
                                    <th>Actividad Evaluada</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Nota Obtenida</th>
                                    <th class="text-end">Retroalimentación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tareas as $t): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($t['titulo']); ?></div>
                                            <div class="smaller text-muted" style="font-size: 11px;">Vencimiento: <?= date('d/m/Y', strtotime($t['fecha_entrega'])); ?></div>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($t['fecha_entrega_estudiante']): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 border border-success border-opacity-25">ENTREGADO</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 border border-danger border-opacity-25">SIN ENVÍO</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($t['calificacion'] !== null): ?>
                                                <div class="h5 mb-0 fw-bold text-<?= ($t['calificacion'] ?? 0) >= 70 ? 'success' : 'danger'; ?>">
                                                    <?= $t['calificacion']; ?> <small class="text-muted" style="font-size: 10px;">/ 100</small>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted small italic">Pendiente de calificar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if(!empty($t['retroalimentacion'])): ?>
                                                <button class="btn btn-sm btn-dark rounded-pill px-3" 
                                                        data-bs-toggle="popover" 
                                                        data-bs-trigger="focus"
                                                        title="Comentario del Docente" 
                                                        data-bs-content="<?= htmlspecialchars($t['retroalimentacion']); ?>">
                                                    Leer Comentario
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted smaller">Sin observaciones</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 4: ASISTENCIA -->
        <div class="tab-pane fade" id="tab-asistencia">
            <div class="card-pro">
                <div class="card-header-pro bg-pro-dark">
                    <i class="fas fa-calendar-check fa-lg"></i>
                    <h5 class="mb-0 fw-bold">Mi Historial de Asistencia</h5>
                </div>
                <div class="card-body p-0">
                    <?php 
                        $total_clases = count($asistencia);
                        $presentes = 0; $ausentes = 0; $retrasos = 0; $licencias = 0;
                        foreach($asistencia as $a) {
                            if($a['estado'] == 'Presente') $presentes++;
                            if($a['estado'] == 'Ausente') $ausentes++;
                            if($a['estado'] == 'Retraso') $retrasos++;
                            if($a['estado'] == 'Licencia') $licencias++;
                        }
                    ?>
                    <div class="row g-0 border-bottom">
                        <div class="col-3 p-4 text-center border-end">
                            <h3 class="text-success fw-bold mb-1"><?= $presentes ?></h3><small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Presentes</small>
                        </div>
                        <div class="col-3 p-4 text-center border-end">
                            <h3 class="text-danger fw-bold mb-1"><?= $ausentes ?></h3><small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Ausentes</small>
                        </div>
                        <div class="col-3 p-4 text-center border-end">
                            <h3 class="text-warning fw-bold mb-1"><?= $retrasos ?></h3><small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Retrasos</small>
                        </div>
                        <div class="col-3 p-4 text-center">
                            <h3 class="text-info fw-bold mb-1"><?= $licencias ?></h3><small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Licencias</small>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($asistencia)): foreach($asistencia as $a): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark"><?= date('d / m / Y', strtotime($a['fecha'])) ?></td>
                                        <td>
                                            <?php if($a['estado'] == 'Presente'): ?> <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Presente</span>
                                            <?php elseif($a['estado'] == 'Ausente'): ?> <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">Ausente</span>
                                            <?php elseif($a['estado'] == 'Retraso'): ?> <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3">Retraso</span>
                                            <?php else: ?> <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3">Licencia</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="2" class="text-center py-5 text-muted">Aún no hay registros de asistencia para ti en esta materia.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 5: EVALUACIÓN DOCENTE -->
        <div class="tab-pane fade" id="tab-evaluacion">
            <div class="card-pro">
                <div class="card-header-pro bg-pro-red">
                    <i class="fas fa-comment-dots fa-lg"></i>
                    <h5 class="mb-0 fw-bold">Evaluación al Docente</h5>
                </div>
                <div class="card-body p-5">
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-success alert-dismissible fade show rounded-4">
                            <?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-4">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if($ya_evaluado): ?>
                        <div class="text-center py-5">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                <i class="fas fa-check-circle fa-3x"></i>
                            </div>
                            <h3 class="fw-bold text-dark">¡Gracias por tu evaluación!</h3>
                            <p class="text-muted">Ya has completado la evaluación para este docente. Tu retroalimentación ayuda a mejorar la calidad académica.</p>
                        </div>
                    <?php else: ?>
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center border-end pe-4">
                                <img src="assets/img/perfil1.jpg" class="rounded-circle mb-3 border border-3 border-danger shadow-sm" width="100" height="100" style="object-fit: cover;">
                                <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($materia['docente']); ?></h5>
                                <p class="text-muted small">Docente Titular</p>
                                <div class="bg-light p-3 rounded-4 mt-4 text-start">
                                    <small class="text-muted d-block mb-2"><i class="fas fa-info-circle text-danger me-1"></i> Información:</small>
                                    <small class="d-block text-secondary" style="font-size: 11px;">Esta evaluación es <strong>100% anónima</strong>. Tus respuestas sinceras nos ayudan a mantener un alto nivel académico.</small>
                                </div>
                            </div>
                            <div class="col-md-8 ps-md-5">
                                <form action="index.php?controller=Estudiante&action=enviarEvaluacion" method="POST">
                                    <input type="hidden" name="id_materia" value="<?= $materia['id_materia'] ?>">
                                    <input type="hidden" name="id_docente" value="<?= $materia['id_docente'] ?>">
                                    
                                    <h6 class="fw-bold text-dark mb-3">1. Calificación General del Docente</h6>
                                    <div class="mb-4">
                                        <div class="rating-stars d-flex gap-2 mb-2" id="star-rating">
                                            <i class="far fa-star fs-2 text-warning cursor-pointer" data-val="1"></i>
                                            <i class="far fa-star fs-2 text-warning cursor-pointer" data-val="2"></i>
                                            <i class="far fa-star fs-2 text-warning cursor-pointer" data-val="3"></i>
                                            <i class="far fa-star fs-2 text-warning cursor-pointer" data-val="4"></i>
                                            <i class="far fa-star fs-2 text-warning cursor-pointer" data-val="5"></i>
                                        </div>
                                        <input type="hidden" name="puntuacion" id="puntuacion_input" required>
                                        <div class="invalid-feedback d-none" id="star-error">Por favor, selecciona una puntuación.</div>
                                    </div>

                                    <h6 class="fw-bold text-dark mb-3">2. Preguntas Específicas</h6>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">¿Cómo califica la metodología de enseñanza aplicada por el docente?</label>
                                        <textarea class="form-control bg-light border-0" name="q1" rows="2" placeholder="Ej. Sus clases son dinámicas, usa buenos ejemplos..." required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small fw-bold">¿Fue clara y oportuna la comunicación / resolución de dudas?</label>
                                        <textarea class="form-control bg-light border-0" name="q2" rows="2" placeholder="Ej. Respondía rápido a los mensajes, aclaraba bien las dudas..." required></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label text-muted small fw-bold">¿Algún comentario o sugerencia adicional? (Opcional)</label>
                                        <textarea class="form-control bg-light border-0" name="q3" rows="2"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-red px-5 py-3 rounded-pill fw-bold" id="btn-submit-eval">
                                        ENVIAR EVALUACIÓN <i class="fas fa-paper-plane ms-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; transition: 0.2s; }
    .cursor-pointer:hover { transform: scale(1.1); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });

    // Manejar tab en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
        const targetTab = document.querySelector(`button[data-bs-target="#tab-${tabParam}"]`);
        if (targetTab) {
            new bootstrap.Tab(targetTab).show();
        }
    }

    // Lógica de Estrellas para Evaluación
    const stars = document.querySelectorAll('#star-rating .fa-star');
    const input = document.getElementById('puntuacion_input');
    const form = document.querySelector('form[action*="enviarEvaluacion"]');
    
    if (stars.length > 0) {
        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                const val = this.getAttribute('data-val');
                highlightStars(val);
            });
            star.addEventListener('mouseout', function() {
                highlightStars(input.value || 0);
            });
            star.addEventListener('click', function() {
                const val = this.getAttribute('data-val');
                input.value = val;
                highlightStars(val);
                document.getElementById('star-error').classList.add('d-none');
            });
        });

        function highlightStars(val) {
            stars.forEach(s => {
                if (s.getAttribute('data-val') <= val) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                }
            });
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                if (!input.value) {
                    e.preventDefault();
                    document.getElementById('star-error').classList.remove('d-none');
                }
            });
        }
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>
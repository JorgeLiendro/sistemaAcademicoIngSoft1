<?php require_once 'views/layouts/header.php'; ?>

<style>
    /* Navegación por Pestañas Premium */
    .task-nav-pills {
        background: #f0f2f5;
        padding: 8px;
        border-radius: 15px;
        display: inline-flex;
        margin-bottom: 35px;
        flex-wrap: wrap;
    }
    .task-nav-pills .nav-link {
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 700;
        color: #555;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: 0.3s;
    }
    .task-nav-pills .nav-link.active {
        background-color: white;
        color: var(--stadum-red);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    /* Contenedores Pro */
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

    /* Estilos de Tabla y Badges */
    .custom-table-pro thead { background: #f8f9fa; border-bottom: 2px solid #eee; }
    .custom-table-pro th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #888; padding: 15px 20px; }
    .custom-table-pro td { padding: 15px 20px; vertical-align: middle; }
    
    .student-avatar-small { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    
    .progress-pro { height: 8px; border-radius: 50px; background: #eee; overflow: hidden; }
    .progress-bar-pro { border-radius: 50px; }

    .report-stat-card { border-radius: 15px; padding: 25px; color: white; text-align: center; height: 100%; transition: 0.3s; }
    .stat-val { font-size: 2.5rem; font-weight: 800; line-height: 1; margin-bottom: 5px; }
    .stat-lbl { font-size: 0.75rem; text-transform: uppercase; font-weight: 700; opacity: 0.8; }
</style>

<div class="docente-management-view">
    <!-- Header de Materia -->
    <div class="subject-header-banner mb-4 p-4 bg-white rounded-4 shadow-sm border-start border-danger border-5 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="serif fw-bold mb-1"><?= htmlspecialchars($materia['nombre']); ?></h2>
            <p class="text-muted mb-0"><i class="fas fa-chalkboard-teacher me-2 text-danger"></i> Gestión Docente de Asignatura</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="index.php?controller=Docente&action=tomarAsistencia&id_materia=<?= $materia['id_materia']; ?>" class="btn btn-success rounded-pill px-4">
                <i class="fas fa-clipboard-check me-2"></i> Tomar Asistencia
            </a>
            <a href="index.php?controller=Docente&action=dashboard" class="btn btn-outline-dark rounded-pill px-4">
                <i class="fas fa-chevron-left me-2"></i> Volver
            </a>
        </div>
    </div>

    <ul class="nav nav-pills task-nav-pills" id="mainTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-materiales"><i class="fas fa-book me-2"></i> Materiales</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-tareas"><i class="fas fa-tasks me-2"></i> Tareas</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-entregas"><i class="fas fa-file-upload me-2"></i> Entregas</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-alumnos"><i class="fas fa-users me-2"></i> Alumnos</button></li>
        
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-evaluaciones"><i class="fas fa-star me-2"></i> Evaluaciones</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-reportes"><i class="fas fa-chart-line me-2"></i> Reportes</button></li>
    </ul>

    <div class="tab-content">
        <!-- 1. MATERIALES (Mismo diseño Pro) -->
        <div class="tab-pane fade show active" id="tab-materiales">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card-pro">
                        <div class="card-header-pro bg-pro-dark"><i class="fas fa-upload"></i> Publicar Material</div>
                        <div class="card-body p-4">
                            <form action="index.php?controller=Docente&action=subirMaterial" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id_materia" value="<?= $materia['id_materia']; ?>">
                                <div class="mb-3"><label class="form-label-pro">Título</label><input type="text" name="titulo" class="form-control form-control-pro" required></div>
                                <div class="mb-3">
                                    <label class="form-label-pro">Tipo</label>
                                    <select name="tipo" class="form-select form-control-pro" id="tipoSelect">
                                        <option value="Documento">Documento</option><option value="Video">Video</option><option value="Enlace">Enlace</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="fGroup"><label class="form-label-pro">Archivo</label><input type="file" name="archivo" class="form-control form-control-pro"></div>
                                <div class="mb-3 d-none" id="uGroup"><label class="form-label-pro">URL</label><input type="url" name="url" class="form-control form-control-pro"></div>
                                <button type="submit" class="btn btn-dark w-100 rounded-pill py-3">Subir Recurso</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card-pro">
                        <div class="card-header-pro bg-pro-red"><i class="fas fa-folder-open"></i> Repositorio</div>
                        <div class="card-body p-0">
                            <?php if(!empty($materiales)): foreach($materiales as $m): ?>
                                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light-red p-2 rounded-3 me-3 text-center" style="width:40px"><i class="fas <?= $m['tipo'] === 'Video' ? 'fa-play' : ($m['tipo'] === 'Enlace' ? 'fa-link' : 'fa-file-alt') ?>"></i></div>
                                        <div><h6 class="mb-0 fw-bold"><?= $m['titulo'] ?></h6><small class="text-muted"><?= $m['tipo'] ?></small></div>
                                    </div>
                                    <div style="display: flex; gap: 8px;">
                                        <?php if ($m['tipo'] === 'Enlace'): ?>
                                            <a href="<?= $m['ruta'] ?>" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill"><i class="fas fa-external-link-alt me-1"></i> Ir</a>
                                        <?php elseif ($m['tipo'] === 'Video'): ?>
                                            <a href="<?= $m['ruta'] ?>" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill"><i class="fas fa-play me-1"></i> Ver</a>
                                        <?php else: ?>
                                            <a href="index.php?controller=Docente&action=verMaterial&id_material=<?= $m['id_material'] ?>&id_materia=<?= $materia['id_materia'] ?>" class="btn btn-sm btn-outline-dark rounded-pill"><i class="fas fa-eye me-1"></i> Ver</a>
                                            <a href="index.php?controller=Docente&action=descargarMaterial&id_material=<?= $m['id_material'] ?>&id_materia=<?= $materia['id_materia'] ?>" class="btn btn-sm btn-outline-primary rounded-pill"><i class="fas fa-download me-1"></i> Descargar</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; else: echo '<p class="p-4 text-center">Sin materiales.</p>'; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. TAREAS -->
        <div class="tab-pane fade" id="tab-tareas">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card-pro">
                        <div class="card-header-pro bg-pro-dark">Asignar Tarea</div>
                        <div class="card-body p-4">
                            <form action="index.php?controller=Docente&action=crearTarea" method="POST">
                                <input type="hidden" name="id_materia" value="<?= $materia['id_materia']; ?>">
                                <div class="mb-3"><label class="form-label-pro">Título de la Tarea</label><input type="text" name="titulo" class="form-control form-control-pro" required></div>
                                <div class="mb-3"><label class="form-label-pro">Descripción</label><textarea name="descripcion" class="form-control form-control-pro" rows="4"></textarea></div>
                                <div class="mb-3"><label class="form-label-pro">Fecha Límite</label><input type="datetime-local" name="fecha_entrega" class="form-control form-control-pro" required></div>
                                <button type="submit" class="btn btn-dark w-100 rounded-pill py-3">Publicar Actividad</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card-pro">
                        <div class="card-header-pro bg-pro-red">Tareas Programadas</div>
                        <div class="card-body p-0">
                            <?php if(!empty($tareas)): foreach($tareas as $t): ?>
                                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($t['titulo']) ?></h6>
                                        <small class="text-muted"><i class="far fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($t['fecha_entrega'])) ?></small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#modalEditFecha"
                                            data-id="<?= $t['id_tarea'] ?>"
                                            data-titulo="<?= htmlspecialchars($t['titulo']) ?>"
                                            data-fecha="<?= date('Y-m-d\TH:i', strtotime($t['fecha_entrega'])) ?>"
                                            title="Editar fecha">
                                            <i class="fas fa-calendar-alt"></i>
                                        </button>
                                        <a href="index.php?controller=Docente&action=gestionTareas&id_materia=<?= $id_materia ?>&id_tarea=<?= $t['id_tarea'] ?>&tab=entregas#mainTabs" class="btn btn-sm btn-dark rounded-pill">Ver Entregas</a>
                                    </div>
                                </div>
                            <?php endforeach; else: echo '<p class="p-4 text-center text-muted">Sin tareas.</p>'; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Editar Fecha de Tarea -->
        <div class="modal fade" id="modalEditFecha" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                    <div class="modal-header" style="background: var(--stadum-dark); color: white;">
                        <h5 class="modal-title fw-bold"><i class="fas fa-calendar-edit me-2"></i>Editar Fecha Límite</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="index.php?controller=Docente&action=editarFechaTarea" method="POST">
                        <div class="modal-body p-4">
                            <input type="hidden" name="id_tarea" id="edit_id_tarea">
                            <input type="hidden" name="id_materia" value="<?= $id_materia ?>">
                            <p class="text-muted mb-3">Tarea: <strong id="edit_titulo_tarea"></strong></p>
                            <label class="form-label fw-bold text-uppercase small">Nueva Fecha Límite</label>
                            <input type="datetime-local" name="fecha_entrega" id="edit_fecha_tarea"
                                class="form-control form-control-lg border-0 bg-light rounded-3" required>
                        </div>
                        <div class="modal-footer border-0 pb-4 justify-content-center">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-dark rounded-pill px-5"><i class="fas fa-save me-2"></i>Guardar Cambio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 3. ENTREGAS -->
        <div class="tab-pane fade" id="tab-entregas">
            <?php if(empty($tarea_seleccionada)): ?>
                <div class="alert alert-info text-center rounded-4 p-5"><h5>Seleccione una tarea para calificar</h5></div>
            <?php else: ?>
                <div class="card-pro">
                    <div class="card-header-pro bg-pro-blue">Evaluando: <?= $tarea_seleccionada['titulo'] ?></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 custom-table-pro">
                                <thead><tr><th>Estudiante</th><th>Fecha</th><th>Archivo</th><th>Nota</th><th>Acción</th></tr></thead>
                                <tbody>
                                    <?php foreach($entregas as $e): ?>
                                        <tr>
                                            <td class="fw-bold"><?= $e['estudiante'] ?></td>
                                            <td class="small"><?= date('d/m H:i', strtotime($e['fecha_entrega'])) ?></td>
                                            <td><a href="<?= $e['archivo'] ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">Abrir</a></td>
                                            <td><span class="badge bg-<?= isset($e['calificacion']) ? 'success' : 'warning text-dark' ?> rounded-pill px-3"><?= $e['calificacion'] ?? 'Pend' ?></span></td>
                                            <td>
                                                <button class="btn btn-sm btn-dark rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalCalificar<?= $e['id_entrega'] ?>">Calificar</button>
                                                
                                                <!-- Modal Calificar -->
                                                <div class="modal fade" id="modalCalificar<?= $e['id_entrega'] ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 border-0 shadow-lg">
                                                            <div class="modal-header bg-dark text-white border-0">
                                                                <h5 class="modal-title fw-bold">Calificar Entrega</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="index.php?controller=Docente&action=guardarCalificacion" method="POST">
                                                                <div class="modal-body p-4">
                                                                    <input type="hidden" name="id_entrega" value="<?= $e['id_entrega'] ?>">
                                                                    <input type="hidden" name="id_tarea" value="<?= $tarea_seleccionada['id_tarea'] ?>">
                                                                    <input type="hidden" name="id_materia" value="<?= $id_materia ?>">
                                                                    
                                                                    <div class="mb-3 text-center">
                                                                        <h6 class="text-muted mb-1">Estudiante</h6>
                                                                        <p class="fw-bold fs-5"><?= htmlspecialchars($e['estudiante']) ?></p>
                                                                    </div>
                                                                    
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Nota (0-100)</label>
                                                                        <input type="number" name="calificacion" class="form-control form-control-lg rounded-3" min="0" max="100" value="<?= $e['calificacion'] ?? '' ?>" required>
                                                                    </div>
                                                                    
                                                                    <div class="mb-0">
                                                                        <label class="form-label fw-bold">Retroalimentación</label>
                                                                        <textarea name="retroalimentacion" class="form-control rounded-3" rows="3" placeholder="Buen trabajo..."><?= htmlspecialchars($e['retroalimentacion'] ?? '') ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-0 pb-4">
                                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-dark rounded-pill px-4">Guardar Nota</button>
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
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- 4. PESTAÑA ALUMNOS (NUEVA: LISTA DE ESTUDIANTES) -->
        <div class="tab-pane fade" id="tab-alumnos">
            <div class="card-pro">
                <div class="card-header-pro bg-pro-dark">
                    <i class="fas fa-user-graduate me-2"></i> Alumnos Inscritos en la Materia
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-table-pro">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th class="text-center">Entregas</th>
                                    <th class="text-center">Promedio</th>
                                    <th>Progreso Académico</th>
                                    <th class="text-center">Tareas Pendientes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($reportes['estudiantes'])): foreach($reportes['estudiantes'] as $est): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light p-2 rounded-circle me-3"><i class="fas fa-user text-muted"></i></div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($est['nombre']) ?></div>
                                            </div>
                                        </td>
                                        <td class="text-center"><span class="badge bg-light text-dark border"><?= $est['entregadas'] ?> Envío(s)</span></td>
                                        <td class="text-center">
                                            <span class="fw-bold text-<?= $est['promedio'] >= 70 ? 'success' : 'danger' ?>">
                                                <?= number_format($est['promedio'] ?? 0, 1) ?>
                                            </span>
                                        </td>
                                        <td style="width: 250px;">
                                            <?php 
                                                $total_t = $reportes['total_tareas'] ?: 1;
                                                $perc = ($est['entregadas'] / $total_t) * 100;
                                            ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress-pro flex-grow-1">
                                                    <div class="progress-bar-pro bg-danger h-100" style="width: <?= $perc ?>%"></div>
                                                </div>
                                                <span class="small fw-bold"><?= round($perc) ?>%</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-<?= $est['pendientes'] > 0 ? 'warning text-dark' : 'success' ?> rounded-pill">
                                                <?= $est['pendientes'] ?> faltante(s)
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="5" class="text-center py-5">No hay estudiantes inscritos.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. ASISTENCIA -->
        <div class="tab-pane fade" id="tab-asistencia">
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4">
                    <?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <div class="card-pro">
                <div class="card-header-pro bg-pro-blue d-flex justify-content-between align-items-center">
                    <div><i class="fas fa-calendar-check me-2"></i> Control de Asistencia</div>
                    <form class="d-flex align-items-center bg-white rounded p-1" method="GET" action="index.php">
                        <input type="hidden" name="controller" value="Docente">
                        <input type="hidden" name="action" value="gestionTareas">
                        <input type="hidden" name="id_materia" value="<?= $id_materia ?>">
                        <input type="hidden" name="tab" value="asistencia">
                        <input type="date" name="fecha" class="form-control form-control-sm border-0" value="<?= htmlspecialchars($fecha_asistencia) ?>" onchange="this.form.submit()">
                    </form>
                </div>
                <div class="card-body p-0">
                    <form action="index.php?controller=Docente&action=guardarAsistencia" method="POST">
                        <input type="hidden" name="id_materia" value="<?= $id_materia ?>">
                        <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha_asistencia) ?>">
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 custom-table-pro">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th class="text-center">Presente</th>
                                        <th class="text-center">Ausente</th>
                                        <th class="text-center">Retraso</th>
                                        <th class="text-center">Licencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($lista_estudiantes)): foreach($lista_estudiantes as $est): 
                                        $estado_actual = $asistencia_registrada[$est['id_estudiante']] ?? 'Presente'; // Default to Presente
                                    ?>
                                        <tr>
                                            <td class="fw-bold">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light p-2 rounded-circle me-3"><i class="fas fa-user text-muted"></i></div>
                                                    <?= htmlspecialchars($est['nombre_completo']) ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <input class="form-check-input border-success fs-5" type="radio" name="asistencia[<?= $est['id_estudiante'] ?>]" value="Presente" <?= $estado_actual == 'Presente' ? 'checked' : '' ?>>
                                            </td>
                                            <td class="text-center">
                                                <input class="form-check-input border-danger fs-5" type="radio" name="asistencia[<?= $est['id_estudiante'] ?>]" value="Ausente" <?= $estado_actual == 'Ausente' ? 'checked' : '' ?>>
                                            </td>
                                            <td class="text-center">
                                                <input class="form-check-input border-warning fs-5" type="radio" name="asistencia[<?= $est['id_estudiante'] ?>]" value="Retraso" <?= $estado_actual == 'Retraso' ? 'checked' : '' ?>>
                                            </td>
                                            <td class="text-center">
                                                <input class="form-check-input border-info fs-5" type="radio" name="asistencia[<?= $est['id_estudiante'] ?>]" value="Licencia" <?= $estado_actual == 'Licencia' ? 'checked' : '' ?>>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="5" class="text-center py-5">No hay estudiantes inscritos.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if(!empty($lista_estudiantes)): ?>
                            <div class="p-4 border-top text-end bg-light">
                                <button type="submit" class="btn btn-dark rounded-pill px-4 py-2"><i class="fas fa-save me-2"></i> Guardar Asistencia</button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- 6. EVALUACIONES -->
        <div class="tab-pane fade" id="tab-evaluaciones">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-pro text-center p-5 bg-pro-dark text-white mb-4">
                        <h6 class="text-uppercase letter-spacing-1 mb-3 opacity-75">Promedio del Curso</h6>
                        <h1 class="display-1 fw-bold mb-0 text-warning">
                            <?= number_format($promedio_evaluaciones, 1) ?>
                        </h1>
                        <div class="mb-3">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="fas fa-star <?= $i <= round($promedio_evaluaciones) ? 'text-warning' : 'text-secondary' ?> fs-4"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="mb-0 opacity-75">Basado en <?= count($evaluaciones) ?> evaluaciones anónimas.</p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card-pro">
                        <div class="card-header-pro bg-pro-red"><i class="fas fa-comments"></i> Comentarios de Estudiantes</div>
                        <div class="card-body p-0">
                            <?php if(!empty($evaluaciones)): foreach($evaluaciones as $ev): ?>
                                <div class="p-4 border-bottom">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="text-warning">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= $ev['puntuacion'] ? '' : 'text-muted' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <small class="text-muted"><i class="far fa-clock"></i> <?= date('d/m/Y', strtotime($ev['fecha'])) ?></small>
                                    </div>
                                    <p class="mb-0 text-dark fst-italic">"<?= htmlspecialchars($ev['comentario'] ?? 'Sin comentario') ?>"</p>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="p-5 text-center text-muted">Aún no hay evaluaciones registradas para esta materia.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. REPORTES -->
        <div class="tab-pane fade" id="tab-reportes">
            <div class="row g-4 mb-4">
                <div class="col-md-4"><div class="report-stat-card bg-pro-dark"><div class="stat-val"><?= $reportes['total_tareas'] ?? 0 ?></div><div class="stat-lbl">Tareas</div></div></div>
                <div class="col-md-4"><div class="report-stat-card bg-pro-red"><div class="stat-val"><?= $reportes['tareas_calificadas'] ?? 0 ?></div><div class="stat-lbl">Calificadas</div></div></div>
                <div class="col-md-4"><div class="report-stat-card bg-pro-blue"><div class="stat-val"><?= $reportes['total_estudiantes'] ?? 0 ?></div><div class="stat-lbl">Alumnos</div></div></div>
            </div>
            <div class="card-pro p-4">
                <h5 class="serif fw-bold mb-4">Distribución de Notas</h5>
                <div style="height: 350px;"><canvas id="chartCalificaciones"></canvas></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar tab en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
        const targetTab = document.querySelector(`button[data-bs-target="#tab-${tabParam}"]`);
        if (targetTab) {
            new bootstrap.Tab(targetTab).show();
            // Hacer scroll suave hacia las pestañas para que el docente vea el contenido directamente
            setTimeout(() => {
                targetTab.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
    }

    const ts = document.getElementById('tipoSelect');
    if(ts) ts.addEventListener('change', function() {
        document.getElementById('fGroup').classList.toggle('d-none', this.value === 'Enlace');
        document.getElementById('uGroup').classList.toggle('d-none', this.value !== 'Enlace');
    });

    // Poblar el modal de edición de fecha con los datos de la tarea clickeada
    const modalEditFecha = document.getElementById('modalEditFecha');
    if (modalEditFecha) {
        modalEditFecha.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            document.getElementById('edit_id_tarea').value  = btn.getAttribute('data-id');
            document.getElementById('edit_titulo_tarea').textContent = btn.getAttribute('data-titulo');
            document.getElementById('edit_fecha_tarea').value = btn.getAttribute('data-fecha');
        });
    }

    const ctx = document.getElementById('chartCalificaciones')?.getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Excelente (90-100)', 'Bueno (80-89)', 'Regular (70-79)', 'Suficiente (60-69)', 'Insuficiente (<60)'],
                datasets: [{
                    label: 'Cantidad de Alumnos',
                    data: [<?= $reportes['distribucion_calificaciones']['excelente'] ?? 0 ?>, <?= $reportes['distribucion_calificaciones']['bueno'] ?? 0 ?>, <?= $reportes['distribucion_calificaciones']['regular'] ?? 0 ?>, <?= $reportes['distribucion_calificaciones']['suficiente'] ?? 0 ?>, <?= $reportes['distribucion_calificaciones']['insuficiente'] ?? 0 ?>],
                    backgroundColor: ['#28a745', '#20c997', '#ffc107', '#fd7e14', '#dc3545'],
                    borderRadius: 10
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>
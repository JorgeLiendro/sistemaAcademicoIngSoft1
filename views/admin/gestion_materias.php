<?php require_once 'views/layouts/header.php'; ?>

<style>
    .admin-card-header {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border-left: 6px solid var(--stadum-red);
    }

    .table-container {
        background: white;
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .custom-table {
        margin-bottom: 0;
    }

    .custom-table thead {
        background: var(--stadum-dark);
        color: white;
    }

    .custom-table thead th {
        padding: 20px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        border: none;
    }

    .custom-table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        border-color: #f8f9fa;
        font-size: 0.95rem;
    }

    .subject-icon {
        width: 40px;
        height: 40px;
        background: #fdf0f2;
        color: var(--stadum-red);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 15px;
    }

    .docente-badge {
        background: #f1f6ff;
        color: var(--stadum-blue);
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .btn-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        border: none;
        margin-left: 5px;
    }

    .btn-edit-pro { background: #f8f9fa; color: #555; }
    .btn-edit-pro:hover { background: var(--stadum-blue); color: white; transform: scale(1.1); }
    
    .btn-delete-pro { background: #fff5f7; color: var(--stadum-red); }
    .btn-delete-pro:hover { background: var(--stadum-red); color: white; transform: scale(1.1); }

    .filter-section {
        background: #f8f9fa;
        padding: 25px;
        border-bottom: 1px solid #eee;
    }

    .form-control-custom {
        border-radius: 10px;
        padding: 12px 20px;
        border: 1px solid #ddd;
        font-size: 0.9rem;
    }

    .btn-search {
        background: var(--stadum-dark);
        color: white;
        border-radius: 10px;
        padding: 12px 25px;
        font-weight: 600;
        border: none;
        transition: 0.3s;
    }

    .btn-search:hover { background: var(--stadum-red); color: white; }

    /* Modal Styling */
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
    .modal-body-pro {
        padding: 30px;
    }
</style>

<div class="admin-dashboard-view">
    <!-- Header de Sección -->
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="serif fw-bold mb-1">Gestión de Materias</h2>
            <p class="text-muted mb-0">Administre el currículo académico y asigne docentes a las asignaturas.</p>
        </div>
        <button type="button" class="btn btn-stadum px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalCrearMateria">
            <i class="fas fa-plus-circle me-2"></i> Nueva Materia
        </button>
    </div>

    <!-- Alertas -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['mensaje']; ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <!-- Tabla y Filtros -->
    <div class="table-container">
        <!-- Filtros -->
        <div class="filter-section">
            <form method="GET" action="index.php" class="row g-3">
                <input type="hidden" name="controller" value="Admin">
                <input type="hidden" name="action" value="gestionMaterias">
                
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-custom border-start-0" name="busqueda" 
                            placeholder="Buscar por nombre o descripción..." value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select form-control-custom" name="filtroDocente">
                        <option value="">Todos los Docentes</option>
                        <?php foreach ($docentes as $docente): ?>
                        <option value="<?= $docente['id_docente']; ?>" <?= (isset($_GET['filtroDocente']) && $_GET['filtroDocente'] == $docente['id_docente']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($docente['nombre_completo']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-search w-100">
                        <i class="fas fa-filter me-2"></i> Filtrar Materias
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de Materias -->
        <div class="table-responsive">
            <table class="table custom-table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Cód.</th>
                        <th>Asignatura</th>
                        <th>Carrera / Semestre</th>
                        <th>Grupo / Turno</th>
                        <th>Docente Asignado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materias as $materia): ?>
                    <tr>
                        <td class="fw-bold text-muted">#<?= str_pad($materia['id_materia'], 3, '0', STR_PAD_LEFT); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="subject-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($materia['nombre']); ?></div>
                                    <div class="smaller text-muted" style="font-size: 11px;"><?= substr(htmlspecialchars($materia['descripcion']), 0, 40) . '...'; ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small"><?= htmlspecialchars($materia['nombre_carrera'] ?? 'General'); ?></div>
                            <div class="smaller text-muted">Semestre <?= $materia['nivel_semestre']; ?></div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small">Grupo <?= htmlspecialchars($materia['grupo']); ?></div>
                            <div class="smaller text-muted"><?= htmlspecialchars($materia['turno']); ?></div>
                        </td>
                        <td>
                            <span class="docente-badge">
                                <i class="fas fa-chalkboard-teacher me-1"></i> <?= htmlspecialchars($materia['nombre_docente']); ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="index.php?controller=Admin&action=editarMateria&id=<?= $materia['id_materia']; ?>" 
                               class="btn-circle btn-edit-pro" title="Editar">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                            <a href="index.php?controller=Admin&action=eliminarMateria&id=<?= $materia['id_materia']; ?>" 
                               class="btn-circle btn-delete-pro" title="Eliminar"
                               onclick="return confirm('¿Está seguro de eliminar esta materia?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Crear Materia (Estilizado) -->
<div class="modal fade" id="modalCrearMateria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-pro shadow-lg">
            <div class="modal-header modal-header-pro">
                <h4 class="modal-title serif fw-bold mb-0">Nueva Materia Académica</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?controller=Admin&action=crearMateria" method="POST">
                <div class="modal-body modal-body-pro">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small text-uppercase">Nombre de la Asignatura</label>
                        <input type="text" class="form-control form-control-custom" name="nombre" placeholder="Ej: Cálculo Integral" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small text-uppercase">Descripción / Objetivos</label>
                        <textarea class="form-control form-control-custom" name="descripcion" rows="3" placeholder="Breve resumen del contenido..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark small text-uppercase">Asignar Docente</label>
                            <select class="form-select form-control-custom" name="id_docente" required>
                                <option value="" selected disabled>Seleccionar docente...</option>
                                <?php foreach ($docentes as $docente): ?>
                                <option value="<?= $docente['id_docente']; ?>"><?= htmlspecialchars($docente['nombre_completo']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark small text-uppercase">Periodo Académico</label>
                            <select class="form-select form-control-custom" name="id_periodo" required>
                                <option value="" selected disabled>Seleccionar periodo...</option>
                                <?php foreach ($periodos as $periodo): ?>
                                <option value="<?= $periodo['id_periodo']; ?>"><?= htmlspecialchars($periodo['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small text-uppercase">Carrera <span class="text-danger">*</span></label>
                        <select class="form-select form-control-custom" name="id_carrera" required>
                            <option value="" selected disabled>Seleccionar carrera...</option>
                            <?php foreach ($carreras as $carrera): ?>
                            <option value="<?= $carrera['id_carrera']; ?>"><?= htmlspecialchars($carrera['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-dark small text-uppercase">Semestre</label>
                            <select class="form-select form-control-custom" name="nivel_semestre" required>
                                <?php for($i=1; $i<=9; $i++): ?>
                                    <option value="<?= $i ?>">Semestre <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-dark small text-uppercase">Grupo</label>
                            <input type="text" class="form-control form-control-custom" name="grupo" placeholder="Ej: A" value="A" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-dark small text-uppercase">Turno</label>
                            <select class="form-select form-control-custom" name="turno" required>
                                <option value="Mañana">Mañana</option>
                                <option value="Medio Día">Medio Día</option>
                                <option value="Tarde">Tarde</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
                    <button type="submit" class="btn btn-stadum px-5 py-2">
                        <i class="fas fa-save me-2"></i> Crear Materia Ahora
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
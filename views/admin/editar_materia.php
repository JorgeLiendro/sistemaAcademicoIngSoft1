<?php require_once 'views/layouts/header.php'; ?>

<style>
    .admin-form-header {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border-left: 6px solid var(--stadum-red);
    }

    .form-container-pro {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    }

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
        transition: all 0.3s;
    }

    .form-control-pro:focus {
        background-color: #fff;
        border-color: var(--stadum-red);
        box-shadow: 0 0 0 0.25rem rgba(211, 26, 67, 0.1);
        outline: none;
    }

    .input-group-text-pro {
        background-color: #f8f9fc;
        border: 1px solid #e1e5eb;
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: #888;
    }

    .input-pro-group .form-control-pro {
        border-radius: 0 10px 10px 0;
    }

    .section-title-pro {
        font-family: 'Crimson Text', serif;
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--stadum-dark);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
        position: relative;
    }

    .section-title-pro::after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background: var(--stadum-red);
    }

    .btn-save-pro {
        background: var(--stadum-dark);
        color: white;
        border-radius: 50px;
        padding: 15px 40px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .btn-save-pro:hover {
        background: var(--stadum-red);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(211, 26, 67, 0.2);
    }

    .btn-back-pro {
        background: #f8f9fa;
        color: #666;
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
        border: 1px solid #eee;
    }

    .btn-back-pro:hover {
        background: #eee;
        color: #333;
    }

    .subject-info-box {
        background: #f1f6ff;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        border: 1px solid rgba(11, 28, 57, 0.1);
    }

    .subject-icon-large {
        width: 60px;
        height: 60px;
        background: white;
        color: var(--stadum-blue);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-right: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .required-mark { color: var(--stadum-red); }
</style>

<div class="admin-form-view">
    <!-- Header de Sección -->
    <div class="admin-form-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="serif fw-bold mb-1">Editar Materia Académica</h2>
            <p class="text-muted mb-0">Actualice el contenido, nombre o docente asignado a la asignatura.</p>
        </div>
        <a href="index.php?controller=Admin&action=gestionMaterias" class="btn-back-pro">
            <i class="fas fa-chevron-left me-2"></i> Cancelar y Volver
        </a>
    </div>

    <!-- Alertas -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 py-3">
            <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Formulario Profesional -->
    <div class="form-container-pro">
        <!-- Resumen de la Materia -->
        <div class="subject-info-box">
            <div class="subject-icon-large">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($materia['nombre']); ?></h5>
                <span class="text-muted small">ID de Materia: #<?= str_pad($materia['id_materia'], 3, '0', STR_PAD_LEFT); ?></span>
            </div>
        </div>

        <form action="index.php?controller=Admin&action=actualizarMateria" method="POST">
            <input type="hidden" name="id_materia" value="<?= $materia['id_materia']; ?>">
            
            <div class="row">
                <!-- Columna Izquierda: Información de la Materia -->
                <div class="col-lg-7 pe-lg-5">
                    <h4 class="section-title-pro">Detalles de Asignatura</h4>
                    
                    <div class="mb-4">
                        <label class="form-label-pro">Nombre de la Materia <span class="required-mark">*</span></label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-heading"></i></span>
                            <input type="text" class="form-control form-control-pro" name="nombre" value="<?= htmlspecialchars($materia['nombre']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Descripción Curricular</label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-align-left"></i></span>
                            <textarea class="form-control form-control-pro" name="descripcion" rows="5" placeholder="Objetivos y metas de la materia..."><?= htmlspecialchars($materia['descripcion']); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Asignación y Estado -->
                <div class="col-lg-5 ps-lg-5 border-start-lg">
                    <h4 class="section-title-pro">Asignación</h4>

                    <div class="mb-4">
                        <label class="form-label-pro">Docente Responsable <span class="required-mark">*</span></label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-chalkboard-teacher"></i></span>
                            <select class="form-select form-control-pro" name="id_docente" required>
                                <option value="" disabled>Seleccione un docente...</option>
                                <?php foreach ($docentes as $docente): ?>
                                <option value="<?= $docente['id_docente']; ?>" <?= $docente['id_docente'] == $materia['id_docente'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($docente['nombre_completo']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Carrera <span class="required-mark">*</span></label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-graduation-cap"></i></span>
                            <select class="form-select form-control-pro" name="id_carrera" required>
                                <option value="" disabled>Seleccione carrera...</option>
                                <?php foreach ($carreras as $carrera): ?>
                                <option value="<?= $carrera['id_carrera']; ?>" <?= $carrera['id_carrera'] == $materia['id_carrera'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($carrera['nombre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Periodo Académico</label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-calendar-alt"></i></span>
                            <select class="form-select form-control-pro" name="id_periodo">
                                <option value="">Sin periodo asignado</option>
                                <?php foreach ($periodos as $periodo): ?>
                                <option value="<?= $periodo['id_periodo']; ?>" <?= $periodo['id_periodo'] == $materia['id_periodo'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($periodo['nombre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label-pro">Semestre</label>
                            <select class="form-select form-control-pro" name="nivel_semestre" required>
                                <?php for($i=1; $i<=9; $i++): ?>
                                    <option value="<?= $i ?>" <?= $materia['nivel_semestre'] == $i ? 'selected' : ''; ?>>Semestre <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label-pro">Grupo</label>
                            <input type="text" class="form-control form-control-pro" name="grupo" value="<?= htmlspecialchars($materia['grupo']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Turno</label>
                        <select class="form-select form-control-pro" name="turno" required>
                            <option value="Mañana" <?= $materia['turno'] == 'Mañana' ? 'selected' : ''; ?>>Mañana</option>
                            <option value="Medio Día" <?= $materia['turno'] == 'Medio Día' ? 'selected' : ''; ?>>Medio Día</option>
                            <option value="Tarde" <?= $materia['turno'] == 'Tarde' ? 'selected' : ''; ?>>Tarde</option>
                        </select>
                    </div>

                    <div class="mb-4 pt-3">
                        <label class="form-label-pro">Estado del Curso</label>
                        <div class="bg-light p-3 rounded-3 border">
                            <div class="form-check form-switch d-flex justify-content-between align-items-center p-0">
                                <label class="form-check-label fw-bold text-dark mb-0" for="estadoMateria" style="cursor: pointer;">
                                    Materia Habilitada
                                </label>
                                <input class="form-check-input ms-0" type="checkbox" id="estadoMateria" name="estado" style="width: 45px; height: 22px; cursor: pointer;" checked>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">Las materias inactivas no aparecerán en la lista de inscripciones.</small>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 pt-3 border-top">
                <button type="submit" class="btn-save-pro">
                    <i class="fas fa-save me-2"></i> Actualizar Materia Académica
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @media (min-width: 992px) {
        .border-start-lg {
            border-left: 1px solid #f0f0f0 !important;
        }
    }
</style>

<?php require_once 'views/layouts/footer.php'; ?>
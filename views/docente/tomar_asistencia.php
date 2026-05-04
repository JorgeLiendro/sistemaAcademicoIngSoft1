<?php require_once 'views/layouts/header.php'; ?>

<style>
    .attendance-header {
        background: linear-gradient(135deg, #3c50e0 0%, #80caee 100%);
        color: white;
        padding: 40px 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .attendance-header h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .attendance-header .info-row {
        display: flex;
        gap: 30px;
        margin-top: 15px;
        font-size: 14px;
    }

    .attendance-header .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .attendance-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-left: 4px solid #3c50e0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: 0.3s;
    }

    .attendance-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 1;
    }

    .student-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3c50e0 0%, #80caee 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }

    .student-name {
        font-weight: 600;
        font-size: 15px;
        color: #1c2434;
    }

    .attendance-buttons {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-attendance {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid #ddd;
        background: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s;
        position: relative;
    }

    .btn-attendance:hover {
        transform: scale(1.05);
        border-color: #666;
    }

    /* Presente - Verde */
    .btn-attendance.btn-present {
        background: #d4edda;
        color: #155724;
        border-color: #155724;
        box-shadow: 0 0 0 3px rgba(21, 87, 36, 0.1);
    }

    .btn-attendance.btn-present:hover {
        background: #c3e6cb;
        box-shadow: 0 0 0 5px rgba(21, 87, 36, 0.2);
    }

    /* Falta - Rojo */
    .btn-attendance.btn-absent {
        background: #f8d7da;
        color: #721c24;
        border-color: #721c24;
        box-shadow: 0 0 0 3px rgba(114, 28, 36, 0.1);
    }

    .btn-attendance.btn-absent:hover {
        background: #f5c6cb;
        box-shadow: 0 0 0 5px rgba(114, 28, 36, 0.2);
    }

    /* Licencia - Azul */
    .btn-attendance.btn-leave {
        background: #d1ecf1;
        color: #0c5460;
        border-color: #0c5460;
        box-shadow: 0 0 0 3px rgba(12, 84, 96, 0.1);
    }

    .btn-attendance.btn-leave:hover {
        background: #bee5eb;
        box-shadow: 0 0 0 5px rgba(12, 84, 96, 0.2);
    }

    /* Retraso - Amarillo (si aplica) */
    .btn-attendance.btn-delay {
        background: #fff3cd;
        color: #856404;
        border-color: #856404;
        box-shadow: 0 0 0 3px rgba(133, 100, 4, 0.1);
    }

    .btn-attendance.btn-delay:hover {
        background: #ffeaa7;
        box-shadow: 0 0 0 5px rgba(133, 100, 4, 0.2);
    }

    /* Estado seleccionado */
    .btn-attendance.active {
        border-width: 3px;
        transform: scale(1.1);
    }

    .attendance-buttons input[type="radio"] {
        display: none;
    }

    .attendance-buttons input[type="radio"]:checked + .btn-attendance {
        border-width: 3px;
        transform: scale(1.1);
    }

    .attendance-buttons input[type="radio"][value="Presente"]:checked + .btn-attendance {
        background: #28a745;
        color: white;
        box-shadow: 0 0 0 5px rgba(40, 167, 69, 0.2);
    }

    .attendance-buttons input[type="radio"][value="Ausente"]:checked + .btn-attendance {
        background: #dc3545;
        color: white;
        box-shadow: 0 0 0 5px rgba(220, 53, 69, 0.2);
    }

    .attendance-buttons input[type="radio"][value="Licencia"]:checked + .btn-attendance {
        background: #17a2b8;
        color: white;
        box-shadow: 0 0 0 5px rgba(23, 162, 184, 0.2);
    }

    .attendance-buttons input[type="radio"][value="Retraso"]:checked + .btn-attendance {
        background: #ffc107;
        color: #333;
        box-shadow: 0 0 0 5px rgba(255, 193, 7, 0.2);
    }

    .form-group-date {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .form-group-date label {
        font-weight: 600;
        color: #1c2434;
        margin-bottom: 0;
        white-space: nowrap;
    }

    .form-group-date input {
        padding: 10px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        transition: 0.3s;
    }

    .form-group-date input:focus {
        outline: none;
        border-color: #3c50e0;
        box-shadow: 0 0 0 3px rgba(60, 80, 224, 0.1);
    }

    .save-button-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 40px;
    }

    .btn-save {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }

    .btn-cancel {
        background: #f8f9fa;
        color: #1c2434;
        border: 2px solid #e2e8f0;
        padding: 15px 40px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        border-color: #cbd5e0;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 16px;
    }

    .btn-label {
        font-size: 12px;
        display: block;
        margin-top: 5px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .attendance-header {
            padding: 25px 15px;
        }

        .attendance-header h2 {
            font-size: 22px;
        }

        .attendance-header .info-row {
            flex-direction: column;
            gap: 10px;
        }

        .attendance-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .attendance-buttons {
            width: 100%;
            justify-content: space-around;
        }

        .btn-attendance {
            width: 45px;
            height: 45px;
            font-size: 12px;
        }

        .form-group-date {
            flex-direction: column;
            align-items: flex-start;
        }

        .save-button-container {
            flex-direction: column;
        }

        .btn-save, .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="attendance-header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h2><?= htmlspecialchars($materia['nombre']); ?></h2>
                <p style="margin-bottom: 0; opacity: 0.9;">Tomar Asistencia</p>
            </div>
            <a href="index.php?controller=Docente&action=gestionTareas&id_materia=<?= $materia['id_materia']; ?>" class="btn btn-light rounded-pill px-4">
                <i class="fas fa-chevron-left me-2"></i> Volver
            </a>
        </div>
        <div class="info-row">
            <div class="info-item">
                <i class="fas fa-users"></i>
                <span><?= count($estudiantes); ?> Estudiantes</span>
            </div>
            <div class="info-item">
                <i class="fas fa-calendar"></i>
                <span id="fecha-display"><?= date('d/m/Y'); ?></span>
            </div>
        </div>
    </div>

    <!-- Selector de Fecha -->
    <div class="form-group-date">
        <label for="fecha">Seleccionar Fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?= date('Y-m-d'); ?>" onchange="actualizarFecha()">
    </div>

    <!-- Formulario de Asistencia -->
    <form action="index.php?controller=Docente&action=guardarAsistencia" method="POST">
        <input type="hidden" name="id_materia" value="<?= $materia['id_materia']; ?>">
        <input type="hidden" name="fecha" id="fecha-hidden" value="<?= date('Y-m-d'); ?>">

        <?php if (count($estudiantes) > 0): ?>
            <?php foreach ($estudiantes as $estudiante): ?>
                <div class="attendance-card">
                    <div class="student-info">
                        <div class="student-avatar">
                            <?= strtoupper(substr($estudiante['nombre_completo'], 0, 1)); ?>
                        </div>
                        <div class="student-name">
                            <?= htmlspecialchars($estudiante['nombre_completo']); ?>
                        </div>
                    </div>
                    <div class="attendance-buttons">
                        <input type="radio" id="presente_<?= $estudiante['id_estudiante']; ?>" 
                               name="asistencia[<?= $estudiante['id_estudiante']; ?>]" 
                               value="Presente" checked>
                        <label for="presente_<?= $estudiante['id_estudiante']; ?>" class="btn-attendance btn-present">
                            <span>P</span>
                            
                        </label>

                        <input type="radio" id="ausente_<?= $estudiante['id_estudiante']; ?>" 
                               name="asistencia[<?= $estudiante['id_estudiante']; ?>]" 
                               value="Ausente">
                        <label for="ausente_<?= $estudiante['id_estudiante']; ?>" class="btn-attendance btn-absent">
                            <span>F</span>
                            
                        </label>

                        <input type="radio" id="licencia_<?= $estudiante['id_estudiante']; ?>" 
                               name="asistencia[<?= $estudiante['id_estudiante']; ?>]" 
                               value="Licencia">
                        <label for="licencia_<?= $estudiante['id_estudiante']; ?>" class="btn-attendance btn-leave">
                            <span>L</span>
                            
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Botones de Guardar -->
            <div class="save-button-container">
                <button type="submit" class="btn-save">
                    <i class="fas fa-check-circle"></i> Guardar Asistencia
                </button>
                <a href="index.php?controller=Docente&action=gestionTareas&id_materia=<?= $materia['id_materia']; ?>" class="btn-cancel">
                    <i class="fas fa-times-circle"></i> Cancelar
                </a>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users-slash"></i>
                <p>No hay estudiantes inscritos en esta materia.</p>
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    function actualizarFecha() {
        const fechaInput = document.getElementById('fecha').value;
        document.getElementById('fecha-hidden').value = fechaInput;
        
        // Actualizar el display
        const date = new Date(fechaInput);
        const opciones = { day: '2-digit', month: '2-digit', year: 'numeric' };
        const fechaFormato = date.toLocaleDateString('es-ES', opciones);
        document.getElementById('fecha-display').textContent = fechaFormato;
    }

    // Mejorar la interacción de los botones circulares
    document.addEventListener('DOMContentLoaded', function() {
        const labels = document.querySelectorAll('.btn-attendance');
        const radios = document.querySelectorAll('input[type="radio"]');

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remover la clase active de todos los botones en este grupo
                const groupName = this.name;
                document.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
                    r.nextElementSibling.classList.remove('active');
                });
                // Agregar la clase active al botón seleccionado
                this.nextElementSibling.classList.add('active');
            });

            // Inicializar los botones seleccionados por defecto
            if (radio.checked) {
                radio.nextElementSibling.classList.add('active');
            }
        });
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>

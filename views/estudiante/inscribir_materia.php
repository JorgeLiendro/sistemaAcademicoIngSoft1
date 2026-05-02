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

    .enroll-container {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    }

    .search-wrapper {
        position: relative;
        margin-bottom: 30px;
    }

    .search-input-pro {
        border-radius: 50px;
        padding: 15px 25px 15px 50px;
        border: 1px solid #e1e5eb;
        background: #f8f9fc;
        font-size: 1rem;
        transition: 0.3s;
        width: 100%;
    }

    .search-input-pro:focus {
        background: white;
        border-color: var(--stadum-red);
        box-shadow: 0 0 0 0.25rem rgba(211, 26, 67, 0.1);
        outline: none;
    }

    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
        font-size: 1.2rem;
    }

    .materia-selection-list {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 10px;
        margin-bottom: 30px;
    }

    .materia-selection-list::-webkit-scrollbar {
        width: 6px;
    }

    .materia-selection-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .materia-selection-list::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    .materia-selection-list::-webkit-scrollbar-thumb:hover {
        background: var(--stadum-red);
    }

    .materia-card-option {
        border: 1px solid #e1e5eb;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 15px;
        transition: 0.3s;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fc;
        position: relative;
    }

    .materia-card-option:hover {
        border-color: var(--stadum-red);
        background: #fff;
        transform: translateX(10px);
    }

    .materia-card-option.selected {
        background: #fff1f3;
        border-color: var(--stadum-red);
        box-shadow: 0 5px 15px rgba(211, 26, 67, 0.1);
    }

    .materia-card-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .materia-info h6 {
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--stadum-dark);
        font-size: 1.1rem;
    }

    .materia-info p {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0;
    }

    .materia-status {
        text-align: right;
    }

    .btn-enroll-now {
        background: var(--stadum-dark);
        color: white;
        border-radius: 50px;
        padding: 15px 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-enroll-now:hover:not(:disabled) {
        background: var(--stadum-red);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(211, 26, 67, 0.2);
    }

    .btn-enroll-now:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-state i {
        font-size: 4rem;
        color: #eee;
        margin-bottom: 20px;
    }
</style>

<div class="estudiante-enroll-view animate__animated animate__fadeIn">
    <!-- Header de Sección -->
    <div class="section-header-banner">
        <div>
            <h2 class="serif fw-bold mb-1 text-dark">Inscripción de Materias</h2>
            <p class="text-muted mb-0"><i class="fas fa-plus-circle me-2 text-danger"></i> Seleccione una asignatura para integrarla a su periodo académico actual.</p>
        </div>
        <a href="index.php?controller=Estudiante&action=dashboard" class="btn btn-outline-secondary rounded-pill px-4 fw-bold small">
            <i class="fas fa-chevron-left me-2"></i> Volver al Panel
        </a>
    </div>

    <!-- Alertas -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 py-3">
            <i class="fas fa-exclamation-triangle me-2"></i> <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Contenedor Principal -->
    <div class="enroll-container">
        <?php if (count($materias) > 0): ?>
            <form action="index.php?controller=Estudiante&action=guardarInscripcion" method="POST" id="enrollForm">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" class="search-input-pro" placeholder="Buscar por nombre de materia o docente...">
                </div>

                <h5 class="serif fw-bold mb-4 text-dark">Oferta Académica Disponible</h5>
                
                <div class="materia-selection-list">
                    <?php foreach ($materias as $materia): ?>
                        <label class="materia-card-option materia-item" for="mat-<?= $materia['id_materia']; ?>">
                            <input type="radio" name="id_materia" id="mat-<?= $materia['id_materia']; ?>" value="<?= $materia['id_materia']; ?>" required>
                            <div class="materia-info">
                                <h6><?= htmlspecialchars($materia['nombre']); ?></h6>
                                <div class="d-flex flex-wrap gap-3 small text-muted mb-2" style="font-size: 12px;">
                                    <span><i class="fas fa-graduation-cap me-1 text-danger"></i> <?= htmlspecialchars($materia['nombre_carrera'] ?? 'General'); ?></span>
                                    <span><i class="fas fa-layer-group me-1 text-danger"></i> <?= $materia['nivel_semestre']; ?>º Semestre</span>
                                    <span><i class="fas fa-users me-1 text-danger"></i> GRP: <?= htmlspecialchars($materia['grupo']); ?></span>
                                    <span><i class="fas fa-clock me-1 text-danger"></i> <?= htmlspecialchars($materia['turno']); ?></span>
                                </div>
                                <p class="small"><i class="fas fa-chalkboard-teacher me-2 text-danger"></i> Docente: <?= htmlspecialchars($materia['nombre_docente']); ?></p>
                            </div>
                            <div class="materia-status">
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-25">
                                    <i class="fas fa-check-circle me-1"></i> Disponible
                                </span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-enroll-now" id="btnEnroll" disabled>
                        <i class="fas fa-user-plus"></i> Procesar Inscripción
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h4 class="text-dark fw-bold">No hay materias disponibles</h4>
                <p class="text-muted">En este momento no existen cupos abiertos para nuevas inscripciones.</p>
                <a href="index.php?controller=Estudiante&action=dashboard" class="btn btn-dark rounded-pill px-5 mt-3">Regresar al Panel</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const materiaCards = document.querySelectorAll('.materia-item');
    const enrollForm = document.getElementById('enrollForm');
    const submitBtn = document.getElementById('btnEnroll');
    const radios = document.querySelectorAll('input[type="radio"]');

    // Filtrado en tiempo real
    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        materiaCards.forEach(card => {
            const text = card.innerText.toLowerCase();
            card.style.display = text.includes(term) ? 'flex' : 'none';
        });
    });

    // Manejo visual de selección
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remover clase de todos
            materiaCards.forEach(c => c.classList.remove('selected'));
            
            // Añadir al seleccionado
            if (this.checked) {
                this.closest('.materia-card-option').classList.add('selected');
                submitBtn.disabled = false;
            }
        });
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>
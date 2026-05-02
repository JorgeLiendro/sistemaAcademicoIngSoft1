<?php 
$show_student_topnav = true;
require_once 'views/layouts/header.php'; 
?>

<style>
    .welcome-banner {
        background: linear-gradient(135deg, var(--stadum-dark) 0%, #1a2537 100%);
        border-radius: 20px;
        padding: 50px;
        color: white;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }

    .welcome-banner::after {
        content: "\f19d";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        right: -20px;
        bottom: -30px;
        font-size: 200px;
        color: rgba(255,255,255,0.05);
        transform: rotate(-15deg);
    }

    .section-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        height: 100%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
    }

    .section-title {
        font-family: 'Crimson Text', serif;
        font-weight: 700;
        font-size: 1.4rem;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--stadum-dark);
    }

    .section-title i {
        color: var(--stadum-red);
    }

    .item-row {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 12px;
        background: #f8f9fc;
        transition: 0.3s;
        border: 1px solid transparent;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .item-row:hover {
        background: white;
        border-color: var(--stadum-red);
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .item-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .bg-light-red { background: #fff1f3; color: var(--stadum-red); }
    .bg-light-blue { background: #f1f6ff; color: var(--stadum-blue); }

    .item-content h6 {
        margin-bottom: 2px;
        font-weight: 700;
        color: var(--stadum-dark);
        font-size: 0.95rem;
    }

    .item-content p {
        margin-bottom: 0;
        font-size: 0.8rem;
        color: #888;
    }

    .badge-date {
        margin-left: auto;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 50px;
        background: white;
        border: 1px solid #eee;
    }

    .empty-state {
        text-align: center;
        padding: 40px 0;
        color: #ccc;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
    }
</style>

<div class="container py-4 animate__animated animate__fadeIn">
    <!-- Banner Principal -->
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="serif fw-bold mb-3">¡Bienvenido de nuevo, <?= explode(' ', $_SESSION['nombre_completo'])[0] ?>!</h1>
                <p class="lead mb-4 opacity-75">Tu carrera en <?= $_SESSION['carrera'] ?> te espera. Revisa tus pendientes y mantente al día con tus estudios.</p>
                <div class="d-flex gap-3">
                    <a href="index.php?controller=Estudiante&action=dashboard" class="btn btn-danger rounded-pill px-4 py-2 fw-bold">
                        Ver Mis Materias
                    </a>
                    <a href="index.php?controller=Estudiante&action=inscribirMateria" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold">
                        Inscribir Materia
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Información de la Universidad -->
        <div class="col-lg-7">
            <div class="section-card">
                <h4 class="section-title">
                    <i class="fas fa-university"></i> Nuestra Institución
                </h4>
                <div class="p-3 bg-light rounded-3 mb-3">
                    <p class="mb-0" style="font-size: 0.95rem; line-height: 1.6; color: #444;">
                        Bienvenidos a **Academic Pro**, el pilar de excelencia académica donde formamos líderes con visión global. Nuestra misión es proporcionar una educación integral de alta calidad, fomentando la investigación, la innovación y el compromiso social.
                    </p>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 small fw-bold text-dark">
                            <i class="fas fa-check-circle text-success"></i> Acreditación Internacional
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 small fw-bold text-dark">
                            <i class="fas fa-microscope text-primary"></i> Centros de Investigación
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Beneficios Estudiantiles -->
        <div class="col-lg-5">
            <div class="section-card" style="border-top: 4px solid var(--stadum-red);">
                <h4 class="section-title">
                    <i class="fas fa-gift"></i> Tus Beneficios
                </h4>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex align-items-start gap-3">
                        <div class="bg-soft-red p-2 rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #fff1f3;">
                            <i class="fas fa-wifi text-danger" style="font-size: 12px;"></i>
                        </div>
                        <div>
                            <div class="fw-bold small">Campus Conectado</div>
                            <div class="smaller text-muted" style="font-size: 11px;">Wi-Fi de alta velocidad en todo el campus.</div>
                        </div>
                    </li>
                    <li class="mb-3 d-flex align-items-start gap-3">
                        <div class="bg-soft-blue p-2 rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #f1f6ff;">
                            <i class="fas fa-book-reader text-primary" style="font-size: 12px;"></i>
                        </div>
                        <div>
                            <div class="fw-bold small">Biblioteca 24/7</div>
                            <div class="smaller text-muted" style="font-size: 11px;">Acceso a repositorios digitales globales.</div>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-3">
                        <div class="bg-soft-green p-2 rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #f0fff4;">
                            <i class="fas fa-heartbeat text-success" style="font-size: 12px;"></i>
                        </div>
                        <div>
                            <div class="fw-bold small">Seguro Médico</div>
                            <div class="smaller text-muted" style="font-size: 11px;">Cobertura integral para todos los estudiantes.</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tareas Pendientes -->
        <div class="col-lg-6">
            <div class="section-card">
                <h4 class="section-title">
                    <i class="fas fa-tasks"></i> Próximos Pendientes
                </h4>
                
                <?php if (count($tareas_pendientes) > 0): ?>
                    <?php foreach ($tareas_pendientes as $tarea): ?>
                        <div class="item-row">
                            <div class="item-icon bg-light-red">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="item-content">
                                <h6><?= htmlspecialchars($tarea['titulo']) ?></h6>
                                <p><?= htmlspecialchars($tarea['materia_nombre']) ?></p>
                            </div>
                            <div class="badge-date text-danger">
                                <?= date('d M', strtotime($tarea['fecha_entrega'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-check-double"></i>
                        <p>No tienes tareas pendientes para hoy.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Material Reciente -->
        <div class="col-lg-6">
            <div class="section-card">
                <h4 class="section-title">
                    <i class="fas fa-copy"></i> Material de Estudio
                </h4>

                <?php if (count($material_reciente) > 0): ?>
                    <?php foreach ($material_reciente as $material): ?>
                        <div class="item-row">
                            <div class="item-icon bg-light-blue">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="item-content">
                                <h6><?= htmlspecialchars($material['titulo']) ?></h6>
                                <p><?= htmlspecialchars($material['materia_nombre']) ?></p>
                            </div>
                            <div class="badge-date">
                                <?= htmlspecialchars($material['tipo']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-folder"></i>
                        <p>No hay material compartido recientemente.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

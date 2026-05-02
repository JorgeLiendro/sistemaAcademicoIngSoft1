<?php require_once 'views/layouts/header.php'; ?>

<style>
    .profile-header-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border-left: 6px solid var(--stadum-red);
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .profile-avatar-wrapper {
        position: relative;
    }

    .profile-avatar-large {
        width: 130px;
        height: 130px;
        border-radius: 25px;
        object-fit: cover;
        border: 5px solid #f8f9fa;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .role-badge-floating {
        position: absolute;
        bottom: -10px;
        right: -10px;
        background: var(--stadum-red);
        color: white;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 3px solid white;
    }

    .info-card-pro {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        height: 100%;
    }

    .info-card-header {
        background: var(--stadum-dark);
        color: white;
        padding: 20px 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .info-card-body {
        padding: 30px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 700;
        color: #888;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .info-value {
        font-weight: 600;
        color: var(--stadum-dark);
        font-size: 0.95rem;
    }

    .btn-edit-profile {
        background: var(--stadum-blue);
        color: white;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border: none;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(11, 28, 57, 0.2);
    }

    .btn-edit-profile:hover {
        background: var(--stadum-red);
        color: white;
        transform: translateY(-3px);
    }

    .section-icon {
        width: 35px;
        height: 35px;
        background: rgba(211, 26, 67, 0.1);
        color: var(--stadum-red);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="profile-view-container animate__animated animate__fadeIn">
    
    <!-- Banner de Perfil -->
    <div class="profile-header-card">
        <div class="profile-avatar-wrapper">
            <img src="<?= !empty($usuario['foto_perfil']) ? $usuario['foto_perfil'] : 'assets/img/perfil1.jpg' ?>" 
                 class="profile-avatar-large" alt="Avatar" onerror="this.src='assets/img/perfil1.jpg'">
            <span class="role-badge-floating"><?= $_SESSION['rol']; ?></span>
        </div>
        <div class="flex-grow-1">
            <h1 class="serif fw-bold mb-1 text-dark"><?= htmlspecialchars($usuario['nombre_completo']); ?></h1>
            <p class="text-muted mb-3"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($usuario['correo_electronico']); ?></p>
            <div class="d-flex gap-2">
                <a href="index.php?controller=Perfil&action=editar" class="btn-edit-profile">
                    <i class="fas fa-user-edit me-2"></i> Editar Perfil
                </a>
                <a href="index.php?controller=Perfil&action=cambiarPassword" class="btn btn-outline-secondary rounded-pill px-4 fw-bold small">
                    <i class="fas fa-key me-2"></i> Seguridad
                </a>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 py-3">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['mensaje']; ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Información Personal -->
        <div class="col-lg-6">
            <div class="info-card-pro">
                <div class="info-card-header">
                    <div class="section-icon bg-white"><i class="fas fa-id-card"></i></div>
                    <h5 class="mb-0 fw-bold serif">Información Personal</h5>
                </div>
                <div class="info-card-body">
                    <div class="info-item">
                        <span class="info-label">Carnet / DNI</span>
                        <span class="info-value"><?= htmlspecialchars($usuario['carnet']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha Nacimiento</span>
                        <span class="info-value"><?= $usuario['fecha_nacimiento'] ? date('d M, Y', strtotime($usuario['fecha_nacimiento'])) : 'No especificada'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Género / Otros</span>
                        <span class="info-value text-muted italic">No definido</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Contacto -->
        <div class="col-lg-6">
            <div class="info-card-pro">
                <div class="info-card-header">
                    <div class="section-icon bg-white"><i class="fas fa-map-marker-alt"></i></div>
                    <h5 class="mb-0 fw-bold serif">Contacto y Ubicación</h5>
                </div>
                <div class="info-card-body">
                    <div class="info-item">
                        <span class="info-label">Teléfono</span>
                        <span class="info-value"><?= $usuario['numero_telefono'] ? htmlspecialchars($usuario['numero_telefono']) : 'Sin teléfono'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dirección</span>
                        <span class="info-value text-end"><?= $usuario['direccion'] ? htmlspecialchars($usuario['direccion']) : 'No especificada'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Ciudad</span>
                        <span class="info-value">Santa Cruz, BO</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Académica (Si aplica) -->
        <?php if (!empty($info_adicional)): ?>
        <div class="col-12">
            <div class="info-card-pro">
                <div class="info-card-header" style="background: var(--stadum-red);">
                    <div class="section-icon bg-white"><i class="fas fa-graduation-cap"></i></div>
                    <h5 class="mb-0 fw-bold serif">Expediente Académico</h5>
                </div>
                <div class="info-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Correo Institucional</span>
                                <span class="info-value text-danger"><?= htmlspecialchars($info_adicional['correo_institucional']); ?></span>
                            </div>
                            <?php if ($_SESSION['rol'] === 'Docente'): ?>
                                <div class="info-item">
                                    <span class="info-label">Nivel de Educación</span>
                                    <span class="info-value"><?= htmlspecialchars($info_adicional['nivel_educacion']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if ($_SESSION['rol'] === 'Docente'): ?>
                                <div class="info-item">
                                    <span class="info-label">Años de Experiencia</span>
                                    <span class="info-value"><?= $info_adicional['experiencia_ensenanza']; ?> Años</span>
                                </div>
                            <?php endif; ?>
                            <div class="info-item">
                                <span class="info-label">Estado de Cuenta</span>
                                <span class="badge bg-success rounded-pill px-3">ACTIVO</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
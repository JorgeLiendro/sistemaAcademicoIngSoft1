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

    .profile-edit-avatar-section {
        background: #f8f9fc;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        border: 1px solid #e1e5eb;
        margin-bottom: 30px;
    }

    .edit-avatar-preview {
        width: 140px;
        height: 140px;
        border-radius: 30px;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        margin-bottom: 20px;
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
    }

    .btn-upload-custom {
        background: white;
        color: var(--stadum-dark);
        border: 1px solid #ddd;
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: 0.3s;
    }

    .btn-upload-custom:hover {
        background: #f8f9fa;
        border-color: #bbb;
    }
</style>

<div class="admin-form-view">
    <!-- Header de Sección -->
    <div class="admin-form-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="serif fw-bold mb-1">Configuración de Perfil</h2>
            <p class="text-muted mb-0">Actualice su información personal y fotografía de identidad.</p>
        </div>
        <a href="index.php?controller=Perfil&action=ver" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-chevron-left me-2"></i> Cancelar
        </a>
    </div>

    <!-- Formulario Profesional -->
    <div class="form-container-pro">
        <form action="index.php?controller=Perfil&action=actualizar" method="POST" enctype="multipart/form-data">
            <div class="row">
                <!-- Columna Izquierda: Foto y Básicos -->
                <div class="col-lg-4 text-center border-end-lg">
                    <h4 class="section-title-pro">Imagen de Perfil</h4>
                    <div class="profile-edit-avatar-section">
                        <img src="<?= !empty($usuario['foto_perfil']) ? $usuario['foto_perfil'] : 'assets/img/perfil1.jpg' ?>" 
                             id="previewFoto" class="edit-avatar-preview" alt="Avatar">
                        
                        <input type="file" class="d-none" id="foto_perfil" name="foto_perfil" accept="image/*">
                        <button type="button" class="btn-upload-custom w-100 mb-2" onclick="document.getElementById('foto_perfil').click()">
                            <i class="fas fa-camera me-2"></i> Subir Nueva Foto
                        </button>
                        <small class="text-muted d-block">Recomendado: 400x400 px</small>
                    </div>
                </div>

                <!-- Columna Derecha: Datos Detallados -->
                <div class="col-lg-8 ps-lg-5">
                    <h4 class="section-title-pro">Información de Cuenta</h4>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label-pro">Nombre Completo *</label>
                            <input type="text" class="form-control form-control-pro" name="nombre_completo" value="<?= htmlspecialchars($usuario['nombre_completo']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-pro">Carnet / Identificación *</label>
                            <input type="text" class="form-control form-control-pro" name="carnet" value="<?= htmlspecialchars($usuario['carnet']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label-pro">Fecha de Nacimiento</label>
                            <input type="date" class="form-control form-control-pro" name="fecha_nacimiento" value="<?= $usuario['fecha_nacimiento']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-pro">Teléfono</label>
                            <input type="tel" class="form-control form-control-pro" name="numero_telefono" value="<?= htmlspecialchars($usuario['numero_telefono']); ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Correo Electrónico *</label>
                        <input type="email" class="form-control form-control-pro" name="correo_electronico" value="<?= htmlspecialchars($usuario['correo_electronico']); ?>" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label-pro">Dirección de Domicilio</label>
                        <textarea class="form-control form-control-pro" name="direccion" rows="3"><?= htmlspecialchars($usuario['direccion']); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 pt-4 border-top">
                <button type="submit" class="btn-save-pro">
                    <i class="fas fa-save me-2"></i> Guardar Cambios en mi Perfil
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('foto_perfil').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewFoto').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

<style>
    @media (min-width: 992px) {
        .border-end-lg { border-right: 1px solid #f0f0f0 !important; }
    }
</style>

<?php require_once 'views/layouts/footer.php'; ?>
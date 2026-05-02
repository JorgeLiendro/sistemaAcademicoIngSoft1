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

    .required-mark { color: var(--stadum-red); }
</style>

<div class="admin-form-view">
    <!-- Header de Sección -->
    <div class="admin-form-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="serif fw-bold mb-1">Registrar Nuevo Usuario</h2>
            <p class="text-muted mb-0">Complete el formulario para dar de alta un nuevo miembro en la institución.</p>
        </div>
        <a href="index.php?controller=Admin&action=gestionUsuarios" class="btn-back-pro">
            <i class="fas fa-chevron-left me-2"></i> Volver al Listado
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
        <form action="index.php?controller=Admin&action=guardarUsuario" method="POST">
            <div class="row">
                <!-- Información Personal -->
                <div class="col-lg-6 pe-lg-5">
                    <h4 class="section-title-pro">Información Personal</h4>
                    
                    <div class="mb-4">
                        <label class="form-label-pro">Nombre Completo <span class="required-mark">*</span></label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control form-control-pro" name="nombre_completo" placeholder="Ej: Juan Pérez García" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Documento de Identidad / Carnet</label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control form-control-pro" name="carnet" placeholder="Número de documento">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Fecha de Nacimiento</label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" class="form-control form-control-pro" name="fecha_nacimiento">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Dirección de Domicilio</label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" class="form-control form-control-pro" name="direccion" placeholder="Calle, Número, Ciudad">
                        </div>
                    </div>
                </div>

                <!-- Credenciales y Contacto -->
                <div class="col-lg-6 ps-lg-5 border-start-lg">
                    <h4 class="section-title-pro">Acceso y Contacto</h4>

                    <div class="mb-4">
                        <label class="form-label-pro">Correo Electrónico <span class="required-mark">*</span></label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control form-control-pro" name="correo_electronico" placeholder="usuario@institucion.edu" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Teléfono de Contacto</label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control form-control-pro" name="numero_telefono" placeholder="+591 ...">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Rol de Usuario <span class="required-mark">*</span></label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-user-tag"></i></span>
                            <select class="form-select form-control-pro" name="rol" required>
                                <option value="" selected disabled>Seleccione un rol...</option>
                                <option value="Estudiante">Estudiante</option>
                                <option value="Docente">Docente</option>
                                <option value="Administrador">Administrador</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4 d-none" id="carrera_container">
                        <label class="form-label-pro">Carrera a Cursar <span class="required-mark">*</span></label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-graduation-cap"></i></span>
                            <select class="form-select form-control-pro" name="id_carrera" id="id_carrera">
                                <option value="" selected disabled>Seleccione una carrera...</option>
                                <?php if(!empty($carreras)): foreach($carreras as $c): ?>
                                    <option value="<?= $c['id_carrera'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-pro">Contraseña Temporal <span class="required-mark">*</span></label>
                        <div class="input-group input-pro-group">
                            <span class="input-group-text-pro"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control form-control-pro" name="contrasena" placeholder="Mínimo 8 caracteres" required>
                        </div>
                        <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i> Se recomienda que el usuario la cambie en su primer inicio.</small>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 pt-3 border-top">
                <button type="submit" class="btn-save-pro">
                    <i class="fas fa-save me-2"></i> Finalizar Registro de Usuario
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rolSelect = document.querySelector('select[name="rol"]');
    const carreraContainer = document.getElementById('carrera_container');
    const carreraSelect = document.getElementById('id_carrera');

    if(rolSelect) {
        rolSelect.addEventListener('change', function() {
            if(this.value === 'Estudiante') {
                carreraContainer.classList.remove('d-none');
                carreraSelect.setAttribute('required', 'required');
            } else {
                carreraContainer.classList.add('d-none');
                carreraSelect.removeAttribute('required');
            }
        });
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>
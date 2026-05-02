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
        max-width: 600px;
        margin: 0 auto;
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
        width: 100%;
    }

    .btn-save-pro:hover {
        background: var(--stadum-red);
        color: white;
        transform: translateY(-3px);
    }

    .security-badge {
        background: #fff5f7;
        color: var(--stadum-red);
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid rgba(211, 26, 67, 0.1);
    }

    .security-icon {
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
</style>

<div class="admin-form-view">
    <!-- Header de Sección -->
    <div class="admin-form-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="serif fw-bold mb-1">Seguridad de la Cuenta</h2>
            <p class="text-muted mb-0">Gestione su contraseña de acceso para mantener su cuenta protegida.</p>
        </div>
        <a href="index.php?controller=Perfil&action=ver" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-chevron-left me-2"></i> Volver al Perfil
        </a>
    </div>

    <!-- Alertas -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['tipo_mensaje']; ?> border-0 shadow-sm mb-4 py-3">
            <i class="fas fa-info-circle me-2"></i> <?php echo $_SESSION['mensaje']; ?>
        </div>
        <?php unset($_SESSION['mensaje']); unset($_SESSION['tipo_mensaje']); ?>
    <?php endif; ?>

    <!-- Formulario de Contraseña -->
    <div class="form-container-pro">
        <div class="security-badge">
            <div class="security-icon"><i class="fas fa-shield-alt"></i></div>
            <div>
                <h6 class="fw-bold mb-0">Protección Activa</h6>
                <p class="small mb-0 opacity-75">Use una combinación fuerte de caracteres.</p>
            </div>
        </div>

        <form action="index.php?controller=Perfil&action=actualizarPassword" method="POST" id="passwordForm">
            <div class="mb-4">
                <label class="form-label-pro">Contraseña Actual *</label>
                <div class="input-group input-pro-group">
                    <span class="input-group-text-pro"><i class="fas fa-lock-open"></i></span>
                    <input type="password" class="form-control form-control-pro" name="password_actual" placeholder="Ingrese su clave actual" required>
                </div>
            </div>

            <hr class="my-4 opacity-50">

            <div class="mb-4">
                <label class="form-label-pro">Nueva Contraseña *</label>
                <div class="input-group input-pro-group">
                    <span class="input-group-text-pro"><i class="fas fa-key"></i></span>
                    <input type="password" class="form-control form-control-pro" id="nuevo_password" name="nuevo_password" 
                           placeholder="Nueva clave" required minlength="6" oninput="checkPasswordStrength(this.value)">
                </div>
                <!-- Indicador de fortaleza -->
                <div class="mt-2">
                    <?php include 'views/perfil/password_strength/indicator.php'; ?>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label-pro">Confirmar Nueva Contraseña *</label>
                <div class="input-group input-pro-group">
                    <span class="input-group-text-pro"><i class="fas fa-check-double"></i></span>
                    <input type="password" class="form-control form-control-pro" id="confirmar_password" name="confirmar_password" 
                           placeholder="Repita la nueva clave" required oninput="checkPasswordMatch()">
                </div>
                <div id="confirmPasswordError" class="text-danger small mt-1 fw-bold"></div>
            </div>

            <div class="mt-5">
                <button type="submit" class="btn-save-pro" id="submitBtn" disabled>
                    <i class="fas fa-shield-alt me-2"></i> Actualizar Contraseña Ahora
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="assets/js/password_strength.js"></script>
<script src="views/perfil/password_strength/script.js"></script>

<?php require_once 'views/layouts/footer.php'; ?>
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

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        object-fit: cover;
        margin-right: 15px;
        border: 2px solid #eee;
    }

    .badge-role {
        padding: 8px 15px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-admin { background: rgba(7, 18, 33, 0.1); color: var(--stadum-dark); }
    .badge-docente { background: rgba(211, 26, 67, 0.1); color: var(--stadum-red); }
    .badge-estudiante { background: rgba(11, 28, 57, 0.1); color: var(--stadum-blue); }

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
</style>

<div class="admin-dashboard-view">
    <!-- Header de Sección -->
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="serif fw-bold mb-1">Gestión de Usuarios</h2>
            <p class="text-muted mb-0">Control centralizado de Administradores, Docentes y Estudiantes.</p>
        </div>
        <a href="index.php?controller=Admin&action=nuevoUsuario" class="btn btn-stadum px-4 py-2">
            <i class="fas fa-plus-circle me-2"></i> Nuevo Registro
        </a>
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
                <input type="hidden" name="action" value="gestionUsuarios">
                
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-custom border-start-0" name="busqueda" 
                            placeholder="Buscar por nombre, carnet o email..." value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select form-control-custom" name="filtroRol">
                        <option value="">Todos los Roles</option>
                        <option value="Administrador" <?= (isset($_GET['filtroRol']) && $_GET['filtroRol'] === 'Administrador') ? 'selected' : '' ?>>Administradores</option>
                        <option value="Docente" <?= (isset($_GET['filtroRol']) && $_GET['filtroRol'] === 'Docente') ? 'selected' : '' ?>>Docentes</option>
                        <option value="Estudiante" <?= (isset($_GET['filtroRol']) && $_GET['filtroRol'] === 'Estudiante') ? 'selected' : '' ?>>Estudiantes</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-search w-100">
                        <i class="fas fa-filter me-2"></i> Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table custom-table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Identificación</th>
                        <th>Nombre Completo</th>
                        <th>Carnet / DNI</th>
                        <th>Rol</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td class="fw-bold text-muted">#<?= str_pad($usuario['id_usuario'], 4, '0', STR_PAD_LEFT); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= !empty($usuario['foto_perfil']) ? $usuario['foto_perfil'] : 'assets/img/perfil1.jpg' ?>" 
                                     class="user-avatar" alt="User" onerror="this.src='assets/img/perfil1.jpg'">
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($usuario['nombre_completo']); ?></div>
                                    <div class="small text-muted"><?= $usuario['correo_electronico']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-dark fw-medium"><?= $usuario['carnet']; ?></span>
                        </td>
                        <td>
                            <?php 
                                $roleClass = '';
                                if($usuario['rol'] === 'Administrador') $roleClass = 'badge-admin';
                                elseif($usuario['rol'] === 'Docente') $roleClass = 'badge-docente';
                                else $roleClass = 'badge-estudiante';
                            ?>
                            <span class="badge-role <?= $roleClass; ?>"><?= $usuario['rol']; ?></span>
                        </td>
                        <td class="text-end">
                            <a href="index.php?controller=Admin&action=editarUsuario&id=<?= $usuario['id_usuario']; ?>" 
                               class="btn-circle btn-edit-pro" title="Editar">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                            <a href="index.php?controller=Admin&action=eliminarUsuario&id=<?= $usuario['id_usuario']; ?>" 
                               class="btn-circle btn-delete-pro" title="Eliminar"
                               onclick="return confirm('¿Está seguro de eliminar permanentemente a este usuario?')">
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

<?php require_once 'views/layouts/footer.php'; ?>
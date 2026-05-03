<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lógica Global (Notificaciones, Perfil y Materias Docente)
if (isset($_SESSION['id_usuario'])) {
    require_once 'controllers/AdminController.php';
    $adminController = new AdminController();
    $notificaciones_data = $adminController->obtenerNotificaciones();
    
    $notificaciones = $notificaciones_data['notificaciones'];
    $notificaciones_sin_leer = $notificaciones_data['notificaciones_sin_leer'];

    // Cargar materias si es docente para el sidebar
    $materias_sidebar = [];
    if ($_SESSION['rol'] === 'Docente') {
        require_once 'models/DocenteModel.php';
        $docenteModel = new DocenteModel();
        $db = (new Database())->connect();
        $stmt = $db->prepare("SELECT id_docente FROM Docente WHERE id_usuario = ?");
        $stmt->execute([$_SESSION['id_usuario']]);
        $docente_sidebar = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($docente_sidebar) {
            $materias_sidebar = $docenteModel->obtenerMateriasDocente($docente_sidebar['id_docente']);
        }
    }

    if (!isset($_SESSION['foto_perfil'])) {
        $db = (new Database())->connect();
        $stmt = $db->prepare("SELECT foto_perfil FROM usuario WHERE id_usuario = ?");
        $stmt->execute([$_SESSION['id_usuario']]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['foto_perfil'] = $user_data['foto_perfil'] ?? 'assets/img/perfil1.jpg';
    }

    // LÓGICA DE NAVEGACIÓN PARA ESTUDIANTE
    $is_student = ($_SESSION['rol'] === 'Estudiante');
    $current_action = $_GET['action'] ?? 'dashboard';
    $in_course = ($is_student && in_array($current_action, ['verTareas', 'entregarTarea']));
    if (!isset($show_student_topnav)) {
        $show_student_topnav = ($is_student && !$in_course);
    }

} else {
    $notificaciones = [];
    $notificaciones_sin_leer = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Pro - Gestión Académica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Crimson+Text:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --stadum-red: #d31a43;
            --stadum-dark: #071221;
            --stadum-blue: #0b1c39;
            --sidebar-bg: #071221;
            --sidebar-hover: rgba(255,255,255,0.05);
            --body-bg: #f1f5f9;
            --text-main: #64748b;
            --upds-blue-pro: #3c50e0;
        }

        body { font-family: 'Inter', sans-serif; color: var(--text-main); background-color: var(--body-bg); margin: 0; }
        .serif { font-family: 'Crimson Text', serif; }

        /* --- ESTILOS LANDING PAGE (DISEÑO PROFESIONAL ORIGINAL) --- */
        .top-bar { background: var(--stadum-dark); color: white; padding: 8px 0; font-size: 13px; }
        .top-bar a { color: #ccc; text-decoration: none; transition: 0.3s; }
        .top-bar a:hover { color: white; }

        .middle-header { padding: 25px 0; background: white; }
        .contact-info-box { display: flex; align-items: center; margin-left: 30px; }
        .contact-info-box i {
            font-size: 24px; color: var(--stadum-red); margin-right: 15px;
            background: #fdf0f2; padding: 12px; border-radius: 5px;
        }
        .contact-info-box .text-label { font-size: 12px; color: #888; display: block; }
        .contact-info-box .text-value { font-size: 14px; font-weight: 600; color: var(--stadum-dark); }

        .main-nav { background: white; border-top: 1px solid #eee; position: sticky; top: 0; z-index: 1000; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .nav-link-landing { color: var(--stadum-dark) !important; font-weight: 500; padding: 20px 15px !important; text-decoration: none; display: inline-block; }
        .nav-link-landing:hover { color: var(--stadum-red) !important; }

        .btn-get-info {
            background: var(--stadum-blue); color: white; border: none;
            padding: 15px 30px; font-weight: 600; transition: 0.3s;
        }
        .btn-get-info:hover { background: var(--stadum-red); color: white; }

        .btn-stadum {
            background: var(--stadum-red); color: white; border-radius: 4px;
            padding: 12px 25px; font-weight: 600; border: none;
        }

        /* --- ESTILOS APP DASHBOARD --- */
        .app-container { display: flex; min-height: 100vh; }
        .app-sidebar {
            width: 280px; background: var(--sidebar-bg); color: #dee4ee;
            position: fixed; height: 100vh; z-index: 1200; transition: all 0.3s;
            overflow-y: auto;
        }
        .sidebar-header { padding: 30px 25px; display: flex; align-items: center; gap: 12px; }
        .sidebar-logo { font-size: 22px; font-weight: 700; color: #fff; text-decoration: none; text-transform: uppercase; }
        .sidebar-logo span { color: var(--stadum-red); }
        .sidebar-nav { padding: 0 15px; }
        .nav-item-link {
            display: flex; align-items: center; padding: 12px 15px; color: #dee4ee;
            text-decoration: none; border-radius: 6px; margin-bottom: 5px; transition: 0.3s;
        }
        .nav-item-link i { width: 22px; font-size: 18px; margin-right: 12px; }
        .nav-item-link:hover, .nav-item-link.active { background-color: var(--sidebar-hover); color: var(--stadum-red); }

        .submenu-list { list-style: none; padding-left: 35px; margin-bottom: 10px; }
        .submenu-item-link { display: block; padding: 8px 15px; color: #8a99af; text-decoration: none; font-size: 13px; border-radius: 4px; }
        .submenu-item-link:hover { color: #fff; background: var(--sidebar-hover); }

        .main-content { flex-grow: 1; margin-left: 280px; display: flex; flex-direction: column; min-height: 100vh; transition: 0.3s; }
        .app-topbar {
            height: 80px; background: #fff; display: flex; align-items: center; justify-content: space-between;
            padding: 0 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.03); position: sticky; top: 0; z-index: 1100;
        }

        .topbar-search { width: 300px; position: relative; }
        .topbar-search input {
            width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px; border: 1px solid #e2e8f0;
            background: #f8fafc; font-size: 14px;
        }
        .topbar-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }

        .topbar-actions { display: flex; align-items: center; gap: 20px; }
        .topbar-btn {
            width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
            color: var(--upds-blue-pro); font-size: 20px; text-decoration: none; position: relative; border-radius: 50%;
        }
        .topbar-btn:hover { background: #f1f5f9; }
        .btn-badge { position: absolute; top: 8px; right: 8px; width: 10px; height: 10px; background: var(--stadum-red); border-radius: 50%; border: 2px solid #fff; }

        .user-profile-top { display: flex; align-items: center; gap: 12px; text-decoration: none; cursor: pointer; }
        .user-info-top { text-align: right; line-height: 1.2; }
        .user-name-top { display: block; font-size: 14px; font-weight: 600; color: var(--upds-blue-pro); }
        .user-role-top { display: block; font-size: 11px; color: #94a3b8; }
        .user-avatar-top { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }

        .content-body { padding: 40px; }

        @media (max-width: 991px) {
            .app-sidebar { margin-left: -280px; }
            .main-content { margin-left: 0; }
            .middle-header .contact-info-box { display: none; }
        }

        /* --- ESTILOS NAVEGACIÓN ESTUDIANTE --- */
        <?php if (isset($show_student_topnav) && $show_student_topnav): ?>
            .app-sidebar { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .app-topbar { display: none !important; }
        <?php endif; ?>

        .student-nav-bar { background: var(--sidebar-bg); color: white; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .student-nav-menu { background: var(--sidebar-bg); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .student-nav-menu .nav-link { 
            color: #dee4ee !important; 
            padding: 15px 20px !important; 
            font-size: 14px; 
            font-weight: 500;
            transition: 0.3s;
            border-bottom: 3px solid transparent;
        }
        .student-nav-menu .nav-link:hover { color: var(--stadum-red) !important; background: var(--sidebar-hover); }
        .student-nav-menu .nav-link.active { 
            color: var(--stadum-red) !important; 
            font-weight: 600;
            background: var(--sidebar-hover);
            border-bottom: 3px solid var(--stadum-red);
        }
        .student-dropdown-menu { background: var(--sidebar-bg) !important; border: 1px solid rgba(255,255,255,0.1) !important; }
        .student-dropdown-menu .dropdown-item { color: #dee4ee !important; transition: 0.3s; }
        .student-dropdown-menu .dropdown-item:hover { background: var(--sidebar-hover) !important; color: var(--stadum-red) !important; }
        .student-dropdown-menu .dropdown-item i { color: var(--stadum-red) !important; }

        <?php if (isset($show_student_topnav) && $show_student_topnav): ?>
        .main-content { margin-left: 0 !important; width: 100% !important; }
        .app-sidebar, .app-topbar { display: none !important; }
        <?php endif; ?>
    </style>
</head>
<body>

<?php if(isset($_SESSION['id_usuario'])): ?>
    <!-- --- ESTRUCTURA PANEL PROFESIONAL --- -->
    <div class="app-container" style="<?php if (isset($show_student_topnav) && $show_student_topnav) echo 'flex-direction: column;'; ?>">

        <?php if (isset($show_student_topnav) && $show_student_topnav): ?>
            <!-- TOPNAV ESTUDIANTE (SIN SIDEBAR) -->
            <div style="width: 100%; display: flex; flex-direction: column; z-index: 1000; position: sticky; top: 0;">
                <header class="student-nav-bar">
                    <div class="container d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <div style="text-align: center; line-height: 1.1; margin-right: 15px; color: white;">
                                <i class="fas fa-graduation-cap" style="font-size: 24px; color: var(--stadum-red);"></i><br>
                                <span style="font-size: 11px; font-weight: bold; text-transform: uppercase;">Academic Pro</span><br>
                            </div>
                            <div style="font-size: 20px; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 15px; margin-left: 5px; color: white;">
                                <span class="fw-bold">Estudiante<span style="color:var(--stadum-red);">Portal</span></span> <span style="font-size: 15px; font-weight: 300; color:rgba(255,255,255,0.7);"><?= htmlspecialchars($_SESSION['carrera'] ?? 'Sistema de Consulta Académica') ?></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-end d-none d-md-block">
                                <div style="font-size: 14px; color:white; font-weight: 600;"><?= htmlspecialchars($_SESSION['nombre_completo'] ?? 'Estudiante') ?></div>
                                <div style="font-size: 11px; color:rgba(255,255,255,0.6);"><?= htmlspecialchars($_SESSION['correo_electronico'] ?? '') ?></div>
                            </div>
                            <img src="<?= htmlspecialchars($_SESSION['foto_perfil']) ?>" class="rounded-circle" width="44" height="44" style="border: 2px solid rgba(255,255,255,0.5); object-fit: cover;" onerror="this.src='assets/img/perfil1.jpg'">
                            <a href="index.php?controller=Auth&action=logout" class="text-white text-decoration-none ms-2" title="Salir">
                                <i class="fas fa-sign-out-alt" style="font-size: 20px;"></i>
                            </a>
                        </div>
                    </div>
                </header>
                <div class="student-nav-menu">
                    <div class="container">
                        <ul class="nav">
                            <?php $current_action = $_GET['action'] ?? ''; ?>
                            <li class="nav-item">
                                <a href="index.php?controller=Estudiante&action=inicio" target="_self" class="nav-link <?= $current_action == 'inicio' ? 'active' : '' ?>">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?controller=Estudiante&action=dashboard" target="_self" class="nav-link <?= $current_action == 'dashboard' ? 'active' : '' ?>">Histórico Registro</a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?controller=Estudiante&action=evaluacionDocente" target="_self" class="nav-link <?= $current_action == 'evaluacionDocente' ? 'active' : '' ?>">Evaluación Docente</a>
                            </li>
                            <li class="nav-item">
                                <a href="index.php?controller=Estudiante&action=inscribirMateria" target="_self" class="nav-link <?= $current_action == 'inscribirMateria' ? 'active' : '' ?>">Seguimiento & Registro</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Comunicación</a>
                                <ul class="dropdown-menu shadow border-0 student-dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-2"></i>Mensajes</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <aside class="app-sidebar">
            <div class="sidebar-header">
                <a href="index.php?controller=<?php echo $_SESSION['rol']; ?>&action=dashboard" class="sidebar-logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Academic <span>Pro</span></span>
                </a>
            </div>

            <div class="sidebar-nav">
                <a href="index.php?controller=<?php echo $_SESSION['rol']; ?>&action=dashboard" class="nav-item-link active">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>

                <?php if($_SESSION['rol'] === 'Administrador'): ?>
                    <div class="sidebar-section-title">ADMINISTRACIÓN</div>
                    <a href="index.php?controller=Admin&action=gestionUsuarios" class="nav-item-link"><i class="fas fa-users-cog"></i> Usuarios</a>
                    <a href="index.php?controller=Admin&action=gestionMaterias" class="nav-item-link"><i class="fas fa-book"></i> Materias</a>
                    <a href="index.php?controller=Admin&action=gestionCarreras" class="nav-item-link"><i class="fas fa-graduation-cap"></i> Carreras</a>
                    <a href="index.php?controller=Admin&action=gestionPeriodos" class="nav-item-link"><i class="fas fa-calendar-alt"></i> Periodos Académicos</a>
                    <a href="index.php?controller=Admin&action=reportes" class="nav-item-link"><i class="fas fa-chart-bar"></i> Reportes</a>
                    <?php endif; ?>

                <?php if($_SESSION['rol'] === 'Docente'): ?>
                    <div class="sidebar-section-title">DOCENCIA</div>
                    <a class="nav-item-link" data-bs-toggle="collapse" href="#collapseMaterias">
                        <i class="fas fa-book-open"></i> Mis Materias <i class="fas fa-chevron-down ms-auto small"></i>
                    </a>
                    <div class="collapse show" id="collapseMaterias">
                        <ul class="submenu-list">
                            <?php foreach($materias_sidebar as $mat): ?>
                                <li><a href="index.php?controller=Docente&action=gestionTareas&id_materia=<?= $mat['id_materia'] ?>" class="submenu-item-link"><?= htmlspecialchars($mat['nombre']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(isset($in_course) && $in_course): ?>
                    <div class="sidebar-section-title" style="margin-top: 15px; font-size: 11px; color: #8a99af; font-weight: 600; padding: 10px 15px;">MENÚ DEL CURSO</div>
                    <a href="index.php?controller=Estudiante&action=dashboard" class="nav-item-link" style="background: rgba(255,255,255,0.05); color: #fff;">
                        <i class="fas fa-arrow-left"></i> Volver al Histórico
                    </a>
                    <a href="#" class="nav-item-link active mt-2">
                        <i class="fas fa-tasks"></i> Tareas y Contenido
                    </a>
                <?php endif; ?>

                <a href="index.php?controller=Auth&action=logout" class="nav-item-link text-danger mt-4"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="app-topbar">
                <div class="topbar-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar en el sistema...">
                </div>

                <div class="topbar-actions">
                    <div class="dropdown">
                        <a href="#" class="topbar-btn" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <?php if($notificaciones_sin_leer > 0): ?><span class="btn-badge"></span><?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3" style="width: 300px; border-radius: 12px;">
                            <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                                <span class="fw-bold small">Notificaciones</span>
                                <a href="#" class="text-danger tiny text-decoration-none" style="font-size:10px;">Marcar leídas</a>
                            </div>
                            <div style="max-height: 250px; overflow-y: auto;">
                                <?php if(empty($notificaciones)): ?>
                                    <div class="p-3 text-center text-muted small">Sin avisos nuevos</div>
                                <?php else: ?>
                                    <?php foreach($notificaciones as $not): ?>
                                        <div class="p-3 border-bottom small">
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($not['titulo']) ?></div>
                                            <div class="text-muted"><?= htmlspecialchars($not['mensaje']) ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <div class="user-profile-top dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-info-top d-none d-md-block">
                                <span class="user-name-top"><?php echo htmlspecialchars($_SESSION['rol']); ?></span>
                                <span class="user-role-top">En línea</span>
                            </div>
                            <img src="<?php echo htmlspecialchars($_SESSION['foto_perfil']); ?>" class="user-avatar-top" onerror="this.src='assets/img/perfil1.jpg'">
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="border-radius: 12px;">
                            <li><a class="dropdown-item py-2" href="index.php?controller=Perfil&action=ver">Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger fw-bold" href="index.php?controller=Auth&action=logout">Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            <div class="content-body">
<?php else: ?>
    <!-- --- ESTRUCTURA LANDING PAGE DISEÑO PROFESIONAL --- -->
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="top-links">
                <a href="#" class="me-3">Estudiantes <i class="fas fa-chevron-down ms-1 small"></i></a>
                <a href="#" class="me-3">Personal</a>
                <a href="#" class="me-3">Alumni</a>
                <a href="#" class="me-3">Facultad</a>
            </div>
            <div class="top-auth">
                <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-user me-1"></i> Iniciar Sesión / Registro</a>
            </div>
        </div>
    </div>

    <div class="middle-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="index.php">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRmzBETipial1jCQY_RZffOUe_9BRm0EYWl-A&s" alt="Logo" height="50">
            </a>
            <div class="d-none d-lg-flex">
                <div class="contact-info-box">
                    <i class="fas fa-map-marker-alt"></i>
                    <div><span class="text-label">Dirección</span><span class="text-value">Av. Principal 123, Santa Cruz</span></div>
                </div>
                <div class="contact-info-box">
                    <i class="fas fa-envelope"></i>
                    <div><span class="text-label">Correo Institucional</span><span class="text-value">info@academicpro.edu</span></div>
                </div>
            </div>
        </div>
    </div>

    <nav class="main-nav">
        <div class="container p-0">
            <div class="d-flex align-items-stretch">
                <button class="btn-get-info d-none d-md-block">Más Información <i class="fas fa-arrow-right ms-2"></i></button>
                <div class="flex-grow-1 d-flex align-items-center px-4">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link-landing" href="#inicio">Inicio +</a></li>
                        <li class="nav-item"><a class="nav-link-landing" href="#nosotros">Nosotros</a></li>
                        <li class="nav-item"><a class="nav-link-landing" href="#programas">Programas +</a></li>
                        <li class="nav-item"><a class="nav-link-landing" href="#contacto">Contacto</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Modal Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h4 class="fw-bold serif mb-0">Inicia sesión en tu cuenta</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="index.php?controller=Auth&action=login" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control bg-light border-0 py-3" required placeholder="ejemplo@correo.com">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">Contraseña</label>
                            <input type="password" name="contrasena" class="form-control bg-light border-0 py-3" required placeholder="********">
                        </div>
                        <button type="submit" class="btn btn-stadum w-100 py-3 mb-3 shadow-sm">ENTRAR AHORA</button>
                        <p class="text-center small text-muted mb-0">¿No tienes cuenta? <a href="#" class="text-danger fw-bold text-decoration-none">Contáctanos</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
<?php endif; ?>

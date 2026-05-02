<?php require_once 'views/layouts/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-bell me-2"></i>Todas las notificaciones</h1>
        <a href="index.php?controller=Estudiante&action=dashboard" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Volver al panel
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($notificaciones)): ?>
                <div class="text-center py-5">
                    <i class="far fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay notificaciones</h4>
                </div>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($notificaciones as $notificacion): ?>
                        <li class="list-group-item <?php echo $notificacion['leida'] ? '' : 'bg-light'; ?>">
                            <div class="d-flex justify-content-between">
                                <div class="me-3">
                                    <i class="fas <?php echo $notificacion['icono']; ?> fa-2x text-<?php echo $notificacion['tipo']; ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold"><?php echo $notificacion['titulo']; ?></div>
                                    <div class="text-muted"><?php echo $notificacion['mensaje']; ?></div>
                                    <div class="small text-muted mt-2">
                                        <i class="far fa-clock me-1"></i>
                                        <?php echo $notificacion['fecha']; ?>
                                    </div>
                                </div>
                                <?php if (!$notificacion['leida']): ?>
                                    <div class="ms-3">
                                        <a href="index.php?controller=Estudiante&action=marcarNotificacionLeida&id=<?php echo $notificacion['id']; ?>" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-check"></i> Marcar como leída
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
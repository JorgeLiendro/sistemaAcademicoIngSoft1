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

    .notif-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .notif-list-header {
        background: var(--stadum-dark);
        color: white;
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notif-card {
        padding: 25px 30px;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s;
        display: flex;
        gap: 20px;
        position: relative;
    }

    .notif-card:hover {
        background-color: #fcfcfc;
    }

    .notif-card.unread {
        background-color: #fef9fa;
        border-left: 4px solid var(--stadum-red);
    }

    .notif-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .bg-light-red { background: rgba(211, 26, 67, 0.1); color: var(--stadum-red); }
    .bg-light-blue { background: rgba(11, 28, 57, 0.1); color: var(--stadum-blue); }
    .bg-light-success { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .bg-light-info { background: rgba(13, 202, 240, 0.1); color: #0dcaf0; }

    .notif-content {
        flex-grow: 1;
    }

    .notif-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .notif-title {
        font-weight: 700;
        color: var(--stadum-dark);
        font-size: 1.1rem;
    }

    .notif-time {
        font-size: 0.8rem;
        color: #999;
        font-weight: 500;
    }

    .notif-message {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .unread-indicator {
        width: 10px;
        height: 10px;
        background: var(--stadum-red);
        border-radius: 50%;
        display: inline-block;
        margin-left: 10px;
    }

    .empty-notif {
        padding: 100px 30px;
        text-align: center;
    }

    .empty-notif i {
        font-size: 4rem;
        color: #eee;
        margin-bottom: 20px;
    }
</style>

<div class="admin-notif-view">
    <!-- Header de Sección -->
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="serif fw-bold mb-1">Centro de Notificaciones</h2>
            <p class="text-muted mb-0">Historial completo de alertas, actualizaciones y eventos del sistema.</p>
        </div>
        <?php if(!empty($notificaciones)): ?>
            <a href="index.php?controller=Admin&action=marcarNotificacionesLeidas" class="btn btn-stadum px-4 py-2">
                <i class="fas fa-check-double me-2"></i> Marcar todas como leídas
            </a>
        <?php endif; ?>
    </div>

    <!-- Lista de Notificaciones -->
    <div class="notif-container">
        <div class="notif-list-header">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list-ul me-2"></i> Historial Reciente</h5>
            <span class="badge bg-danger rounded-pill"><?= count($notificaciones) ?> Total</span>
        </div>

        <?php if (empty($notificaciones)): ?>
            <div class="empty-notif">
                <i class="fas fa-bell-slash"></i>
                <h4 class="text-muted">No hay notificaciones para mostrar</h4>
                <p class="text-muted">Le avisaremos cuando ocurra algo importante en el sistema.</p>
            </div>
        <?php else: ?>
            <div class="notif-list">
                <?php foreach ($notificaciones as $notificacion): ?>
                    <div class="notif-card <?= !$notificacion['leida'] ? 'unread' : '' ?>">
                        <div class="notif-icon-box <?= 'bg-light-' . ($notificacion['tipo'] ?? 'red') ?>">
                            <i class="fas <?= $notificacion['icono'] ?? 'fa-info-circle' ?>"></i>
                        </div>
                        <div class="notif-content">
                            <div class="notif-title-row">
                                <div class="notif-title">
                                    <?= htmlspecialchars($notificacion['titulo'] ?? 'Actualización de Sistema') ?>
                                    <?php if(!$notificacion['leida']): ?>
                                        <span class="unread-indicator"></span>
                                    <?php endif; ?>
                                </div>
                                <div class="notif-time">
                                    <i class="far fa-clock me-1"></i>
                                    <?= date('d M, Y - H:i', strtotime($notificacion['fecha'])) ?>
                                </div>
                            </div>
                            <div class="notif-message">
                                <?= htmlspecialchars($notificacion['mensaje']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
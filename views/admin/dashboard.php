<?php require_once 'views/layouts/header.php'; ?>

<style>
    .card-stat {
        background: #fff;
        padding: 25px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .icon-box {
        width: 45px;
        height: 45px;
        background: #eff4fb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3c50e0;
        font-size: 20px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1c2434;
        margin-bottom: 5px;
    }

    .stat-title {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }

    .stat-trend {
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .trend-up { color: #10b981; }
    .trend-down { color: #f43f5e; }

    /* Estilo del Gráfico Simulado */
    .chart-card {
        background: #fff;
        padding: 30px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        height: 450px;
    }

    .chart-title {
        font-size: 20px;
        font-weight: 700;
        color: #1c2434;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chart-placeholder {
        width: 100%;
        height: 300px;
        background: linear-gradient(0deg, #f8fafc 0%, #fff 100%);
        border-bottom: 2px solid #e2e8f0;
        position: relative;
        display: flex;
        align-items: flex-end;
        gap: 20px;
        padding: 0 20px;
    }

    .bar {
        flex: 1;
        background: #3c50e0;
        border-radius: 4px 4px 0 0;
        transition: 0.3s;
        position: relative;
    }

    .bar:nth-child(even) { background: #80caee; }
    .bar:hover { opacity: 0.8; }
</style>

<!-- FILA DE ESTADÍSTICAS -->
<div class="row g-4 mb-4">
    <!-- Total Usuarios -->
    <div class="col-md-3">
        <div class="card-stat">
            <div>
                <div class="stat-value"><?php echo count($usuarios); ?></div>
                <div class="stat-title">Total Usuarios</div>
                <div class="stat-trend trend-up mt-2">
                    0.43% <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="icon-box">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Total Materias -->
    <div class="col-md-3">
        <div class="card-stat">
            <div>
                <div class="stat-value"><?php echo count($materias); ?></div>
                <div class="stat-title">Total Materias</div>
                <div class="stat-trend trend-up mt-2">
                    4.35% <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="icon-box">
                <i class="fas fa-book-open"></i>
            </div>
        </div>
    </div>

    <!-- Pendientes -->
    <div class="col-md-3">
        <div class="card-stat">
            <div>
                <div class="stat-value">2.450</div>
                <div class="stat-title">Solicitudes</div>
                <div class="stat-trend trend-up mt-2">
                    2.59% <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="icon-box">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    <div class="col-md-3">
        <div class="card-stat">
            <div>
                <div class="stat-value">3.456</div>
                <div class="stat-title">Mensajes</div>
                <div class="stat-trend trend-down mt-2">
                    0.95% <i class="fas fa-arrow-down"></i>
                </div>
            </div>
            <div class="icon-box">
                <i class="fas fa-comment-dots"></i>
            </div>
        </div>
    </div>
</div>

<!-- FILA DE GRÁFICOS -->
<div class="row g-4">
    <!-- Gráfico Principal -->
    <div class="col-lg-8">
        <div class="chart-card">
            <div class="chart-title">
                Resumen Académico Semanal
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-secondary">Día</button>
                    <button class="btn btn-sm btn-outline-secondary active">Semana</button>
                    <button class="btn btn-sm btn-outline-secondary">Mes</button>
                </div>
            </div>
            <div class="chart-placeholder">
                <div class="bar" style="height: 60%;"></div>
                <div class="bar" style="height: 80%;"></div>
                <div class="bar" style="height: 45%;"></div>
                <div class="bar" style="height: 90%;"></div>
                <div class="bar" style="height: 55%;"></div>
                <div class="bar" style="height: 70%;"></div>
                <div class="bar" style="height: 85%;"></div>
            </div>
            <div class="d-flex justify-content-between mt-3 text-muted small">
                <span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
            </div>
        </div>
    </div>

    <!-- Resumen Secundario -->
    <div class="col-lg-4">
        <div class="chart-card">
            <div class="chart-title">Distribución de Roles</div>
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-bold">Administradores</span>
                    <span class="badge bg-primary rounded-pill">12%</span>
                </div>
                <div class="progress mb-4" style="height: 8px;">
                    <div class="progress-bar" role="progressbar" style="width: 12%"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-bold">Docentes</span>
                    <span class="badge bg-info rounded-pill">35%</span>
                </div>
                <div class="progress mb-4" style="height: 8px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 35%"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-bold">Estudiantes</span>
                    <span class="badge bg-success rounded-pill">53%</span>
                </div>
                <div class="progress mb-4" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 53%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
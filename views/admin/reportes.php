<?php require_once 'views/layouts/header.php'; ?>

<style>
    .reportes-header {
        background: linear-gradient(135deg, #3c50e0 0%, #80caee 100%);
        color: white;
        padding: 40px 25px;
        border-radius: 10px;
        margin-bottom: 40px;
        text-align: center;
    }

    .reportes-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .reportes-header p {
        font-size: 16px;
        opacity: 0.95;
        margin: 0;
    }

    .reportes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .reporte-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 2px solid #f0f0f0;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        gap: 15px;
        text-decoration: none;
        color: inherit;
    }

    .reporte-card:hover {
        border-color: #3c50e0;
        box-shadow: 0 10px 30px rgba(60, 80, 224, 0.15);
        transform: translateY(-5px);
    }

    .reporte-icon {
        font-size: 48px;
        color: #3c50e0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 70px;
    }

    .reporte-card:nth-child(2) .reporte-icon { color: #10b981; }
    .reporte-card:nth-child(3) .reporte-icon { color: #f59e0b; }
    .reporte-card:nth-child(4) .reporte-icon { color: #ef4444; }
    .reporte-card:nth-child(5) .reporte-icon { color: #8b5cf6; }
    .reporte-card:nth-child(6) .reporte-icon { color: #ec4899; }

    .reporte-title {
        font-size: 18px;
        font-weight: 700;
        color: #1c2434;
    }

    .reporte-description {
        font-size: 14px;
        color: #888;
        line-height: 1.5;
        flex-grow: 1;
    }

    .reporte-btn {
        background: linear-gradient(135deg, #3c50e0 0%, #80caee 100%);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.3s;
        text-align: center;
        display: inline-block;
    }

    .reporte-card:hover .reporte-btn {
        background: linear-gradient(135deg, #2a3bb7 0%, #6ba5d5 100%);
        transform: translateY(-2px);
    }

    .reporte-card:nth-child(2) .reporte-btn { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); }
    .reporte-card:nth-child(3) .reporte-btn { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); }
    .reporte-card:nth-child(4) .reporte-btn { background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); }
    .reporte-card:nth-child(5) .reporte-btn { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); }
    .reporte-card:nth-child(6) .reporte-btn { background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%); }

    .info-box {
        background: #f0f7ff;
        border-left: 4px solid #3c50e0;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .info-box i {
        color: #3c50e0;
        margin-right: 10px;
        font-size: 18px;
    }

    .info-box p {
        margin: 0;
        color: #1c2434;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .reportes-header h1 {
            font-size: 24px;
        }

        .reportes-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .reporte-card {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="reportes-header">
        <h1> Centro de Reportes</h1>
        
    </div>

    

    <!-- Grid de Reportes -->
    <div class="reportes-grid">
        <!-- Reporte 1: Desempeño por Carrera -->
        <a href="index.php?controller=Admin&action=generarReporte&tipo=desempenoCarrera" class="reporte-card">
            <div class="reporte-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="reporte-title">Desempeño por Carrera</div>
            <div class="reporte-description">
                Análisis del promedio de calificaciones, cantidad de estudiantes y mejores desempeños por carrera.
            </div>
            <button class="reporte-btn"><i class="fas fa-arrow-right me-2"></i> Ver Reporte</button>
        </a>

        <!-- Reporte 2: Docentes -->
        <a href="index.php?controller=Admin&action=generarReporte&tipo=docentes" class="reporte-card">
            <div class="reporte-icon">
                <i class="fas fa-chalkboard-user"></i>
            </div>
            <div class="reporte-title">Gestión de Docentes</div>
            <div class="reporte-description">
                Información de docentes activos, cantidad de materias, estudiantes y evaluaciones de desempeño.
            </div>
            <button class="reporte-btn"><i class="fas fa-arrow-right me-2"></i> Ver Reporte</button>
        </a>

        <!-- Reporte 3: Asistencia -->
        <a href="index.php?controller=Admin&action=generarReporte&tipo=asistencia" class="reporte-card">
            <div class="reporte-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="reporte-title">Control de Asistencia</div>
            <div class="reporte-description">
                Estadísticas de asistencia, faltas, licencias y porcentajes por materia.
            </div>
            <button class="reporte-btn"><i class="fas fa-arrow-right me-2"></i> Ver Reporte</button>
        </a>

        <!-- Reporte 4: Calificaciones -->
        <a href="index.php?controller=Admin&action=generarReporte&tipo=calificaciones" class="reporte-card">
            <div class="reporte-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="reporte-title">Entregas y Calificaciones</div>
            <div class="reporte-description">
                Distribución de notas, promedios, materias de mejor y peor desempeño.
            </div>
            <button class="reporte-btn"><i class="fas fa-arrow-right me-2"></i> Ver Reporte</button>
        </a>

        <!-- Reporte 5: Inscripciones -->
        <a href="index.php?controller=Admin&action=generarReporte&tipo=inscripciones" class="reporte-card">
            <div class="reporte-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="reporte-title">Inscripciones</div>
            <div class="reporte-description">
                Ocupación de materias, capacidad de grupos y distribución de estudiantes.
            </div>
            <button class="reporte-btn"><i class="fas fa-arrow-right me-2"></i> Ver Reporte</button>
        </a>

        <!-- Reporte 6: Evaluaciones de Docentes -->
        <a href="index.php?controller=Admin&action=generarReporte&tipo=evaluacionesDocentes" class="reporte-card">
            <div class="reporte-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="reporte-title">Evaluaciones de Docentes</div>
            <div class="reporte-description">
                Ranking de docentes, puntajes de estudiantes y análisis de evaluaciones.
            </div>
            <button class="reporte-btn"><i class="fas fa-arrow-right me-2"></i> Ver Reporte</button>
        </a>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

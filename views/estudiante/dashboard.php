<?php require_once 'views/layouts/header.php'; ?>

<style>
    .dashboard-container {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .welcome-banner {
        background: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 40px;
        border-left: 6px solid var(--stadum-red);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .welcome-banner::after {
        content: "\f19d";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        right: -20px;
        bottom: -30px;
        font-size: 150px;
        color: rgba(0,0,0,0.03);
        transform: rotate(-15deg);
    }

    .welcome-text h2 {
        font-size: 2.5rem;
        color: var(--stadum-dark);
        margin-bottom: 10px;
    }

    .welcome-text p {
        font-size: 1.1rem;
        color: #666;
        max-width: 600px;
    }

    .subject-card {
        border: none;
        border-radius: 20px;
        background: white;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .subject-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 45px rgba(0,0,0,0.1);
    }

    .subject-header {
        padding: 25px;
        background: linear-gradient(135deg, var(--stadum-dark) 0%, var(--stadum-blue) 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .subject-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
        letter-spacing: 0.5px;
    }

    .subject-body {
        padding: 30px;
        flex-grow: 1;
    }

    .docente-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding: 10px 15px;
        background: #f8f9fc;
        border-radius: 12px;
    }

    .docente-icon {
        width: 35px;
        height: 35px;
        background: white;
        color: var(--stadum-red);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .btn-access {
        background: var(--stadum-red);
        color: white;
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        border: none;
        transition: 0.3s;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
    }

    .btn-access:hover {
        background: var(--stadum-dark);
        color: white;
        transform: scale(1.02);
    }

    .stat-mini-row {
        margin-bottom: 40px;
    }

    .stat-pill {
        background: white;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }

    .stat-pill-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .bg-soft-red { background: rgba(211, 26, 67, 0.1); color: var(--stadum-red); }
    .bg-soft-blue { background: rgba(11, 28, 57, 0.1); color: var(--stadum-blue); }

    /* Estilos para la tabla histórica (Diseño similar a la imagen) */
    .table-container-custom {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }
    
    .table-header-custom {
        text-align: center;
        margin-bottom: 25px;
    }
    
    .table-header-custom h5 {
        color: #64748b;
        font-size: 14px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .table-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        font-size: 13px;
        color: #64748b;
    }

    .table-controls select, .table-controls input {
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 5px 10px;
        font-size: 13px;
        outline: none;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom thead th {
        background: #fff;
        color: #1c2434;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
        padding: 12px 15px;
        text-align: left;
    }

    .table-custom tbody td {
        padding: 15px;
        vertical-align: middle;
        font-size: 13px;
        color: #64748b;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-custom tbody tr:hover {
        background: #f8fafc;
    }

    .btn-ver-curso {
        background: #3c50e0;
        color: white;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.3s;
        border: none;
    }

    .btn-ver-curso:hover {
        background: #2a3bb7;
        color: white;
    }

    .text-nota {
        font-weight: 600;
        color: #1c2434;
    }
</style>

<div class="dashboard-container">
    <!-- Alertas -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 py-3">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['mensaje']; ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <!-- Resumen Académico eliminado para limpieza visual -->

    <h4 class="serif fw-bold mb-4 text-dark" style="font-size: 22px;">Histórico <span style="color:#64748b; font-weight:400; font-family:'Inter', sans-serif;">materias registradas</span></h4>

    <div class="table-container-custom">
        <div class="table-header-custom">
            <h5>MATERIAS CURSADAS EN "<?= htmlspecialchars(strtoupper($_SESSION['carrera'] ?? 'SISTEMA ACADÉMICO')) ?>"</h5>
        </div>
        
        <div class="table-controls">
            <div>
                Mostrar 
                <select>
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select> 
                registros
            </div>
            <div>
                Buscar: <input type="text">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>OFERTA <i class="fas fa-sort" style="color:#cbd5e1; margin-left:5px;"></i></th>
                        <th>MATERIA</th>
                        <th>SIGLA</th>
                        <th>GRUPO</th>
                        <th>HORARIO</th>
                        <th>TURNO</th>
                        <th>SEMESTRE</th>
                        <th>DOCENTE</th>
                        <th>NOTA</th>
                        <th>VER</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($materias) > 0): ?>
                        <?php foreach ($materias as $materia): ?>
                            <tr>
                                <td><?= date('Y') ?>/<?= date('n') > 6 ? '2' : '1' ?>/<?= $materia['id_materia'] ?></td>
                                <td style="color: #64748b; font-weight: 500;"><?= htmlspecialchars($materia['nombre']); ?></td>
                                <td>SIS-0<?= 100 + $materia['id_materia'] ?></td>
                                <td>A</td>
                                <td>LAB-01 : LU a VI<br><span style="font-size:11px; color:#94a3b8;">07:30 - 10:30</span></td>
                                <td>Mañana</td>
                                <td>OCTAVO<br>SEMESTRE</td>
                                <td style="color: #64748b;"><?= htmlspecialchars($materia['docente']); ?></td>
                                <td class="text-nota">
                                    <?php if ($materia['promedio'] !== null): ?>
                                        <span class="text-<?= $materia['promedio'] >= 70 ? 'success' : 'danger' ?>">
                                            <?= number_format($materia['promedio'], 1) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="index.php?controller=Estudiante&action=verTareas&id_materia=<?= $materia['id_materia']; ?>" class="btn-ver-curso">
                                        <i class="fas fa-graduation-cap"></i> Ir al curso
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                <span class="text-muted d-block mb-3">Aún no estás inscrito en ninguna materia.</span>
                                <a href="index.php?controller=Estudiante&action=inscribirMateria" class="btn btn-danger rounded-pill px-4 py-2 fw-bold shadow-sm">
                                    <i class="fas fa-plus-circle me-2"></i> IR A INSCRIPCIONES
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
<!-- views/docente/gestion_evaluacion.php -->
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Evaluación: <?php echo htmlspecialchars($examen['titulo']); ?></h2>
        
        <!-- Botón para alternar la visibilidad manual -->
        <form action="index.php?controller=Docente&action=publicarCalificaciones" method="POST">
            <input type="hidden" name="id_evaluacion" value="<?php echo $examen['id']; ?>">
            <?php if ($examen['publicacion_forzada'] == 1): ?>
                <button type="submit" name="estado" value="0" class="btn btn-warning">
                    <i class="fas fa-eye-slash"></i> Ocultar Notas a Estudiantes
                </button>
            <?php else: ?>
                <button type="submit" name="estado" value="1" class="btn btn-success">
                    <i class="fas fa-eye"></i> Publicar Notas Ahora
                </button>
            <?php endif; ?>
        </form>
    </div>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Lista de Resultados</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Fecha de Envío</th>
                        <th>Nota Final</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($resultados)): ?>
                        <tr><td colspan="4" class="text-center">Nadie ha rendido el examen aún.</td></tr>
                    <?php else: ?>
                        <?php foreach ($resultados as $res): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($res['nombre_completo']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($res['fecha_envio'])); ?></td>
                                <td><strong><?php echo number_format($res['nota'], 2); ?>/100</strong></td>
                                <td>
                                    <?php 
                                    $ahora = date('Y-m-d H:i:s');
                                    if ($ahora > $examen['fecha_fin'] || $examen['publicacion_forzada'] == 1): ?>
                                        <span class="badge bg-success">Visible para alumno</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Oculto (Pendiente)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

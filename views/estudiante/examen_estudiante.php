<!-- views/estudiante/examen_estudiante.php -->
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3><?php echo htmlspecialchars($examen['titulo']); ?></h3>
            <p class="mb-0">Materia: <?php echo htmlspecialchars($examen['materia_nombre'] ?? 'Evaluación'); ?></p>
        </div>
        <div class="card-body">
            <!-- El formulario envía los datos al controlador mediante POST -->
            <form action="index.php?controller=Estudiante&action=enviarExamen" method="POST">
                
                <!-- Campos ocultos necesarios para procesar la calificación -->
                <input type="hidden" name="id_evaluacion" value="<?php echo $examen['id']; ?>">

                <?php foreach ($examen['preguntas'] as $index => $pregunta): ?>
                    <div class="mb-4 p-3 border-bottom">
                        <h5>
                            <strong><?php echo ($index + 1); ?>.</strong> 
                            <?php echo htmlspecialchars($pregunta['texto']); ?>
                        </h5>
                        
                        <div class="options-group mt-2">
                            <?php foreach ($pregunta['opciones'] as $opcion): ?>
                                <div class="form-check mb-2">
                                    <!-- El name usa el ID de la pregunta para crear un array en PHP -->
                                    <input class="form-check-input" type="radio" 
                                           name="respuestas[<?php echo $pregunta['id']; ?>]" 
                                           id="opt_<?php echo $opcion['id']; ?>" 
                                           value="<?php echo $opcion['id']; ?>" required>
                                    
                                    <label class="form-check-label" for="opt_<?php echo $opcion['id']; ?>">
                                        <?php echo htmlspecialchars($opcion['texto']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5">Enviar Examen</button>
                    <a href="index.php?controller=Estudiante&action=materias" class="btn btn-secondary btn-lg">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

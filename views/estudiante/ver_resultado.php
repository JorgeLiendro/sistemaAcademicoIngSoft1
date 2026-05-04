<!-- views/estudiante/ver_resultado.php -->
<?php 
// Usamos el ID que viene del controlador o lo recuperamos del parámetro GET directamente
$id_eval_vista = $id_evaluacion ?? $_GET['id']; 

// Ahora llamamos al método del modelo con la variable segura
$infoNota = $this->model->obtenerNotaSiEstaPublicada($id_eval_vista, $_SESSION['id_usuario']); 
?>

<div class="container mt-5">
    <!-- Resto del código que ya tienes -->
    <?php if ($infoNota && $infoNota['visible']): ?>
        <h1 class="display-4">Tu nota: <?php echo number_format($infoNota['nota'], 2); ?>/100</h1>
    <?php else: ?>
        <!-- Mensaje de espera por tiempo -->
        <p>Disponible el: <?php echo date('d/m/Y H:i', strtotime($infoNota['fecha_fin'])); ?></p>
    <?php endif; ?>
</div>

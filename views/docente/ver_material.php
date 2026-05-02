<?php require_once 'views/layouts/header.php'; ?>

<style>
    .material-header {
        background: linear-gradient(135deg, #3c50e0 0%, #80caee 100%);
        color: white;
        padding: 30px 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .material-info h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .material-info p {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    .material-viewer {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 25px;
        min-height: 600px;
    }

    .pdf-viewer {
        width: 100%;
        height: 700px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    .document-preview {
        width: 100%;
        height: 700px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    .no-preview {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 700px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 2px dashed #e0e0e0;
        text-align: center;
    }

    .no-preview i {
        font-size: 64px;
        color: #ccc;
        margin-bottom: 20px;
    }

    .no-preview p {
        color: #999;
        font-size: 16px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 25px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-descargar {
        background: #28a745;
        color: white;
    }

    .btn-descargar:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .btn-volver {
        background: #f8f9fa;
        color: #1c2434;
        border: 2px solid #e2e8f0;
    }

    .btn-volver:hover {
        background: #e2e8f0;
    }

    .material-info-box {
        font-size: 13px;
        opacity: 0.95;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
    }

    @media (max-width: 768px) {
        .material-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .action-buttons {
            margin-top: 15px;
        }

        .material-viewer {
            min-height: auto;
        }

        .pdf-viewer,
        .document-preview {
            height: 400px;
        }

        .no-preview {
            height: 400px;
        }
    }
</style>

<div class="container-fluid py-5">
    <!-- Header -->
    <div class="material-header">
        <div class="material-info">
            <h2><?= htmlspecialchars($material['titulo']); ?></h2>
            <div class="material-info-box">
                <div class="info-item">
                    <i class="fas fa-book"></i>
                    <span><?= htmlspecialchars($materia['nombre']); ?></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-tag"></i>
                    <span><?= htmlspecialchars($material['tipo']); ?></span>
                </div>
            </div>
        </div>
        <div class="action-buttons">
            <a href="index.php?controller=Docente&action=descargarMaterial&id_material=<?= $material['id_material'] ?>&id_materia=<?= $material['id_materia'] ?>" class="btn-action btn-descargar">
                <i class="fas fa-download"></i> Descargar
            </a>
            <a href="index.php?controller=Docente&action=gestionTareas&id_materia=<?= $material['id_materia'] ?>&tab=materiales" class="btn-action btn-volver">
                <i class="fas fa-chevron-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Contenido del Material -->
    <div class="material-viewer">
        <?php
            $extension = strtolower(pathinfo($material['ruta'], PATHINFO_EXTENSION));
            $ruta_completa = 'uploads/materiales/' . $material['ruta'];
        ?>

        <?php if ($extension === 'pdf'): ?>
            <!-- Visor de PDF -->
            <object data="<?= $ruta_completa ?>" type="application/pdf" class="pdf-viewer" style="width: 100%; height: 700px; border-radius: 8px; border: 1px solid #e0e0e0;">
                <p style="text-align: center; padding: 20px;">
                    No se puede mostrar el PDF aquí. 
                    <a href="index.php?controller=Docente&action=descargarMaterial&id_material=<?= $material['id_material'] ?>&id_materia=<?= $material['id_materia'] ?>" style="color: #3c50e0; text-decoration: underline;">Descargar PDF</a>
                </p>
            </object>

        <?php elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
            <!-- Visor de Imágenes -->
            <img src="<?= $ruta_completa ?>" alt="<?= htmlspecialchars($material['titulo']); ?>" style="max-width: 100%; height: auto; border-radius: 8px; border: 1px solid #e0e0e0;">

        <?php elseif (in_array($extension, ['txt', 'csv'])): ?>
            <!-- Para archivos de texto -->
            <div class="document-preview" style="padding: 20px; overflow-y: auto; background: #f8f9fa; border-radius: 8px; border: 1px solid #e0e0e0; font-family: monospace; font-size: 12px; white-space: pre-wrap; word-wrap: break-word;">
                <?= htmlspecialchars(file_get_contents($ruta_completa)); ?>
            </div>

        <?php else: ?>
            <!-- Sin vista previa disponible -->
            <div class="no-preview">
                <i class="fas fa-file"></i>
                <p>Vista previa no disponible para este tipo de archivo.</p>
                <p style="font-size: 14px; color: #ccc; margin-top: 10px;">Extensión: <strong>.<?= htmlspecialchars($extension); ?></strong></p>
                <p style="font-size: 13px; color: #999; margin-top: 5px;">Por favor descarga el archivo para verlo.</p>
                <a href="index.php?controller=Docente&action=descargarMaterial&id_material=<?= $material['id_material'] ?>&id_materia=<?= $material['id_materia'] ?>" class="btn-action btn-descargar" style="margin-top: 20px;">
                    <i class="fas fa-download"></i> Descargar Archivo
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Información del Documento -->
    <?php if (!empty($material['descripcion'])): ?>
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <h5 style="font-weight: 700; color: #1c2434; margin-bottom: 15px;">Descripción</h5>
        <p style="color: #555; line-height: 1.6;"><?= htmlspecialchars($material['descripcion']); ?></p>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

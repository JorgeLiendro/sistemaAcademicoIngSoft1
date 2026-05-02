<?php if(isset($_SESSION['id_usuario'])): ?>
        <?php if(in_array($_SESSION['rol'], ['Administrador','Docente'])): ?>
                    </div> <!-- End container dashboard-wrapper -->
                </main>
            </div> <!-- End app-container (was app-shell in some versions) -->
        <?php else: ?>
                    </div> <!-- End content-wrapper -->
                </main> <!-- End main-content -->
            </div> <!-- End app-container -->
        <?php endif; ?>
<?php else: ?>
    </div> <!-- End landing wrapper -->
<?php endif; ?>

<?php if(!isset($_SESSION['id_usuario'])): ?>
<style>
    .main-footer {
        background: var(--stadum-dark);
        color: white;
        padding: 80px 0 30px;
        position: relative;
        overflow: hidden;
    }
    .footer-logo img { filter: brightness(0) invert(1); margin-bottom: 25px; }
    .footer-title { 
        font-family: 'Crimson Text', serif; 
        font-weight: 700; 
        font-size: 24px; 
        margin-bottom: 30px; 
        position: relative;
        padding-bottom: 15px;
    }
    .footer-title::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 30px;
        height: 2px;
        background: var(--stadum-red);
    }
    .footer-links { list-style: none; padding: 0; }
    .footer-links li { margin-bottom: 12px; }
    .footer-links a { color: #aaa; text-decoration: none; transition: 0.3s; font-size: 14px; }
    .footer-links a:hover { color: white; padding-left: 8px; }
    
    .instagram-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .instagram-grid img { width: 100%; height: 80px; object-fit: cover; }
    
    .bottom-footer {
        border-top: 1px solid rgba(255,255,255,0.05);
        padding: 30px 0;
        margin-top: 50px;
        font-size: 13px;
        color: #777;
    }
    
    .live-chat-box {
        background: rgba(255,255,255,0.05);
        padding: 40px;
        margin-bottom: 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .live-chat-text { font-family: 'Crimson Text', serif; font-size: 28px; }
    .live-chat-text span { color: var(--stadum-red); }
</style>

<footer class="main-footer">
    <div class="container">
        <!-- Live Chat Section -->
        <div class="live-chat-box d-none d-lg-flex">
            <div class="d-flex align-items-center">
                <div class="me-4 d-flex">
                    <img src="https://i.pravatar.cc/150?u=1" class="rounded-circle border border-white" width="50" alt="">
                    <img src="https://i.pravatar.cc/150?u=2" class="rounded-circle border border-white ms-n3" width="50" style="margin-left: -15px;" alt="">
                    <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle border border-white ms-n3" width="50" style="margin-left: -15px;" alt="">
                </div>
                <div class="live-chat-text">¿Tienes alguna duda? <span>Chatea con nosotros</span></div>
            </div>
            <a href="#" class="btn btn-stadum px-4 py-2"><i class="fas fa-comment-dots me-2"></i> Contáctanos</a>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="footer-logo">
                    <img src="https://via.placeholder.com/180x50?text=ACADEMIC+PRO" height="50" alt="">
                </div>
                <p class="small text-muted mb-4">Desde 1999, cuando el equipo de Academic Pro se formó para dar nueva vida a la gestión educativa moderna.</p>
                <div class="d-flex gap-3 text-muted">
                    <i class="fas fa-map-marker-alt"></i> <span class="small">Av. Principal 123, Santa Cruz</span>
                </div>
                <div class="d-flex gap-3 text-muted mt-2">
                    <i class="fas fa-envelope"></i> <span class="small">info@academicpro.edu</span>
                </div>
            </div>

            <div class="col-lg-3 ps-lg-5">
                <h4 class="footer-title">Enlaces Útiles</h4>
                <ul class="footer-links">
                    <li><a href="#">Estudiantes</a></li>
                    <li><a href="#">Admisiones</a></li>
                    <li><a href="#">Facultad y Personal</a></li>
                    <li><a href="#">Relaciones Públicas</a></li>
                    <li><a href="#">Alumnos</a></li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h4 class="footer-title">Nuestros Programas</h4>
                <ul class="footer-links">
                    <li><a href="#">Programas de Pregrado</a></li>
                    <li><a href="#">Programas de Posgrado</a></li>
                    <li><a href="#">Certificaciones</a></li>
                    <li><a href="#">Programas Acelerados</a></li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h4 class="footer-title">Instagram</h4>
                <div class="instagram-grid">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=150&q=80" alt="">
                    <img src="https://images.unsplash.com/photo-1524178232363-1fb28f74b573?auto=format&fit=crop&w=150&q=80" alt="">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=150&q=80" alt="">
                    <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=150&q=80" alt="">
                    <img src="https://images.unsplash.com/photo-1519331379826-f10be5486c6f?auto=format&fit=crop&w=150&q=80" alt="">
                    <img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&w=150&q=80" alt="">
                </div>
            </div>
        </div>

        <div class="bottom-footer d-flex justify-content-between align-items-center">
            <p class="mb-0">© <?php echo date('Y'); ?> Academic Pro. Todos los derechos reservados.</p>
            <div class="d-flex gap-4">
                <a href="#" class="text-muted text-decoration-none">Privacidad</a>
                <a href="#" class="text-muted text-decoration-none">Términos y Condiciones</a>
            </div>
        </div>
    </div>
</footer>
<?php else: ?>
   
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/scripts.js"></script>
</body>
</html>
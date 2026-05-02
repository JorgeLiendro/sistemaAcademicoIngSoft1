<?php require_once 'views/layouts/header.php'; ?>

<style>
    /* Hero Section */
    .hero-stadum {
        background: linear-gradient(rgba(7, 18, 33, 0.6), rgba(7, 18, 33, 0.6)), url('https://conecta.tec.mx/sites/default/files/styles/header_full/public/2019-12/jovenes-graduados.webp?itok=yUxb6nb6');
        background-size: cover;
        background-position: center;
        height: 750px;
        display: flex;
        align-items: center;
        color: white;
        position: relative;
    }
    .hero-stadum h1 { font-size: 5rem; line-height: 1.1; margin-bottom: 25px; }
    .hero-stadum p { font-size: 1.1rem; max-width: 600px; margin-bottom: 40px; color: rgba(255,255,255,0.8); }
    .btn-hero { padding: 15px 40px; font-weight: 600; text-transform: uppercase; border-radius: 0; }
    .btn-red { background: var(--stadum-red); color: white; border: none; }
    .btn-outline { border: 1px solid rgba(255,255,255,0.5); color: white; background: transparent; }
    .btn-hero:hover { transform: translateY(-3px); transition: 0.3s; color: white; opacity: 0.9; }

    /* Info Cards */
    .feature-cards { margin-top: -80px; position: relative; z-index: 10; }
    .feature-card { 
        padding: 50px 40px; 
        border: none; 
        border-radius: 0; 
        transition: 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .feature-card.c-1 { background: #fff1f3; }
    .feature-card.c-2 { background: #f1f6ff; }
    .feature-card.c-3 { background: #f2f2fe; }
    .feature-card.c-4 { background: #f0faf4; }
    .feature-card i { font-size: 40px; color: var(--stadum-dark); margin-bottom: 30px; }
    .feature-card h4 { font-weight: 700; margin-bottom: 20px; font-size: 1.5rem; }
    .feature-card p { color: #666; font-size: 15px; margin-bottom: 30px; }
    .learn-more { font-weight: 700; text-decoration: none; color: var(--stadum-dark); display: flex; align-items: center; }
    .learn-more i { font-size: 12px; margin-bottom: 0; margin-left: 8px; }

    /* Section Styles */
    .section-title { position: relative; padding-left: 20px; margin-bottom: 20px; color: var(--stadum-red); font-weight: 700; font-size: 14px; text-transform: uppercase; }
    .section-title::before { content: ""; position: absolute; left: 0; top: 50%; width: 15px; height: 2px; background: var(--stadum-red); transform: translateY(-50%); }
    .main-heading { font-size: 2.8rem; font-weight: 700; margin-bottom: 30px; line-height: 1.2; }

    /* Campus Life */
    .campus-img-box { position: relative; overflow: hidden; margin-bottom: 25px; }
    .campus-img-box img { transition: 0.5s; width: 100%; }
    .campus-img-box:hover img { transform: scale(1.1); }

    /* Events Section Styles */
    .event-card {
        background: white;
        border: none;
        border-radius: 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: 0.3s;
        margin-bottom: 30px;
    }
    .event-card:hover {
        transform: translateY(-10px);
    }
    .event-date {
        background: var(--stadum-red);
        color: white;
        padding: 10px 15px;
        position: absolute;
        top: 0;
        left: 0;
        text-align: center;
        z-index: 1;
    }
    .event-date span {
        display: block;
        font-weight: 700;
        font-size: 20px;
    }
    .event-body {
        padding: 30px;
    }
    .event-meta {
        font-size: 13px;
        color: #888;
        margin-bottom: 10px;
    }
    .event-meta i {
        color: var(--stadum-red);
        margin-right: 5px;
    }
</style>

<!-- Hero Section -->
<div id="inicio"></div>
<section class="hero-stadum">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="serif">Donde la Innovación <br> se encuentra con la Educación</h1>
                <p>Queremos que cada estudiante y compañero de estudio sienta que es parte de un equipo común y cohesionado. Ayudamos a nuestros equipos a formar relaciones más sólidas.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-hero btn-red" data-bs-toggle="modal" data-bs-target="#loginModal">Admisión Ahora <i class="fas fa-arrow-right ms-2"></i></a>
                    <a href="#programas" class="btn btn-hero btn-outline">Ver Programas <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<div id="nosotros"></div>
<section class="feature-cards">
    <div class="container">
        <div class="row g-0">
            <div class="col-md-3">
                <div class="feature-card c-1">
                    <div>
                        <i class="fas fa-university"></i>
                        <h4 class="serif">Vida Universitaria</h4>
                        <p>Por otro lado, denunciamos con justa indignación el disgusto.</p>
                    </div>
                    <a href="#" class="learn-more">Leer Más <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card c-2">
                    <div>
                        <i class="fas fa-microscope"></i>
                        <h4 class="serif">Investigación</h4>
                        <p>Por otro lado, denunciamos con justa indignación el disgusto.</p>
                    </div>
                    <a href="#" class="learn-more">Leer Más <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card c-3">
                    <div>
                        <i class="fas fa-running"></i>
                        <h4 class="serif">Atletismo</h4>
                        <p>Por otro lado, denunciamos con justa indignación el disgusto.</p>
                    </div>
                    <a href="#" class="learn-more">Leer Más <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card c-4">
                    <div>
                        <i class="fas fa-book-reader"></i>
                        <h4 class="serif">Académicos</h4>
                        <p>Por otro lado, denunciamos con justa indignación el disgusto.</p>
                    </div>
                    <a href="#" class="learn-more">Leer Más <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Us Section -->
<section class="py-100 mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="pe-lg-5">
                    <div class="section-title">Sobre Nosotros</div>
                    <h2 class="main-heading serif">Ofrecemos el mejor programa para moldear <br> el mejor Futuro</h2>
                    <p class="text-muted mb-4">Estamos comprometidos a dejar el mundo como un lugar mejor. Perseguimos nueva tecnología, fomentamos la creatividad, nos involucramos con nuestras comunidades y compartimos una mentalidad emprendedora.</p>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-light p-3 me-3 text-danger"><i class="fas fa-users fa-2x"></i></div>
                        <div>
                            <h5 class="serif fw-bold">Tres grados de MBA</h5>
                            <p class="small text-muted mb-0">¡Nuestro equipo está listo para cualquier desafío! Ponemos nuestros esfuerzos conjuntos para generar ideas de negocios valientes.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-7">
                        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="img-fluid" alt="Estudiantes">
                    </div>
                    <div class="col-5">
                        <img src="https://images.unsplash.com/photo-1519331379826-f10be5486c6f?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80" class="img-fluid mt-4" alt="Campus">
                    </div>
                    <div class="col-12 mt-3">
                        <img src="https://images.unsplash.com/photo-1524178232363-1fb28f74b573?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="img-fluid" style="height: 250px; width: 100%; object-fit: cover;" alt="Edificio">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<div id="programas"></div>
<section class="py-5 bg-light mt-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <div class="section-title">Vive la Experiencia Academic Pro</div>
                <h2 class="main-heading serif mb-0">Vida en el Campus</h2>
            </div>
            <a href="#" class="btn btn-outline-dark px-4 py-2 rounded-0">Explorar Todo <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="campus-item">
                    <div class="campus-img-box">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Conferencia">
                    </div>
                    <h4 class="serif fw-bold">Clase Magistral</h4>
                    <p class="text-muted small">Agenda un recorrido personalizado por nuestro campus y una reunión individual con un asesor de admisiones.</p>
                    <a href="#" class="learn-more small">Ver el Campus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="campus-item">
                    <div class="campus-img-box">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Estudio Grupal">
                    </div>
                    <h4 class="serif fw-bold">Estudio Grupal en Campus</h4>
                    <p class="text-muted small">Nuestras visitas programadas son días planificados que se adaptan especialmente a los diferentes intereses de cada estudiante.</p>
                    <a href="#" class="learn-more small">Ver el Campus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="campus-item">
                    <div class="campus-img-box">
                        <img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Cultura">
                    </div>
                    <h4 class="serif fw-bold">Arte y Cultura</h4>
                    <p class="text-muted small">¿No puedes venir al campus? Explora partes de nuestro campus a través de una serie de videos cortos.</p>
                    <a href="#" class="learn-more small">Ver el Campus <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Events Section -->
<div id="eventos"></div>
<section class="py-5 mt-5">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-title mx-auto" style="width: fit-content;">Próximos Eventos</div>
            <h2 class="main-heading serif">Únete a nuestras actividades</h2>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="event-card">
                    <div class="position-relative">
                        <div class="event-date">
                            <span>25</span> ABR
                        </div>
                        <img src="https://images.unsplash.com/photo-1475721027785-f74dea9f2943?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="img-fluid w-100" alt="Evento">
                    </div>
                    <div class="event-body">
                        <div class="event-meta">
                            <span><i class="fas fa-clock"></i> 09:00 AM - 12:00 PM</span>
                            <span class="ms-3"><i class="fas fa-map-marker-alt"></i> Salón A</span>
                        </div>
                        <h4 class="serif fw-bold">Conferencia de Innovación Tecnológica</h4>
                        <p class="text-muted small">Descubre las últimas tendencias en IA y desarrollo de software con expertos de la industria.</p>
                        <a href="#" class="btn btn-link text-danger fw-bold text-decoration-none p-0">Leer Más <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="event-card">
                    <div class="position-relative">
                        <div class="event-date">
                            <span>12</span> MAY
                        </div>
                        <img src="https://images.unsplash.com/photo-1523580494863-6f3031224694?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="img-fluid w-100" alt="Evento">
                    </div>
                    <div class="event-body">
                        <div class="event-meta">
                            <span><i class="fas fa-clock"></i> 10:00 AM - 04:00 PM</span>
                            <span class="ms-3"><i class="fas fa-map-marker-alt"></i> Campus Central</span>
                        </div>
                        <h4 class="serif fw-bold">Feria de Carreras 2024</h4>
                        <p class="text-muted small">Conecta con las mejores empresas y explora oportunidades laborales exclusivas para nuestros alumnos.</p>
                        <a href="#" class="btn btn-link text-danger fw-bold text-decoration-none p-0">Leer Más <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="event-card">
                    <div class="position-relative">
                        <div class="event-date">
                            <span>05</span> JUN
                        </div>
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" class="img-fluid w-100" alt="Evento">
                    </div>
                    <div class="event-body">
                        <div class="event-meta">
                            <span><i class="fas fa-clock"></i> 02:00 PM - 05:00 PM</span>
                            <span class="ms-3"><i class="fas fa-map-marker-alt"></i> Auditorio</span>
                        </div>
                        <h4 class="serif fw-bold">Taller de Diseño Creativo</h4>
                        <p class="text-muted small">Aprende técnicas avanzadas de diseño visual y comunicación efectiva en este taller práctico.</p>
                        <a href="#" class="btn btn-link text-danger fw-bold text-decoration-none p-0">Leer Más <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer link target -->
<div id="contacto"></div>

<?php require_once 'views/layouts/footer.php'; ?>
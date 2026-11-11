<?php require __DIR__ . '/partials/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

<style>
    /* Neutralizar subrayados forzados por Bootstrap en toda la página */
    a, button, .btn, .boton, .boton-sm, .boton-destacado, .boton-outline,
    .site-header a, .site-footer a, .links a, .accordion-button {
        text-decoration: none !important;
    }
    a:hover, button:hover, .btn:hover, .boton:hover, .boton-sm:hover,
    .boton-destacado:hover, .site-header a:hover, .site-footer a:hover,
    .links a:hover, .accordion-button:hover {
        text-decoration: none !important;
    }

    /* Restaurar colores corporativos de Vantia en barra superior */
    .site-header a {
        color: #ffffff !important;
    }
    .site-header .logo span {
        color: #d5a347 !important;
    }
    .site-header .links a:hover {
        color: #d5a347 !important;
    }
    .site-header .boton-sm {
        color: #14233d !important;
        background-color: #d5a347 !important;
    }
    .site-header .boton-outline {
        color: #ffffff !important;
        background-color: transparent !important;
        border: 1px solid rgba(255,255,255,0.4) !important;
    }
    .site-footer a {
        color: inherit !important;
    }

    .bg-gradient-vantia {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
    }
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.12) !important;
    }
    .input-group-text {
        background-color: #f8fafc;
        border-right: none;
    }
    .form-control-custom, .form-select-custom {
        border-left: none;
        background-color: #f8fafc;
    }
    .form-control-custom:focus, .form-select-custom:focus {
        background-color: #ffffff;
        box-shadow: none;
        border-color: #dee2e6;
    }
    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.15);
        border-radius: 0.5rem;
    }
    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control-custom,
    .input-group:focus-within .form-select-custom {
        border-color: #2563eb;
    }
    .btn-vantia {
        background: #d5a347 !important;
        color: #14233d !important;
        border: none !important;
        font-weight: bold !important;
        text-decoration: none !important;
        transition: all 0.3s ease;
    }
    .btn-vantia:hover {
        background: #e2b45d !important;
        color: #14233d !important;
        text-decoration: none !important;
        box-shadow: 0 8px 20px rgba(213, 163, 71, 0.35);
    }
</style>

<div class="container py-5">

   
    <div class="card border-0 bg-gradient-vantia text-white shadow-lg rounded-4 overflow-hidden mb-5">
        <div class="card-body p-4 p-md-5 position-relative">
            <div class="row align-items-center">
                <div class="col-12">
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold text-uppercase mb-3 shadow-sm">
                        <i class="bi bi-shield-check me-1"></i> Soporte Oficial Vantia
                    </span>
                    <h1 class="display-5 fw-bold mb-3 text-white">Centro de Atención al Cliente</h1>
                    <p class="lead mb-0 text-white-50">
                        ¿Tienes dudas, inconvenientes técnicos o consultas corporativas? Nuestro equipo de soporte está listo para darte respuesta personalizada directamente en tu correo.
                    </p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover bg-light-subtle">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-4 p-3 me-3 fs-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Tiempo de Respuesta</h6>
                        <p class="small text-muted mb-0">Respondemos en 24 a 48 horas hábiles.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover bg-light-subtle">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-success bg-opacity-10 text-success rounded-4 p-3 me-3 fs-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-mailbox"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Atención Directa</h6>
                        <p class="small text-muted mb-0">Seguimiento por correo electrónico.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover bg-light-subtle">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-4 p-3 me-3 fs-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Soporte Multirrol</h6>
                        <p class="small text-muted mb-0">Para postulantes y empleadores.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-4">

        
        <div class="col-lg-7 col-xl-8">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                
                
                <?php if (isset($_SESSION['exito_soporte'])): ?>
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                            <div><?= $_SESSION['exito_soporte']; unset($_SESSION['exito_soporte']); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_soporte'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div><?= $_SESSION['error_soporte']; unset($_SESSION['error_soporte']); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-1">Envíanos tu mensaje</h3>
                    <p class="text-muted">Por favor, completa los siguientes datos para procesar tu solicitud.</p>
                </div>

                <form action="ban.php?pagina=enviar_soporte" method="POST">
                    <div class="row g-3">
                        <!-- Nombre Completo -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label fw-semibold text-secondary small">Nombre Completo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" class="form-control form-control-custom" id="nombre" name="nombre" placeholder="Ej. Juan Pérez" required>
                            </div>
                        </div>

                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold text-secondary small">Correo Electrónico (Gmail) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" class="form-control form-control-custom" id="email" name="email" placeholder="ejemplo@gmail.com" required>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        
                        <div class="col-md-6 mb-3">
                            <label for="rol" class="form-label fw-semibold text-secondary small">Tipo de Usuario <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-people text-muted"></i></span>
                                <select class="form-select form-select-custom" id="rol" name="rol" required>
                                    <option value="" disabled selected>Selecciona tu rol...</option>
                                    <option value="Empleado / Candidato">Empleado / Candidato</option>
                                    <option value="Empleador / Empresa">Empleador / Empresa</option>
                                    <option value="Visitante">Visitante General</option>
                                </select>
                            </div>
                        </div>

                       
                        <div class="col-md-6 mb-3">
                            <label for="asunto" class="form-label fw-semibold text-secondary small">Asunto <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-chat-left-dots text-muted"></i></span>
                                <input type="text" class="form-control form-control-custom" id="asunto" name="asunto" placeholder="Ej. Inconveniente con postulación" required>
                            </div>
                        </div>
                    </div>

                    
                    <div class="mb-4">
                        <label for="mensaje" class="form-label fw-semibold text-secondary small">Detalle del Mensaje <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text align-items-start pt-2"><i class="bi bi-pencil-square text-muted"></i></span>
                            <textarea class="form-control form-control-custom" id="mensaje" name="mensaje" rows="5" placeholder="Escribe aquí detalladamente tu consulta..." required></textarea>
                        </div>
                    </div>

                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-vantia btn-lg fw-bold rounded-3 py-3 shadow-sm">
                            <i class="bi bi-send-fill me-2"></i> Enviar Consulta a Soporte
                        </button>
                    </div>
                </form>

            </div>
        </div>

      
        <div class="col-lg-5 col-xl-4">
            <div class="card border-0 shadow-lg rounded-4 p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-question-circle text-primary me-2"></i> Preguntas Frecuentes</h5>
                <p class="text-muted small mb-4">Respuestas rápidas a las inquietudes más habituales.</p>

                
                <div class="accordion accordion-flush" id="faqAccordion">
                    
                    <div class="accordion-item border-bottom">
                        <h2 class="accordion-header" id="faqOne">
                            <button class="accordion-button collapsed fw-semibold text-dark fs-6" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                ¿Cómo puedo adjuntar mi CV?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                Puedes actualizar tu Curriculum Vitae desde tu panel personal en la sección de <strong>Mi Perfil</strong> en formato PDF o Word.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-bottom">
                        <h2 class="accordion-header" id="faqTwo">
                            <button class="accordion-button collapsed fw-semibold text-dark fs-6" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                ¿Cómo publico empleos siendo Empresa?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                Una vez iniciada sesión con tu cuenta de Empresa, dirígete al menú <strong>Panel Empresa</strong> y haz clic en <em>Publicar Vacante</em>.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqThree">
                            <button class="accordion-button collapsed fw-semibold text-dark fs-6" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                ¿Dónde recibiré la respuesta?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                La respuesta del equipo técnico se enviará a la dirección de correo que ingreses en el formulario de esta sección.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-4 p-3 bg-light rounded-3 text-center border">
                    <i class="bi bi-shield-lock text-primary fs-4 d-block mb-1"></i>
                    <span class="small text-muted">Tus datos personales están protegidos por nuestras políticas de privacidad.</span>
                </div>

            </div>
        </div>

    </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/header.php'; ?>

<section class="auth-layout">
  <div class="auth-info">
    <span class="etiqueta">VANTIA · ACCESO</span>
    <h1>Bienvenido de nuevo.</h1>
    <p>Ingresa a tu cuenta para acceder a las oportunidades laborales o administrar tus vacantes y postulantes.</p>
    
    <div class="nota-seguridad" style="margin-top:2rem;">
      <strong>Protección y Verificación</strong>
      <span>Vantia valida empresas y resguarda a los jóvenes postulantes en conformidad con la normativa laboral.</span>
    </div>
  </div>

  <div class="form-card">
    <h2>Iniciar sesión</h2>
    <p class="texto-suave">Ingresa tus credenciales registradas para acceder a la plataforma.</p>

    <?php if (!empty($error)): ?>
      <div class="mensaje-error"><?= e($error) ?></div>
    <?php endif; ?>

    <form action="ban.php?pagina=validar_login" method="post" id="formLogin">
      <input type="hidden" name="tipo" value="<?= e($tipo ?? '') ?>">

      <label for="correo">Correo electrónico</label>
      <input id="correo" name="correo" type="email" required maxlength="150" value="<?= e($correo ?? ($tipo === 'admin' ? 'admin@vantia.com' : '')) ?>" placeholder="ejemplo@correo.com">

      <label for="contrasena">Contraseña</label>
      <input id="contrasena" name="contrasena" type="password" required placeholder="Tu contraseña" value="<?= $tipo === 'admin' ? 'admin123' : '' ?>">

      <button class="boton ancho" type="submit" id="btnSubmitLogin">Ingresar a Vantia</button>
    </form>

    <div class="auth-footer-links">
      <p>¿Aún no tienes cuenta?</p>
      <div class="enlaces-registro">
        <a href="ban.php?pagina=registro_empleado">Registrarme como joven / empleado</a>
        <span>·</span>
        <a href="ban.php?pagina=registro_empresa">Registrar mi empresa</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>

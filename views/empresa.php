<?php require __DIR__ . '/partials/header.php'; ?>

<section class="pagina-cabecera">
  <div class="contenedor">
    <span class="etiqueta">ESPACIO PARA ORGANIZACIONES</span>
    <h1>Conecta con talento joven.</h1>
    <p>Publica oportunidades laborales para jóvenes y administra tus procesos de selección.</p>
  </div>
</section>

<section class="contenedor seccion">
  <div class="tarjetas dos">
    <article class="tarjeta">
      <span class="numero">01 · ACCESO</span>
      <h2>Iniciar sesión</h2>
      <p>Ingresa a tu cuenta de empresa para publicar vacantes y revisar los candidatos postulados.</p>
      <?php if (!empty($_SESSION['usuario_id']) && ($_SESSION['tipo'] ?? '') === 'empresa'): ?>
        <a class="boton" href="ban.php?pagina=panel_empresa">Ir al panel de empresa →</a>
      <?php else: ?>
        <a class="boton" href="ban.php?pagina=login&tipo=empresa">Iniciar sesión →</a>
      <?php endif; ?>
    </article>

    <article class="tarjeta">
      <span class="numero">02 · REGISTRO</span>
      <h2>Registrar empresa</h2>
      <p>Si aún no tienes cuenta, registra los datos de tu organización con NIT y número de ROE.</p>
      <a class="boton boton-oscuro" href="ban.php?pagina=registro_empresa">Crear registro →</a>
    </article>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>

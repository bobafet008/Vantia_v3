<?php 
require __DIR__ . '/partials/header.php'; 
$tipo = (!empty($tipo) && $tipo === 'empresa') ? 'empresa' : 'empleado'; 
$roeActual = $form['roe_actual'] ?? $form['roe'] ?? 'uploads/roe/roe_default.png';
?>

<section class="auth-layout">
  <div class="auth-info">
    <span class="etiqueta">VANTIA · <?= $tipo === 'empresa' ? 'EMPRESAS VERIFICADAS' : 'JÓVENES Y TALENTO' ?></span>
    <h1><?= $tipo === 'empresa' ? 'Conecta con nuevo talento.' : 'Da el primer paso hacia tu empleo.' ?></h1>
    <p>
      <?= $tipo === 'empresa' 
          ? 'Registra los datos de tu empresa u organización, incluyendo NIT y ROE para publicar vacantes seguras.' 
          : 'Crea tu perfil laboral para postularte a ofertas de trabajo verificadas. Si eres menor de edad, te acompañamos con el registro de tu tutor legal.' ?>
    </p>

    <div class="nota-seguridad" style="margin-top:2rem;">
      <strong>Protección y Verificación</strong>
      <span>Vantia valida empresas y resguarda a los jóvenes postulantes en conformidad con la normativa laboral.</span>
    </div>
  </div>

  <div class="form-card">
    <!-- Pestañas para alternar fácilmente entre tipo de cuenta -->
    <div style="display:flex; background:#eef2f6; padding:4px; border-radius:6px; margin-bottom:1.25rem; gap:4px;">
      <a href="ban.php?pagina=registro_empleado" style="flex:1; text-align:center; padding:9px 12px; font-size:13px; font-weight:bold; border-radius:4px; text-decoration:none; transition:all 0.2s; <?= $tipo === 'empleado' ? 'background:var(--azul); color:white; box-shadow:0 1px 3px rgba(0,0,0,0.1);' : 'color:var(--gris); background:transparent;' ?>">
        Joven / Postulante
      </a>
      <a href="ban.php?pagina=registro_empresa" style="flex:1; text-align:center; padding:9px 12px; font-size:13px; font-weight:bold; border-radius:4px; text-decoration:none; transition:all 0.2s; <?= $tipo === 'empresa' ? 'background:var(--azul); color:white; box-shadow:0 1px 3px rgba(0,0,0,0.1);' : 'color:var(--gris); background:transparent;' ?>">
        Empresa / Empleador
      </a>
    </div>

    <h2><?= $tipo === 'empresa' ? 'Registro de empresa' : 'Registro de empleado / joven' ?></h2>
    <p class="texto-suave">Completa los datos para crear tu cuenta en la plataforma.</p>

    <?php if (!empty($error)): ?>
      <div class="mensaje-error"><?= e($error) ?></div>
    <?php endif; ?>

    <form action="ban.php?pagina=guardar_usuario" method="post" id="formRegistro" enctype="multipart/form-data">
      <input type="hidden" name="tipo" value="<?= e($tipo) ?>">

      <label for="nombre">Nombre <?= $tipo === 'empresa' ? 'de la empresa' : 'completo' ?> *</label>
      <input id="nombre" name="nombre" type="text" required maxlength="120" value="<?= e($form['nombre'] ?? '') ?>" placeholder="<?= $tipo === 'empresa' ? 'Ej. Soluciones Digitales SRL' : 'Ej. Juan Pérez' ?>">

      <label for="correo">Correo electrónico *</label>
      <input id="correo" name="correo" type="email" required maxlength="150" value="<?= e($form['correo'] ?? '') ?>" placeholder="ejemplo@correo.com">

      <label for="contrasena">Contraseña *</label>
      <input id="contrasena" name="contrasena" type="password" required minlength="6" placeholder="Mínimo 6 caracteres">

      <label for="confirmar_contrasena">Confirmar contraseña *</label>
      <input id="confirmar_contrasena" name="confirmar_contrasena" type="password" required minlength="6" placeholder="Repite tu contraseña">
      <div id="errorPasswordMismatch" class="mensaje-error" style="display:none; margin-top:4px; padding:6px 10px; font-size:12px;">Las contraseñas no coinciden.</div>

      <label for="telefono">Teléfono / WhatsApp *</label>
      <input id="telefono" name="telefono" type="text" required maxlength="30" value="<?= e($form['telefono'] ?? '') ?>" placeholder="Ej. 70012345">

      <?php if ($tipo === 'empresa'): ?>
        <label for="representante">Nombre del representante / persona de contacto *</label>
        <input id="representante" name="representante" type="text" required maxlength="150" value="<?= e($form['representante'] ?? '') ?>" placeholder="Ej. Juan Pérez">

        <label for="nit">Número de NIT *</label>
        <input id="nit" name="nit" type="text" required minlength="7" maxlength="50" pattern="[0-9]{7,}" inputmode="numeric" oninput="this.value = this.value.replace(/\D/g, '')" value="<?= e($form['nit'] ?? '') ?>" placeholder="Ej. 1029384756" title="El NIT debe ser numérico y tener al menos 7 dígitos">
        <small style="display:block; margin-top:-4px; margin-bottom:8px; color:var(--gris);">NIT oficial de la empresa (solo dígitos numéricos).</small>

        <label for="roe_archivo">Certificado / Código QR de ROE (Formato de imagen) *</label>
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:1rem; display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
          <img id="previewRoe" src="<?= e(rutaArchivo($roeActual)) ?>" alt="Código QR ROE" style="width:85px; height:85px; object-fit:contain; border:1px solid #cbd5e1; border-radius:6px; background:#fff; padding:4px; box-shadow:0 1px 3px rgba(0,0,0,0.08);">
          <div style="flex:1; min-width:200px;">
            <div style="font-weight:600; font-size:0.88rem; color:#1e293b; margin-bottom:2px;">Imagen asignada para validación laboral</div>
            <div style="font-size:0.78rem; color:#64748b; margin-bottom:8px;">Formato de imagen (PNG, JPG). Código QR predeterminado activo. Puedes subir otra imagen si lo requieres:</div>
            <input type="file" id="roe_archivo" name="roe_archivo" accept="image/png, image/jpeg, image/jpg" style="font-size:0.82rem;" onchange="actualizarPreviewRoe(this)">
            <input type="hidden" name="roe_actual" value="<?= e($roeActual) ?>">
          </div>
        </div>

        <label for="descripcion_empresa">Descripción de la empresa</label>
        <textarea id="descripcion_empresa" name="descripcion_empresa" rows="3" placeholder="A qué se dedica tu empresa y qué rubro maneja"><?= e($form['descripcion_empresa'] ?? '') ?></textarea>
      
      <?php else: ?>
        <label for="fecha_nacimiento">Fecha de nacimiento *</label>
        <input id="fecha_nacimiento" name="fecha_nacimiento" type="date" required max="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d', strtotime('-100 years')) ?>" value="<?= e($form['fecha_nacimiento'] ?? '') ?>">

        <!-- Alerta para menores de 16 años o fechas futuras -->
        <div id="alertaMenor16" class="mensaje-error" style="display: none; margin-top: 10px;">
          <strong>Edad no permitida:</strong> Debes tener al menos 16 años cumplidos para crear una cuenta en Vantia.
        </div>

        <!-- Sección condicional para Jóvenes de 16 y 17 años -->
        <div id="seccionTutor" class="caja-tutor" style="display: none;">
          <div class="aviso-tutor">
            <strong>Detección de joven de 16 a 17 años</strong>
            <span>Al ser menor de 18 años, la ley exige que cuentes con la autorización y datos de tu padre, madre o tutor legal.</span>
          </div>

          <label for="tutor_nombre">Nombre del padre, madre o tutor *</label>
          <input id="tutor_nombre" name="tutor_nombre" type="text" maxlength="100" value="<?= e($form['tutor_nombre'] ?? '') ?>" placeholder="Nombre completo del tutor">

          <label for="tutor_telefono">Teléfono de contacto del tutor *</label>
          <input id="tutor_telefono" name="tutor_telefono" type="text" maxlength="30" value="<?= e($form['tutor_telefono'] ?? '') ?>" placeholder="Teléfono del tutor">

          <label for="tutor_relacion">Parentesco / Relación</label>
          <?php $relacionActual = $form['tutor_relacion'] ?? 'Madre'; ?>
          <select id="tutor_relacion" name="tutor_relacion">
            <option value="Madre" <?= $relacionActual === 'Madre' ? 'selected' : '' ?>>Madre</option>
            <option value="Padre" <?= $relacionActual === 'Padre' ? 'selected' : '' ?>>Padre</option>
            <option value="Tutor legal" <?= $relacionActual === 'Tutor legal' ? 'selected' : '' ?>>Tutor(a) legal</option>
          </select>
        </div>
      <?php endif; ?>

      <button id="btnCrearCuenta" class="boton ancho" type="submit" style="margin-top:1.5rem;">Crear cuenta e ingresar</button>
    </form>

    <div class="auth-footer-links">
      <p>¿Ya tienes una cuenta?</p>
      <a href="ban.php?pagina=login">Iniciar sesión</a>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('formRegistro');
  const pass = document.getElementById('contrasena');
  const passConfirm = document.getElementById('confirmar_contrasena');
  const errorPass = document.getElementById('errorPasswordMismatch');

  function validarPasswords() {
    if (passConfirm.value && pass.value !== passConfirm.value) {
      if (errorPass) errorPass.style.display = 'block';
      passConfirm.setCustomValidity('Las contraseñas no coinciden');
      return false;
    } else {
      if (errorPass) errorPass.style.display = 'none';
      passConfirm.setCustomValidity('');
      return true;
    }
  }

  if (pass && passConfirm) {
    pass.addEventListener('input', validarPasswords);
    passConfirm.addEventListener('input', validarPasswords);
  }

  if (form) {
    form.addEventListener('submit', function(e) {
      if (!validarPasswords()) {
        e.preventDefault();
        passConfirm.focus();
      }
    });
  }

  <?php if ($tipo === 'empleado'): ?>
  const inputFecha = document.getElementById('fecha_nacimiento');
  const seccionTutor = document.getElementById('seccionTutor');
  const alertaMenor16 = document.getElementById('alertaMenor16');
  const btnCrearCuenta = document.getElementById('btnCrearCuenta');
  const inputTutorNombre = document.getElementById('tutor_nombre');
  const inputTutorTel = document.getElementById('tutor_telefono');

  function calcularEdad(fechaString) {
    if (!fechaString) return null;
    const partes = fechaString.split('-');
    if (partes.length !== 3) return null;
    const [y, m, d] = partes.map(Number);
    if (!y || !m || !d) return null;
    // Usar fecha local para evitar desfases de zona horaria UTC
    const nac = new Date(y, m - 1, d);
    const hoy = new Date();
    let edad = hoy.getFullYear() - nac.getFullYear();
    const mes = hoy.getMonth() - nac.getMonth();
    if (mes < 0 || (mes === 0 && hoy.getDate() < nac.getDate())) {
      edad--;
    }
    return edad;
  }

  function verificarMenor() {
    if (!inputFecha.value) {
      alertaMenor16.style.display = 'none';
      seccionTutor.style.display = 'none';
      inputTutorNombre.required = false;
      inputTutorTel.required = false;
      btnCrearCuenta.disabled = false;
      btnCrearCuenta.style.opacity = '1';
      btnCrearCuenta.style.cursor = 'pointer';
      return;
    }

    const edad = calcularEdad(inputFecha.value);

    if (edad !== null && edad < 0) {
      alertaMenor16.innerHTML = '<strong>Fecha no válida:</strong> La fecha de nacimiento no puede ser futura.';
      alertaMenor16.style.display = 'block';
      seccionTutor.style.display = 'none';
      inputTutorNombre.required = false;
      inputTutorTel.required = false;
      btnCrearCuenta.disabled = true;
      btnCrearCuenta.style.opacity = '0.5';
      btnCrearCuenta.style.cursor = 'not-allowed';
    } else if (edad !== null && edad < 16) {
      // Bloquear registro a menores de 16 años
      alertaMenor16.innerHTML = '<strong>Edad no permitida:</strong> Debes tener al menos 16 años cumplidos para crear una cuenta en Vantia.';
      alertaMenor16.style.display = 'block';
      seccionTutor.style.display = 'none';
      inputTutorNombre.required = false;
      inputTutorTel.required = false;
      btnCrearCuenta.disabled = true;
      btnCrearCuenta.style.opacity = '0.5';
      btnCrearCuenta.style.cursor = 'not-allowed';
    } else if (edad !== null && edad < 18) {
      // Permitir de 16 a 17 años con tutor obligatorio
      alertaMenor16.style.display = 'none';
      seccionTutor.style.display = 'block';
      inputTutorNombre.required = true;
      inputTutorTel.required = true;
      btnCrearCuenta.disabled = false;
      btnCrearCuenta.style.opacity = '1';
      btnCrearCuenta.style.cursor = 'pointer';
    } else {
      // Mayor de 18 años
      alertaMenor16.style.display = 'none';
      seccionTutor.style.display = 'none';
      inputTutorNombre.required = false;
      inputTutorTel.required = false;
      btnCrearCuenta.disabled = false;
      btnCrearCuenta.style.opacity = '1';
      btnCrearCuenta.style.cursor = 'pointer';
    }
  }

  inputFecha.addEventListener('change', verificarMenor);
  inputFecha.addEventListener('input', verificarMenor);
  verificarMenor();
  <?php endif; ?>
});

function actualizarPreviewRoe(input) {
  if (input.files && input.files[0]) {
    const lector = new FileReader();
    lector.onload = function(e) {
      const img = document.getElementById('previewRoe');
      if (img) {
        img.src = e.target.result;
      }
    };
    lector.readAsDataURL(input.files[0]);
  }
}
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>

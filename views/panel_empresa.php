<?php require __DIR__ . '/partials/header.php'; ?>

<section class="pagina-cabecera">
  <div class="contenedor" style="display:flex; justify-content:space-between; align-items:flex-start; gap:20px; flex-wrap:wrap;">
    <div>
      <span class="etiqueta">ESPACIO DE GESTIÓN EMPRESARIAL</span>
      <h1>Panel de <?= e($_SESSION['usuario'] ?? 'Empresa') ?></h1>
      <?php if (!empty($_SESSION['representante'])): ?>
        <p style="margin-bottom:0.25rem;">Representante: <strong><?= e($_SESSION['representante']) ?></strong></p>
      <?php endif; ?>
      <p>Publica oportunidades laborales y gestiona a los candidatos que se postulan a tus vacantes.</p>
      
      <div style="display:flex; gap:10px; margin-top:1rem; flex-wrap:wrap;">
        <a href="#nueva-vacante" class="boton boton-sm">+ Publicar nueva vacante</a>
        <a href="#postulantes" class="boton boton-sm boton-oscuro">Gestionar postulantes (<?= count($postulaciones ?? []) ?>)</a>
        <a href="ban.php?pagina=trabajos" class="boton boton-sm" style="background:#e2f5e8; color:#1f7a46;">Ver bolsa pública de empleos →</a>
      </div>
    </div>

    <?php if (!empty($empresa['roe'])): ?>
      <div style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.18); border-radius:10px; padding:12px; text-align:center; min-width:130px;">
        <a href="<?= e(rutaArchivo($empresa['roe'])) ?>" target="_blank" title="Ver código QR ROE">
          <img src="<?= e(rutaArchivo($empresa['roe'])) ?>" alt="Código QR ROE" style="width:75px; height:75px; object-fit:contain; background:#fff; border-radius:6px; padding:3px; display:block; margin:0 auto 6px;">
        </a>
        <span style="font-size:0.75rem; color:#e2e8f0; font-weight:600; display:block;">Certificado ROE</span>
        <span style="font-size:0.7rem; color:#38bdf8;">QR Verificado</span>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="contenedor seccion">
  <?php if (!empty($error)): ?><div class="mensaje-error" style="margin-bottom:1.5rem;"><?= e($error) ?></div><?php endif; ?>
  <?php if (!empty($exito)): ?><div class="mensaje-exito" style="margin-bottom:1.5rem;"><?= e($exito) ?></div><?php endif; ?>

  <!-- Resumen superior -->
  <div class="tarjetas tres" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; margin-bottom:2.5rem;">
    <div class="tarjeta">
      <span class="numero">01 · VACANTES</span>
      <h2><?= count($empleos ?? []) ?> vacantes</h2>
      <p>Ofertas publicadas en tu cuenta.</p>
    </div>
    <div class="tarjeta">
      <span class="numero">02 · POSTULANTES</span>
      <h2><?= count($postulaciones ?? []) ?> solicitudes</h2>
      <p>Candidatos recibidos a evaluar.</p>
    </div>
    <div class="tarjeta">
      <span class="numero">03 · CALIFICACIÓN EMPLEADOS</span>
      <h2><?= !empty($resumenCalif['total']) ? ($resumenCalif['promedio'] . ' / 5') : 'Sin evaluar' ?></h2>
      <p><?= !empty($resumenCalif['total']) ? ($resumenCalif['total'] . ' evaluación(es) de empleados contratados') : 'Puntajes otorgados por tus trabajadores' ?></p>
    </div>
  </div>

  <!-- Sección de Calificaciones Recibidas de Empleados Contratados -->
  <?php if (!empty($calificacionesRecibidas)): ?>
  <div id="calificaciones-empresa" class="bloque-panel" style="margin-bottom:3rem;">
    <div class="titulo-seccion">
      <div>
        <span class="etiqueta">TRANSPARENCIA LABORAL</span>
        <h2>Calificaciones de tus empleados</h2>
        <p class="texto-suave">Evaluaciones asignadas del 0 al 5 por jóvenes contratados en tu empresa.</p>
      </div>
    </div>
    <div class="grilla-empleos">
      <?php foreach ($calificacionesRecibidas as $cal): ?>
        <article class="trabajo" style="border-top:3px solid #10b981;">
          <div class="trabajo-header">
            <span class="badge-estado badge-contratado">Puntuación: <?= (int)$cal['puntuacion'] ?> / 5</span>
            <span style="font-size:11px; color:var(--gris); margin-left:auto;"><?= date('d/m/Y', strtotime($cal['creado_en'])) ?></span>
          </div>
          <h3 style="font: normal 18px Georgia,serif; color: var(--azul); margin: 6px 0 4px;">Empleado: <?= e($cal['candidato_nombre']) ?></h3>
          <?php if (!empty($cal['comentario'])): ?>
            <p style="font-size:13px; color:var(--texto); font-style:italic; margin-top:6px;">"<?= e($cal['comentario']) ?>"</p>
          <?php else: ?>
            <p class="texto-suave" style="font-size:12px; margin-top:6px;">Sin comentario adicional.</p>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Sección de Postulantes Recibidos -->
  <div id="postulantes" class="bloque-panel" style="margin-bottom:3rem;">
    <div class="titulo-seccion">
      <div>
        <span class="etiqueta">GESTIÓN DE TALENTO</span>
        <h2>Candidatos postulados a tus ofertas</h2>
      </div>
    </div>

    <?php if (empty($postulaciones)): ?>
      <div class="aviso">Aún no hay postulaciones para tus vacantes activas. Cuando los jóvenes postulen, aparecerán aquí.</div>
    <?php else: ?>
      <div class="tabla-contenedor">
        <table class="tabla-panel">
          <thead>
            <tr>
              <th>Candidato</th>
              <th>Contacto Rápido</th>
              <th>Puesto / Vacante</th>
              <th>Protección / Tutor</th>
              <th>Estado Actual</th>
              <th>Acción de Estado</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($postulaciones as $post): ?>
              <tr>
                <td>
                  <strong><?= e($post['candidato_nombre']) ?></strong>
                  <?php if (!empty($post['mensaje'])): ?>
                    <p class="nota-postulante"><em>"<?= e($post['mensaje']) ?>"</em></p>
                  <?php endif; ?>
                  <small class="texto-suave">Postulado: <?= date('d/m/Y H:i', strtotime($post['fecha_postulacion'])) ?></small>
                </td>
                <td>
                  <div>
                    <a href="mailto:<?= e($post['candidato_correo']) ?>" class="enlace-contacto" title="Enviar correo">
                      <?= e($post['candidato_correo']) ?>
                    </a>
                  </div>
                  <?php if (!empty($post['candidato_telefono'])): ?>
                    <?php 
                      $soloNum = preg_replace('/\D/', '', $post['candidato_telefono']); 
                    ?>
                    <div style="margin-top:4px;">
                      <a href="https://wa.me/591<?= $soloNum ?>" target="_blank" class="badge-whatsapp" title="Contactar por WhatsApp">
                        WhatsApp: <?= e($post['candidato_telefono']) ?>
                      </a>
                    </div>
                  <?php else: ?>
                    <div class="texto-suave">Sin teléfono</div>
                  <?php endif; ?>
                </td>
                <td>
                  <strong><?= e($post['empleo_titulo']) ?></strong>
                  <div class="texto-suave"><?= e(str_replace('_', ' ', $post['empleo_tipo'])) ?></div>
                </td>
                <td>
                  <?php if (!empty($post['es_menor'])): ?>
                    <span class="badge-menor">Menor de edad</span>
                    <div style="font-size:12px; margin-top:4px;">
                      <strong>Tutor:</strong> <?= e($post['tutor_nombre'] ?? 'No registrado') ?><br>
                      <strong>Tel:</strong> <?= e($post['tutor_telefono'] ?? '-') ?> (<?= e($post['tutor_relacion'] ?? 'Tutor') ?>)
                    </div>
                  <?php else: ?>
                    <span class="badge-mayor">Mayor de edad</span>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="badge-estado badge-<?= e($post['estado']) ?>">
                    <?= ucfirst(e($post['estado'])) ?>
                  </span>
                </td>
                <td>
                  <form action="ban.php?pagina=cambiar_estado_postulacion" method="post" class="form-cambiar-estado">
                    <input type="hidden" name="postulacion_id" value="<?= (int) $post['id'] ?>">
                    <select name="estado" class="select-sm">
                      <option value="pendiente" <?= $post['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                      <option value="revision" <?= $post['estado'] === 'revision' ? 'selected' : '' ?>>En revisión</option>
                      <option value="entrevista" <?= $post['estado'] === 'entrevista' ? 'selected' : '' ?>>Entrevista</option>
                      <option value="contratado" <?= $post['estado'] === 'contratado' ? 'selected' : '' ?>>Contratado</option>
                      <option value="rechazado" <?= $post['estado'] === 'rechazado' ? 'selected' : '' ?>>Rechazado</option>
                    </select>
                    <button type="submit" class="boton-sm">Actualizar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- Formulario para publicar vacante y lista de vacantes -->
  <div class="panel-vacantes" id="nueva-vacante">
    <div class="form-card">
      <h2>Publicar una nueva vacante</h2>
      <p class="texto-suave">Completa los datos del empleo a publicar en Vantia.</p>

      <form action="ban.php?pagina=guardar_empleo" method="post">
        <label for="titulo">Nombre del puesto *</label>
        <input id="titulo" name="titulo" type="text" maxlength="150" required placeholder="Ej. Desarrollador Web Jr.">

        <label for="categoria">Área o categoría *</label>
        <input id="categoria" name="categoria" type="text" maxlength="80" placeholder="Ej. Tecnología, Ventas, Gastronomía" required>

        <label for="tipo_empleo">Tipo de empleo *</label>
        <select id="tipo_empleo" name="tipo_empleo" required>
          <option value="">Selecciona una opción</option>
          <option value="medio_tiempo">Medio tiempo</option>
          <option value="practicas">Prácticas / Pasantías</option>
          <option value="fines_semana">Fines de semana</option>
          <option value="remoto">Remoto</option>
        </select>

        <label for="descripcion">Descripción del puesto *</label>
        <textarea id="descripcion" name="descripcion" rows="4" required placeholder="Detalla las responsabilidades principales"></textarea>

        <label for="requisitos">Requisitos deseables</label>
        <textarea id="requisitos" name="requisitos" rows="3" placeholder="Habilidades, conocimientos o requisitos mínimos"></textarea>

        <div class="campos-vacante">
          <div>
            <label for="salario">Salario / Remuneración</label>
            <input id="salario" name="salario" type="text" maxlength="50" placeholder="Ej. Bs 2.000 al mes">
          </div>
          <div>
            <label for="ubicacion">Ubicación</label>
            <input id="ubicacion" name="ubicacion" type="text" maxlength="120" placeholder="Ej. La Paz, Sopocachi o Remoto">
          </div>
        </div>

        <button class="boton" type="submit">Publicar vacante en Vantia</button>
      </form>
    </div>

    <div class="vacantes-publicadas" id="mis-vacantes">
      <h2>Mis vacantes publicadas</h2>
      <?php if (empty($empleos)): ?>
        <p class="texto-suave">Todavía no has publicado vacantes.</p>
      <?php else: ?>
        <?php foreach ($empleos as $empleo): ?>
          <article class="trabajo" style="position:relative;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
              <span class="etiqueta"><?= e($empleo['categoria']) ?> · <?= e(str_replace('_', ' ', strtoupper($empleo['tipo']))) ?></span>
              <?php if (!empty($empleo['activo'])): ?>
                <span class="etiqueta-verificada">Activa</span>
              <?php else: ?>
                <span class="badge-menor" style="background:#fee2e2; color:#991b1b;">Pausada</span>
              <?php endif; ?>
            </div>

            <h2><?= e($empleo['titulo']) ?></h2>
            <p><?= nl2br(e($empleo['descripcion'])) ?></p>
            
            <?php if (!empty($empleo['requisitos'])): ?><p><strong>Requisitos:</strong> <?= e($empleo['requisitos']) ?></p><?php endif; ?>
            <?php if (!empty($empleo['ubicacion'])): ?><p><strong>Ubicación:</strong> <?= e($empleo['ubicacion']) ?></p><?php endif; ?>
            <?php if (!empty($empleo['salario'])): ?><p><strong>Salario:</strong> <?= e($empleo['salario']) ?></p><?php endif; ?>

            <!-- Botones de gestión de vacante -->
            <div style="display:flex; gap:8px; margin-top:14px; padding-top:10px; border-top:1px dashed var(--linea); flex-wrap:wrap;">
              <button type="button" class="boton-sm btn-editar-vacante" style="background:#2563eb; color:white;" data-empleo="<?= htmlspecialchars(json_encode([
                  'id' => (int) $empleo['id'],
                  'titulo' => (string) $empleo['titulo'],
                  'categoria' => (string) $empleo['categoria'],
                  'tipo' => (string) $empleo['tipo'],
                  'descripcion' => (string) $empleo['descripcion'],
                  'requisitos' => (string) ($empleo['requisitos'] ?? ''),
                  'salario' => (string) ($empleo['salario'] ?? ''),
                  'ubicacion' => (string) ($empleo['ubicacion'] ?? ''),
              ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>">
                Editar
              </button>

              <form action="ban.php?pagina=alternar_empleo" method="post" style="display:inline;">
                <input type="hidden" name="empleo_id" value="<?= (int) $empleo['id'] ?>">
                <button type="submit" class="boton-sm <?= !empty($empleo['activo']) ? 'boton-outline-oscuro' : 'boton-exito' ?>">
                  <?= !empty($empleo['activo']) ? 'Pausar vacante' : 'Reactivar vacante' ?>
                </button>
              </form>

              <form action="ban.php?pagina=eliminar_empleo" method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta vacante y sus postulaciones?');">
                <input type="hidden" name="empleo_id" value="<?= (int) $empleo['id'] ?>">
                <button type="submit" class="boton-sm boton-peligro">Eliminar</button>
              </form>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Modal de Edición de Vacante -->
<div id="modalEditarVacante" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.65); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(2px); padding:16px;">
  <div style="background:white; border-radius:8px; width:100%; max-width:580px; max-height:92vh; overflow-y:auto; padding:24px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.25); border:1px solid #cbd5e1;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; border-bottom:1px solid #e2e8f0; padding-bottom:10px;">
      <h3 style="margin:0; font-size:18px; color:var(--azul); font-family:Georgia,serif;">
        Editar Vacante
      </h3>
      <button type="button" onclick="cerrarModalEditar()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#64748b; line-height:1;">&times;</button>
    </div>

    <form action="ban.php?pagina=actualizar_empleo" method="post" id="formEditarVacante" class="formulario" style="gap:10px;">
      <input type="hidden" name="empleo_id" id="edit_empleo_id" value="">

      <div>
        <label for="edit_titulo" style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Título del puesto *</label>
        <input id="edit_titulo" name="titulo" type="text" required placeholder="Ej. Auxiliar de atención al cliente" style="width:100%; height:38px; padding:8px 10px; border:1px solid #cbd5e1; border-radius:4px; font-size:13px;">
      </div>

      <div class="campos-vacante" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div>
          <label for="edit_categoria" style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Categoría laboral *</label>
          <input id="edit_categoria" name="categoria" type="text" required placeholder="Ej. Ventas, Logística" style="width:100%; height:38px; padding:8px 10px; border:1px solid #cbd5e1; border-radius:4px; font-size:13px;">
        </div>
        <div>
          <label for="edit_tipo_empleo" style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Modalidad o tipo *</label>
          <select id="edit_tipo_empleo" name="tipo_empleo" required style="width:100%; height:38px; padding:8px 10px; border:1px solid #cbd5e1; border-radius:4px; font-size:13px;">
            <option value="">Selecciona modalidad</option>
            <option value="medio_tiempo">Medio tiempo</option>
            <option value="practicas">Pasantía / Prácticas</option>
            <option value="fines_semana">Fines de semana</option>
            <option value="remoto">Remoto</option>
          </select>
        </div>
      </div>

      <div>
        <label for="edit_descripcion" style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Descripción del puesto *</label>
        <textarea id="edit_descripcion" name="descripcion" rows="4" required placeholder="Detalla las responsabilidades principales" style="width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:4px; font-size:13px; font-family:inherit; resize:vertical;"></textarea>
      </div>

      <div>
        <label for="edit_requisitos" style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Requisitos deseables</label>
        <textarea id="edit_requisitos" name="requisitos" rows="3" placeholder="Habilidades, conocimientos o requisitos mínimos" style="width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:4px; font-size:13px; font-family:inherit; resize:vertical;"></textarea>
      </div>

      <div class="campos-vacante" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
        <div>
          <label for="edit_salario" style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Salario / Remuneración</label>
          <input id="edit_salario" name="salario" type="text" maxlength="50" placeholder="Ej. Bs 2.000 al mes" style="width:100%; height:38px; padding:8px 10px; border:1px solid #cbd5e1; border-radius:4px; font-size:13px;">
        </div>
        <div>
          <label for="edit_ubicacion" style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Ubicación</label>
          <input id="edit_ubicacion" name="ubicacion" type="text" maxlength="120" placeholder="Ej. La Paz o Remoto" style="width:100%; height:38px; padding:8px 10px; border:1px solid #cbd5e1; border-radius:4px; font-size:13px;">
        </div>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:14px; padding-top:12px; border-top:1px solid #e2e8f0;">
        <button type="button" onclick="cerrarModalEditar()" class="boton-sm boton-outline-oscuro" style="border-color:#cbd5e1; color:#475569;">Cancelar</button>
        <button type="submit" class="boton-sm" style="background:var(--dorado); color:var(--azul);">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<script>
function abrirModalEditar(datos) {
  document.getElementById('edit_empleo_id').value = datos.id || '';
  document.getElementById('edit_titulo').value = datos.titulo || '';
  document.getElementById('edit_categoria').value = datos.categoria || '';
  document.getElementById('edit_tipo_empleo').value = datos.tipo || '';
  document.getElementById('edit_descripcion').value = datos.descripcion || '';
  document.getElementById('edit_requisitos').value = datos.requisitos || '';
  document.getElementById('edit_salario').value = datos.salario || '';
  document.getElementById('edit_ubicacion').value = datos.ubicacion || '';
  document.getElementById('modalEditarVacante').style.display = 'flex';
}

function cerrarModalEditar() {
  document.getElementById('modalEditarVacante').style.display = 'none';
}

document.querySelectorAll('.btn-editar-vacante').forEach(function(btn) {
  btn.addEventListener('click', function() {
    try {
      const data = JSON.parse(this.getAttribute('data-empleo'));
      abrirModalEditar(data);
    } catch (e) {
      console.error('Error al procesar datos de la vacante:', e);
    }
  });
});

window.addEventListener('click', function(e) {
  const modal = document.getElementById('modalEditarVacante');
  if (e.target === modal) {
    cerrarModalEditar();
  }
});
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>

<?php require __DIR__ . '/partials/header.php'; ?>

<section class="pagina-cabecera">
  <div class="contenedor">
    <span class="etiqueta">PANEL GENERAL DE CONTROL</span>
    <h1>Administración de Vantia</h1>
    <p>Supervisión de empresas verificadas, tutores legales, menores de edad y mayores de edad en la plataforma.</p>
    
    <div style="margin-top:1rem;">
      <a href="ban.php?pagina=trabajos" class="boton boton-sm" style="background:#e2f5e8; color:#1f7a46;">Bolsa pública de empleos →</a>
    </div>
  </div>
</section>

<section class="contenedor seccion">
  <?php if (!empty($exito)): ?><div class="mensaje-exito" style="margin-bottom:1.5rem;"><?= e($exito) ?></div><?php endif; ?>
  <?php if (!empty($error)): ?><div class="mensaje-error" style="margin-bottom:1.5rem;"><?= e($error) ?></div><?php endif; ?>

  <!-- Tarjetas de Estadísticas Globales -->
  <div class="tarjetas" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; margin-bottom:2.5rem;">
    <div class="tarjeta">
      <span class="numero">01 · EMPRESAS</span>
      <h2><?= (int) $totalEmpresas ?></h2>
    </div>
    <div class="tarjeta">
      <span class="numero">02 · MENORES (&lt;18)</span>
      <h2><?= (int) ($totalMenores ?? 0) ?></h2>
    </div>
    <div class="tarjeta">
      <span class="numero">03 · MAYORES (&ge;18)</span>
      <h2><?= (int) ($totalMayores ?? 0) ?></h2>
    </div>
    <div class="tarjeta">
      <span class="numero">04 · VACANTES</span>
      <h2><?= (int) $totalEmpleos ?></h2>
    </div>
    <div class="tarjeta">
      <span class="numero">05 · POSTULACIONES</span>
      <h2><?= (int) $totalPostulaciones ?></h2>
    </div>
    <div class="tarjeta">
      <span class="numero">06 · TUTORES</span>
      <h2><?= (int) ($totalTutores ?? 0) ?></h2>
    </div>
    <div class="tarjeta">
      <span class="numero">07 · ADMINS</span>
      <h2><?= (int) ($totalAdmins ?? 1) ?></h2>
    </div>
  </div>

  <!-- TABLA 1: Empresas Registradas -->
  <div class="bloque-panel" style="margin-bottom:3rem;">
    <div class="titulo-seccion">
      <div>
        <span class="etiqueta">EMPRESAS REGISTRADAS</span>
        <h2>Organizaciones y Validación Fiscal (NIT / ROE)</h2>
        <p class="texto-suave">Empresas registradas en la base de datos con verificación laboral.</p>
      </div>
    </div>

    <div class="tabla-contenedor">
      <table class="tabla-panel">
        <thead>
          <tr>
            <th>ID</th>
            <th>Empresa / Razón Social</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>NIT / ROE</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($empresas)): ?>
            <tr>
              <td colspan="7" style="text-align:center; padding:1.5rem; color:var(--gris);">No hay empresas registradas actualmente.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($empresas as $emp): ?>
              <tr>
                <td>#<?= (int) $emp['id'] ?></td>
                <td>
                  <strong><?= e($emp['nombre']) ?></strong>
                  <?php if (!empty($emp['representante'])): ?>
                    <div style="font-size:0.82rem; color:var(--texto-suave, #64748b); margin-top:2px;">
                      Rep: <?= e($emp['representante']) ?>
                    </div>
                  <?php endif; ?>
                </td>
                <td><a href="mailto:<?= e($emp['correo']) ?>" class="enlace-contacto"><?= e($emp['correo']) ?></a></td>
                <td><?= !empty($emp['telefono']) ? e($emp['telefono']) : '<span class="texto-suave">-</span>' ?></td>
                <td>
                  <div>NIT: <code><?= e($emp['nit'] ?? 'S/N') ?></code></div>
                  <div style="margin-top:6px;">
                    <?php if (!empty($emp['roe']) && preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', $emp['roe'])): ?>
                      <div style="font-size:0.75rem; font-weight:600; color:var(--texto-suave); margin-bottom:3px;">ROE (QR):</div>
                      <a href="<?= e(rutaArchivo($emp['roe'])) ?>" target="_blank" title="Clic para ver imagen de ROE / QR">
                        <img src="<?= e(rutaArchivo($emp['roe'])) ?>" alt="ROE QR" style="width:50px; height:50px; object-fit:contain; border:1px solid #cbd5e1; border-radius:4px; background:#fff; padding:2px; box-shadow:0 1px 3px rgba(0,0,0,0.08); transition:transform 0.15s ease;" onmouseover="this.style.transform='scale(1.8)'" onmouseout="this.style.transform='scale(1)'">
                      </a>
                    <?php else: ?>
                      <div>ROE: <code><?= e($emp['roe'] ?? 'S/N') ?></code></div>
                    <?php endif; ?>
                  </div>
                </td>
                <td>
                  <div style="margin-bottom:4px;">
                    <?php if (!empty($emp['verificada'])): ?>
                      <span class="etiqueta-verificada">Verificada</span>
                    <?php else: ?>
                      <span class="badge-menor" style="background:#fee2e2; color:#991b1b;">Pendiente</span>
                    <?php endif; ?>
                  </div>
                  <?php if (estaBaneado($emp)): ?>
                    <div style="margin-top:4px;">
                      <?php if (empty($emp['baneado_hasta'])): ?>
                        <span class="badge-estado badge-rechazado" style="font-size:10px;">Suspensión permanente</span>
                      <?php else: ?>
                        <span class="badge-estado" style="background:#fed7aa; color:#9a3412; font-size:10px;" title="Hasta: <?= e($emp['baneado_hasta']) ?>">
                          Baneo hasta <?= date('d/m/Y', strtotime($emp['baneado_hasta'])) ?>
                        </span>
                      <?php endif; ?>
                      <?php if (!empty($emp['motivo_baneo'])): ?>
                        <div style="font-size:10px; color:#991b1b; margin-top:2px; max-width:140px; word-break:break-word;">
                          <?= e($emp['motivo_baneo']) ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php else: ?>
                    <span class="badge-estado badge-contratado" style="font-size:10px;">Activa</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="display:flex; gap:6px; flex-wrap:wrap;">
                    <form action="ban.php?pagina=toggle_verificar_empresa" method="post" style="display:inline;">
                      <input type="hidden" name="empresa_id" value="<?= (int) $emp['id'] ?>">
                      <button type="submit" class="boton-sm <?= !empty($emp['verificada']) ? 'boton-outline-oscuro' : 'boton-exito' ?>">
                        <?= !empty($emp['verificada']) ? 'Quitar verificación' : 'Verificar NIT' ?>
                      </button>
                    </form>

                    <?php if (estaBaneado($emp)): ?>
                      <form action="ban.php?pagina=desbanear_cuenta" method="post" style="display:inline;" onsubmit="return confirm('¿Deseas reactivar y revocar la suspensión de esta empresa?');">
                        <input type="hidden" name="id" value="<?= (int) $emp['id'] ?>">
                        <input type="hidden" name="tabla" value="empresas">
                        <button type="submit" class="boton-sm" style="background:#10b981; color:white;">Reactivar</button>
                      </form>
                    <?php else: ?>
                      <button type="button" class="boton-sm" style="background:#f97316; color:white;" onclick="abrirModalBaneo(<?= (int)$emp['id'] ?>, 'empresas', '<?= e(addslashes($emp['nombre'])) ?>')">
                        Suspender
                      </button>
                    <?php endif; ?>

                    <form action="ban.php?pagina=eliminar_empresa" method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta empresa y todas sus vacantes?');">
                      <input type="hidden" name="empresa_id" value="<?= (int) $emp['id'] ?>">
                      <button type="submit" class="boton-sm boton-peligro">Eliminar</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- TABLA 2: Jóvenes Menores de 18 años -->
  <div class="bloque-panel" style="margin-bottom:3rem;">
    <div class="titulo-seccion">
      <div>
        <span class="etiqueta">JÓVENES MENORES DE EDAD</span>
        <h2>Jóvenes Menores de 18 años (16 y 17 años con Tutor Legal)</h2>
        <p class="texto-suave">Postulantes menores de edad que requieren consentimiento y datos de su tutor legal.</p>
      </div>
    </div>

    <div class="tabla-contenedor">
      <table class="tabla-panel">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre del Joven</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Nacimiento / Edad</th>
            <th>Tutor Legal (Padre/Madre)</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($usuariosMen)): ?>
            <tr>
              <td colspan="8" style="text-align:center; padding:1.5rem; color:var(--gris);">No hay jóvenes menores de 18 años registrados actualmente.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($usuariosMen as $umen): ?>
              <tr>
                <td>#<?= (int) $umen['id'] ?></td>
                <td>
                  <strong><?= e($umen['nombre']) ?></strong>
                  <span class="badge-menor" style="margin-left:6px; font-size:11px;">Menor de edad</span>
                </td>
                <td><a href="mailto:<?= e($umen['correo']) ?>" class="enlace-contacto"><?= e($umen['correo']) ?></a></td>
                <td><?= !empty($umen['telefono']) ? e($umen['telefono']) : '<span class="texto-suave">-</span>' ?></td>
                <td>
                  <?= e($umen['fecha_nacimiento']) ?>
                  <?php if (!empty($umen['fecha_nacimiento'])): ?>
                    <div style="font-size:11px; color:var(--gris);"><?= calcularEdad($umen['fecha_nacimiento']) ?> años</div>
                  <?php endif; ?>
                </td>
                <td>
                  <div><strong><?= e($umen['tutor_nombre'] ?? 'N/D') ?></strong> (<?= e($umen['tutor_relacion'] ?? 'Tutor') ?>)</div>
                  <div style="font-size:12px; color:var(--gris);">Tel: <?= e($umen['tutor_telefono'] ?? '-') ?></div>
                </td>
                <td>
                  <?php if (estaBaneado($umen)): ?>
                    <div>
                      <?php if (empty($umen['baneado_hasta'])): ?>
                        <span class="badge-estado badge-rechazado" style="font-size:10px;">Permanente</span>
                      <?php else: ?>
                        <span class="badge-estado" style="background:#fed7aa; color:#9a3412; font-size:10px;">
                          Hasta <?= date('d/m/Y', strtotime($umen['baneado_hasta'])) ?>
                        </span>
                      <?php endif; ?>
                      <?php if (!empty($umen['motivo_baneo'])): ?>
                        <div style="font-size:10px; color:#991b1b; margin-top:2px; max-width:130px; word-break:break-word;"><?= e($umen['motivo_baneo']) ?></div>
                      <?php endif; ?>
                    </div>
                  <?php else: ?>
                    <span class="badge-estado badge-contratado" style="font-size:10px;">Activo</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="display:flex; gap:6px; flex-wrap:wrap;">
                    <?php if (estaBaneado($umen)): ?>
                      <form action="ban.php?pagina=desbanear_cuenta" method="post" style="display:inline;" onsubmit="return confirm('¿Deseas reactivar este postulante?');">
                        <input type="hidden" name="id" value="<?= (int) $umen['id'] ?>">
                        <input type="hidden" name="tabla" value="usuarios_men">
                        <button type="submit" class="boton-sm" style="background:#10b981; color:white;">Reactivar</button>
                      </form>
                    <?php else: ?>
                      <button type="button" class="boton-sm" style="background:#f97316; color:white;" onclick="abrirModalBaneo(<?= (int)$umen['id'] ?>, 'usuarios_men', '<?= e(addslashes($umen['nombre'])) ?>')">
                        Suspender
                      </button>
                    <?php endif; ?>

                    <form action="ban.php?pagina=eliminar_usuario" method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este postulante menor de edad?');">
                      <input type="hidden" name="usuario_id" value="<?= (int) $umen['id'] ?>">
                      <input type="hidden" name="tabla" value="usuarios_men">
                      <button type="submit" class="boton-sm boton-peligro">Eliminar</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- TABLA 3: Tutores Legales y Jóvenes a su Cargo -->
  <div class="bloque-panel" style="margin-bottom:3rem;">
    <div class="titulo-seccion">
      <div>
        <span class="etiqueta">TUTORES LEGALES</span>
        <h2>Tutores Legales y Jóvenes Menores a su Cargo</h2>
        <p class="texto-suave">Padres, madres o tutores legales responsables de los postulantes menores de edad (16 y 17 años).</p>
      </div>
    </div>

    <div class="tabla-contenedor">
      <table class="tabla-panel">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tutor Legal</th>
            <th>Parentesco</th>
            <th>Teléfono Tutor</th>
            <th>Joven a su Cargo</th>
            <th>Contacto del Joven</th>
            <th>Edad del Joven</th>
            <th>Fecha Registro</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($tutores)): ?>
            <tr>
              <td colspan="8" style="text-align:center; padding:1.5rem; color:var(--gris);">No hay tutores registrados actualmente.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($tutores as $tut): ?>
              <tr>
                <td>#<?= (int) $tut['id'] ?></td>
                <td>
                  <strong><?= e($tut['nombre']) ?></strong>
                  <div style="font-size:11px; color:#1d4ed8; margin-top:2px;">Tutor legal activo</div>
                </td>
                <td>
                  <span class="badge-menor" style="background:#f1f5f9; color:#334155; font-size:11px;">
                    <?= e($tut['relacion'] ?? 'Tutor legal') ?>
                  </span>
                </td>
                <td>
                  <a href="tel:<?= e($tut['telefono']) ?>" class="enlace-contacto">
                    <?= e($tut['telefono']) ?>
                  </a>
                </td>
                <td>
                  <div style="font-weight:600; color:#0f172a;">
                    <?= e($tut['menor_nombre']) ?>
                  </div>
                  <div style="font-size:11px; color:var(--gris);">
                    ID Joven: <code>#<?= (int) $tut['usuario_men_id'] ?></code>
                  </div>
                </td>
                <td>
                  <div><a href="mailto:<?= e($tut['menor_correo']) ?>" class="enlace-contacto"><?= e($tut['menor_correo']) ?></a></div>
                  <?php if (!empty($tut['menor_telefono'])): ?>
                    <div style="font-size:12px; color:var(--gris);">Tel: <?= e($tut['menor_telefono']) ?></div>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($tut['fecha_nacimiento'])): ?>
                    <strong><?= calcularEdad($tut['fecha_nacimiento']) ?> años</strong>
                    <div style="font-size:11px; color:var(--gris);"><?= e($tut['fecha_nacimiento']) ?></div>
                  <?php else: ?>
                    <span class="texto-suave">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <span style="font-size:12px; color:var(--gris);">
                    <?= !empty($tut['creado_en']) ? date('d/m/Y H:i', strtotime($tut['creado_en'])) : '-' ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- TABLA 4: Jóvenes Mayores de 18 años y Administradores -->
  <div class="bloque-panel">
    <div class="titulo-seccion">
      <div>
        <span class="etiqueta">JÓVENES MAYORES Y ADMINISTRADORES</span>
        <h2>Jóvenes Mayores de 18 años y Administradores</h2>
        <p class="texto-suave">Cuentas individuales de postulantes mayores de edad y personal administrativo.</p>
      </div>
    </div>

    <div class="tabla-contenedor">
      <table class="tabla-panel">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre Completo</th>
            <th>Tipo</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Nacimiento / Edad</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($usuariosMay)): ?>
            <tr>
              <td colspan="8" style="text-align:center; padding:1.5rem; color:var(--gris);">No hay usuarios mayores registrados actualmente.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($usuariosMay as $umay): ?>
              <tr>
                <td>#<?= (int) $umay['id'] ?></td>
                <td><strong><?= e($umay['nombre']) ?></strong></td>
                <td>
                  <span class="tipo-badge <?= e($umay['tipo']) ?>">
                    <?= ucfirst(e($umay['tipo'])) ?>
                  </span>
                </td>
                <td>
                  <a href="mailto:<?= e($umay['correo']) ?>" class="enlace-contacto"><?= e($umay['correo']) ?></a>
                </td>
                <td><?= !empty($umay['telefono']) ? e($umay['telefono']) : '<span class="texto-suave">-</span>' ?></td>
                <td>
                  <?php if (!empty($umay['fecha_nacimiento'])): ?>
                    <?= e($umay['fecha_nacimiento']) ?>
                    <div style="font-size:11px; color:var(--gris);"><?= calcularEdad($umay['fecha_nacimiento']) ?> años</div>
                  <?php else: ?>
                    <span class="texto-suave">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ((int)$umay['id'] === (int)($_SESSION['usuario_id'] ?? 0)): ?>
                    <span class="badge-estado badge-contratado" style="font-size:10px;">Activo (Admin)</span>
                  <?php elseif (estaBaneado($umay)): ?>
                    <div>
                      <?php if (empty($umay['baneado_hasta'])): ?>
                        <span class="badge-estado badge-rechazado" style="font-size:10px;">Permanente</span>
                      <?php else: ?>
                        <span class="badge-estado" style="background:#fed7aa; color:#9a3412; font-size:10px;">
                          Hasta <?= date('d/m/Y', strtotime($umay['baneado_hasta'])) ?>
                        </span>
                      <?php endif; ?>
                      <?php if (!empty($umay['motivo_baneo'])): ?>
                        <div style="font-size:10px; color:#991b1b; margin-top:2px; max-width:130px; word-break:break-word;"><?= e($umay['motivo_baneo']) ?></div>
                      <?php endif; ?>
                    </div>
                  <?php else: ?>
                    <span class="badge-estado badge-contratado" style="font-size:10px;">Activo</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ((int)$umay['id'] !== (int)($_SESSION['usuario_id'] ?? 0)): ?>
                    <div style="display:flex; gap:6px; flex-wrap:wrap;">
                      <?php if (estaBaneado($umay)): ?>
                        <form action="ban.php?pagina=desbanear_cuenta" method="post" style="display:inline;" onsubmit="return confirm('¿Deseas reactivar este usuario?');">
                          <input type="hidden" name="id" value="<?= (int) $umay['id'] ?>">
                          <input type="hidden" name="tabla" value="usuarios_may">
                          <button type="submit" class="boton-sm" style="background:#10b981; color:white;">Reactivar</button>
                        </form>
                      <?php else: ?>
                        <button type="button" class="boton-sm" style="background:#f97316; color:white;" onclick="abrirModalBaneo(<?= (int)$umay['id'] ?>, 'usuarios_may', '<?= e(addslashes($umay['nombre'])) ?>')">
                          Suspender
                        </button>
                      <?php endif; ?>

                      <form action="ban.php?pagina=eliminar_usuario" method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                        <input type="hidden" name="usuario_id" value="<?= (int) $umay['id'] ?>">
                        <input type="hidden" name="tabla" value="usuarios_may">
                        <button type="submit" class="boton-sm boton-peligro">Eliminar</button>
                      </form>
                    </div>
                  <?php else: ?>
                    <span class="texto-suave" style="font-size:12px;">Tu sesión actual</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- TABLA 5: Personal Administrativo (usuarios_admin) -->
  <div class="bloque-panel" style="margin-bottom:3rem;">
    <div class="titulo-seccion">
      <div>
        <span class="etiqueta">EQUIPO Y ACCESO PRIVILEGIADO</span>
        <h2>Administradores del Sistema</h2>
      </div>
      <span class="total-badge"><?= count($administradores ?? []) ?> registrados</span>
    </div>

    <div class="tabla-contenedor">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo Electrónico</th>
            <th>Teléfono</th>
            <th>Cargo</th>
            <th>Estado</th>
            <th>Fecha Registro</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($administradores)): ?>
            <tr><td colspan="7" class="tabla-vacia">No hay administradores adicionales registrados.</td></tr>
          <?php else: ?>
            <?php foreach ($administradores as $adm): ?>
              <tr>
                <td>#<?= (int) $adm['id'] ?></td>
                <td><strong><?= e($adm['nombre']) ?></strong></td>
                <td><?= e($adm['correo']) ?></td>
                <td><?= e($adm['telefono'] ?: 'No registrado') ?></td>
                <td><span class="tipo-badge admin"><?= e($adm['cargo'] ?? 'Administrador') ?></span></td>
                <td>
                  <?php if (!empty($adm['activo'])): ?>
                    <span class="badge badge-verificada">Activo</span>
                  <?php else: ?>
                    <span class="badge badge-suspendida">Inactivo</span>
                  <?php endif; ?>
                </td>
                <td style="font-size:12px; color:#64748b;"><?= date('d/m/Y H:i', strtotime($adm['fecha_registro'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Modal Reutilizable de Baneo Temporal o Permanente -->
<div id="modalBaneo" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(2px);">
  <div style="background:white; border-radius:8px; width:90%; max-width:440px; padding:24px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.2); border:1px solid #e2e8f0;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; border-bottom:1px solid #e2e8f0; padding-bottom:10px;">
      <h3 style="margin:0; font-size:17px; color:#991b1b; display:flex; align-items:center; gap:6px;">
        Suspender Cuenta (Baneo)
      </h3>
      <button type="button" onclick="cerrarModalBaneo()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#64748b; line-height:1;">&times;</button>
    </div>

    <form action="ban.php?pagina=banear_cuenta" method="post" id="formBaneo">
      <input type="hidden" name="id" id="baneoId" value="">
      <input type="hidden" name="tabla" id="baneoTabla" value="">

      <div style="margin-bottom:14px;">
        <label style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Cuenta seleccionada:</label>
        <div id="baneoNombre" style="font-weight:700; color:#0f172a; font-size:14px; background:#f8fafc; padding:8px 12px; border-radius:4px; border:1px solid #e2e8f0;"></div>
      </div>

      <div style="margin-bottom:14px;">
        <label for="tipo_baneo" style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Tipo de Suspensión:</label>
        <select name="tipo_baneo" id="tipo_baneo" class="select-sm" style="width:100%; height:38px; border-color:#cbd5e1; border-radius:4px;" onchange="alternarDuracionBaneo(this.value)">
          <option value="temporal">Suspensión Temporal (días)</option>
          <option value="permanente">Suspensión Permanente</option>
        </select>
      </div>

      <div id="grupoDuracion" style="margin-bottom:14px;">
        <label for="duracion_dias" style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Tiempo de suspensión:</label>
        <select name="duracion_dias" id="duracion_dias" class="select-sm" style="width:100%; height:38px; border-color:#cbd5e1; border-radius:4px;">
          <option value="1">1 Día</option>
          <option value="3">3 Días</option>
          <option value="7" selected>7 Días (1 semana)</option>
          <option value="15">15 Días (2 semanas)</option>
          <option value="30">30 Días (1 mes)</option>
          <option value="90">90 Días (3 meses)</option>
        </select>
      </div>

      <div style="margin-bottom:20px;">
        <label for="motivo" style="font-size:12px; font-weight:bold; color:#475569; display:block; margin-bottom:4px;">Motivo de la sanción:</label>
        <input type="text" name="motivo" id="motivo" placeholder="Ej. Publicación de ofertas falsas o conducta inapropiada" required style="width:100%; height:38px; padding:6px 10px; border:1px solid #cbd5e1; border-radius:4px; font-size:13px;">
      </div>

      <div style="display:flex; justify-content:flex-end; gap:8px;">
        <button type="button" onclick="cerrarModalBaneo()" class="boton-sm boton-outline-oscuro" style="border-color:#cbd5e1; color:#475569;">Cancelar</button>
        <button type="submit" class="boton-sm boton-peligro" style="background:#dc2626;">Aplicar Suspensión</button>
      </div>
    </form>
  </div>
</div>

<script>
function abrirModalBaneo(id, tabla, nombre) {
  document.getElementById('baneoId').value = id;
  document.getElementById('baneoTabla').value = tabla;
  document.getElementById('baneoNombre').textContent = nombre;
  document.getElementById('tipo_baneo').value = 'temporal';
  document.getElementById('grupoDuracion').style.display = 'block';
  document.getElementById('motivo').value = '';
  document.getElementById('modalBaneo').style.display = 'flex';
}

function cerrarModalBaneo() {
  document.getElementById('modalBaneo').style.display = 'none';
}

function alternarDuracionBaneo(tipo) {
  document.getElementById('grupoDuracion').style.display = (tipo === 'temporal') ? 'block' : 'none';
}

// Cerrar modal al hacer clic en el fondo oscuro
window.addEventListener('click', function(e) {
  const modal = document.getElementById('modalBaneo');
  if (e.target === modal) {
    cerrarModalBaneo();
  }
});
</script>

<?php require __DIR__ . '/partials/footer.php'; ?>

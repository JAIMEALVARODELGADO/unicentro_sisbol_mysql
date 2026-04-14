/* ================================================================
   clientes.js – Lógica del formulario de registro de clientes
   Unicentro | Bootstrap 5 + Fetch API (AJAX)
================================================================ */

/* ── Utilidad ── */
const $ = id => document.getElementById(id);

/* ================================================================
   Contadores de caracteres
================================================================ */
const charFields = [
  { input: 'email_cli',  counter: 'cnt-email', max: 60 },
  { input: 'nrod_cli',   counter: 'cnt-nrod',  max: 20 },
  { input: 'exped_cli',  counter: 'cnt-exped', max: 40 },
  { input: 'nomb_cli',   counter: 'cnt-nomb',  max: 25 },
  { input: 'ape_cli',    counter: 'cnt-ape',   max: 25 },
  { input: 'tele_cli',   counter: 'cnt-tele',  max: 22 },
  { input: 'dire_cli',   counter: 'cnt-dire',  max: 50 },
];

charFields.forEach(({ input, counter, max }) => {
  const el  = $(input);
  const cnt = $(counter);
  const update = () => {
    const len = el.value.length;
    cnt.textContent = `${len} / ${max}`;
    cnt.classList.toggle('warn', len >= max * 0.9);
  };
  el.addEventListener('input', update);
});

/* ================================================================
   Toast / notificaciones
================================================================ */
function showToast(msg, type = 'success') {
  const area = $('toast-area');
  const icon = type === 'success'
    ? 'bi-check-circle-fill'
    : 'bi-exclamation-triangle-fill';
  const div = document.createElement('div');
  div.className = `toast-msg toast-${type}`;
  div.innerHTML = `<i class="bi ${icon} toast-icon"></i><span>${msg}</span>`;
  area.appendChild(div);
  setTimeout(() => div.remove(), 4500);
}

/* ================================================================
   Overlay de carga
================================================================ */
const overlay   = $('loading-overlay');
const setLoading = on => overlay.classList.toggle('active', on);

/* ================================================================
   Limpiar formulario
================================================================ */
$('btnLimpiar').addEventListener('click', () => {
  const form = $('frmCliente');
  form.reset();
  form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  charFields.forEach(({ counter, max }) => {
    $(counter).textContent = `0 / ${max}`;
    $(counter).classList.remove('warn');
  });
});

/* ================================================================
   Validación del lado cliente
================================================================ */
function validarFormulario() {
  const form = $('frmCliente');
  let valido = true;

  /* Limpiar errores previos */
  form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

  /* Helper: marcar campo requerido vacío */
  const req = field => {
    const el = $(field);
    if (!el.value.trim()) {
      el.classList.add('is-invalid');
      valido = false;
    }
  };

  /* Email con formato */
  const emailEl  = $('email_cli');
  const emailRgx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailEl.value.trim() || !emailRgx.test(emailEl.value.trim())) {
    emailEl.classList.add('is-invalid');
    valido = false;
  }

  /* Campos obligatorios */
  ['tpid_cli', 'nrod_cli', 'nomb_cli', 'ape_cli',
   'sexo_cli', 'fnac_cli', 'id_barrio'].forEach(req);

  /* Fecha de nacimiento: no puede ser hoy ni futura */
  const fnac = $('fnac_cli');
  if (fnac.value) {
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    if (new Date(fnac.value) >= hoy) {
      fnac.classList.add('is-invalid');
      valido = false;
    }
  }

  return valido;
}

/* ================================================================
   Envío AJAX
================================================================ */
$('frmCliente').addEventListener('submit', async function (e) {
  e.preventDefault();

  if (!validarFormulario()) {
    showToast('Por favor corrija los campos marcados en rojo.', 'error');
    const first = this.querySelector('.is-invalid');
    if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  /* Construir payload */
  const data = {
    email_cli : $('email_cli').value.trim(),
    tpid_cli  : $('tpid_cli').value,
    nrod_cli  : $('nrod_cli').value.trim(),
    exped_cli : $('exped_cli').value.trim(),
    nomb_cli  : $('nomb_cli').value.trim(),
    ape_cli   : $('ape_cli').value.trim(),
    sexo_cli  : $('sexo_cli').value,
    fnac_cli  : $('fnac_cli').value,
    tele_cli  : $('tele_cli').value.trim(),
    dire_cli  : $('dire_cli').value.trim(),
    id_barrio : $('id_barrio').value,
  };

  setLoading(true);
  $('btnGuardar').disabled = true;

  try {
    const response = await fetch('guardar_cliente.php', {
      method : 'POST',
      headers: { 'Content-Type': 'application/json' },
      body   : JSON.stringify(data),
    });

    const result = await response.json();

    if (result.success) {
      showToast(
        `✓ Cliente <strong>${data.nomb_cli} ${data.ape_cli}</strong> registrado correctamente.`,
        'success'
      );
      /* Limpiar tras éxito */
      $('frmCliente').reset();
      charFields.forEach(({ counter, max }) => {
        $(counter).textContent = `0 / ${max}`;
        $(counter).classList.remove('warn');
      });
    } else {
      showToast(result.message || 'No se pudo guardar el cliente. Intente nuevamente.', 'error');
    }

  } catch (err) {
    console.error('[clientes.js] Error AJAX:', err);
    showToast('Error de conexión con el servidor. Verifique su red.', 'error');
  } finally {
    setLoading(false);
    $('btnGuardar').disabled = false;
  }
});

/* ================================================================
   clientes.js – Lógica del formulario de registro de clientes
   Unicentro | Bootstrap 5 + Fetch API (AJAX)
================================================================ */

/* ── Utilidad ── */
const $ = id => document.getElementById(id);

/* ================================================================
   cargarTpDocumento
   Llama al backend PHP con opcion='tipoIdentificacion' y puebla
   el <select id="tpid_cli"> con los registros de la tabla `tipo`
   donde codi_gru = '01'.
================================================================ */
async function cargarTpDocumento() {
  const opcion  = 'tipoIdentificacion';
  const select  = $('tpid_cli');

  /* Estado de carga */
  select.disabled = true;
  select.innerHTML = '<option value="">Cargando…</option>';

  try {
    const response = await fetch(
      `crudCliente.php?opcion=${encodeURIComponent(opcion)}`,
      { method: 'GET', headers: { 'Accept': 'application/json' } }
    );

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    const result = await response.json();

    /* Limpiar y agregar opción por defecto */
    select.innerHTML = '<option value="">Seleccione…</option>';

    if (result.success && Array.isArray(result.data) && result.data.length > 0) {
      result.data.forEach(row => {
        const opt   = document.createElement('option');
        opt.value   = row.valo_tip;   /* valor corto: CC, SI, TI… */
        opt.textContent = row.desc_tip; /* descripción larga        */
        opt.dataset.codi = row.codi_tip; /* código completo como data */
        select.appendChild(opt);
      });
    } else {
      select.innerHTML = '<option value="">Sin opciones disponibles</option>';
    }

  } catch (err) {
    console.error('[cargarTpDocumento] Error:', err);
    select.innerHTML = '<option value="">Error al cargar</option>';
    showToast('No se pudo cargar los tipos de documento.', 'error');
  } finally {
    select.disabled = false;
  }
}

/* Invocar automáticamente al cargar el DOM */
document.addEventListener('DOMContentLoaded', cargarTpDocumento);

/* ================================================================
   Contadores de caracteres
================================================================ */
const charFields = [
  { input: 'email_cli',  counter: 'cnt-email', max: 60 },
  { input: 'nrod_cli',   counter: 'cnt-nrod',  max: 20 },
  { input: 'exped_cli',  counter: 'cnt-exped', max: 40 },
  { input: 'nomb_cli',   counter: 'cnt-nomb',  max: 25 },
  { input: 'ape_cli',    counter: 'cnt-ape',   max: 25 },
  { input: 'tele_cli',   counter: 'cnt-tele',  max: 22 },
  { input: 'dire_cli',   counter: 'cnt-dire',  max: 50 },
];

charFields.forEach(({ input, counter, max }) => {
  const el  = $(input);
  const cnt = $(counter);
  const update = () => {
    const len = el.value.length;
    cnt.textContent = `${len} / ${max}`;
    cnt.classList.toggle('warn', len >= max * 0.9);
  };
  el.addEventListener('input', update);
});

/* ================================================================
   Toast / notificaciones
================================================================ */
function showToast(msg, type = 'success') {
  const area = $('toast-area');
  const icon = type === 'success'
    ? 'bi-check-circle-fill'
    : 'bi-exclamation-triangle-fill';
  const div = document.createElement('div');
  div.className = `toast-msg toast-${type}`;
  div.innerHTML = `<i class="bi ${icon} toast-icon"></i><span>${msg}</span>`;
  area.appendChild(div);
  setTimeout(() => div.remove(), 4500);
}

/* ================================================================
   Overlay de carga
================================================================ */
const overlay   = $('loading-overlay');
const setLoading = on => overlay.classList.toggle('active', on);

/* ================================================================
   Limpiar formulario
================================================================ */
$('btnLimpiar').addEventListener('click', () => {
  const form = $('frmCliente');
  form.reset();
  form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  charFields.forEach(({ counter, max }) => {
    $(counter).textContent = `0 / ${max}`;
    $(counter).classList.remove('warn');
  });
});

/* ================================================================
   Validación del lado cliente
================================================================ */
function validarFormulario() {
  const form = $('frmCliente');
  let valido = true;

  /* Limpiar errores previos */
  form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

  /* Helper: marcar campo requerido vacío */
  const req = field => {
    const el = $(field);
    if (!el.value.trim()) {
      el.classList.add('is-invalid');
      valido = false;
    }
  };

  /* Email con formato */
  const emailEl  = $('email_cli');
  const emailRgx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailEl.value.trim() || !emailRgx.test(emailEl.value.trim())) {
    emailEl.classList.add('is-invalid');
    valido = false;
  }

  /* Campos obligatorios */
  ['tpid_cli', 'nrod_cli', 'nomb_cli', 'ape_cli',
   'sexo_cli', 'fnac_cli', 'id_barrio'].forEach(req);

  /* Fecha de nacimiento: no puede ser hoy ni futura */
  const fnac = $('fnac_cli');
  if (fnac.value) {
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    if (new Date(fnac.value) >= hoy) {
      fnac.classList.add('is-invalid');
      valido = false;
    }
  }

  return valido;
}

/* ================================================================
   Envío AJAX
================================================================ */
$('frmCliente').addEventListener('submit', async function (e) {
  e.preventDefault();

  if (!validarFormulario()) {
    showToast('Por favor corrija los campos marcados en rojo.', 'error');
    const first = this.querySelector('.is-invalid');
    if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  /* Construir payload */
  const data = {
    email_cli : $('email_cli').value.trim(),
    tpid_cli  : $('tpid_cli').value,
    nrod_cli  : $('nrod_cli').value.trim(),
    exped_cli : $('exped_cli').value.trim(),
    nomb_cli  : $('nomb_cli').value.trim(),
    ape_cli   : $('ape_cli').value.trim(),
    sexo_cli  : $('sexo_cli').value,
    fnac_cli  : $('fnac_cli').value,
    tele_cli  : $('tele_cli').value.trim(),
    dire_cli  : $('dire_cli').value.trim(),
    id_barrio : $('id_barrio').value,
  };

  setLoading(true);
  $('btnGuardar').disabled = true;

  try {
    const response = await fetch('guardar_cliente.php', {
      method : 'POST',
      headers: { 'Content-Type': 'application/json' },
      body   : JSON.stringify(data),
    });

    const result = await response.json();

    if (result.success) {
      showToast(
        `✓ Cliente <strong>${data.nomb_cli} ${data.ape_cli}</strong> registrado correctamente.`,
        'success'
      );
      /* Limpiar tras éxito */
      $('frmCliente').reset();
      charFields.forEach(({ counter, max }) => {
        $(counter).textContent = `0 / ${max}`;
        $(counter).classList.remove('warn');
      });
    } else {
      showToast(result.message || 'No se pudo guardar el cliente. Intente nuevamente.', 'error');
    }

  } catch (err) {
    console.error('[clientes.js] Error AJAX:', err);
    showToast('Error de conexión con el servidor. Verifique su red.', 'error');
  } finally {
    setLoading(false);
    $('btnGuardar').disabled = false;
  }
});
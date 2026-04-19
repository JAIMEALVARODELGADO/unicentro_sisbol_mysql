  /* ==========================================================
   clientes.js
   Logica del formulario de registro de clientes
   Unicentro | Bootstrap 5 + Fetch API (AJAX)
========================================================== */

var nuevo=1;


/* Utilidad: obtener elemento por id */
function $id(id) {
  return document.getElementById(id);
}

/* ==========================================================
 cargarTpDocumento
 Llama al backend PHP con opcion='tipoIdentificacion' y
 puebla el select#tpid_cli con los registros de la tabla
 tipo donde codi_gru = '01'.
========================================================== */
async function cargarTpDocumento() {
    var opcion = 'tipoIdentificacion';
    url="crudCliente.php?opcion=" + opcion;
    
    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();
        cargarOptionTpDoc(data);
    } 
    catch (error) {
        console.error('Falló la petición:', error);
    }
}

function cargarOptionTpDoc(data){
    const select = $id('tpid_cli');

    const option = document.createElement('option');
    option.value = "";
    option.textContent = "Seleccione…";
    select.appendChild(option);

    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.codi_tip;
        option.textContent = item.desc_tip;
        select.appendChild(option);
    });
}

async function cargarBarrios() {
    var opcion = 'barrio';
    url="crudCliente.php?opcion=" + opcion;
    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();
        cargarOptionBarrio(data);
    } 
    catch (error) {
        console.error('Falló la petición:', error);
    }
}

function cargarOptionBarrio(data){
    const select = $id('id_barrio');

    data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id_barrio;
        option.textContent = item.nombre_bar + " - " + item.comuna_bar;
        select.appendChild(option);
    });
}

async function enviarCorreo() {
    
    var emailEl  = $id('email_cli');
    var emailRgx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailEl.value.trim() || !emailRgx.test(emailEl.value.trim())) {
        emailEl.classList.add('is-invalid');
        valido = false;
        return alert('Por favor ingrese un correo electrónico válido.');
    }

    email = $id('email_cli').value.trim();

    const btn = $id('btn_enviar');
    btn.disabled = true;
    btn.textContent = 'Enviando...';
    const datos = {
        opcion: 'enviarCorreo',
        email: email
    };

    try {
        const response = await fetch("crudCliente.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        });

        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const data = await response.json();
        mostrarMsg(data);

    } catch (error) {
        console.error('Falló la petición:', error);
        alert('Error de conexión, intente de nuevo.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Enviar';
    }
    
}

function mostrarMsg(data) {
    //console.log(data);
    if (data.success) {
        document.getElementById('btn_enviar').style.display = 'none';
        document.getElementById('email_cli').disabled = true;
        var msj='Correo enviado exitosamente a ' + data.email;
        msj += '\nEl código de validación es: ' + data.codigo;
        alert(msj);
        document.getElementById('validacion-section').classList.remove('d-none');
        if (data.cliente) {
            document.getElementById('tpid_cli').value = data.cliente.tpid_cli;
            document.getElementById('nrod_cli').value = data.cliente.nrod_cli;
            document.getElementById('exped_cli').value = data.cliente.exped_cli;
            document.getElementById('nomb_cli').value = data.cliente.nomb_cli;
            document.getElementById('apel_cli').value = data.cliente.apel_cli;
            document.getElementById('sexo_cli').value = data.cliente.sexo_cli;
            document.getElementById('fnac_cli').value = data.cliente.fnac_cli;
            document.getElementById('tele_cli').value = data.cliente.tele_cli;
            document.getElementById('dire_cli').value = data.cliente.dire_cli;
            document.getElementById('id_barrio').value = data.cliente.id_barrio;

            nuevo=0;
        }
    } else {
        alert('Error al enviar correo: ' + data.message);
    }
}

async function validarCodigo() {
    email = $id('email_cli').value.trim();
    codigo = $id('code_val').value.trim();

    const btn = $id('btn_validar');
    btn.disabled = true;
    btn.textContent = 'Validando...';
    const datos = {
        opcion: 'validarCodigo',
        email: email,
        codigo: codigo
    };

    try {
        const response = await fetch("crudCliente.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        });

        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const data = await response.json();
        mostrarMsgValidacion(data);

    } catch (error) {
        console.error('Falló la petición:', error);
        alert('Error de conexión, intente de nuevo.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Enviar';
    }
}

function mostrarMsgValidacion(data) {
    if (data.success) {
        //alert('Código validado correctamente. Puede continuar con el registro.');
        document.getElementById('btn_validar').style.display = 'none';
        document.getElementById('code_val').disabled = true;
        mostrarCampos();
    } else {
        alert('Código inválido: ' + data.message);
    }
}

function validarFormulario(){
    var form   = $id('frmCliente');
    var valido = true;

    /* Limpiar errores previos */
    form.querySelectorAll('.is-invalid').forEach(function(el) {
        el.classList.remove('is-invalid');
    });

    /* Helper: marcar campo requerido vacio */
    function req(fieldId) {
        var el = $id(fieldId);
        if (!el.value.trim()) {
            el.classList.add('is-invalid');
            valido = false;
        }
    }

    /* Email con formato */
    var emailEl  = $id('email_cli');
    var emailRgx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailEl.value.trim() || !emailRgx.test(emailEl.value.trim())) {
        emailEl.classList.add('is-invalid');
        valido = false;
    }

    /* Campos obligatorios */
    ['tpid_cli', 'nrod_cli', 'nomb_cli', 'apel_cli',
     'sexo_cli', 'fnac_cli', 'id_barrio'].forEach(req);

    /* Fecha de nacimiento: no puede ser hoy ni futura */
    var fnac = $id('fnac_cli');
    if (fnac.value) {
        var hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        if (new Date(fnac.value) >= hoy) {
            fnac.classList.add('is-invalid');
            valido = false;
        }
    }

    if(valido){
        guardarRegistro();
    }
}
async function guardarRegistro() {
    email = $id('email_cli').value.trim();
    codigo = $id('code_val').value.trim();
    tpid_cli = $id('tpid_cli').value;
    nrod_cli = $id('nrod_cli').value.trim();
    exped_cli = $id('exped_cli').value.trim();
    nomb_cli = $id('nomb_cli').value.trim();
    apel_cli = $id('apel_cli').value.trim();
    sexo_cli = $id('sexo_cli').value;
    fnac_cli = $id('fnac_cli').value;
    tele_cli = $id('tele_cli').value.trim();
    dire_cli = $id('dire_cli').value.trim();
    id_barrio = $id('id_barrio').value;

    opcion = (nuevo) ? 'insertar' : 'actualizar';

    const btn = $id('btnGuardar');
    btn.disabled = true;
    btn.textContent = 'Guardando...';
    const datos = {
        opcion: opcion,
        email: email,
        codigo: codigo,
        tpid_cli: tpid_cli,
        nrod_cli: nrod_cli,
        exped_cli: exped_cli,
        nomb_cli: nomb_cli,
        apel_cli: apel_cli,
        sexo_cli: sexo_cli,
        fnac_cli: fnac_cli,
        tele_cli: tele_cli,
        dire_cli: dire_cli,
        id_barrio: id_barrio
    };

    try {
        const response = await fetch("crudCliente.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datos)
        });

        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        const data = await response.json();
        mostrarMsgGuardado(data);

    } catch (error) {
        console.error('Falló la petición:', error);
        alert('Error de conexión, intente de nuevo.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Guardar cliente';
    }
}

function mostrarMsgGuardado(data) {
    if (data.success) {
        alert('Cliente registrado correctamente.');
        document.getElementById('btnGuardar').style.display = 'none';
        limpiarFormulario();
        ocultarCampos();
        document.getElementById('btn_enviar').style.display = 'inline-block';
        document.getElementById('email_cli').disabled = false;
        document.getElementById('code_val').disabled = false;
        document.getElementById('validacion-section').classList.add('d-none');
        document.getElementById('btn_validar').style.display = 'inline-block';
    } else {
        alert('Registro sin guardar: ' + data.message);
    }
}

function limpiarFormulario() {
    var form = $id('frmCliente');
    form.reset();
    form.querySelectorAll('.is-invalid').forEach(function(el) {
        el.classList.remove('is-invalid');
    });
}

/* ==========================================================
 Contadores de caracteres
========================================================== */
var charFields = [
  { input: 'email_cli',  counter: 'cnt-email', max: 60 },
  { input: 'nrod_cli',   counter: 'cnt-nrod',  max: 20 },
  { input: 'exped_cli',  counter: 'cnt-exped', max: 40 },
  { input: 'nomb_cli',   counter: 'cnt-nomb',  max: 25 },
  { input: 'apel_cli',    counter: 'cnt-ape',   max: 25 },
  { input: 'tele_cli',   counter: 'cnt-tele',  max: 22 },
  { input: 'dire_cli',   counter: 'cnt-dire',  max: 50 },
];

charFields.forEach(function(field) {
  var el  = $id(field.input);
  var cnt = $id(field.counter);
  el.addEventListener('input', function() {
      var len = el.value.length;
      cnt.textContent = len + ' / ' + field.max;
      if (len >= field.max * 0.9) {
          cnt.classList.add('warn');
      } else {
          cnt.classList.remove('warn');
      }
  });
});

/* ==========================================================
 Toast / notificaciones
========================================================== */
/*function showToast(msg, type) {
  type = type || 'success';
  var area = $id('toast-area');
  var icon = (type === 'success')
      ? 'bi-check-circle-fill'
      : 'bi-exclamation-triangle-fill';
  var div = document.createElement('div');
  div.className = 'toast-msg toast-' + type;
  div.innerHTML = '<i class="bi ' + icon + ' toast-icon"></i><span>' + msg + '</span>';
  area.appendChild(div);
  setTimeout(function() { div.remove(); }, 4500);
}*/

/* ==========================================================
 Overlay de carga
========================================================== */
/*var overlay = $id('loading-overlay');
function setLoading(on) {
  if (on) {
      overlay.classList.add('active');
  } else {
      overlay.classList.remove('active');
  }
}*/

/* ==========================================================
 Limpiar formulario
========================================================== */
$id('btnLimpiar').addEventListener('click', function() {
  var form = $id('frmCliente');
  form.reset();
  form.querySelectorAll('.is-invalid').forEach(function(el) {
      el.classList.remove('is-invalid');
  });
  charFields.forEach(function(field) {
      $id(field.counter).textContent = '0 / ' + field.max;
      $id(field.counter).classList.remove('warn');
  });
});



/* ==========================================================
 Inicializacion al cargar la pagina
========================================================== */
document.addEventListener('DOMContentLoaded', function() {
    cargarTpDocumento();
    cargarBarrios();
    ocultarCampos();
});

function ocultarCampos() {
    document.getElementById('tpid_cli').style.display = 'none';
    document.getElementById('nrod_cli').style.display = 'none';
    document.getElementById('exped_cli').style.display = 'none';
    document.getElementById('nomb_cli').style.display = 'none';
    document.getElementById('apel_cli').style.display = 'none';
    document.getElementById('sexo_cli').style.display = 'none';
    document.getElementById('fnac_cli').style.display = 'none';
    document.getElementById('tele_cli').style.display = 'none';
    document.getElementById('dire_cli').style.display = 'none';
    document.getElementById('id_barrio').style.display = 'none';
    document.getElementById('btnGuardar').style.display = 'none';
    document.getElementById('btnLimpiar').style.display = 'none';
}

function mostrarCampos(){
    document.getElementById('tpid_cli').style.display = 'block';
    document.getElementById('nrod_cli').style.display = 'block';
    document.getElementById('exped_cli').style.display = 'block';
    document.getElementById('nomb_cli').style.display = 'block';
    document.getElementById('apel_cli').style.display = 'block';
    document.getElementById('sexo_cli').style.display = 'block';
    document.getElementById('fnac_cli').style.display = 'block';
    document.getElementById('tele_cli').style.display = 'block';
    document.getElementById('dire_cli').style.display = 'block';
    document.getElementById('id_barrio').style.display = 'block';
    document.getElementById('btnGuardar').style.display = 'inline-block';
    document.getElementById('btnLimpiar').style.display = 'inline-block';
}

function cerrarFormulario() {
    document.querySelector(".form-card").style.display = "none";
}
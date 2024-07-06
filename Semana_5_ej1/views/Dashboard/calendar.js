function init() {
  $("#Citas_form").on("submit", function (e) {
    guardaryeditar(e);
  });
  $("#Pacientes_form").on("submit", function (e) {
    guardaryeditarPaceinteCita(e);
  });
}

var guardaryeditar = (e) => {
  e.preventDefault();
  var formData = new FormData($("#Citas_form")[0]);
  var idCitas = document.getElementById("idCitas").value;
  var accion = "";
  formData.append("Estado", 1);
  if (idCitas === undefined || idCitas === "") {
    accion = "../../controllers/Citas.controllers.php?op=insertar";
  } else {
    accion = "../../controllers/Citas.controllers.php?op=actualizar";
  }
  $.ajax({
    url: accion,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    cache: false,
    success: (res) => {
      res = JSON.parse(res);
      if (res == "ok") {
        Swal.fire("Citas", "Se guardó con exito", "success");
        cargaCalendario();
        limpiacajas();
      } else {
        Swal.fire("Citas", "Ocurrió un error al guardar", "error");
      }
    },
  });
};

$(document).ready(function () {
  //cargaCalendario();

  $(".js-example-basic-single").select2({
    theme: "default",
    width: "resolve",
    minimumResultsForSearch: 5,
    dropdownParent: $("#modalCitasNuevo"), // Asegúrate de que este ID coincida con el ID de tu modal
  });
  $("#cedula").select2({
    theme: "classic",
    dropdownParent: $("#modalPacientes"),
  });
  flatpickr("#fecha", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    minuteIncrement: 15,
  });
});

function cargaCalendario() {
  var calendarEl = document.getElementById("calendar");
  var calendar = new FullCalendar.Calendar(calendarEl, {
    // initialView: 'dayGridMonth',
    locale: "es",
    eventClick: (info) => {
      datostodos(info.event.id);
      valcedula(info.event.id);
    },
    //events: '../../controllers/Citas.controllers.php?op=todosCalendario',
    events: function (fetchInfo, successCallback, failureCallback) {
      fetch("../../controllers/Citas.controllers.php?op=todosCalendario")
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then((events) => {
          successCallback(events);
        })
        .catch((error) => {
          failureCallback(error);
          console.log("Error while fetching events:", error);
        });
    },
    eventContent: function (info) {
      var color = "black";

      if (info.event.title.includes("Ambato")) {
        color = "#286EE2";
      } else if (info.event.title.includes("Puyo")) {
        color = "#199535";
      }
      var start = moment(info.event.start).format("HH:mm");
      return {
        html:
          "<div class='fc-content' style='color: " +
          color +
          "; font-size: 12px; line-height: 1; padding: 2px; max-width: 200px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;'><h6 style='color: " +
          color +
          "'>" +
          info.event.title +
          "</h6><p><strong>Inicio:</strong> " +
          start +
          "</p><p><strong>Paciente: </strong> <br/>" +
          info.event.extendedProps.description +
          "</p><p><strong>Especilidad:</strong><br/> " +
          info.event.extendedProps.especialidad +
          "</p></div>",
      };
    },
    slotLabelFormat: [
      {
        hour: "numeric",
        minute: "2-digit",
        hour12: true,
      },
    ],
    eventDidMount: function (info) {
      var start = moment(info.event.start).format("HH:mm");
      var color = "black";
      if (info.event.title.includes("Ambato")) {
        color = "#286EE2";
      } else if (info.event.title.includes("Puyo")) {
        color = "#199535";
      }
      if (
        typeof info.event.extendedProps.description != "undefined" &&
        info.event.extendedProps.description != ""
      ) {
        $(info.el).popover({
          title:
            "<div style='color: " +
            color +
            "><small class='d-block'>" +
            info.event.title +
            "</small>",
          content:
            "<strong>Paciente:</strong><br>" +
            info.event.extendedProps.description +
            "<br> " +
            "<strong>Especilidad:</strong><br/> " +
            info.event.extendedProps.especialidad +
            "<p><strong>Inicio:</strong> " +
            start +
            "</p></div>",
          html: true,
          placement: "top",
          container: "body",
          delay: {
            show: 50,
            hide: 50,
          },
          trigger: "hover",
          template:
            '<div class="popover fc-med-popover ' +
            info.event.classNames[0] +
            '" role="tooltip" style="color: ' +
            color +
            ';"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
        });
      }
    },
  });
  calendar.render();
}
var limpia = () => {
  $("#tablacita").html("");
};
var datostodos = (cita) => {
  var html = "";
  $.post(
    "../../controllers/Citas.controllers.php?op=uno",
    {
      idCitas: cita,
    },
    (cita) => {
      cita = JSON.parse(cita);
      var objFecha = new Date(cita.Fecha);
      html +=
        "<tr><td><b>Paciente:</b></td><td>" +
        cita.Nombres +
        " " +
        cita.Apellidos +
        "</td></tr>";
      html +=
        "<tr><td><b>Fecha:</b></td><td>" +
        objFecha.toLocaleDateString() +
        "</td></tr>";
      html +=
        "<tr><td><b>Hora:</b></td><td>" +
        objFecha.toLocaleTimeString() +
        "</td></tr>";
      html +=
        "<tr><td><b>Especialidad:</b></td><td>" + cita.Detalle + "</td></tr>";
      html += "<tr><td><b>Sucursal:</b></td><td>" + cita.Nombre + "</td></tr>";
      html += "<tr><td><b>Motivo:</b></td><td>" + cita.Motivo + "</td></tr>";
      if (cita.Estado == 2) {
        html += `<tr><td><b>Valores a Pagar:</b></td><td><a href='../pagos/factura_venta.php?op=${cita.idCitas}' class='btn btn-success'>Valores a Pagar</button></td></tr>`;
      }
      if (cita.Estado == 3) {
        html += `<tr><td><b>Pagos Realzados:</b></td><td><a href='../pagos/facturas_ventas.php?op=${cita.idCitas}' class='btn btn-success'>Valores Cancelados</button></td></tr>`;
      }
      $("#tablacita").append(html);
      $("#idCitas").val(cita.idCitas);
    }
  );
};
var valcedula = (cita) => {
  var html = "";
  $.post(
    "../../controllers/Citas.controllers.php?op=Telefonos",
    {
      idCitas: cita,
    },
    (cita) => {
      cita = JSON.parse(cita);
      if (cita) {
        $.each(cita, (index, val) => {
          html +=
            "<tr><td><b>Telefono:</b></td><td>" + val.Detalle + "</td></tr>";
        });
      } else {
        html +=
          "<tr><td><b>Telefono:</b></td><td>No existen telefonos registrados</td></tr>";
      }
      $("#tablacita").append(html);
    }
  );
  $("#modalCitas").modal("show");
};
var EvolucionCita = () => {
  var id = document.getElementById("idCitas").value;
  window.location.href =
    "../Cita_Signos_Vitales/Cita_Signos_Vitales.php?codigocita=" + id;
};
async function EditarCita() {
  nuevoSucursal();
  nuevoSeguro();
  await nuevoPacientes();
  await nuevoEspecialidades();
  jQuery("#modalCitas").modal("hide");
  var idCitas = document.getElementById("idCitas").value;
  jQuery.post(
    "../../controllers/Citas.controllers.php?op=uno",
    {
      idCitas: idCitas,
    },
    async (unCitas) => {
      unCitas = JSON.parse(unCitas);

      document.getElementById("idCitas").value = unCitas.idCitas;
      document.getElementById("Fecha").value = unCitas.Fecha;
      document.getElementById("combo_idPacientes").value = unCitas.IdPacientes;
      document.getElementById("combo_idEspecialidades").value =
        unCitas.idEspecialidades;
      document.getElementById("Motivo").value = unCitas.Motivo;
      document.getElementById("idSeguro").value = unCitas.idSeguro;
      document.getElementById("combo_SucursalId").value = unCitas.SucursalId;
      document.getElementById("titleLabelCitas").innerHTML = "Editar Cita";

      // Actualiza Select2 para cada elemento <select>
      $("#combo_idPacientes").trigger("change");
      $("#combo_idEspecialidades").trigger("change");
      $("#idSeguro").trigger("change");
      $("#combo_SucursalId").trigger("change");

      // Muestra el modal
      jQuery("#modalCitasNuevo").modal("show");
    }
  );
  limpia();
}
function EliminarCita() {
  var idCitas = document.getElementById("idCitas").value;
  Swal.fire({
    title: "Eliminar!",
    text: "Desea Eliminar el Registro?",
    icon: "error",
    confirmButtonText: "Si",
    showCancelButton: true,
    cancelButtonText: "No",
  }).then((result) => {
    if (result.value) {
      $.post(
        "../../controllers/Citas.controllers.php?op=eliminar",
        {
          idCitas: idCitas,
        },
        function (data) {
          data = JSON.parse(data);
          if (data == true) {
            Swal.fire("Citas", "Se Elimino Correctamente", "success");
            cargaCalendario();
            limpia();
          } else {
            Swal.fire("Citas", "Error al Elminar el registro", "error");
          }
        }
      );
    } else {
      limpia();
    }
  });
}
var nuevoPacientes = () => {
  return new Promise((resolve, reject) => {
    $.post(
      "../../controllers/Pacientes.controllers.php?op=buscador",
      {},
      (datos) => {
        var html = "";
        datos = JSON.parse(datos);
        $.each(datos, (index, val) => {
          html +=
            "<option value=" + val.idPacientes + ">" + val.name + "</option>";
        });
        $("#combo_idPacientes").html(html);
        resolve(); // Resuelve la promesa cuando la petición se complete y se haya actualizado el contenido del elemento
      }
    ).fail((error) => {
      reject(error); // Rechaza la promesa si la petición falla
    });
  });
};
var llenaNombres = (combo) => {
  var id = combo.options[combo.selectedIndex].value;
  if (id === undefined || id === "") {
    document.getElementById("Nombres").value = "";
    document.getElementById("Apellidos").value = "";
    document.getElementById("idPacientes").value = "";
  } else {
    jQuery.post(
      "../../controllers/Pacientes.controllers.php?op=uno",
      {
        idPacientes: id,
      },
      (unPacientes) => {
        unPacientes = JSON.parse(unPacientes);
        document.getElementById("Nombres").value = unPacientes.Nombres;
        document.getElementById("Apellidos").value = unPacientes.Apellidos;
        document.getElementById("combo_idPacientes").value =
          unPacientes.idPacientes;
      }
    );
  }
};
var nuevoPacientesCombo = () => {
  return new Promise((resolve, reject) => {
    $.post(
      "../../controllers/Pacientes.controllers.php?op=todos",
      {},
      (datos) => {
        var html = '<option value="0">Seleccione una opcion</option>';
        datos = JSON.parse(datos);
        $.each(datos, (index, val) => {
          html +=
            "<option value=" +
            val.idPacientes +
            ">" +
            val.Cedula +
            " - " +
            val.Nombres +
            " " +
            val.Apellidos +
            "</option>";
        });
        $("#cedula").html(html);
        resolve(); // Resuelve la promesa cuando la petición se complete y se haya actualizado el contenido del elemento
      }
    ).fail((error) => {
      reject(error); // Rechaza la promesa si la petición falla
    });
  });
};

const nuevoSeguro = () => {
  return new Promise((resolve, reject) => {
    $.post(
      "../../controllers/SeguroMedico.controllers.php?op=todos",
      {},
      (datos) => {
        try {
          datos = JSON.parse(datos);
          const html = datos
            .map(
              (val) =>
                `<option value=${val.idSeguro}>${val.Nombre_Empresa}</option>`
            )
            .join("");

          $("#idSeguro").html(html);
          $("#idSeguroPaciente").html(html);

          resolve(); // Resuelve la promesa cuando la petición se complete y se haya actualizado el contenido del elemento
        } catch (error) {
          reject(error); // Rechaza la promesa si hay algún error en la respuesta
        }
      }
    ).fail((error) => {
      reject(error); // Rechaza la promesa si la petición falla
    });
  });
};
const nuevoSucursal = () => {
  $.post(
    "../../controllers/Sucursales.controllers.php?op=todos",
    {},
    (datos) => {
      try {
        datos = JSON.parse(datos);
        const html = datos
          .map(
            (val) => `<option value=${val.SucursalId}>${val.Nombre}</option>`
          )
          .join("");
        $("#combo_SucursalId").html(html);
      } catch (error) {
        console.error("Error al procesar los datos de sucursales:", error);
      }
    }
  ).fail((error) => {
    console.error("Error al cargar los datos de sucursales:", error);
  });
};

var nuevoEspecialidades = () => {
  return new Promise((resolve, reject) => {
    $.post(
      "../../controllers/Especialidades.controllers.php?op=especialidadMedico",
      {},
      (datos) => {
        var html = "";
        datos = JSON.parse(datos);
        $.each(datos, (index, val) => {
          html +=
            "<option value=" +
            val.idEspecialidades +
            ">" +
            val.Detalle +
            "</option>";
        });
        $("#combo_idEspecialidades").html(html);
        resolve(); // Resuelve la promesa cuando la petición se complete y se haya actualizado el contenido del elemento
      }
    ).fail((error) => {
      reject(error); // Rechaza la promesa si la petición falla
    });
  });
};
function guardaryeditarPaceinteCita(e) {
  e.preventDefault();

  return new Promise((resolve, reject) => {
    const formData = new FormData($("#Pacientes_form")[0]);
    const idPacientes = document.getElementById("combo_idPacientesCita").value;
    formData.append(
      "Usuarios_idUsuarios",
      document.getElementById("idUsuarios").value
    );
    formData.append("Estado", 1);
    let accion = "";

    if (idPacientes === "0" || idPacientes === "") {
      accion = "../../controllers/Pacientes.controllers.php?op=insertarCita";

      sendRequest(accion, formData)
        .then((res) => {
          const resParsed = JSON.parse(res);

          if (parseInt(resParsed) !== 0) {
            formData.append("combo_idPacientes", resParsed);
            return sendRequest(
              "../../controllers/Citas.controllers.php?op=insertar",
              formData
            );
          } else {
            throw new Error("Error al guardar");
          }
        })
        .then(() => {
          Swal.fire("Pacientes", "Se guardó con éxito", "success");
          cargaCalendario();
          limpiacajas();
          resolve();
        })
        .catch((error) => {
          Swal.fire("Pacientes", "Ocurrió un error al guardar", "error");
          console.error(error);
          reject(error);
        });
    } else {
      // Agrega el código correspondiente para el caso en que idPacientes no sea '0' o vacío
      // ...
    }
  });
}

function sendRequest(url, formData) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      success: (res) => {
        resolve(res);
      },
      error: (xhr, status, error) => {
        reject(new Error(error));
      },
    });
  });
}
//aqui---------
const ValidaCitaRepetida = () => {
  return new Promise((resolve, reject) => {
    const formData = new FormData($("#Pacientes_form")[0]);

    $.post(
      "../../controllers/Citas.controllers.php?op=citarepetida",
      {},
      (datos) => {
        try {
          datos = JSON.parse(datos);
          const html = datos
            .map(
              (val) =>
                `<option value=${val.idSeguro}>${val.Nombre_Empresa}</option>`
            )
            .join("");

          $("#idSeguro").html(html);
          $("#idSeguroPaciente").html(html);

          resolve(); // Resuelve la promesa cuando la petición se complete y se haya actualizado el contenido del elemento
        } catch (error) {
          reject(error); // Rechaza la promesa si hay algún error en la respuesta
        }
      }
    ).fail((error) => {
      reject(error); // Rechaza la promesa si la petición falla
    });
  });
};

function validarFechaHora() {
  // Obtén la fecha y hora actual
  const ahora = new Date();

  // Obtiene la fecha y hora ingresadas y el elemento para mostrar mensajes de error
  const inputFechaHora = document.getElementById("Fecha");
  const fechaHoraIngresada = new Date(inputFechaHora.value);
  const mensajeError = document.getElementById("mensajeError");

  // Compara la fecha y hora ingresadas con la fecha y hora actual
  if (fechaHoraIngresada < ahora) {
    mensajeError.textContent = "La fecha y hora ingresadas están en el pasado.";
    return false;
  } else {
    mensajeError.textContent = "";
    return true;
  }
}
var nuevoSucursalPaciente = () => {
  $.post(
    "../../controllers/Sucursales.controllers.php?op=todos",
    {},
    (datos) => {
      var html = "";
      datos = JSON.parse(datos);
      $.each(datos, (index, val) => {
        html +=
          "<option value=" + val.SucursalId + ">" + val.Nombre + "</option>";
      });
      $("#SucursalId").html(html);
    }
  );
};
var nuevoEspecialidadesPaciente = () => {
  $.post(
    "../../controllers/Especialidades.controllers.php?op=todos",
    {},
    (datos) => {
      var html = "";
      datos = JSON.parse(datos);
      $.each(datos, (index, val) => {
        html +=
          "<option value=" +
          val.idEspecialidades +
          ">" +
          val.Detalle +
          "</option>";
      });
      $("#idEspecialidades").html(html);
    }
  );
};
var nuevo = () => {
  nuevoSucursal();
  nuevoPacientes();
  nuevoEspecialidades();
  nuevoSeguro();

  document.getElementById("titleLabelCitas").innerHTML = "Nuevo Citas";
};
var limpiacajas = () => {
  document.getElementById("idCitas").value = "";
  document.getElementById("Fecha").value = "";

  document.getElementById("combo_idPacientes").value = "";
  document.getElementById("combo_idEspecialidades").value = "";
  document.getElementById("Motivo").value = "";
  document.getElementById("idCitas").value = "";
  jQuery("#modalCitasNuevo").modal("hide");
  jQuery("#modalPacientes").modal("hide");
  $("#modalCitas").modal("hide");
  document.getElementById("titleLabelCitas").innerHTML = "Nuevo Citas";
};
init();

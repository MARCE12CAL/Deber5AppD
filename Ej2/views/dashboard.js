function cargarProductos() {
  $.ajax({
      type: "GET",
      url: "productos.php",
      success: function(data) {
          $("#contenido").html(data);
      }
  });
}

function init() {
  $("#frm_usuarios").on("submit", function (e) {
      guardaryeditar(e);
  });
  cargaTabla();
}

$().ready(() => {
  cargaTabla();
});

var cargaTabla = () => {
  var html = "";

  $.get("../controllers/usuarios.controllers.php", (listausuarios) => {
      console.log(listausuarios);
      $.each(listausuarios, (indice, unusuario) => {
          html += `
              <tr>
                  <td>${indice + 1}</td>
                  <td>${unusuario.Nombre}</td>
                  <td>${unusuario.correo}</td>
                  <td>${
                      unusuario.estado == 1
                          ? "<p class='bg-success text-white text-center'>Activo</p>"
                          : "<p class='bg-danger text-white text-center'>Bloqueado</p>"
                  }</td>
                  <td>${unusuario.rol}</td>
                  <td>
                      <button class="btn btn-primary" onclick="uno(${unusuario.UsuarioId})">Editar</button>
                      <button class="btn btn-danger" onclick="eliminar(${unusuario.UsuarioId})">Eliminar</button>
                  </td>
              </tr>
          `;
      });
      $("#cuerpousuarios").html(html);
  });
};

var cargarRoles = () => {
  $.get("../controllers/roles.controllers.php", (roles) => {
      var selectRoles = $("#RolesId");
      selectRoles.empty();
      selectRoles.append("<option  value=''>Seleccione un rol</option>");
      $.each(roles, (index, rol) => {
          selectRoles.append(
              `<option value='${rol.RolesId}'>${rol.Detalle}</option>`
          );
      });
  });
};

var guardaryeditar = (e) => {
  e.preventDefault();
  var frm_usuarios = new FormData($("#frm_usuarios")[0]);
  var UsuarioId = $("#UsuarioId").val();
  var ruta = UsuarioId == 0 ? "../controllers/usuarios.controllers.php" : "../controllers/usuarios.controllers.php?op=actualizar";

  $.ajax({
      url: ruta,
      type: "POST",
      data: frm_usuarios,
      contentType: false,
      processData: false,
      success: function (datos) {
          console.log(datos);
          $("#usuariosModal").modal("hide");
          cargaTabla();
      },
  });
};

var uno = async (UsuarioId) => {
  await cargarRoles();
  $.get(
      `../controllers/usuarios.controllers.php?UsuarioId=${UsuarioId}`,
      (usuario) => {
          console.log(usuario);
          $("#Nombre").val(usuario.Nombre);
          $("#correo").val(usuario.correo);
          $("#password").val(usuario.password);
          $("#UsuarioId").val(usuario.UsuarioId);
          $("#RolesId").val(usuario.RolesId);
          $("#estado").prop("checked", usuario.estado == 1);
      }
  );

  $("#modalUsuario").modal("show");
};

var eliminar = (UsuarioId) => {
  Swal.fire({
      title: "Usuarios",
      text: "¿Está seguro que desea eliminar el usuario?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Eliminar",
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: `../controllers/usuarios.controllers.php?UsuarioId=${UsuarioId}`,
              type: "DELETE",
              success: function (resultado) {
                  if (resultado) {
                      Swal.fire({
                          title: "Usuarios",
                          text: "Se eliminó con éxito",
                          icon: "success",
                      }).then(() => cargaTabla());
                  } else {
                      Swal.fire({
                          title: "Usuarios",
                          text: "No se pudo eliminar",
                          icon: "error",
                      });
                  }
              },
          });
      }
  });
};

init();

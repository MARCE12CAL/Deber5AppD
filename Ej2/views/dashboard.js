//cuerpousuarios
function init() {
  $("#frm_usuarios").on("submit", function (e) {
    guardaryeditar(e);
  });
}

$().ready(() => {
  cargaTabla();
});

var cargaTabla = () => {
  //1 crear una variable html para cargar en la table tbody  id="cuerpousuarios"
  //voy a llamar al controlador mediante un metodo get  sin parametros
  // voy a usar un metodo de repeticion each para recorrer el json de objetos
  // y por cada objeto debo construir una fila de la table
  // y por ultimo agrego la fila al tbody

  var html = "";

  //$.get(url, parametros, funcion);
  //url = ../controllers/usuarios.controllers.php
  $.get("../controllers/usuarios.controllers.php", (listausuarios) => {
    console.log(listausuarios);
    //$.each(listadedatos, funcion)
    //$.each(variosusuarios,( indice, valor )=>{})
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
<button class="btn btn-primary" onclick="uno(${
        unusuario.UsuarioId
      })">Editar</button>
<button class="btn btn-danger" onclick="eliminar(${
        unusuario.UsuarioId
      })">Eliminar</button>
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
    //carga una opcion en el select
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
  for (var pair of frm_usuarios.entries()) {
    console.log(pair[0] + ", " + pair[1]);
  }
  var UsuarioId = $("#UsuarioId").val();
  var ruta = "";
  if (UsuarioId == 0) {
    //insertar
    ruta = "../controllers/usuarios.controllers.php";
  } else {
    //actualziar
    ruta = "../controllers/usuarios.controllers.php?op=actualizar";
  }

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
//var frm_usuarios = $("#frm_usuarios").serialize();
/*$.post(
    "../controllers/usuarios.controllers.php",
    frm_usuarios,
    (resultado) => {
      console.log(resultado);
    }
  );*/

var uno = async (UsuarioId) => {
  await cargarRoles();
  $.get(
    "../controllers/usuarios.controllers.php?UsuarioId=" + UsuarioId,
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
    text: "Esta seguro que desea eliminar el usuario???",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Eliminar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.delete(
        "../controllers/usuarios.controllers.php?UsuarioId=",
        { UsuarioId: UsuarioId },
        (resultado) => {
          if (resultado) {
            Swal.fire({
              title: "Usuarios",
              text: "Se eliminar con exito",
              icon: "success",
            });
          } else {
            Swal.fire({
              title: "Usuairos!",
              text: "No se pudo eliminar",
              icon: "danger",
            });
          }
        }
      );
    }
  });
};

init();

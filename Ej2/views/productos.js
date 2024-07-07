// productos.js

function init() {
  $("#frm_productos").on("submit", function (e) {
    guardarEditar(e);
  });
}

$().ready(() => {
  cargaTabla();
});

var cargaTabla = () => {
  var html = "";

  $.get("../controllers/productos.controller.php", (listaproductos) => {
    console.log(listaproductos);
    $.each(listaproductos, (indice, unproducto) => {
      html += `
            <tr>
                <td>${indice + 1}</td>
                <td>${unproducto.nombre}</td>
                <td>${unproducto.precio}</td>
                <td>${unproducto.stock}</td>
                <td>
                  <button class="btn btn-primary" onclick="uno(${unproducto.id})">Editar</button>
                  <button class="btn btn-danger" onclick="eliminar(${unproducto.id})">Eliminar</button>
                </td>
            </tr>
        `;
    });
    $("#cuerpoproductos").html(html);
  });
};

var guardarEditar = (e) => {
  e.preventDefault();
  var frm_productos = new FormData($("#frm_productos")[0]);
  var id = $("#id").val();
  var ruta = "";
  if (id == 0) {
    //insertar
    ruta = "../controllers/productos.controller.php";
  } else {
    //actualizar
    ruta = "../controllers/productos.controller.php?op=actualizar";
  }

  $.ajax({
    url: ruta,
    type: "POST",
    data: frm_productos,
    contentType: false,
    processData: false,
    success: function (datos) {
      console.log(datos);
      $("#productosModal").modal("hide");
      cargaTabla();
    },
  });
};

var uno = async (id) => {
  $.get(
    "../controllers/productos.controller.php?id=" + id,
    (producto) => {
      console.log(producto);
      $("#nombre").val(producto.nombre);
      $("#precio").val(producto.precio);
      $("#stock").val(producto.stock);
      $("#id").val(producto.id);
    }
  );

  $("#modalProducto").modal("show");
};

var eliminar = (id) => {
  Swal.fire({
    title: "Productos",
    text: "Esta seguro que desea eliminar el producto?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Eliminar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../controllers/productos.controller.php",
        type: "DELETE",
        data: { id: id },
        success: function (resultado) {
          if (resultado) {
            Swal.fire({
              title: "Productos",
              text: "Se elimino con exito",
              icon: "success",
            });
          } else {
            Swal.fire({
              title: "Productos!",
              text: "No se pudo eliminar",
              icon: "danger",
            });
          }
        },
      });
    }
  });
};

init();
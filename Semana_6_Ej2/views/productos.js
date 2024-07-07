// Inicializa la funcionalidad al cargar el documento
function init() {
  $("#frm_productos").on("submit", function(e) {
      guardarEditar(e);
  });
}

// Función para obtener los datos de un producto y mostrarlos en el modal para editar
function uno(id) {
  $.ajax({
      url: "../controllers/productos.controller.php",
      type: "GET",
      data: { accion: 'obtener', ProductoId: id },
      dataType: "json",
      success: function(producto) {
          if (producto) {
              $("#id").val(producto.id);
              $("#nombre").val(producto.nombre);
              $("#precio").val(producto.precio);
              $("#stock").val(producto.stock);
              $("#modalProducto").modal("show");
              $("#exampleModalLabel").text("Editar Producto");
          } else {
              Swal.fire('Error', 'No se encontró el producto para editar', 'error');
          }
      },
      error: function(xhr, status, error) {
          console.error("Error al cargar producto para editar:", error);
          Swal.fire('Error', 'No se pudo cargar el producto para editar', 'error');
      }
  });
}

// Función para guardar o actualizar un producto
function guardarEditar(e) {
  e.preventDefault();

  var formData = new FormData($("#frm_productos")[0]);
  var id = $("#id").val();
  var url = "../controllers/productos.controller.php";
  
  formData.append("accion", id ? "actualizar" : "insertar");
  if (id) {
      formData.append("ProductoId", id);
  }

  $.ajax({
      url: url,
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
          var res = typeof response === 'string' ? JSON.parse(response) : response;
          if (res.status === "success") {
              $("#modalProducto").modal("hide");
              cargaTabla();
              Swal.fire('Éxito', res.message, 'success');
          } else {
              Swal.fire('Error', res.message, 'error');
          }
      },
      error: function(xhr, status, error) {
          console.error("Error al guardar/editar producto:", error);
          Swal.fire('Error', 'No se pudo guardar el producto', 'error');
      }
  });
}

// Función para abrir el modal, ya sea para insertar o para editar
function abrirModal(modo, producto = null) {
  $("#frm_productos")[0].reset();
  if (modo === "insertar") {
      $("#id").val("");
      $("#exampleModalLabel").text("Nuevo Producto");
  } else if (modo === "editar" && producto) {
      $("#id").val(producto.id);
      $("#nombre").val(producto.nombre);
      $("#precio").val(producto.precio);
      $("#stock").val(producto.stock);
      $("#exampleModalLabel").text("Editar Producto");
  }
  $("#modalProducto").modal("show");
}

// Función para cargar la tabla de productos
function cargaTabla() {
  $.ajax({
      url: "../controllers/productos.controller.php",
      type: "GET",
      data: { accion: 'listar' },
      dataType: "json",
      success: function(listaproductos) {
          var html = "";
          $.each(listaproductos, function(indice, unproducto) {
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
      },
      error: function(xhr, status, error) {
          console.error("Error al cargar productos:", error);
          Swal.fire('Error', 'No se pudieron cargar los productos', 'error');
      }
  });
}

// Función para eliminar un producto
function eliminar(id) {
  Swal.fire({
      title: '¿Estás seguro?',
      text: "¡No podrás revertir esto!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminarlo'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: "../controllers/productos.controller.php",
              type: "POST",
              data: { accion: 'eliminar', ProductoId: id },
              success: function(response) {
                  var res = typeof response === 'string' ? JSON.parse(response) : response;
                  if (res.status === "success") {
                      Swal.fire('Eliminado!', 'El producto ha sido eliminado.', 'success');
                      cargaTabla();
                  } else {
                      Swal.fire('Error', res.message, 'error');
                  }
              },
              error: function(xhr, status, error) {
                  console.error("Error al eliminar producto:", error);
                  Swal.fire('Error', 'No se pudo eliminar el producto', 'error');
              }
          });
      }
  });
}

// Cargar los productos al iniciar la página
$(document).ready(function() {
  init();
  cargaTabla();
});
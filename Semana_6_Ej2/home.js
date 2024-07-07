var nummero1;
var nummero2;
var tipooperacion;
var llenarcaja = (numero) => {
  var valorcaja = document.getElementById("resultado").value;
  valorcaja += numero;
  document.getElementById("resultado").value = valorcaja;
};
var operacion = (tipo) => {
  tipooperacion = tipo;
  nummero1 = document.getElementById("resultado").value;
  document.getElementById("resultado").value = "";
};
var resultado = () => {
  var nummero2 = document.getElementById("resultado").value;
  var res = 0;
  switch (tipooperacion) {
    case 1:
      res = parseInt(nummero1) + parseInt(nummero2);
      break;
    case 2:
      res = parseInt(nummero1) - parseInt(nummero2);
      break;
    case 3:
      res = parseInt(nummero1) * parseInt(nummero2);
      break;
    case 4:
      res = parseInt(nummero1) / parseInt(nummero2);
      break;
  }
  document.getElementById("resultado").value = res;
};

$(document).ready(function () {

  var $fone = $("#telefone");

  $fone.mask('(00)0000-0000', {reverse: false});

});

$(document).ready(function () {

  var $celular = $("#celular");

  $celular.mask('(00)00000-0000', {reverse: false});

});

$(document).ready(function () {

  var $cpf = $("#cpf");

  $cpf.mask('000.000.000-00', {reverse: false});

});
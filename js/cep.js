
// Autocomplete com jquery-ui
$( function() {
    $("#cep").autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: "app/endereco.php",
          dataType: "json",
          data: {
            _action: 'get_endereco', // Método remoto
            term: request.term // Parâmetro enviado ao método
          },
          success: function( data ) {
            response( data );
          }
        });
      },
      minLength: 4,
      select: function( event, ui ) {
        // Alimenta os campos a partir do retorno do método remoto
        $("#logradouro").val(ui.item.logradouro);
        $("#cidade").val(ui.item.cidade);
        $("#bairro").val(ui.item.bairro);
        $("#uf").val(ui.item.uf);
      }
    });


  /*************************************
   * Adicionado em 10/05/2018
   *
   * Objetivo: 
   * Ao digitar o nome da cidade o sistem irá autocompletar o nome da cidade
   * oferecendo uma lista de cidades para ser selecionada, assim que selecionada 
   * os campos CEP, Cidade, Bairro e Logradouro serão retornados nos respectivos 
   * campos da página.
   *
   * Autocomplete com jquery-ui
   *************************************/
    $("#cidade").autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: "app/endereco.php",
          dataType: "json",
          data: {
            _action: 'get_endereco_cidade', // Método remoto
            term: request.term // Parâmetro enviado ao método
          },
          success: function( data ) {
            response( data );
          }
        });
      },
      minLength: 2,
      select: function( event, ui ) {
        // Alimenta os campos a partir do retorno do método remoto
        $("#cep").val(ui.item.cep);
        $("#logradouro").val(ui.item.logradouro);
        $("#bairro").val(ui.item.bairro);
        $("#uf").val(ui.item.uf);
      }
    });
});
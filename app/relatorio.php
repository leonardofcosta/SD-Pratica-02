<!doctype html>
<html>
<head>

  <meta charset="utf-8">

  <title>Relatório de pessoas cadastrados</title>

  <link rel="stylesheet" href="../css/bootstrap.min.css"/>
  <link rel="stylesheet" href="../css/bootstrap.min.css.map"/>

</head>
<body>

<div class="container">
  <h1 class="page-header">Relatório de Pessoas</h1>
 
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
      <thead class="thead-light">
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>CPF</th>
          <th>E-mail</th>
          <th>Cidade</th>
          <th>Bairro</th>
          <th>Logradouro</th>
        </tr>
      </thead>
      <tbody>
    	
        <?php
        // Efetua conecxão com base de dados
        $mysqli = new mysqli("127.0.0.1:3307", "root", "root3307", "trabalho_sd");
        
        $mysqli->set_charset("utf8");
        
        // Verifica conexão
        if ($mysqli->connect_errno) {
            printf("Connect failed: %s\n", $mysqli->connect_error);
            exit();
        }
        
        $sql = 
        "SELECT c.id, c.nome, c.cpf, c.email, 
                ci.descricao as cidade,
                b.descricao as bairro,
                e.logradouro
        
           FROM cliente c, 
                cidade ci , 
                endereco e, 
                bairro b
        
         WHERE c.id_cidade = ci.id
           AND e.id_bairro = b.id
           AND c.id_endereco = e.id
        ";
        
        // Prepara uma instrução SQL para execução
        if ($stmt = $mysqli->prepare($sql))
        {
            // Executa uma consulta preparada
            $stmt->execute();
            
            // Vincula variáveis a uma instrução preparada para armazenamento de resultados
            $stmt-> bind_result($id, $nome, $cpf, $email, $cidade, $bairro, $logradouro);
            
            // Buscar resultados de um comando preparado nas variáveis ligadas
            while ($stmt->fetch()) {
                
                echo "<tr>";
                echo "<th>$id</th>";
                echo "<td>$nome</td>";
                echo "<td>$cpf</td>";
                echo "<td>$email</td>";
                echo "<td>$cidade</td>";
                echo "<td>$bairro</td>";
                echo "<td>$logradouro</td>";
                echo "</tr>";      
                
            }
            
            // Fecha um comando preparado
            $stmt->close();
        }
        
        $mysqli->close();
        
        ?>
    
      </tbody>
    </table>
  </div>
</div>


<div class="container">
    <hr />
	<a href="/SD-Pratica-02" target="_self">Novo Cadastro</a>
</div>

</body>
</html>
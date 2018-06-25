<?php 

include('utils/funcUtils.php');

if (!validaCPF($_POST["cpf"])) {
	header("Location: erro.php");
}
else
{
	$data = (object) ($_POST);

	$mysqli = new mysqli("localhost", "root", "root", "trabalho_sd");

	$mysqli->begin_transaction();
	$mysqli->set_charset("utf8");

	/* check connection */
	if ($mysqli->connect_errno) {
	    printf("Connect failed: %s\n", $mysqli->connect_error);
	    exit();
	}

	/* Verifica se UF já existe na base */
	$sql = "select id from uf u where u.sigla = ?";

	if ($stmt = $mysqli->prepare($sql))
	{
		$stmt->bind_param("s", $data->uf);
		$stmt->execute();
		$stmt->bind_result($id_uf);
		$stmt->fetch();

		$stmt->close();
	}

	/* Se não existe UF faz o insert */
	if (!isset($id_uf))
	{
		$sql = "insert into uf (sigla, descricao) values ('".$data->uf."', '".nomeEstado($data->uf)."')";

		if ($result = $mysqli->query($sql))
		{
			$retInsertUF = $mysqli->insert_id;
		}
		else 
		{
			echo "ERRO 1: " . $mysqli->error;
			$mysqli->rollback();
			exit();
		}	
	}
	else
	{
		$retInsertUF = $id_uf;
	}

	/* Verifica se UF já existe na base */
	$sql = "select id from cidade c where c.cep = ?";

	if ($stmt = $mysqli->prepare($sql))
	{
		$stmt->bind_param("s", $data->cep);
		$stmt->execute();
		$stmt->bind_result($id);
		$stmt->fetch();

		$stmt->close();
	}

	if (!isset($id))
	{
		/* insert da Cidade */
		$sql = "insert into cidade (cep, id_uf, descricao) values ('".$data->cep."', $retInsertUF, '".$data->cidade."')";

		if ($result = $mysqli->query($sql))
		{
			$retInsertCidade = $mysqli->insert_id;
		}
		else 
		{
			echo "ERRO 2: " . $mysqli->error;
			$mysqli->rollback();
			exit();
		}
	} 
	else 
	{
		$retInsertCidade = $id;
	}
    
	/**
	 * 
	 * Verifica se já existe o bairro cadastrado em determinada cidade
	 * 
	 */
	$sql = "select id from bairro b where b.id_cidade = ? and b.descricao = ?";
	
	if ($stmt = $mysqli->prepare($sql))
	{
	    $stmt->bind_param("is", $retInsertCidade, $data->bairro);
	    $stmt->execute();
	    $stmt->bind_result($id_bairro);
	    $stmt->fetch();
	    
	    $stmt->close();
	}

	// se não existir o id do bairro faz o insert, caso contrário popula variavel $retInsertBairro com o id bairro encontrada.
	if (!isset($id_bairro)) {
    	/* Faz o insert do Bairro */
    	$sql = "insert into bairro (id_cidade, descricao) values ($retInsertCidade, '".$data->bairro."')";
    
    	if ($result = $mysqli->query($sql))
    	{
    		$retInsertBairro = $mysqli->insert_id;
    	}
    	else 
    	{
    		echo "ERRO 3: " . $mysqli->error;
    		$mysqli->rollback();
    		exit();
    	}
	}
	else 
	{
	    $retInsertBairro = $id_bairro;
	}
	
	// Verifica se endereço já existe
	$sql = "select id from endereco e where e.id_bairro = ? and e.cep = ? and e.logradouro = ?";
	
	if($stmt = $mysqli->prepare($sql)) {
	    $stmt->bind_param("iss", $retInsertBairro, $data->cep, $data->logradouro);
	    $stmt->execute();
	    $stmt->bind_result($id_endereco);
	    $stmt->fetch();
	    $stmt->close();
	}
	
	// Se o id do endereço não for encontrado, faz o inserto, caso contrario popula a variável $retInsertEndereco com id encontrado
	if (!isset($id_endereco)) {
    	/* Faz insert do Endereço */
    	$sql = "insert into endereco 
    			(id_bairro, 
    			 cep, 
    			 logradouro) 
    			values 
    			($retInsertBairro, 
    			 '".$data->cep."', 
    			 '".$data->logradouro."')";
    	
    	if ($result = $mysqli->query($sql))
    	{
    		$retInsertEndereco = $mysqli->insert_id;
    	}
    	else 
    	{
    		echo "ERRO 4: " . $mysqli->error;
    		$mysqli->rollback();
    		exit();
    	}
	} 
	else 
	{
	    $retInsertEndereco = $id_endereco;
	}
	
	/* Faz insert do Cliente */
	$sql = "insert into cliente 
			(cpf, 
			 nome, 
			 email, 
			 telefone, 
			 celular, 
			 id_cidade, 
			 id_endereco) 
			values 
			('".$data->cpf."', 
			 '".$data->nome."', 
			 '".$data->email."', 
			 '".$data->telefone."', 
			 '".$data->celular."', 
			 $retInsertCidade, 
			 $retInsertEndereco)";
	
	if ($result = $mysqli->query($sql))
	{
		$retInsertCliente = $mysqli->insert_id;
	}
	else 
	{
		echo "cpf " .$data->cpf. " já cadastrado";
		echo "<br /><br />";
		echo "ERRO 5: " . $mysqli->error;
		$mysqli->rollback();
		exit();
	}
	
	$mysqli->commit();
	$mysqli->close();

	echo "inserção ok";

}


?>
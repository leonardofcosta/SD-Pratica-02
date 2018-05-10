<?php

require_once('baseClass.php');

class endereco extends baseClass {

    // Lista de métodos disponíveis nesta classe
    protected $actions = array(
        'get_endereco',
        'get_endereco_cidade'
    );

    // Recebe o CEP como parâmetro
    // Retorna dados de endereço
    public function get_endereco(){


        $data = (object) $_GET;

        $term = mysqli_real_escape_string($this->conn,$data->term);

        $sql =
        "
        SELECT
        tb3.endereco_codigo as id,
        concat('Cep: ', ifnull(tb3.endereco_cep,tb1.cidade_cep) ,' | Cidade: ', tb1.cidade_descricao, ' | Bairro: ', ifnull(tb2.bairro_descricao,'Centro'), ' | Logradouro: ', ifnull(tb3.endereco_logradouro,'')) as label,
        tb1.cidade_descricao as cidade,
        ifnull(tb2.bairro_descricao,'Centro') as bairro,
        ifnull(tb3.endereco_logradouro,'') as logradouro,
        ifnull(tb3.endereco_cep,tb1.cidade_cep) as value
        from
        cep.cidade tb1
        left join
        cep.bairro tb2
        on tb1.cidade_codigo = tb2.cidade_codigo
        left JOIN
        cep.endereco tb3
        on tb2.bairro_codigo = tb3.bairro_codigo
        where (ifnull(tb3.endereco_cep,tb1.cidade_cep) like '%$term%') or (tb1.cidade_descricao like '%$term%')
        order by
        tb1.cidade_descricao,
        tb2.bairro_descricao,
        tb3.endereco_logradouro
        limit 10
        ";

        $result = $this->_select_fetch_all($sql);

        // Retorna os dados em formato JSON
        echo json_encode($result);

    }

    /*******************************************
     * Adicionado em 10/05/2018
     *
     * Objetivo:
     * Recebe nome da cidade como parâmetro e retorna dados de endereço
     *
     *******************************************/
    public function get_endereco_cidade(){


        $data = (object) $_GET;

        $term = mysqli_real_escape_string($this->conn,$data->term);

        $sql =
        "
        SELECT
        e.endereco_codigo as id,
        concat('Cidade: ', c.cidade_descricao, ' | CEP: ', ifnull(e.endereco_cep,c.cidade_cep), ' | Bairro: ', ifnull(b.bairro_descricao,'Centro'), ' | Logradouro: ', ifnull(e.endereco_logradouro,'')) as label,
        c.cidade_descricao as value,
        ifnull(b.bairro_descricao,'Centro') as bairro,
        ifnull(e.endereco_logradouro,'') as logradouro,
        ifnull(e.endereco_cep,c.cidade_cep) as cep
        from cep.cidade c
        left join cep.bairro b on c.cidade_codigo = b.cidade_codigo
        left JOIN cep.endereco e on b.bairro_codigo = e.bairro_codigo
        where c.cidade_descricao like '%$term%'
        order by
        c.cidade_descricao,
        b.bairro_descricao,
        e.endereco_logradouro
        limit 10
        ";

        $result = $this->_select_fetch_all($sql);

        // Retorna os dados em formato JSON
        echo json_encode($result);

    }

}

$endereco = new endereco($_GET['_action']);
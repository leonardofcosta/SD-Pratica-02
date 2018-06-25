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
        e.endereco_codigo as id,
        CONCAT('Cep: ', ifnull(e.endereco_cep,c.cidade_cep) ,
            ' | Logradouro: ', ifnull(e.endereco_logradouro,''),
            ' | Bairro: ', ifnull(b.bairro_descricao,'Centro'),
            ' | Cidade: ', c.cidade_descricao,
            ' | UF: ', u.uf_sigla) as label,
        c.cidade_descricao as cidade,
        ifnull(b.bairro_descricao,'Centro') as bairro,
        ifnull(e.endereco_logradouro,'') as logradouro,
        ifnull(e.endereco_cep,c.cidade_cep) as value,
        ifnull(u.uf_sigla,'') as uf

        FROM cep.cidade c
        LEFT  JOIN cep.bairro b   ON c.cidade_codigo = b.cidade_codigo
        LEFT  JOIN cep.endereco e ON b.bairro_codigo = e.bairro_codigo
        INNER JOIN cep.uf u       ON u.uf_codigo = c.uf_codigo

        WHERE (ifnull(e.endereco_cep,c.cidade_cep) LIKE '$term%') OR (c.cidade_descricao like '%$term%')

        ORDER BY
        c.cidade_descricao,
        b.bairro_descricao,
        e.endereco_logradouro

        LIMIT 10;
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
        CONCAT('Cep: ', ifnull(e.endereco_cep,c.cidade_cep) ,
            ' | Logradouro: ', ifnull(e.endereco_logradouro,''),
            ' | Bairro: ', ifnull(b.bairro_descricao,'Centro'),
            ' | Cidade: ', c.cidade_descricao,
            ' | UF: ', u.uf_sigla) as label,
        ifnull(e.endereco_cep,c.cidade_cep) as cep,
        ifnull(e.endereco_logradouro,'') as logradouro,
        ifnull(b.bairro_descricao,'Centro') as bairro,
        c.cidade_descricao as value,
        u.uf_sigla as uf

        FROM cep.cidade c
        LEFT JOIN cep.bairro b   ON c.cidade_codigo = b.cidade_codigo
        LEFT JOIN cep.endereco e ON b.bairro_codigo = e.bairro_codigo
        INNER JOIN cep.uf u      ON u.uf_codigo = c.uf_codigo

        WHERE c.cidade_descricao LIKE '%$term%'

        ORDER BY
        c.cidade_descricao,
        b.bairro_descricao,
        e.endereco_logradouro

        LIMIT 10;
        ";

        $result = $this->_select_fetch_all($sql);

        // Retorna os dados em formato JSON
        echo json_encode($result);

    }

}

$endereco = new endereco($_GET['_action']);
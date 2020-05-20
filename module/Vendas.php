<?php

require_once "Conexao.php";
include_once "Email.php";

class Vendas extends Conexao
{
    const TABELA_VENDAS = 'vendas';
    const TABELA_ITENS_VENDA = 'itens_venda';

    const DINHEIRO       = 1;
    const CARTAO_DEBITO  = 2;
    const CARTAO_CREDITO = 3;
    const CHEQUE         = 4;
    const TRANSFERENCIA  = 5;
    const SALDO_LOJA     = 6;

    public function insertVenda($values)
    {
        $idVenda = null;
        $dados = array();
        $msg = array();
        $values['desconto'] = str_replace(',', '.', $values['desconto']);
        $values['credito'] = empty($values['credito']) ? '0,0' : $values['credito'];
        $values['frete'] = empty($values['frete']) ? '0,0' : $values['frete'];
        $dados['table'] = $values['table'];
        $dados['ven_id'] = null;
        $dados['formas_pagamento'] = $values['formas_pagamento'];
        $dados['tipo_venda'] = $values['tipo_venda'];
        $dados['valor'] = $values['valorSemDesconto'];
        $dados['desconto'] = !empty($values['desconto']) ? $values['desconto'] : '0';
        $dados['valor_total'] = $values['valorTotal'];
        $dados['data'] = date('Y-m-d');
        $dados['cliente'] = $this->getCliente($values['cliente']);
        $dados['credito'] = str_replace(',', '.', $values['credito']);
        $dados['frete'] = str_replace(',', '.', $values['frete']);
//            echo '<pre>';
//            var_dump($dados); die;

//        if (!empty($dados['cliente'])) {
//            $this->updateCredito($dados);
//        }
        $idVenda = $this->insert(self::TABELA_VENDAS, $dados, array(), true);
        if ($idVenda) {
            $msg = $this->insertItensVenda($values, $idVenda);
            $email = new Email();
            $email->emailProprietarioPosVenda($idVenda);
            return $msg;
        }
        return false;
    }

    public function getCliente($nome){
        $sql = "SELECT cpf FROM pessoa where nome = ?";
        $row = $this->query($sql, [1 => $nome]);

        return isset($row[0]) ? $row[0]->cpf : '';

    }

    public function updateCredito($values)
    {

        $dados = array();
        $dados['cx_cpf'] = $values['cliente'];

        $sql = "SELECT cx_credito FROM caixa_pessoa where cx_cpf = ?";
        $row = $this->query($sql, [1 => $dados['cx_cpf']]);
        $credito = $row[0]->cx_credito;

        $dados['cx_credito'] = ($values['credito'] - $credito);
        return $this->update('caixa_pessoa', $dados, ['cx_cpf' => $dados['cx_cpf']]);


    }


    public function insertItensVenda($values, $idVenda)
    {
        $msg = null;
        $campos['table'] = self::TABELA_ITENS_VENDA;
        $campos['item_id'] = null;
//        $campos['quantidade'] = 1;
        $campos['venda'] = $idVenda;


        foreach ($values['item'] as $key => $value) {
            $campos['peca'] = $value;
            $campos['preco'] = $values['valorItem'][$key];
            $campos['quantidade'] = $values['qtdItem'][$key];
//        var_dump($campos); die;

            $msg = $this->insert($campos['table'], $campos);
        }
        return $msg;

    }

    public function agruparItensPorVenda($values)
    {
        $vendas = array();
        foreach ($values as $key => $value) {
            if ($key == 0) {
                $vendas[$value->ven_id][] = $value;
            } else {
                $vendas[$value->ven_id][] = $value;
            }
        }

        return $vendas;
    }

    public function getPecasDisponiveisPorPessoa($cpf){

        $sql = "SELECT ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) as vencimento, p.*
                FROM pecas p
                WHERE p.quantidade > 0 AND p.pessoa = ?";
        return $this->query($sql, [1 => $cpf]);

    }

    public function financeiroPorPessoa($cpf){

        $sqlPecasByPessoa = "SELECT p.cpf, p.nome, p.email, pec.titulo as peca_titulo, pec.preco_venda as peca_preco_venda,
pec.percentual_lucro as peca_percentual_lucro, pec.data_recebimento as peca_data_recebimento,
pec.disponibilidade as peca_disponibilidade, v.data as data_venda, iv.quantidade, pec.data_recebimento, pec.quantidade as qtd_peca,
tv.descricao as tipo_venda, fp.pg_descricao as forma_pagamento, v.ven_id, ci.cx_valor, ci.data_pagamento, cp.cx_credito,
ADDDATE(pec.data_recebimento, INTERVAL pec.disponibilidade DAY) as vencimento, dev.dev_data, dev.dev_justificativa
                        FROM pessoa p 
                        INNER JOIN caixa_pessoa cp on cp.cx_cpf = p.cpf
                        INNER JOIN pecas pec on pec.pessoa = p.cpf
                        LEFT JOIN itens_venda iv on iv.peca = pec.codigo
                        LEFT JOIN caixa_item ci on ci.cx_item = iv.item_id
                        LEFT JOIN vendas v on v.ven_id = iv.venda
                        LEFT JOIN tipo_venda tv on tv.tp_id = v.tipo_venda
                        LEFT JOIN formas_pagamento fp on fp.pg_id = v.formas_pagamento
                        LEFT JOIN devolucao dev on dev.dev_id_item = iv.item_id
                        WHERE p.cpf = ? order by v.data DESC, v.ven_id DESC";
        return $this->query($sqlPecasByPessoa, [1 => $cpf]);

    }
}
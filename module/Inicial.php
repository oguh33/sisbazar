<?php

require_once "Conexao.php";

class Inicial extends Conexao
{

    CONST STATUS_VENCIDO    = 'VENCIDO';
    CONST STATUS_VENCENDO   = 'VENCENDO';
    CONST STATUS_DISPONIVEL = 'DISPONIVEL';

    public function getPecasByStatus($tipo, $result = array()){
        switch ($tipo){
            case self::STATUS_VENCIDO :
                return $this->getPecasVencidas();
                break;
            case self::STATUS_VENCENDO :
                return $this->getPecasVencidas();
                break;
            case self::STATUS_DISPONIVEL:
                return $this->getPecasVencidas();
                break;
        }
        if(!empty($result)){
            return $result;
        }
        return $this->getPecas();
    }

    public function getQtdTotalPecas($result = array()){
        if( empty($result) ){
            $result = $this->getPecas();
        }

        $total = 0;
        foreach ($result as $rs){
                $total += $rs->quantidade;
        }
        return $total;
    }

    public function getPecasVencendo($result = array())
    {
        if( empty($result) ){
            $result = $this->getPecas();
        }

        $pecas = array();
        foreach ($result as $rs){
            if( $rs->status == self::STATUS_VENCENDO ){
                $pecas[] = $rs;
            }
        }
        return $pecas;
    }

    public function getPecasVencidas($result = array())
    {
        if( empty($result) ){
            $result = $this->getPecas();
        }

        $pecas = array();
        foreach ($result as $rs){
          if( $rs->status == self::STATUS_VENCIDO ){
                $pecas[] = $rs;
          }
        }
        return $pecas;
    }

    public function getMensagem(){
        $sql = "SELECT msg_id, msg_texto FROM mensagem";
        $result = $this->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getPecas()
    {
        $sql = "SELECT CURRENT_DATE as hoje, ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) as vencimento,
                  CASE  
                  WHEN ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) < CURRENT_DATE THEN 'VENCIDO'
                  WHEN ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) < ADDDATE(CURRENT_DATE, INTERVAL 7 DAY) THEN 'VENCENDO'
                  ELSE 'DISPONIVEL' END as status,
                        p.*, pes.nome
                  FROM pecas p 
                  INNER JOIN pessoa pes ON pes.cpf = p.pessoa
                  WHERE p.quantidade <> 0
                  ORDER BY p.data_recebimento";
        return $result = $this->query($sql);
    }

    public function getPecasByPessoa($cpf)
    {
        $sql = "SELECT CURRENT_DATE as hoje, ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) as vencimento,
                  CASE  
                  WHEN ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) < CURRENT_DATE THEN 'VENCIDO'
                  WHEN ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) < ADDDATE(CURRENT_DATE, INTERVAL 7 DAY) THEN 'VENCENDO'
                  ELSE 'DISPONIVEL' END as status,
                        p.*, pes.nome
                  FROM pecas p 
                  INNER JOIN pessoa pes ON pes.cpf = p.pessoa
                  WHERE p.quantidade <> 0 and pes.cpf = ?
                  ORDER BY p.data_recebimento";
        return $result = $this->query($sql, [1=>$cpf]);
    }

    public function getVendas30dias(){
        $sql = "SELECT tv.descricao, v.ven_id, v.data, v.valor_total, pes.nome, p.titulo, fp.pg_descricao
                FROM vendas v
                INNER JOIN itens_venda iv on v.ven_id = iv.venda
                inner join formas_pagamento fp on fp.pg_id = v.formas_pagamento
                left join tipo_venda tv on tv.tp_id = v.tipo_venda
                LEFT join pessoa pes on pes.cpf = v.cliente
                inner join caixa_item ci on ci.cx_item = iv.item_id
                INNER JOIN pecas p on p.codigo = iv.peca
                left join devolucao d on d.dev_id_item = iv.item_id
                where d.dev_id is null
                and ADDDATE( CURRENT_DATE, INTERVAL -30 DAY) < v.data
                ORDER BY v.ven_id";
         $result = $this->query($sql);
        return $result;

    }

}
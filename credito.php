<?php
include_once "module/Conexao.php";
include_once "module/Email.php";

if (isset($_POST['cpf'])) {
    $con = new Conexao();
    $sql = "SELECT * FROM caixa_pessoa cp inner join pessoa p on p.cpf = cp.cx_cpf where p.nome = ?";
    $row = $con->query($sql, [1 => $_POST['cpf']]);

    echo number_format($row[0]->cx_credito, 2,',', '');

} elseif (isset($_POST['data_pagamento']) && isset($_POST['cx_item_id'])) {

    $con = new Conexao();
    $_POST['data_pagamento'] = $con->inverteData($_POST['data_pagamento']);

    //Registrando o pagamento do proprietario da peca
    $msgPag = $con->registrarPagamento($_POST, $_FILES);

    $sql = "SELECT ci.cx_valor, p.pessoa, cp.cx_credito, p.titulo, pes.email, ci.data_pagamento, p.codigo, pes.nome
            FROM caixa_item ci
            inner join itens_venda iv on iv.item_id = ci.cx_item
            INNER join pecas p on p.codigo = iv.peca
            INNER join caixa_pessoa cp on cp.cx_cpf = p.pessoa
            INNER join pessoa pes on cp.cx_cpf = pes.cpf
            WHERE ci.cx_item_id = ?";
    $resultCPF = $con->query($sql, [1 => $_POST['cx_item_id']]);

    $row = $resultCPF[0];

//    $campos['cx_cpf']     = $row['pessoa'];
    $campos['cx_credito'] = $row->cx_credito - $row->cx_valor;

    if($campos['cx_credito'] < 0){
        $campos['cx_credito'] = 0;
    }

    $msg    = $con->update('caixa_pessoa', $campos, ['cx_cpf' => $row->pessoa]);

    $objEmail = new Email();
    $objEmail->pagamentoEfetuado($row);

    $texto  = '<div class="alert alert-'. $msg['tipo'] .'" role="alert">';
    $texto .= $msg['msg'];
    $texto .= '</div>';

    echo $texto;


} else {
    echo '';
}
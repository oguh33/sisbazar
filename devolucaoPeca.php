<?php
include_once "module/Conexao.php";
include_once "module/Email.php";


    $con = new Conexao();
    $_POST['dev_id'] = '';
    $_POST['dev_data'] = $con->inverteData($_POST['dev_data']);

    $msg    = $con->insert('devolucao', $_POST);

    if($msg['tipo'] == 'success'){
        //$con->
    }

//    $objEmail = new Email();
//    $objEmail->pagamentoEfetuado($row);

    $texto  = '<div class="alert alert-'. $msg['tipo'] .'" role="alert">';
    $texto .= $msg['msg'];
    $texto .= '</div>';

    echo $texto;
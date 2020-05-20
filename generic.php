<?php
require_once "module/Urls.php";
require_once "define.php";
include_once "module/Conexao.php";


if (isset($_POST['table'])) {
    $table = $_POST['table'];
    $action = $_POST['action'];
    $page = $_POST['page'];

    if (array_key_exists('table', $_POST)) {
        unset($_POST['table']);
    }
    if (array_key_exists('action', $_POST)) {
        unset($_POST['action']);
    }
    if (array_key_exists('page', $_POST)) {
        unset($_POST['page']);
    }

    $con = new Conexao();

    if( $table == 'pessoa' && $action == 'update' ){
        $condicional = array('cpf' => $_POST['cpf']);
        unset($_POST['cpf']);
        $result = $con->update($table, $_POST, $condicional);
    }else{
        $result = $con->$action($table, $_POST);
    }


    echo '<script>
                window.location.href = "' . $page . '"
          </script>';

} else {
    header("Location: " . PATH);
}
die;

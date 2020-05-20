<?php
include_once "module/CodBar.php";
$codBar = new CodBar();
//var_dump($_POST);
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
$tamanho = isset($_POST['tamanho']) ? $_POST['tamanho'] : '';
$preco = isset($_POST['preco']) ? $_POST['preco'] : '';
?>
<div style="width: 378px; height:auto; background: #fc7777; border: 1px solid #000; text-align: center; position: absolute;">

    <div style="width: 110px; background: #fc7777; float: left; position:relative;">
        <img src="img/abazaria_logo.jpg" width="110px">
    </div>
    <div style="width: 260px; float: right; position: relative; border-left: 1px solid #000">
        <div style="margin: 0 auto">
            <p>
                <?= $titulo ?>
                <br>
                <b><?= $tamanho ?></b>
                <br>
                <?= $preco ?>
            </p>

        </div>
        <div style="margin: 0 auto;">
            <?php $codBar->gerarCodigoById($codigo) ?>
        </div>
    </div>
</div>
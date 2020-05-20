<?php
include_once "define.php";
include_once "module/Conexao.php";
include_once "module/CodBar.php";
//
//echo '<head>';
//echo '<link href="'. PATH .'vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">';
//echo '</head>';

$codBar = new CodBar();
$con    = new Conexao();

$data_recebimento_inicial = $con->inverteData($_POST['data_recebimento_inicial']);
$data_recebimento_inicial = str_replace('-','',$data_recebimento_inicial);
$data_recebimento_final   = $con->inverteData($_POST['data_recebimento_final']);
$data_recebimento_final   = str_replace('-','',$data_recebimento_final);

$sql = "SELECT codigo, titulo, tamanho, preco_venda FROM pecas WHERE data_recebimento BETWEEN '20191101' AND '20191202'  ";

$result = $con->query($sql);

foreach ($result as $rs):
?>
<div style="width: 378px; margin: 0 auto 5px auto; height:148px; background: #fc7777; border: 1px solid #000; text-align: center;">
    <div style="width: 110px; background: #fc7777; float: left;" >
        <img src="/abazaria/img/abazaria_logo.jpg" width="110px">
    </div>
    <div style="width: 260px; float: right; border-left: 1px solid #000">
        <div style="margin: 0 auto">
            <p>
                <?= $rs->titulo ?>
                <br>
                <b><?= $rs->tamanho ?></b>
                <br>
                <?= 'R$ '.number_format($rs->preco_venda, 2, ',', '.') ?>
            </p>

        </div>
            <?php $codBar->gerarCodigoById($rs->codigo) ?>
    </div>
</div>

<?php endforeach; ?>
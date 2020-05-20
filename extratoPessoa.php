<?php
require_once "module/Urls.php";
include_once "module/Conexao.php";
include_once "module/Vendas.php";
$msg = null;
$vendas = new Vendas();
$con = new Conexao();
$con->conectar();

$urlPag = Urls::getPagina();

if (is_array($urlPag)) {

    $sqlPes = "SELECT * FROM vendas v
                INNER JOIN itens_venda iv on iv.venda = v.ven_id
                INNER JOIN caixa_item ci on ci.cx_item = iv.item_id
                INNER JOIN pecas p on p.codigo = iv.peca
                INNER JOIN pessoa pes on pes.cpf = p.pessoa
                WHERE pes.cpf = ?
                ORDER BY v.data DESC";
    $resultPes = $con->query($sqlPes, [1 => $urlPag[0]['id']]);
    $result = $vendas->agruparItensPorVenda($resultPes);
    // var_dump($result);
}


?><!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-table"></i>
        Extrato Pessoa
    </div>
</div>
<div class="card-body">
    <div class="container">
        <?php foreach ($result as $key => $item): ?>
            <div class="row">
                <div class="col-12 alert-secondary">

                    <a href="#" onclick="abrirFechar('<?= 'itens' . $key ?>')">
                        <div class="col-12">
                            <h4>
                                Venda <strong><?= str_pad($item[0]->ven_id, 6, '0', STR_PAD_LEFT) ?></strong> realizada
                                no
                                dia <strong><?php echo $con->inverteData($item[0]->data, '-', '/'); ?></strong>
                            </h4>
                        </div>
                    </a>
                </div>
                <div class="row" id="itens<?= $key ?>" style="width: 100%; display: none">
                    <div class="col-5"><strong>Peça</strong></div>
                    <div class="col-2 text-right"><strong>Venda R$</strong></div>
                    <div class="col-2 text-right"><strong>À pagar R$</strong></div>
                    <div class="col-3 text-right"><strong>Pagamento</strong></div>
                    <?php foreach ($item as $ii => $row): //var_dump($row); ?>
                        <div class="col-5 border-bottom"> <?= $row->titulo ?>  </div>
                        <div class="col-2 border-bottom text-right"> <?= 'R$ ' . number_format($row->preco_venda, 2, ',', '.') ?>  </div>
                        <div class="col-2 border-bottom text-right">
                            <?php
                            $valorPago = (($row->preco_venda * $row->percentual_lucro) / 100);
                            echo 'R$ ' . number_format($valorPago, 2, ',', '.');
                            ?>
                        </div>
                        <div class="col-3 border-bottom text-right" id="pagamento<?= $ii ?>">
                            <?php if ($row->cx_pago == '0'): ?>
                                <form name="form<?= $ii ?>" method="post">
                                    <select name="tipo" id="tipo<?= $ii ?>" class="form-control" required="required">
                                        <!--                                        <option value="">Forma de pagamento</option>-->
                                        <option value="c">Crédito</option>
                                        <option value="e">Espécie</option>
                                    </select>
                                    <input class="form-control" type="text"
                                           name="data" maxlength="10" id="data<?= $ii ?>"
                                           required="required"
                                           onkeypress="mascaraData(this)"
                                           value="<?= date('d/m/Y') ?>"/>
                                    <input type="hidden" name="cx_pago" id="cx_pago<?= $ii ?>" value="1">
                                    <input type="hidden" name="cx_item_id" id="cx_item_id<?= $ii ?>"
                                           value="<?= $row->cx_item_id ?>">
                                    <button type="button" onclick="enviarForm('<?= $ii ?>')" class="btn-success">
                                        Salvar
                                    </button>
                                </form>
                            <?php else: ?>
                                <?php if ($row->cx_tipo == 'c'): ?>
                                    <div title="<?= $con->inverteData($row->data_pagamento, '-', '/') ?>">
                                        <i class="fas fa-credit-card"></i>
                                        Crédito
                                    </div>
                                <?php else: ?>
                                    <div title="<?= $con->inverteData($row->data_pagamento, '-', '/') ?>">
                                        <i class="fas fa-money-bill"></i>
                                        Espécie
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row">
                <p></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">

    function abrirFechar(obj) {
        if (document.getElementById(obj).style.display == "none") {
            $('#' + obj).show();
        } else {
            $('#' + obj).hide();
        }
    }

    function enviarForm(id) {

        tipo = $("#tipo" + id + " option:selected").val();
        data = $("#data" + id).val();
        cx_pago = $("#cx_pago" + id).val();
        cx_item_id = $("#cx_item_id" + id).val();

        $.post("credito", {data_pagamento: data, cx_tipo: tipo, cx_pago: cx_pago, cx_item_id: cx_item_id},
            function (data) {
                $("#pagamento"+id).html(data);
            });
    }
</script>

<!--<p class="small text-center text-muted my-5">-->
<!--    <em>More table examples coming soon...</em>-->
<!--</p>-->
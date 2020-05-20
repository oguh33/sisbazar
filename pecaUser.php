<?php
include_once "module/Conexao.php";
include_once "module/Vendas.php";

$con                = new Conexao();
$objVendas          = new Vendas();
$rsPecasVendidas    = array();
$rsPecasDevolvidas  = array();
$rsPecasNaoVendidas = array();

$rsPecas              = $objVendas->financeiroPorPessoa($userLogado->user);
$rsPecasNaoVendidas   = $objVendas->getPecasDisponiveisPorPessoa($userLogado->user);

foreach ($rsPecas as $rs) {
    if(!is_null($rs->dev_data)){
        $rsPecasDevolvidas[] = $rs;
    }elseif (!is_null($rs->ven_id)) {
        $rsPecasVendidas[] = $rs;
    }
}
?><!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <i class="fas fa-tags"></i>
                    Minhas Peças
                </div>
                <div class="col-3 text-right">

                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <br>
    </div>
    <div class="container">
            <div class="col-12 text-center">
                <?php if (!empty($rsPecas)): ?>
                    <p><b><?=  $rsPecas[0]->nome ?></b></p>
                    <p>Crédito disponível para compras de <?=  'R$ ' . number_format($rsPecas[0]->cx_credito, 2, ',', '.') ?></p>
                <?php endif; ?>
            </div>
            <div class="col-12 text-center">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr class="thead-dark">
                        <th colspan="7" class="text-center">Peças vendidas</th>
                    </tr>
                    <tr class="thead-dark">
                        <th>Vendas</th>
                        <th class="text-left">Peça</th>
                        <th class="text-center">Data</th>
                        <th>Quantidade</th>
                        <th class="text-right">Valor de venda</th>
                        <th class="text-right">Valor recebido</th>
                        <th class="text-right">Valor a receber</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($rsPecasVendidas)): ?>
                        <?php
                        $totalValorPago = 0;
                        $totalValorPagar = 0;
                        foreach ($rsPecasVendidas as $rsPV):
                            if (!is_null($rsPV->data_pagamento)) {
                                $totalValorPago += ($rsPV->cx_valor);
                            } else {
                                $totalValorPagar += ($rsPV->cx_valor);
                            }
                            ?>
                            <tr>
                                <td><?= str_pad($rsPV->ven_id, '6', '0', STR_PAD_LEFT) ?></td>
                                <td class="text-left"><?= $rsPV->peca_titulo ?></td>
                                <td class="text-center"><?= $con->inverteData($rsPV->data_venda, '-', '/') ?></td>
                                <td><?= $rsPV->quantidade ?></td>
                                <td class="text-right"><?= 'R$ ' . number_format($rsPV->peca_preco_venda, 2, ',', '.') ?></td>
                                <td class="text-right"><?= (!is_null($rsPV->data_pagamento)) ? 'R$ ' . number_format($rsPV->cx_valor, 2, ',', '.') : '-' ?></td>
                                <td class="text-right"><?= (is_null($rsPV->data_pagamento)) ? 'R$ ' . number_format($rsPV->cx_valor, 2, ',', '.') : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5" class="text-left">TOTAL</td>
                            <td class="text-right"><b><?= 'R$ ' . number_format($totalValorPago, 2, ',', '.') ?></b>
                            </td>
                            <td class="text-right">
                                <b><?= 'R$ ' . number_format($totalValorPagar, 2, ',', '.') ?></b></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center"> Nenhuma peça foi vendida</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-12 text-center">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr class="thead-dark">
                        <th colspan="6" class="text-center">Peças disponíveis</th>
                    </tr>
                    <tr class="thead-dark">
                        <th class="text-left">Código Peça</th>
                        <th class="text-left">Peça</th>
                        <th class="text-center">Recebido em</th>
                        <th class="text-center">Disponível até</th>
                        <th>Quantidade</th>
                        <th class="text-right">Valor unitário</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($rsPecasNaoVendidas)): ?>
                        <?php $totalValorPecas = 0; ?>

                        <?php foreach ($rsPecasNaoVendidas as $rsPV): $totalValorPecas += $rsPV->peca_preco_venda; ?>
                            <tr>
                                <td class="text-left"><?= str_pad($rsPV->codigo, '6', '0', STR_PAD_LEFT) ?></td>
                                <td class="text-left"><?= $rsPV->titulo ?></td>
                                <td class="text-center"><?= $con->inverteData($rsPV->data_recebimento, '-', '/') ?></td>
                                <td class="text-center"><?= $con->inverteData($rsPV->vencimento, '-', '/') ?></td>
                                <td><?= $rsPV->quantidade ?></td>
                                <td class="text-right"><?= 'R$ ' . number_format($rsPV->preco_venda, 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5" class="text-left">TOTAL</td>
                            <td class="text-right">
                                <b><?= 'R$ ' . number_format($totalValorPecas, 2, ',', '.') ?></b></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center"> Nenhuma peça disponível a venda</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-12 text-center">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr class="thead-dark">
                        <th colspan="5" class="text-center">Peças devolvidas</th>
                    </tr>
                    <tr class="thead-dark">
                        <th class="text-left">Venda</th>
                        <th class="text-left">Peça</th>
                        <th class="text-center">Devolvida em</th>
                        <th class="text-right">Motivo</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($rsPecasDevolvidas)): ?>

                        <?php foreach ($rsPecasDevolvidas as $rsPd):?>
                            <tr>
                                <td><?= str_pad($rsPd->ven_id, '6', '0', STR_PAD_LEFT) ?></td>
                                <td class="text-left"><?= $rsPd->peca_titulo ?></td>
                                <td class="text-center"><?= $con->inverteData($rsPd->dev_data, '-', '/') ?></td>
                                <td><?= $rsPd->dev_justificativa ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center"> Nenhuma peça foi devolvida</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
    </div>
    <!--    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>-->
</div>

<!--<p class="small text-center text-muted my-5">-->
<!--    <em>More table examples coming soon...</em>-->
<!--</p>-->
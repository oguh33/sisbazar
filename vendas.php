<?php
include_once "module/Conexao.php";
include_once "module/Vendas.php";

$msg = null;
$con = new Conexao();
$objVendas = new Vendas();
$conn = $con->conectar();
$sql = "SELECT v.ven_id, v.data, v.valor, v.desconto, v.frete, v.credito,
               v.valor_total, i.venda item_venda, i.quantidade, i.preco item_preco, 
               i.quantidade, p.titulo item_titulo, p.codigo codigo_peca,
               pes.nome proprietario, p.percentual_lucro, fp.pg_descricao
        FROM vendas v 
        INNER JOIN itens_venda i on i.venda = v.ven_id 
        INNER JOIN pecas p on p.codigo = i.peca 
        INNER JOIN pessoa pes on pes.cpf = p.pessoa 
        INNER JOIN formas_pagamento fp on fp.pg_id = v.formas_pagamento 
        ORDER BY v.data DESC";
$result = $con->query($sql);
$vendas = $objVendas->agruparItensPorVenda($result);
//var_dump($vendas);
//echo '-------------';
//var_dump($result); die;

?><!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    Vendas
                </div>
                <div class="col-3 text-right">
                    <!--                    <a href="addPessoa">-->
                    <!--                        <i class="fa fa-plus-square"></i>-->
                    <!--                        Adicionar novo-->
                    <!--                    </a>-->
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Data</th>
                    <th>Código da venda</th>
                    <th>Pagamento</th>
                    <th>Valor da venda</th>
                    <th>Crédito</th>
                    <th>Desconto</th>
                    <th>Frete</th>
                    <th>Total</th>
                    <th></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Data</th>
                    <th>Código da venda</th>
                    <th>Pagamento</th>
                    <th>Valor da venda</th>
                    <th>Crédito</th>
                    <th>Desconto</th>
                    <th>Frete</th>
                    <th>Total</th>
                    <th></th>
                </tr>
                </tfoot>
                <tbody>

                <?php foreach ($vendas as $rs): ?>
                    <tr>
                        <td><?php echo $con->inverteData($rs[0]->data, '-', '/'); ?></td>
                        <td><?php echo str_pad($rs[0]->ven_id, '6','0',STR_PAD_LEFT); ?></td>
                        <td><?php echo $rs[0]->pg_descricao; ?></td>
<!--                        <td>--><?php //echo $rs[0]->proprietario; ?><!--</td>-->
                        <td><?php echo 'R$ ' . number_format($rs[0]->valor, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($rs[0]->credito, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($rs[0]->desconto, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($rs[0]->frete, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($rs[0]->valor_total, 2, ',', '.'); ?></td>
                        <td align="center">
                            <button type="button" class="btn btn-info mb-2" data-toggle="modal" data-target="#vendaModal<?= $rs[0]->ven_id ?>">
                                <i class="fa fa-address-book"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>-->
</div>
<?php foreach ($vendas as $rs): ?>
<div class="modal fade" id="vendaModal<?= $rs[0]->ven_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Detalhes da venda</strong></h6>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                        <p><strong>Código:</strong> <?php echo str_pad($rs[0]->ven_id, '6','0',STR_PAD_LEFT); ?></p>
                        <p><strong>Data:</strong> <?php echo $con->inverteData($rs[0]->data, '-', '/'); ?></p>

                <table width="100%">
                    <tr>
                        <td><strong>Itens:</strong></td>
                        <td align="right"></td>
                    </tr>
                    <?php $i=1; foreach ($rs as $item):?>
                    <tr style="background-color: <?= (($i++%2) == 0) ? '#fff':'#fff'  ?>">
                        <td>
                            Peça: <?php echo $item->item_titulo; ?><br/>
                            <span style="font-size: 12px">Dono: <?php echo ucwords($item->proprietario); ?></span>
                        </td>
                        <td align="right"  style=""><?php echo $item->quantidade.'x  R$ ' . number_format($item->item_preco, 2, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: 1px solid #ccc"></td>
                    </tr>
                    <?php endforeach;?>
                    <tr>
                        <td><br></td>
                        <td align="right"></td>
                    </tr>

                    <tr>
                        <td><strong>Valor</strong></td>
                        <td align="right"  style="border-bottom: 1px solid #000"><?php echo 'R$ ' . number_format($rs[0]->valor, 2, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Crédito</strong></td>
                        <td align="right" style="border-bottom: 1px solid #000"><?php echo 'R$ -' . number_format($rs[0]->credito, 2, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Frete</strong></td>
                        <td align="right" style="border-bottom: 1px solid #000"><?php echo 'R$ ' . number_format($rs[0]->frete, 2, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Desconto</strong></td>
                        <td align="right" style="border-bottom: 1px solid #000"><?php echo 'R$ -' . number_format($rs[0]->desconto, 2, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Valor total</strong></td>
                        <td align="right"><?php echo 'R$ ' . number_format($rs[0]->valor_total, 2, ',', '.'); ?></td>
                    </tr>
                </table>

            </div>
<!--            <div class="modal-footer">-->
<!--                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>-->
<!--                <button type="submit" id="btnEnviar" class="btn btn-primary">Confirmar</button>-->
<!--            </div>-->
        </div>
    </div>
</div>
<?php endforeach; ?>
<!--<p class="small text-center text-muted my-5">-->
<!--    <em>More table examples coming soon...</em>-->
<!--</p>-->
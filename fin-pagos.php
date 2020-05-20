<?php
include_once "module/Conexao.php";
include_once "module/Thumbnails.php";

$msg = null;
$result = array();
$status = isset($_POST['status']) ? $_POST['status'] : 0;
$con = new Conexao();
$conn = $con->conectar();
$sql = "select v.ven_id, v.data, 
        pes.nome, pes.cpf, b.nome_banco, b.codigo banco,
        pes.agencia, pes.conta, pec.codigo,
        pec.titulo, ci.cx_valor, ci.cx_item_id, ci.data_pagamento, ci.comprovante
        from pessoa pes 
        INNER JOIN banco b on b.codigo = pes.banco
        inner JOIN pecas pec on pes.cpf = pec.pessoa
        inner join itens_venda iv on iv.peca = pec.codigo
        INNER JOIN vendas v on v.ven_id = iv.venda
        INNER JOIN caixa_item ci on ci.cx_item = iv.item_id
        where ci.cx_pago = 1
        order by v.data DESC, v.ven_id DESC, pes.cpf";
$result = $con->query($sql, [1 => $status]);
$thumb = new Thumbnails();
?>
<div class="card mb-3">
    <div class="card-header">
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <i class="fas fa-search-dollar"></i>
                    Financeiro - Peças pagas
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Data de pagamento</th>
                    <th>Data da venda</th>
                    <th>Venda</th>
                    <th>Nome</th>
                    <!--                    <th>CPF</th>-->
                    <!--                    <th>Banco</th>-->
                    <!--                    <th>Ag.</th>-->
                    <!--                    <th>Conta</th>-->
                    <th>Peça</th>
                    <th>Valor</th>
                    <!--                    <th>Endereço</th>-->
                    <th>Ações</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Data de pagamento</th>
                    <th>Data da venda</th>
                    <th>Venda</th>
                    <th>Nome</th>
                    <!--                    <th>CPF</th>-->
                    <!--                    <th>Banco</th>-->
                    <!--                    <th>Ag.</th>-->
                    <!--                    <th>Conta</th>-->
                    <th>Peça</th>
                    <th>Valor</th>
                    <!--                    <th>Endereço</th>-->
                    <th>Ações</th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($result as $ii => $rs): //var_dump($rs); die;?>
                    <tr id="linha<?= $ii ?>">
                        <td><?php echo $con->inverteData($rs->data_pagamento, '-', '/'); ?></td>
                        <td><?php echo $con->inverteData($rs->data, '-', '/'); ?></td>
                        <td><?php echo str_pad($rs->ven_id, '6', '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo($rs->nome); ?></td>
                        <!--                        <td>--><?php //echo($rs->cpf); ?><!--</td>-->
                        <!--                        <td>--><?php //echo $rs->nome_banco; ?><!--</td>-->
                        <!--                        <td>--><?php //echo $rs->agencia; ?><!--</td>-->
                        <!--                        <td>-->
                        <?php //echo ($rs->banco == '104') ? $rs->conta . ' op. ' . $rs->operacao : $rs->conta ?><!--</td>-->
                        <td><?php echo str_pad($rs->codigo, '6', '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo 'R$ ' . number_format($rs->cx_valor, 2, ',', '.') ?></td>
                        <td align="center">
                            <button type="button" class="btn btn-info mb-2" data-toggle="modal"
                                    data-target="#pagamentoModal<?= $ii ?>">
                                <i class="fa fa-funnel-dollar"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php foreach ($result as $ii => $rs): ?>
            <div class="modal fade" id="pagamentoModal<?= $ii ?>" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="exampleModalLabel"><strong>Detalhes pagamento</strong></h6>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">
                                Detalhes pagamento ao cliente, <b><?= $rs->nome ?></b>, referente a venda
                                <b><?php echo str_pad($rs->ven_id, '6', '0', STR_PAD_LEFT); ?></b>.
                                <br>
                                <br>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"><b>Cliente:</b></div>
                                    <div class="col-8"><?= $rs->nome ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><b>CPF:</b></div>
                                    <div class="col-8"><?= $rs->cpf ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><b>Banco:</b></div>
                                    <div class="col-8"><?= $rs->nome_banco ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><b>Agência:</b></div>
                                    <div class="col-8"><?= $rs->agencia ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><b>Conta:</b></div>
                                    <div class="col-8"><?= ($rs->banco == '104') ? $rs->conta . ' op. ' . $rs->operacao : $rs->conta ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-4"><b>Código da Peça:</b></div>
                                    <div class="col-8"><?php echo str_pad($rs->codigo, '6', '0', STR_PAD_LEFT); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><b>Peça:</b></div>
                                    <div class="col-8"><?= $rs->titulo ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><b>Venda:</b></div>
                                    <div class="col-8"><?php echo str_pad($rs->ven_id, '6', '0', STR_PAD_LEFT); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><b>Vendido em:</b></div>
                                    <div class="col-8"><?php echo $con->inverteData($rs->data, '-', '/'); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><b>Pago em:</b></div>
                                    <div class="col-8"><?php echo $con->inverteData($rs->data_pagamento, '-', '/'); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><b>Valor:</b></div>
                                    <div class="col-8"><?php echo 'R$ ' . number_format($rs->cx_valor, 2, ',', '.') ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <b>Comprovante:</b><br/>
                                        <?php if (!empty($rs->comprovante)): ?>
                                            <span class="zoom" id='imagemZoom' onmouseover="houverZoom(this)">
                                            <img src="<?= $thumb->getPathFileOriginComprovante($rs->comprovante); ?>"
                                                 border="1" width="200px" style="border: 1px solid #000;" '/>
                                            </span>
                                        <?php else: ?>
                                            Nenhum comprovante cadastrado.
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-dark">
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!--    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>-->
</div>
<script type="text/javascript">
    function houverZoom(obj){
        $(obj).zoom();
    }
    function enviarForm(id) {
        var formdata = new FormData($("form[name='form" + id + "']")[0]);
        var link = "abazaria/credito";
        var linha = "linha" + id;

        // alert(linha);


        $.ajax({
            type: 'POST',
            url: link,
            data: formdata,
            processData: false,
            contentType: false,
            success: function (data) {
                //return data;
                $("#mensagem").html(data);
                $("#" + linha).hide();
            }
        });

    }
</script>
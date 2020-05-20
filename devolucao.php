<?php
include_once "module/Conexao.php";
$msg = null;
$result = array();
//$status = isset($_POST['status']) ? $_POST['status'] : 0;
$con = new Conexao();
$conn = $con->conectar();
$sql = "select v.ven_id, v.data, (DATE_ADD(v.data, INTERVAL 7 DAY)) as dataLimiteDevolucao,
        pes.nome, pes.cpf, b.nome_banco, b.codigo banco,
        pes.agencia, pes.conta, pec.codigo,
        pec.titulo, ci.cx_valor, ci.cx_item_id, d.dev_id, d.dev_data, iv.item_id, d.dev_data, d.dev_justificativa
        from pessoa pes 
        INNER JOIN banco b on b.codigo = pes.banco
        inner JOIN pecas pec on pes.cpf = pec.pessoa
        inner join itens_venda iv on iv.peca = pec.codigo
        INNER JOIN vendas v on v.ven_id = iv.venda
        INNER JOIN caixa_item ci on ci.cx_item = iv.item_id
        INNER JOIN devolucao d on d.dev_id_item = iv.item_id
        order by v.data DESC, v.ven_id DESC, pes.cpf";
$result = $con->query($sql);

?>
<div class="card mb-3">
    <div class="card-header">
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <i class="fas fa-history"></i>
                    Devolução - Peças disponíveis para devolução
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="container">
            <div id="mensagem" class="offset-4 col-4 text-center">

            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Data da venda</th>
                    <th>Data da devolução</th>
                    <th>Motivo</th>
                    <th>Venda</th>
                    <th>Proprietário</th>
                    <th>Código da peça</th>
                    <th>Peça</th>
                    <th>Valor da peça</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Data da venda</th>
                    <th>Data da devolução</th>
                    <th>Motivo</th>
                    <th>Venda</th>
                    <th>Proprietário</th>
                    <th>Código da peça</th>
                    <th>Peça</th>
                    <th>Valor da peça</th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($result as $ii => $rs): //var_dump($rs); die;?>
                    <tr id="linha<?= $ii ?>">
                        <td align="right"><?php echo $con->inverteData($rs->data, '-', '/'); ?></td>
                        <td align="right"><?php echo $con->inverteData($rs->dev_data, '-', '/'); ?></td>
                        <td align="justify"><?php echo($rs->dev_justificativa); ?></td>
                        <td align="right"><?php echo str_pad($rs->ven_id, '6', '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo($rs->nome); ?></td>
                        <td align="right"><?php echo str_pad($rs->codigo, '6', '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo $rs->titulo ?></td>
                        <td align="right"><?php echo 'R$ ' . number_format($rs->cx_valor, 2, ',', '.') ?></td>
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
                            <h6 class="modal-title" id="exampleModalLabel"><strong>Registrar devolução</strong></h6>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form name="form<?= $ii ?>" id="form<?= $ii ?>" method="post" enctype="multipart/form-data">
                                <div class="col-12 form-group">
                                    Registrar devolução da peça <b><?= $rs->titulo ?></b>, referente a venda
                                    <b><?php echo str_pad($rs->ven_id, '6', '0', STR_PAD_LEFT); ?></b>.
                                </div>
                                <div class="col-12 form-group">
                                    <input class="form-control" type="text"
                                           name="dev_data" maxlength="10"
                                           required="required"
                                           onkeypress="mascaraData(this)"
                                           value="<?= date('d/m/Y') ?>"/>
                                    <input type="hidden" name="dev_id_item" value="<?= $rs->item_id ?>">
                                </div>
                                <div class="col-12 form-group">
                                            <textarea name="dev_justificativa" class="form-control" placeholder="descreva o motivo da devolução"></textarea>
                                </div>
                                <div class="col-12 text-right form-group">
                                    <button type="button" data-dismiss="modal" aria-label="Close"
                                            onclick="enviarForm('<?= $ii ?>')" class="btn btn-success">
                                        Registrar
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!--    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>-->
</div>
<script type="text/javascript">
    function enviarForm(id) {
        var formdata = new FormData($("form[name='form" + id + "']")[0]);
        var link = "abazaria/devolucaoPeca";
        var linha = "linha" + id;

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
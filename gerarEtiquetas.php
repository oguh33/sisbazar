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
                    <i class="fas fa-tags"></i>
                    Gerar Etiquetas
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="container">
            <div class="col-4 offset-4">
                <form action="etiquetas" method="post" target="_blank">
                    De: <input class="form-control" type="text"
                           name="data_recebimento_inicial" maxlength="10"
                           required="required"
                           onkeypress="mascaraData(this)" value="<?php echo date('d/m/Y')?>"/>

                    Até: <input class="form-control" type="text"
                               name="data_recebimento_final" maxlength="10"
                               required="required"
                               onkeypress="mascaraData(this)" value="<?php echo date('d/m/Y')?>"/>
                    <br />
                        <button type="submit" class="btn btn-primary mb-2">Gerar etiquetas</button>
                </form>
            </div>
        </div>
    </div>
    <!--    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>-->
</div>
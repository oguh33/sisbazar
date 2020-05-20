<?php
include_once "module/Conexao.php";
$msg = null;
$con = new Conexao();
$conn = $con->conectar();
$sql = "SELECT CURRENT_DATE as hoje, ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) as vencimento,
          CASE  
          WHEN ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) < CURRENT_DATE THEN 'VENCIDO'
          WHEN ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) < ADDDATE(CURRENT_DATE, INTERVAL 7 DAY) THEN 'A VENCER'
          ELSE 'DISPONIVEL' END as status,
                p.*, pp.* 
                FROM pecas p inner join pessoa pp on pp.cpf = p.pessoa 
                ORDER BY p.titulo";
$result = $con->query($sql);

?><!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <i class="fas fa-tags"></i>
                    Peças
                </div>
                <div class="col-3 text-right">
                    <a href="addPeca">
                        <i class="fa fa-plus-square"></i>
                        Adicionar nova peça
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Recebida</th>
                    <th>Disponível</th>
                    <th>Código</th>
                    <th>Título</th>
<!--                    <th>Descrição</th>-->
<!--                    <th>Tam</th>-->
                    <th>Material</th>
                    <th>Qtd</th>
                    <th>Venda R$</th>
                    <th>Lucro %</th>
                    <th>Dono</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Recebida</th>
                    <th>Disponível</th>
                    <th>Código</th>
                    <th>Título</th>
<!--                    <th>Tam</th>-->
                    <th>Material</th>
                    <th>Qtd</th>
                    <th>Venda R$</th>
                    <th>Lucro %</th>
                    <th>Dono</th>
                    <th>Ações</th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($result as $key => $rs): //var_dump($rs); die;?>
                    <tr class="<?= $rs->status == 'VENCIDO' ? 'alert-danger' :'' ?>">
                        <td><?php echo $con->inverteData($rs->data_recebimento, '-', '/'); ?></td>
                        <td><?php echo $con->inverteData($rs->vencimento, '-', '/'); ?></td>
                        <td>
                            <form method="post" action="<?=  PATH.'etiqueta'?>" name="<?= 'form'.$key ?>" target="_blank">
                            <input type="hidden" name="codigo" value="<?=  $rs->codigo; ?>" />
                            <input type="hidden" name="titulo" value="<?=  $rs->titulo; ?>" />
                            <input type="hidden" name="tamanho" value="<?=  $rs->tamanho; ?>" />
                            <input type="hidden" name="preco" value="<?=  'R$ '. number_format($rs->preco_venda, 2, ',', '.' ); ?>" />
                            <a href="#" title="Gerar etiqueta" onclick="<?= 'document.form'.$key.'.submit();' ?>">
                                <?php echo str_pad($rs->codigo, 6, "0", STR_PAD_LEFT); ?>
                            </a>
                            </form>
                        </td>
                        <td><?php echo $rs->titulo; ?></td>
<!--                        <td>--><?php //echo $rs->tamanho; ?><!--</td>-->
                        <td><?php echo $rs->material; ?></td>
                        <td><?php echo $rs->quantidade; ?></td>
                        <td><?php echo 'R$ '. number_format($rs->preco_venda, 2, ',', '.' ); ?></td>
                        <td><?php echo number_format($rs->percentual_lucro, 2, ',', '').'%'; ?></td>
                        <td><?php echo $rs->nome; ?></td>
                        <td align="center">

                                    <form action="generic" method="post">
                                        <input type="hidden" name="table" value="pecas">
                                        <input type="hidden" name="page" value="peca">
                                        <input type="hidden" name="codigo" value="<?php echo $rs->codigo ?>">
                                        <input type="hidden" name="action" value="del">

                                        <a href="addPeca/edit/<?php echo $rs->codigo ?>" title="Editar"
                                           class="btn btn-group-vertical btn-info">
                                            <i class="fa fa-edit" style="font-size:10px"></i>
                                        </a>
                                        <button type="submit" class="btn btn-group-vertical btn-danger">
                                            <i class="fa fa-times-circle" style="font-size: 10px"></i>
                                        </button>

                                    </form>
                            <!--                        <a href="generic/del/-->
                            <?php //echo $rs->cpf?><!--" id="excluir" title="Excluir">-->
                            <!--                            -->
                            <!--                        </a>-->
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
    <!--    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>-->
</div>

<!--<p class="small text-center text-muted my-5">-->
<!--    <em>More table examples coming soon...</em>-->
<!--</p>-->
<?php
include_once "module/Conexao.php";
$msg = null;
$result = array();
$status = isset($_POST['status']) ? $_POST['status'] : 0;
$con = new Conexao();
$conn = $con->conectar();
$sql = "SELECT * FROM pessoa p inner join banco b on p.banco = b.codigo WHERE p.status = ? order by p.nome";
$result = $con->query($sql, [1 => $status]);

?><!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <i class="fas fa-user"></i>
                    Pessoa
                </div>
                <div class="col-3 text-right">
                    <a href="addPessoa">
                        <i class="fa fa-plus-square"></i>
                        Adicionar novo
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="container">
            <div class="offset-4 col-4 text-center">
                <form name="busca" method="post">
                    <div class="form-group">
                        <div class="form-label-group">
                            <select name="status" class="form-control" onchange="document.busca.submit();">
                                <option>Selecione o status</option>
                                <option value="0" <?= ($status == 0) ? 'selected="selected"' : '' ?>>Ativo</option>
                                <option value="1" <?= ($status == 1) ? 'selected="selected"' : '' ?>>Inativo</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Banco</th>
                    <th>Agência</th>
                    <th>Conta</th>
                    <th>Data nascimento</th>
                    <th>E-mail</th>
                    <!--                    <th>Endereço</th>-->
                    <th>Ações</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Banco</th>
                    <th>Agência</th>
                    <th>Conta</th>
                    <th>Data nascimento</th>
                    <th>E-mail</th>
                    <!--                    <th>Endereço</th>-->
                    <th>Ações</th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($result as $rs): //var_dump($rs); die;?>
                    <tr>
                        <td><?php echo ucwords($rs->nome); ?></td>
                        <td><?php echo $rs->cpf; ?></td>
                        <td><?php echo($rs->nome_banco); ?></td>
                        <td><?php echo $rs->agencia; ?></td>
                        <td><?php echo ($rs->codigo == '104') ? $rs->conta . ' op. ' . $rs->operacao : $rs->conta ?></td>
                        <td><?php echo $con->inverteData($rs->data_nascimento, '-', '/'); ?></td>
                        <td><?php echo $rs->email; ?></td>
                        <!--                        <td>--><?php //echo $rs->endereco; ?><!--</td>-->
                        <td align="center">

<!--                            <a href="extratoPessoa/edit/--><?php //echo $rs->cpf ?><!--" title="Extrado"-->
<!--                               class="btn btn-group btn-success">-->
<!--                                <i class="fa fa-barcode" style="font-size:10px"></i>-->
<!--                            </a>-->

                            <form action="generic" method="post">
                                <input type="hidden" name="table" value="pessoa">
                                <input type="hidden" name="page" value="pessoa">
                                <input type="hidden" name="cpf" value="<?php echo $rs->cpf ?>">
                                <input type="hidden" name="status" value="<?php echo ($rs->status == 1) ? 0 : 1 ?>">
                                <input type="hidden" name="action" value="update">

                                <a href="addPessoa/edit/<?php echo $rs->cpf ?>" title="Editar"
                                   class="btn btn-group btn-info">
                                    <i class="fa fa-edit" style="font-size:10px"></i>
                                </a>
                                <button type="submit" class="btn btn-group <?php echo ($rs->status == 1) ? 'btn-secondary' : 'btn-danger' ?>">
                                    <i class="fa <?php echo ($rs->status == 1) ? 'fa-reply' : 'fa-times-circle' ?>" style="font-size: 10px"></i>
                                </button>
                            </form>

                            <form action="gerarContrato" method="post">
                                <input type="hidden" name="cpf" value="<?php echo $rs->cpf ?>">
                                <button type="submit" class="btn btn-group btn-warning" alt="Gerar contrato">
                                    <i class="fa fa-file-alt" style="font-size:10px"></i>
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
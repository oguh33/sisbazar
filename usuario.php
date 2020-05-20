<?php
include_once "module/Conexao.php";


$msg = null;
$con = new Conexao();
$conn = $con->conectar();
$sql = "SELECT * FROM usuario u INNER JOIN usuario_perfil p on u.perfil = p.id_perfil";
$result = $con->query($sql);


?><!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <i class="fas fa-fw fa-user-cog"></i>
                    Usuários do sistema
                </div>
                <div class="col-3 text-right">
                    <a href="addUsuario">
                        <i class="fa fa-plus-square"></i>
                        Adicionar novo
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>User</th>
                    <th>Perfil</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($result as $rs): //var_dump($rs); die;?>
                    <tr>
                        <td><?php echo $rs->id; ?></td>
                        <td><?php echo strtoupper($rs->nome); ?></td>
                        <td><?php echo $rs->user; ?></td>
                        <td><?php echo $rs->descricao; ?></td>
                        <td align="center">

                                    <form action="generic" method="post">
                                        <input type="hidden" name="table" value="usuario">
                                        <input type="hidden" name="page" value="usuario">
                                        <input type="hidden" name="id" value="<?php echo $rs->id ?>">
                                        <input type="hidden" name="action" value="del">

                                        <a href="addUsuario/edit/<?php echo $rs->id ?>" title="Editar"
                                           class="btn btn-group btn-info">
                                            <i class="fa fa-edit" style="font-size:10px"></i>
                                        </a>
                                        <button type="submit" class="btn btn-group btn-danger">
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
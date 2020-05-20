<?php
require_once "module/Urls.php";
include_once "module/Conexao.php";
include_once "module/Email.php";
$msg = null;
$con = new Conexao();
$sql = "SELECT p.id_perfil, p.descricao FROM usuario_perfil p order by p.descricao";
$result = $con->query($sql);

$urlPag = Urls::getPagina();
//var_dump($urlPag);
if (is_array($urlPag)) {

    if (isset($_POST['id'])) {
        if (empty($_POST['senha'])) {
            unset($_POST['senha']);
        };
        if (isset($_POST['senha'])) {
            $dados['senha'] = $_POST['senha'];
            $_POST['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        };
//        var_dump($_POST); die;
        $msg = $con->update('usuario', $_POST, ['id' => $_POST['id']]);
    }
    $sqlPes = "SELECT * FROM usuario u
               INNER JOIN usuario_perfil p ON p.id_perfil = u.perfil 
               LEFT JOIN pessoa pes on pes.cpf = u.user
               WHERE u.id = ?";
    $resultPes = $con->query($sqlPes, [1 => $urlPag[0]['id']]);

    if(!empty($resultPes[0]->email)){
        $dados['user'] = $resultPes[0]->user;
        $dados['email'] = $resultPes[0]->email;
        $dados['nome'] = $resultPes[0]->nome;
        $objEmail = new Email();
        $objEmail->updateSenhaCliente($dados);
    }
} else {

//    var_dump($_POST); die;

    if (isset($_POST['id'])) {


        $sqlUser = "SELECT * FROM usuario u WHERE u.user = ?";
        $resultUser = $con->query($sqlUser, [1 => $_POST['user']]);
        if(empty($resultUser)) {
            if (isset($_POST['senha'])) {
                $_POST['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            };
            $_POST['user'] = strtolower($_POST['user']);
            $msg = $con->insert('usuario', $_POST);
        }else{
            $msg = ['tipo' => 'danger', 'msg' => 'Nome de usuário, "User", já cadastrado.'];
        }

    }
}


?><!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-fw fa-user-cog"></i>
        Adicionar usuário do sistema
    </div>
</div>
<div class="card-body">
    <?php if ($msg != null): ?>
        <div class="alert alert-<?php echo $msg['tipo'] ?>" role="alert">
            <?php echo $msg['msg'] ?>
        </div>
    <?php endif; ?>
    <form action="" method="post">
        <label>Nome completo:</label>
        <div class="form-group">
            <input class="form-control" required="required" type="text" name="nome"
                    <?php echo((isset($resultPes[0]) && ($resultPes[0]->id_perfil == 3)) ? 'readonly="readonly"' : '') ?>
                   value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->nome : '') ?>"/>
        </div>
        <label>User:</label>
        <div class="form-group">
            <input class="form-control" required="required" type="text" name="user"
                <?php echo(isset($resultPes[0]) ? 'readonly="readonly"' : '') ?>
                   value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->user : '') ?>"/>
        </div>


        <?php if ((isset($resultPes[0]) && ($resultPes[0]->id_perfil != 3)) OR !isset($resultPes[0]) ): ?>
            <div class="form-group">
                <label>Perfil:</label>
                <div class="form-label-group">
                    <select id="selectbasic" name="perfil" required="required" class="form-control">
                        <option value="">Usuário</option>
                        <?php foreach ($result as $perfil): ?>
                            <?php $selected = (isset($resultPes[0]) && $perfil->id_perfil == $resultPes[0]->id_perfil) ? 'selected="selected"' : '' ?>
                            <option value="<?php echo $perfil->id_perfil ?>" <?php echo $selected ?>><?php echo($perfil->descricao) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-inline" <?php echo(!isset($resultPes[0]) ? 'style="display: none;"' : '') ?>>
            <label>
                <input class="form-control" type="checkbox" id="editSenha" onchange="editarSenha()">
                Editar senha
            </label>
        </div>
        <div id="updateSenha" style="<?php echo(isset($resultPes[0]) ? 'display: none;' : 'display: true;') ?>">

            <label>Senha:</label>
            <div class="form-group">
                <input class="form-control" type="password" id="senha" name="senha">
            </div>
            <label>Confirmar senha:</label>
            <div class="form-group">
                <input class="form-control" type="password" id="senha2">
            </div>

            <div id="msgSenha"></div>
        </div>

        <div class="form-group">
            <div class="form-label-group text-center">
                <input type="hidden" value="usuario" name="table"/>
                <input type="hidden" value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->id : '') ?>" name="id"/>
                <button type="submit" id="submit"
                        class="btn btn-primary mb-2"><?php echo(isset($resultPes[0]) ? 'Editar' : 'Adicionar') ?></button>
            </div>
        </div>
    </form>
</div>
<!--    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>-->
</div>

<script type="text/javascript">
    $("#senha").readOnly;

    function editarSenha() {

        if ($("#editSenha").is(':checked')) {
            $("#updateSenha").show();
            $("#senha").show();
        } else {
            $("#updateSenha").hide();
            $("#senha").hide();
        }

    }

    var password = document.getElementById("senha");
    var confirm_password = document.getElementById("senha2");

    function validatePassword() {
        if (password.value != confirm_password.value) {
            $("#msgSenha").html('<div class="alert alert-warning" role="alert">\n' +
                '  As senhas devem ser iguais\n' +
                '</div>');
            $("#submit").hide();
        } else {
            $("#msgSenha").html('<div class="alert alert-primary" role="alert">\n' +
                '  As senhas são iguais\n' +
                '</div>');
            $("#submit").show();
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>
<!--<p class="small text-center text-muted my-5">-->
<!--    <em>More table examples coming soon...</em>-->
<!--</p>-->
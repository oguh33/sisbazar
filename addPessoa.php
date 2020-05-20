<?php
require_once "module/Urls.php";
include_once "module/Conexao.php";
$msg = null;
$con = new Conexao();
$conn = $con->conectar();
$sql = "SELECT b.codigo, b.nome_banco FROM banco b order by b.nome_banco";
$result = $con->query($sql);

$urlPag = Urls::getPagina();
//var_dump($urlPag);
if (is_array($urlPag)) {

    if (isset($_POST['cpf'])) {
        $_POST['nome'] = mb_strtoupper($_POST['nome'], 'UTF-8');
        $msg = $con->update('pessoa', $_POST, ['cpf' => $_POST['cpf']]);
    }
    $sqlPes = "SELECT * FROM pessoa p
               INNER JOIN banco b ON b.codigo = p.banco 
               WHERE p.cpf = ?";
    $resultPes = $con->query($sqlPes, [1 => $urlPag[0]['id']]);
} else {
    if (isset($_POST['cpf'])) {
        $msg = $con->insert('pessoa', $_POST);
        if($msg['tipo'] == 'success'){
            $msg = $con->insertCliente($_POST, true);
        }

    }
}


?><!-- DataTables Example -->
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-user"></i>
        Adicionar Pessoa
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
                   value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->nome : '') ?>"/>
        </div>
        <label>E-mail:</label>
        <div class="form-group">
            <input class="form-control" required="required" type="email" name="email"
                   value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->email : '') ?>"/>
        </div>


        <div class="row">
            <div class="col-4 text-left">
                <div class="form-group">
                    <label>Nacionalidade: </label>
                    <div class="form-label-group">
                        <input class="form-control" required="required" type="text" name="nacionalidade" maxlength="2000"
                               value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->nacionalidade : 'Brasileira') ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>Data nascimento: </label>
                    <div class="form-label-group">
                        <input class="form-control" type="text" required="required" name="data_nascimento" maxlength="10"
                               onkeypress="mascaraData(this)"
                               value="<?php echo(isset($resultPes[0]) ? $con->inverteData($resultPes[0]->data_nascimento, '-', '/') : '') ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>CPF: </label>
                    <div class="form-label-group">
                        <input class="form-control" required="required" pattern="[0-9]+$"
                               maxlength="11" type="text" name="cpf" id="cpf"
                            <?php echo (isset($resultPes[0])) ? 'readonly="readonly"' : ''; ?>
                               placeholder="CPF só número" onblur="checkCPF(this.value)"
                               value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->cpf : '') ?>"/>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-6 text-left">
                <div class="form-group">
                    <label>Carteira de Identidade nº: </label>
                    <div class="form-label-group">
                        <input class="form-control" required="required" type="text" name="rg" maxlength="2000"
                               value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->rg : '') ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Estado civil: </label>
                    <div class="form-label-group">
                        <select name="estado_civil" required="required" class="form-control">
                            <option value="Solteiro(a)" <?php echo(isset($resultPes[0]) && $resultPes[0]->estado_civil == 'Solteiro(a)' ? 'selected="selected"' : '')?>>Solteiro(a)</option>
                            <option value="Casado(a)" <?php echo(isset($resultPes[0]) && $resultPes[0]->estado_civil == 'Casado(a)' ? 'selected="selected"' : '')?>>Casado(a)</option>
                            <option value="Divorciado(a)" <?php echo(isset($resultPes[0]) && $resultPes[0]->estado_civil == 'Divorciado(a)' ? 'selected="selected"' : '')?>>Divorciado(a)</option>
                            <option value="Viúvo(a)" <?php echo(isset($resultPes[0]) && $resultPes[0]->estado_civil == 'Viúvo(a)' ? 'selected="selected"' : '')?>>Viúvo(a)</option>
                            <option value="Separado(a)" <?php echo(isset($resultPes[0]) && $resultPes[0]->estado_civil == 'Separado(a)' ? 'selected="selected"' : '')?>>Separado(a)</option>
                            <option value="União estável" <?php echo(isset($resultPes[0]) && $resultPes[0]->estado_civil == 'União estável' ? 'selected="selected"' : '')?>>União estável</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label>Telefone (somente números): </label>
                    <div class="form-label-group">
                        <input class="form-control"
                               maxlength="20" required="required" pattern="[0-9]+$" type="text" name="telefone"
                               value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->telefone : '') ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Profissão: </label>
                    <div class="form-label-group">
                        <input class="form-control"
                               maxlength="2000" required="required" type="text" name="profissao"
                               value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->profissao : '') ?>"/>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-4 text-left">
                <div class="form-group">
                    <label>CEP: </label>
                    <div class="form-label-group">
                        <input class="form-control" pattern="[0-9]+$"
                               maxlength="8" required="required" type="text" name="cep" id="cep"
                               placeholder="CEP só número"
                               value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->cep : '') ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    <label>Rua / Complemento:</label>
                    <div class="form-label-group">
                        <input class="form-control" required="required" type="text" id="rua" name="rua"
                               value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->rua : '') ?>"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4 text-left">
                <div class="form-group">
                    <label>Bairro: </label>
                    <div class="form-label-group">
                        <input class="form-control"
                               type="text" name="bairro" id="bairro"
                               value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->bairro : '') ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>Cidade:</label>
                    <div class="form-label-group">
                        <input class="form-control" required="required" type="text" id="cidade" name="cidade"
                               value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->cidade : '') ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label>Estado:</label>
                    <div class="form-label-group">
                        <input class="form-control" required="required" type="text" name="estado" id="estado"
                               value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->estado : '') ?>"/>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-group">
            <label>Banco:</label>
            <div class="form-label-group">
                <select id="selectbasic" name="banco" required="required" class="form-control"
                        onchange="exibir_ocultar(this)">
                    <option value="">Banco</option>
                    <?php foreach ($result as $banco): ?>
                        <?php $selected = (isset($resultPes[0]) && $banco->codigo == $resultPes[0]->banco) ? 'selected="selected"' : '' ?>
                        <option value="<?php echo $banco->codigo ?>" <?php echo $selected ?>><?php echo ($banco->nome_banco) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group form-inline">
            <label>Agência:</label>
            <div class="form-label-group">
                <input class="form-control" required="required" type="text" name="agencia"
                       value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->agencia : '') ?>"/>
            </div>
            <div class="form-label-group offset-1">
            </div>
            <label>Conta:</label>
            <div class="form-label-group">
                <input class="form-control" required="required" type="text" name="conta"
                       value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->conta : '') ?>"/>
            </div>
            <div class="form-label-group offset-1">
            </div>
            <label id="labelOperacao" style="display: none">Operação:</label>
            <div class="form-label-group" id="operacao"
                 style="display:<?php echo(isset($resultPes[0]->operacao) && $resultPes[0]->operacao != '' ? 'true' : 'none') ?>">
                <input class="form-control" type="text" name="operacao"
                       value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->operacao : '') ?>"/>
            </div>
        </div>

        <div class="form-group">
            <div class="form-label-group text-center">
                <input type="hidden" value="pessoa" name="table"/>
                <button type="submit"
                        class="btn btn-primary mb-2"><?php echo(isset($resultPes[0]) ? 'Editar' : 'Adicionar') ?></button>
            </div>
        </div>
    </form>
</div>
<!--    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>-->
</div>

<script type="text/javascript">

    //Verifica se CPF é válido
    function checkCPF(strCPF) {
        var Soma, Resto, borda_original;
        Soma = 0;

        if (strCPF == "00000000000") {
            document.getElementById("cpf").setCustomValidity('Invalid');
            return false;
        }

        for (i = 1; i <= 9; i++) {
            Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
        }

        Resto = (Soma * 10) % 11;
        if ((Resto == 10) || (Resto == 11)) {
            Resto = 0;
        }

        if (Resto != parseInt(strCPF.substring(9, 10))) {
            document.getElementById("cpf").setCustomValidity('Invalid');
            return false;
        }

        Soma = 0;
        for (i = 1; i <= 10; i++) {
            Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
        }

        Resto = (Soma * 10) % 11;
        if ((Resto == 10) || (Resto == 11)) {
            Resto = 0;
        }

        if (Resto != parseInt(strCPF.substring(10, 11))) {
            document.getElementById("cpf").setCustomValidity('Invalid');
            return false;
        }

        document.getElementById("cpf").setCustomValidity('');
        return true;
    }

    function exibir_ocultar(val) {
        if (val.value == '104') {
            $("#operacao").show();
            $("#labelOperacao").show();
        } else {
            $("#operacao").hide();
            $("#labelOperacao").hide();
        }
    }
    ;
</script>

<!--<p class="small text-center text-muted my-5">-->
<!--    <em>More table examples coming soon...</em>-->
<!--</p>-->
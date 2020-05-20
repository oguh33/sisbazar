<?php
require_once "module/Urls.php";
include_once "module/Conexao.php";
include_once "module/Email.php";

$cpf = $_POST['cpf'];

$con = new Conexao();
$sql = "SELECT p.nome, p.nacionalidade, p.estado_civil, p.profissao, p.rg, p.cpf, p.rua endereco, 
               p.bairro, p.cep, p.cidade, p.estado, p.telefone, pc.titulo, pc.preco_venda, 
               b.nome_banco, p.agencia, p.conta, p.operacao, pc.codigo
        FROM pessoa p 
        inner join banco b on p.banco = b.codigo 
        inner join pecas pc on pc.pessoa = p.cpf and pc.quantidade > 0
        WHERE p.cpf = ?";
$result = $con->query($sql,[1 => $cpf]);
//echo '<pre>';
//var_dump($result);
//echo '</pre>';
?>
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-fw fa-user-cog"></i>
        Gerar contrato
    </div>
</div>
<div class="card-body">
    <form action="contrato" method="post" target="_blank">
        <label>Gerar contrato com as peças do cliente <b><?php echo $result[0]->nome?></b>:</label>
        <?php foreach($result as $row): ?>
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" name="pecas[]" class="form-check-input" value="<?php echo $row->titulo.' -val-'.$row->preco_venda?>"><?php echo $row->titulo; ?>
            </label>
        </div>
        <?php endforeach; ?>
            <div class="form-group">
                <div class="form-label-group text-center">
                    <input type="hidden" name="nome" value="<?php echo $result[0]->nome?>">
                    <input type="hidden" name="nacionalidade" value="<?php echo $result[0]->nacionalidade?>">
                    <input type="hidden" name="estado_civil" value="<?php echo $result[0]->estado_civil?>">
                    <input type="hidden" name="profissao" value="<?php echo $result[0]->profissao?>">
                    <input type="hidden" name="rg" value="<?php echo $result[0]->rg?>">
                    <input type="hidden" name="cpf" value="<?php echo $result[0]->cpf?>">
                    <input type="hidden" name="endereco" value="<?php echo $result[0]->endereco?>">
                    <input type="hidden" name="bairro" value="<?php echo $result[0]->bairro?>">
                    <input type="hidden" name="cep" value="<?php echo $result[0]->cep?>">
                    <input type="hidden" name="cidade" value="<?php echo $result[0]->cidade?>">
                    <input type="hidden" name="estado" value="<?php echo $result[0]->estado?>">
                    <input type="hidden" name="telefone" value="<?php echo $result[0]->telefone?>">
                    <input type="hidden" name="nome_banco" value="<?php echo $result[0]->nome_banco?>">
                    <input type="hidden" name="agencia" value="<?php echo $result[0]->agencia?>">
                    <input type="hidden" name="conta" value="<?php echo $result[0]->conta?>">
                    <input type="hidden" name="operacao" value="<?php echo $result[0]->operacao?>">
                    <button type="submit" id="submit" class="btn btn-primary mb-2">Gerar contrato</button>
                </div>
            </div>
    </form>
</div>
</div>

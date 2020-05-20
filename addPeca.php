<?php
require_once "module/Urls.php";
include_once "module/Conexao.php";
include_once "module/Thumbnails.php";
include_once "module/Email.php";
$msg = null;
$imagens = array();
$con = new Conexao();
$conn = $con->conectar();
$sql = "SELECT p.cpf, p.nome FROM pessoa p order by p.nome";
$result = $con->query($sql);

$urlPag = Urls::getPagina();
//var_dump($urlPag);
if (is_array($urlPag)) {

    if (isset($_POST['codigo'])) {
        $_POST['titulo'] = mb_strtoupper($_POST['titulo'], 'UTF-8');
        $_POST['tamanho'] = mb_strtoupper($_POST['tamanho'], 'UTF-8');

        $files = isset($_FILES['arquivo']['name'][0]) ? $_FILES['arquivo'] : array();
        $msg = $con->update('pecas', $_POST, ['codigo' => $_POST['codigo']], $files);
    }
    $sqlPes = "SELECT * FROM pecas p
               INNER JOIN pessoa pp ON pp.cpf = p.pessoa 
               LEFT JOIN imagem_pecas img ON img.pecas = p.codigo
               WHERE p.codigo = ? ORDER BY img.arquivo";
    $resultPes = $con->query($sqlPes, [1 => $urlPag[0]['id']]);

    foreach ($resultPes as $key => $row) {

        if (empty($row->arquivo)) {
            continue;
        }
        $imagens[$key]['arquivo'] = $row->arquivo;
        $imagens[$key]['id'] = $row->id;
    }

} else {
    if (isset($_POST['codigo'])) {
        $files = isset($_FILES['arquivo']) ? $_FILES['arquivo'] : array();

        $_POST['titulo'] = mb_strtoupper($_POST['titulo'], 'UTF-8');
        $_POST['tamanho'] = mb_strtoupper($_POST['tamanho'], 'UTF-8');

        $msg = $con->insert('pecas', $_POST, $files);
        if( $msg['tipo'] == 'success' ){
            $objEmail = new Email();
            $objEmail->emailCadastroPeca($msg['codigo']);
        }
    }
}


?><!-- DataTables Example -->
<div class="card mb-3" xmlns="http://www.w3.org/1999/html">

    <div class="card-header">
        <div class="container">
            <div class="row">
                <div class="col-9">
                    <i class="fas fa-tags"></i>
                    Adicionar Peça
                </div>
                <div class="col-3 text-right">
                    <a href="addPessoa">
                        <i class="fa fa-plus-square"></i>
                        Adicionar Pessoa
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="card-body">
    <?php if ($msg != null): ?>
        <div class="alert alert-<?php echo $msg['tipo'] ?>" role="alert">
            <?php echo $msg['msg'] ?>
        </div>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Nome da peça:</label>
        <div class="form-group">
            <input class="form-control" required="required" type="text" name="titulo"
                   value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->titulo : '') ?>"/>
        </div>
        <label>Descrição:</label>
        <div class="form-group">
            <textarea class="form-control" required="required"
                      name="descricao"/><?php echo(isset($resultPes[0]) ? $resultPes[0]->descricao : '') ?></textarea>
        </div>
        <div class="row">
            <div class="col-4 text-left">
                <label>Tamanho (PP, P, M, G, GG ou XG): </label>
                <div class="form-label-group">
                    <input class="form-control"
                           type="text" name="tamanho" id="tamanho"
                           required="required" placeholder="PP, P, M, G, GG ou XG"
                           value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->tamanho : '') ?>"/>
                </div>
            </div>
            <div class="col-4">
                <label>Material: </label>
                <div class="form-label-group">
                    <input class="form-control"
                           type="text" name="material" id="material"
                           required="required" placeholder="algodão, poliéster, jeans"
                           value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->material : '') ?>"/>
                </div>
            </div>
            <div class="col-4">
                <label>Quantidade: </label>
                <div class="form-label-group">
                    <input class="form-control" type="number"
                           required="required" name="quantidade" maxlength="10"
                           value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->quantidade : '') ?>"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <label>Preço de venda: </label>
                <div class="form-label-group">
                    <input class="form-control"
                           type="text" name="preco_venda" required="required"
                           id="preco_venda" onkeyup="formatarMoeda(this);"
                           value="<?php echo (isset($resultPes[0]) ? 'R$ '.number_format($resultPes[0]->preco_venda, 2, ',', '.') : '' )?>"/>
                </div>
            </div>
            <div class="col-6">
                <label>Percentual de lucro: </label>
                <div class="form-label-group">
                    <input class="form-control "
                           type="text" name="percentual_lucro"
                           id="percentual_lucro" required="required" onkeyup="formatarPorcentagem(this)"
                           value="<?php echo(isset($resultPes[0]) ? number_format($resultPes[0]->percentual_lucro, 2, ',', '.') : '') ?>"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 text-left">
                <label>Data de recebimento: </label>
                <div class="form-label-group">
                    <input class="form-control" type="text"
                           name="data_recebimento" maxlength="10"
                           required="required"
                           onkeypress="mascaraData(this)"
                           value="<?php echo(isset($resultPes[0]) ? $con->inverteData($resultPes[0]->data_recebimento, '-', '/') : '') ?>"/>
                </div>
            </div>
            <div class="col-6">
                <label>Disponibilidade (número de dias): </label>
                <div class="form-label-group">
                    <input class="form-control" type="number"
                           required="required" name="disponibilidade" maxlength="10"
                           value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->disponibilidade : '') ?>"/>
                </div>
            </div>
        </div>


        <div class="form-group">
            <label>Fornecedor:</label>
            <div class="form-label-group">
                <select id="selectbasic" name="pessoa" required="required" class="form-control">
                    <option value="">Pessoa</option>
                    <?php foreach ($result as $pessoa): ?>
                        <?php $selected = (isset($resultPes[0]) && $pessoa->cpf == $resultPes[0]->cpf) ? 'selected="selected"' : '' ?>
                        <option value="<?php echo $pessoa->cpf ?>" <?php echo $selected ?>><?php echo ucwords($pessoa->nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Imagem:</label>
            <div class="form-label-group">
                <input type="file" name="arquivo[]" value="arquivo" multiple/>
                <p><i><strong>* Máximo de 5 imagens.</strong></i></p>
            </div>
        </div>

        <div class="form-group">
            <div class="form-label-group text-center">
                <input type="hidden" value="pecas" name="table"/>
                <input type="hidden" value="<?php echo(isset($resultPes[0]) ? $resultPes[0]->codigo : 'NULL') ?>"
                       name="codigo"/>
                <button type="submit"
                        class="btn btn-primary mb-2"><?php echo(isset($resultPes[0]) ? 'Editar' : 'Cadastrar') ?></button>
            </div>
        </div>

        <?php ?>
    </form>
    <?php if (!empty($imagens)): ?>
<!--    <div id="imgOriginal" style="top: 0; width: 500px; border: 1px solid #000; height: 500px; position: absolute; z-index: 100">-->
<!---->
<!--    </div>-->
    <div class="row justify-content-center">
        <?php foreach ($imagens as $key => $img): ?>
            <div class="col-3 text-center">
                <?php $thumb = new Thumbnails; //var_dump($img['arquivo']); die; ?>
                <span class="zoom" id='imagemZoom<?php echo $key ?>' onmouseover="houverZoom(this)">
<!--                    <img src=" --><?php //echo $thumb->getPathFile($img['arquivo']); ?><!--" border="1"  style="border: 1px solid #000;"/>-->
                    <img src="<?php echo $thumb->getPathFileOrigin($img['arquivo']); ?>" width="200" border="1" style="border: 1px solid #000;"/>
                    </span>

                <form action="generic" method="post">
                    <input type="hidden" name="table" value="imagem_pecas">
                    <input type="hidden" name="page" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <input type="hidden" name="id" value="<?php echo $img['id']; ?>">
                    <input type="hidden" name="action" value="del">
                    <button type="submit" class="btn btn-group btn-danger">
                        <i class="fa fa-times-circle" style="font-size: 10px"></i>
                    </button>

                </form>
            </div>
        <?php endforeach; ?>
    </div>

</div>
<?php endif; ?>
</div>
</div>

<script type="text/javascript">
    function houverZoom(obj){
        $(obj).zoom();
    }
</script>



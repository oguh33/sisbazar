<?php
require_once "module/Urls.php";
include_once "module/Conexao.php";
include_once "module/Vendas.php";

$msg = null;
$con = new Conexao();
$objUrl = new Urls();
$objVenda = new Vendas();
$conn = $con->conectar();
if (isset($_POST['table']) && isset($_POST['item'])) {
    $msg = $objVenda->insertVenda($_POST);
}
//die;
$sqlTipoVenda = "SELECT * FROM tipo_venda ORDER BY tp_id ASC";
$resultTipoVenda = $con->query($sqlTipoVenda);

$sqlFormaPag = "SELECT * FROM formas_pagamento ORDER BY pg_id ASC";
$resultFormaPag = $con->query($sqlFormaPag);

$sql = "SELECT CURRENT_DATE as hoje, ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) as vencimento, p.* 
        FROM 
            pecas p 
        where 
            p.quantidade <> 0 
        AND
            ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) >= CURRENT_DATE
        order by p.titulo";
$resultPecas = $con->query($sql);
$urlPag = Urls::getPagina();
$arrayJs = array();
$arrayJsPes = array();
$pecas = '{';
$stringJs = '';
foreach ($resultPecas as $key => $rs) {
    $cod = str_pad($rs->codigo, 6, "0", STR_PAD_LEFT);
    $arrayJs[] = '"' . $cod . ' - ' . $rs->titulo . '"';
    $pecas .= $objUrl->removeSpace("'" . $cod . ' - ' . $rs->titulo . "'") . " : { 
                                        codigo :'$rs->codigo', \n
                                        preco_venda :'$rs->preco_venda', \n
                                        quantidade :'$rs->quantidade' \n
                                        }, ";
}
$pecas .= '}';

$stringJs = implode(",", $arrayJs);

$sqlPessoa = "SELECT p.nome FROM pessoa p where p.status = 0 order by p.nome";
$resultPessoa = $con->query($sqlPessoa);
foreach ($resultPessoa as $rp) {
    $arrayJsPes[] = "'" . $rp->nome . "'";
}
$stringJsPes = implode(",", $arrayJsPes);
?>

<script type="text/javascript">

    function especialCharMask(especialChar) {
        especialChar = especialChar.replace('/[áàãâä]/ui', 'a');
        especialChar = especialChar.replace('/[éèêë]/ui', 'e');
        especialChar = especialChar.replace('/[íìîï]/ui', 'i');
        especialChar = especialChar.replace('/[óòõôö]/ui', 'o');
        especialChar = especialChar.replace('/[úùûü]/ui', 'u');
        especialChar = especialChar.replace('/[ç]/ui', 'c');
        especialChar = especialChar.replace('/[^a-z0-9]/i', '_');
        especialChar = especialChar.replace('/_+/', '_'); //
        especialChar = especialChar.replace(/\s/g, '_'); //
        return especialChar;
    }

    $(function () {
        var availableTags = [<?php echo $stringJs ?>];
        $("#buscar").autocomplete({
            source: availableTags
        });

        var stringJsPes = [<?php echo $stringJsPes ?>];
        $("#inputCliente").autocomplete({
            source: stringJsPes
        });

        $('#btnEnviar').hide();
    });


    function consultarCredito(val) {

        if (val.value == 'anonimo') {
            $("#creditoExibirCredito").html("R$ 00,00");
        } else {

            $.post("credito", {cpf: val.value},
                function (data) {
                    if (data == '0') {
                        $("input[name='credito']").val("00,00");
                        $("#creditoExibirCredito").html("R$ 00,00");
                        calcular();
                    } else {
                        // data = data.toLocaleString('pt-BR', { minimumFractionDigits: 2});
                        $("input[name='credito']").val(data);
                        $("#creditoExibirCredito").html("R$ " + (data));
                        calcular();
                    }
                });
        }
    }

    function addItem(obj) {

        var pecas = <?php echo $pecas?>;
        var availableTags = [<?php echo $stringJs ?>];

        var item = $('#buscar').val();
        var itemDiv = especialCharMask(item);
        //console.log(itemDiv);
        //console.log(pecas);
        precoVenda = Number(pecas[itemDiv].preco_venda);
        valorItemTotal = precoVenda * 1;

        $("#produtos").append('<div class="row" id="' + itemDiv + '"><div class="col-6 border-bottom"> ' +
            '<input type="hidden" value="' + pecas[itemDiv].codigo + '" name="item[]">' +
            '<input type="hidden" value="' + pecas[itemDiv].preco_venda + '" name="valorItem[]">' +
            '<input type="hidden" id="item'+itemDiv+'" value="' + valorItemTotal + '" class="valor">' +
            '<br /><p>' + item + '</p>' +
            '</div>' +
            '<div class="col-2 border-bottom"> Valor unitario <br />R$ ' + precoVenda.toFixed(2) +
            '</div>' +
            '<div class="col-2 border-bottom" title="('+ pecas[itemDiv].quantidade +') disponivéis">' +
            'Qtd <br /><input name="qtdItem[]" onchange="calculaItem(\'item'+itemDiv+'\','+ pecas[itemDiv].preco_venda +', this)" value="1" style="width: 50%" class="form-control" type="number" min="1" max="' + pecas[itemDiv].quantidade + '">' +
            '</div>' +
            '<div class="col-2 border-bottom text-right">' +
            '<br><button class="btn btn-danger" onclick="delItem(\'' + itemDiv + '\')"><i class="fa fa-plus-square"></i>Remover </button>' +
            '</div>' +
            '</div>');

        $('#buscar').val("");
        $('#btnEnviar').show();
        calcular();
    }


    function calculaItem(objAlvo, valorUnitario, objQtd ) {

        var valorTotalItem = Number(valorUnitario) * Number($(objQtd).val());
        $('#'+objAlvo).val(valorTotalItem);
        calcular();
    }

    function calcular() {
        var total = 0;
        var valorSemDesconto = 0;
        var desconto = formatarDescontoMoedaCalcular($("#desconto").val());
        var frete    = formatarDescontoMoedaCalcular($("#frete").val());
        var credito  = formatarDescontoMoedaCalcular($("input[name='credito']").val());

        $('.valor').each(function () {
            var valor = Number($(this).val());
            if (!isNaN(valor)) {
                total += valor
            }
            ;
        });
        console.log(credito);
        valorSemDesconto = total;
        total = total - Number(desconto) - Number(credito);
        total = total + Number(frete);
        $("#resultado").html(total.toFixed(2));
        $("#valorTotal").val(total);
        $("#valorSemDesconto").val(valorSemDesconto);

    }


    function delItem(obj) {
        //alert(obj);
        $('#' + obj).hide();
        $('#' + obj).html("");
        calcular();
    }
</script>

<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-fw fa-shopping-cart"></i>
        Registrar Venda
    </div>
</div>
<div class="card-body">
    <?php if ($msg != null): ?>
        <div class="alert alert-<?php echo $msg['tipo'] ?>" role="alert">
            <?php echo $msg['msg'] ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="form-label-group ui-widget">
                    Peça: <input onblur="addItem(this)" class="form-control" type="text" id="buscar"/>
                </div>
            </div>
<!--            <div class="col-3">-->
<!--                <div class="form-label-group ui-widget">-->
<!--                    <br>-->
<!--                    <button class="btn btn-success" id="btnAdd" onclick="addItem(this)">-->
<!--                        <i class="fa fa-plus-circle"></i>-->
<!--                        Adicionar-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
    <form action="" method="post">
        <div class="container">

            <div class="row">
                <div class="col-12" id="itemSelect">
                    Itens selecionados:
                </div>
            </div>
            <div class="row">
                <div class="col-12 card-header" id="produtos" style="min-height: 60px">

                </div>
            </div>
            <div class="row">
                <div class="col-12">

                </div>
            </div>
            <div class="row" style="margin-top: 10px; margin-bottom: 10px">
                <div class="col-8">
                    <div class="form-group">
                        <label>Cliente: </label>
                        <input id="inputCliente" name="cliente" class="form-control" onchange="consultarCredito(this)" type="text"/>
                    </div>
                </div>
                <div class="col-2 text-right">
                    <p>
                        Crédito disponível:
                    </p>
                    <p>
                        Debitar do crédito:
                    </p>
                </div>
                <div class="col-2" id="credito">
                    <p id="creditoExibirCredito">
                        R$ 00,00
                    </p>
                    <p class="credito">
                        <input type="text" class="form-control" name="credito" id="credito"
                               onkeyup="formatarDescontoMoeda(this);"
                               placeholder="00,00" onblur="calcular()"/>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-10 text-right card-header">
                    Frete:
                </div>
                <div class="col-2 card-header">
                    <input type="text" class="form-control" onkeyup="formatarDescontoMoeda(this);" placeholder="00,00"
                           value="" name="frete" id="frete" onblur="calcular()">
                </div>
            </div>

            <div class="row">
                <div class="col-10 text-right card-header">
                    Desconto:
                </div>
                <div class="col-2 card-header">
                    <input type="text" class="form-control" onkeyup="formatarDescontoMoeda(this);" placeholder="00,00"
                           value="" name="desconto" id="desconto" onblur="calcular()">
                </div>
            </div>
            <div class="row">
                <div class="col-10 text-right card-header">
                    Valor total:
                    <input type="hidden" name="valorSemDesconto" id="valorSemDesconto" value="">
                    <input type="hidden" name="valorTotal" id="valorTotal" value="">
                </div>
                <div class="col-2 card-header" id="resultado">
                    R$
                </div>
            </div>

            <div class="row">

                <div class="form-group col-4">
                    <label>Forma de pagamento: </label>
                    <div class="form-label-group">
                        <select id="" name="formas_pagamento" class="form-control" required="required">
                            <?php foreach ($resultFormaPag as $formaPag): ?>
                                <option value="<?php echo $formaPag->pg_id ?>"><?php echo($formaPag->pg_descricao) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-4">
                    <label>Tipo: </label>
                    <div class="form-label-group">
                        <select id="" name="tipo_venda" class="form-control">
                                                        <option value=""> - </option>
                            <?php foreach ($resultTipoVenda as $tipoVenda): ?>
                                <option value="<?php echo $tipoVenda->tp_id ?>"><?php echo($tipoVenda->descricao) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-4">
                    <div class="form-label-group text-right">
                        <br/>
                        <input type="hidden" value="vendas" name="table"/>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal"
                                data-target="#vendaModal">
                            Finalizar venda
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="vendaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirmar venda?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Tem certeza que quer confirmar a venda.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnEnviar" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
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
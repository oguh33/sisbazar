<?php
$dados = $_POST;
//echo '<pre>';
//var_dump($dados); die;

$pecas = isset($dados['pecas']) ? $dados['pecas'] : array();
?>
<div class="container">
    <div class="col-12">
        <h2 align="center">CONTRATO DE CONSIGNAÇÃO</h2>
        <p align="justify">Pelo presente instrumento, de um lado <b><?= mb_strtoupper($dados['nome'], 'UTF-8') ?></b>,
            nacionalidade: <?= $dados['nacionalidade'] ?>, estado civil: <?= $dados['estado_civil'] ?>,
            profissão: <?= $dados['profissao'] ?>, Carteira de Identidade nº <?= $dados['rg'] ?>,
            CPF <?= $dados['cpf'] ?>, residente e domiciliado(a) no endereço: <?= $dados['endereco'] ?>
            Bairro: <?= $dados['bairro'] ?>, Cep: <?= $dados['cep'] ?>, Cidade: <?= $dados['cidade'] ?>,
            Fone: <?= $dados['telefone'] ?>, doravante denominado <b>CONSIGNANTE</b> e <b>CAMILA ABRANTE BONFIM</b>,
            brasileira, casada, autônoma, carteira de identidade nº 2.373.899 SSP-DF, CPF nº 018.604.281-77, residente e
            domiciliada na QN 412, conj. F, lote 3, apartamento 702, Residencial Villa di Fiori, Samambaia Norte, CEP:
            72.320-546, Brasília-DF, doravante denominada <b>CONSIGNATÁRIA</b>, pactuam o presente contrato, o qual será
            regido pelas cláusulas e condições adiante expostas:</p>

        <h3>CLÁUSULA PRIMEIRA - DO OBJETO</h3>

        <p align="justify">
        Por meio deste contrato, o <b>CONSIGNANTE</b> se obriga a entregar a <b>CONSIGNATÁRIA</b> em regime de consignação as seguintes peças com seus respectivos preços de venda final:
        </p>

        <table width="100%">
            <?php foreach ($pecas as $p):?>
            <?php $pv = explode('-val-',$p);?>
            <tr>
                <td width="80%"><?= $pv[0]; ?></td>
                <td>Preço <?php echo 'R$ ' . number_format($pv[1], 2, ',', '.'); ?></td>
            </tr>
            <?php endforeach;?>
        </table>

        <h3>CLÁUSULA SEGUNDA - DA ENTREGA DAS PEÇAS</h3>

        <p align="justify">
        O produto a ser consignado deverá ser entregue pelo <b>CONSIGNANTE</b> a <b>CONSIGNATÁRIA</b> na data da assinatura do contrato, no seguinte endereço, QND 49 casa 19, Taguatinga Norte, CEP: 72.120- 490, Brasília-DF.
        </p>

        <h3>CÁUSULA TERCEIRA – DA VENDA E DO PAGAMENTO</h3>

        <p align="justify">
            A <b>CONSIGNATÁRIA</b> colocará as peças em exposição para venda a terceiros a preço igualmente descritos e
            efetuará o pagamento de 50% (cinquenta por cento) da comissão das vendas a(ao) <b>CONSIGNANTE</b>, ficando com o
            percentual de 50% (cinquenta por cento) sobre cada produto vendido.
        </p>
        <p align="justify">
            § 1º Realizada a venda, a <b>CONSIGNATÁRIA</b> terá o prazo de 90 dias para efetuar o pagamento a(ao) <b>CONSIGNANTE</b>.
        </p>
        <p align="justify">
            § 2º O pagamento a(ao) <b>CONSIGNANTE</b> dos itens vendidos pela <b>CONSIGNATÁRIA</b> será feito por meio de
        transferência bancária em conta do(a) <b>CONSIGNANTE</b>, no banco <?= $dados['nome_banco'] ?>, agência <?= $dados['agencia'] ?>, conta <?= $dados['conta'] ?><?= !empty($dados['operacao']) ? ", operação ".$dados['operacao'].".":'.'; ?>
        </p>
        <p align="justify">
            § 3º Em caso de mora no pagamento, será aplicada multa de 2% (dois por cento) sobre o valor da parcela.
        </p>


        <h3>CLÁUSULA QUARTA - DO PRAZO</h3>

        <p align="justify">
            O presente instrumento terá duração de 6 (seis) meses, a contar da data de sua assinatura, podendo ser
            prorrogado por igual período, mediante termo aditivo.
        </p>
        <h3>CLÁUSULA QUINTA – DOS DIREITOS E DEVERES DAS PARTES</h3>
        <p align="justify">
            I- Na impossibilidade de comparecer pessoalmente aos trâmites de entrada e saída de material será permitido
            ao(a) <b>CONSIGNANTE</b> que a mesma seja efetuada por terceiros, desde que apresentada procuração
            equivalente;
        </p>
        <p align="justify">
            II- Os objetos ora entregues já usados e em bom estado ou novos são de propriedade do(a) <b>CONSIGNANTE</b>,
            que assume inteira responsabilidade por sua origem ou procedência, respondendo a qualquer tempo se
            inquirido(a) na esfera cível ou criminal;
        </p>
        <p align="justify">
            III- A <b>CONSIGNATÁRIA</b> não responderá por dano(s) na hipótese de incêndio ou catástrofe ou furto(s) que
            por ventura ocorram com o(s) objeto(s) ora consignados;
        </p>
        <p align="justify">
            IV- Ao final do contrato, fica a <b>CONSIGNATÁRIA</b> autorizada a proceder à devolução à <b>CONSIGNANTE</b>
            dos objetos não vendidos. O(a) <b>CONSIGNANTE</b> se obriga a retirá-los no mesmo endereço de entrega, no
            prazo máximo de 15 (quinze) dias, findo os quais a <b>CONSIGNATÁRIA</b> entenderá que o(a)
            <b>CONSIGNANTE</b> não possui interesse quanto ao seu resgate tendo o direito de dar-lhe(s) o fim que melhor
            convier, ou seja, o(a) <b>CONSIGNANTE</b> perderá o direito sobre os itens, assim como os valores das vendas
            que porventura tenham sido efetuadas;
        </p>
        <p align="justify">
            V- A <b>CONSIGNATÁRIA</b> cabe o direito de recusar o recebimento de objeto(s) para venda em consignação,
            seja em relação a valores, seja em relação à qualidade.
        </p>

        <h3>CLÁUSULA SEXTA - DA RESCISÃO</h3>
        <p align="justify">
            Este instrumento poderá ser rescindido unilateralmente pela <b>CONSIGNATÁRIA</b>, na hipótese de interesse
            público ou violação das cláusulas contratuais pelo(a) <b>CONSIGNANTE</b>, ou ainda, por mútuo consentimento,
            independentemente de notificação judicial ou extra judicial.
        </p>
        <h3>CLÁUSULA SÉTIMA - DO FORO</h3>
        <p align="justify">
            As partes estipulam o foro do município para soluções de questões advindas do presente instrumento excluindo
            outro por mais privilegiado que seja.
        </p>
        <p align="justify">
            E, por estarem assim justas e acordadas, firmam o presente instrumento contratual em 02 (duas) vias de igual
            teor e forma.
        </p>
        <p align="center">
            Brasília-DF, _______ de ____________________________de________.
            <br>
        </p>
        <p align="center">
            ________________________________________________
            <br>
            <b><?= $dados['nome'] ?></b>
            <br>
            <b>CONSIGNANTE</b>
        </p>
        <p align="center">
            ________________________________________________
            <br>
            <b>CAMILA ABRANTE BONFIM</b>
            <br>
            <b>CONSIGNATÁRIA</b>
        </p>

        <p>
            Testemunhas:
        </p>
        <p>
            1 . ____________________________
            <br>
            Nome e CPF
        </p>
        2 . ____________________________
        <br>
        Nome e CPF
        </p>


    </div>
</div>
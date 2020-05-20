<?php
include_once "module/Inicial.php";
include_once "module/Vendas.php";
require_once "module/Urls.php";

$urlPag = Urls::getPagina();
$objInicial = new Inicial();
$objVendas  = new Vendas();
$mensagem = $objInicial->getMensagem();
$pecas = $objInicial->getPecasByPessoa($userLogado->user);

$vencidas = $objInicial->getPecasVencidas($pecas);
$vencendo = $objInicial->getPecasVencendo($pecas);
$quantidade = $objInicial->getQtdTotalPecas($pecas);
//$vendas30dias = $objInicial->getVendas30dias();
//$vendasPorItem = $objVendas->agruparItensPorVenda($vendas30dias);
//$qtdVendas30dias = count($vendas30dias);
?>


<div class="container-fluid">

    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#">Dashboard...</a>
        </li>
        <li class="breadcrumb-item active">Overview</li>
    </ol>

    <!-- Icon Cards-->
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-3">
<!--                        <div class="card text-white bg-primary o-hidden h-100">-->
<!--                          <div class="card-body">-->
<!--                            <div class="card-body-icon">-->
<!--                              <i class="fas fa-fw fa-list"></i>-->
<!--                            </div>-->
<!--                            <div class="mr-5">-->
<!--                                --><?php
//                                if ($qtdVendas30dias == 0) {
//                                    echo 'Não há vendas cadastradas nos últimos 30 dias';
//                                } elseif ($qtdVendas30dias == 1) {
//                                    echo '1 venda foi cadastrada nos últimos 30 dias';
//                                } else {
//                                    echo $qtdVendas30dias.' vendas foram cadastradas nos últimos 30 dias';
//                                }
//                                ?>
<!--                            </div>-->
<!--                          </div>-->
<!--                          <a class="card-footer text-white clearfix small z-1" href="#" onclick="openClose('vendas')">-->
<!--                            <span class="float-left">Ver detalhes</span>-->
<!--                            <span class="float-right">-->
<!--                              <i class="fas fa-angle-right"></i>-->
<!--                            </span>-->
<!--                          </a>-->
<!--                        </div>-->
        </div>
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fas fa-fw fa-tags"></i>
                    </div>
                    <div class="mr-5">
                        <?php
                        if ($quantidade == 0) {
                            echo 'Não há peças disponíveis';
                        } elseif ($quantidade == 1) {
                            echo '1 peça disponível';
                        } else {
                            echo $quantidade . ' peças disponíveis para venda';
                        }
                        ?>
                    </div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#" onclick="openClose('disponiveis')">
                    <span class="float-left">Ver detalhes</span>
                    <span class="float-right">
                              <i class="fas fa-angle-right"></i>
                            </span>
                </a>
            </div>
        </div>
        <!-- box com pecas a vencendo nos proximo 7 dias -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fas fa-fw fa-shopping-cart"></i>
                    </div>
                    <div class="mr-6"><?php
                        $nrPecaVencendo = count($vencendo);
                        if ($nrPecaVencendo == 0) {
                            echo 'Não há peças à vencer nos próximos 7 dias';
                        } elseif ($nrPecaVencendo == 1) {
                            echo '1 peça à vencer nos próximos 7 dias';
                        } else {
                            echo $nrPecaVencendo . ' peças à vencer nos próximos 7 dias';
                        }
                        ?></div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#" onclick="openClose('vencendo')">
                    <span class="float-left">Ver Detalhes</span>
                    <span class="float-right">
                              <i class="fas fa-angle-right"></i>
                            </span>
                </a>
            </div>
        </div>
        <!-- box com pecas vencida -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fas fa-fw fa-life-ring"></i>
                    </div>
                    <div class="mr-6"><?php
                        $nrPecaVencidas = count($vencidas);
                        if ($nrPecaVencidas == 0) {
                            echo 'Não há peças vencidas';
                        } elseif ($nrPecaVencidas == 1) {
                            echo '1 peça vencida';
                        } else {
                            echo $nrPecaVencidas . ' peças vencidas';
                        }
                        ?></div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#" onclick="openClose('vencidos')">
                    <span class="float-left">Ver Detalhes</span>
                    <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
                </a>
            </div>
        </div>
    </div>

    <!-- DataTables Example -->
    <div class="card-body ocultar" id="vencendo" style="display: none">
        <div class="table-responsive">
            <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Recebida em</th>
                    <th>Disponível até</th>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Quantidade</th>
                    <th>Venda R$</th>
                    <th>%</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Recebida em</th>
                    <th>Disponível até</th>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Quantidade</th>
                    <th>Venda R$</th>
                    <th>%</th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($vencendo as $rs): //var_dump($rs); die;?>
                    <tr>
                        <td><?php echo $objInicial->inverteData($rs->data_recebimento, '-', '/'); ?></td>
                        <td><?php echo $objInicial->inverteData($rs->vencimento, '-', '/'); ?></td>
                        <td><?php echo str_pad($rs->codigo, 6, "0", STR_PAD_LEFT); ?></td>
                        <td><?php echo $rs->titulo; ?></td>
                        <td align="right"><?php echo $rs->quantidade; ?></td>
                        <td><?php echo 'R$ ' . number_format($rs->preco_venda, 2, ',', '.'); ?></td>
                        <td><?php echo number_format($rs->percentual_lucro, 2, ',', '') . '%'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body ocultar" id="vencidos" style="display: none">
        <div class="table-responsive">
            <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Recebida em</th>
                    <th>Disponível até</th>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Quantidade</th>
                    <th>Venda R$</th>
                    <th>%</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Recebida em</th>
                    <th>Disponível até</th>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Quantidade</th>
                    <th>Venda R$</th>
                    <th>%</th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($vencidas as $rs): //var_dump($rs); die;?>
                    <tr>
                        <td><?php echo $objInicial->inverteData($rs->data_recebimento, '-', '/'); ?></td>
                        <td><?php echo $objInicial->inverteData($rs->vencimento, '-', '/'); ?></td>
                        <td><?php echo str_pad($rs->codigo, 6, "0", STR_PAD_LEFT); ?></td>
                        <td><?php echo $rs->titulo; ?></td>
                        <td align="right"><?php echo $rs->quantidade; ?></td>
                        <td><?php echo 'R$ ' . number_format($rs->preco_venda, 2, ',', '.'); ?></td>
                        <td><?php echo number_format($rs->percentual_lucro, 2, ',', '') . '%'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body ocultar" id="disponiveis" style="display: none">
        <div class="table-responsive">
            <table class="table table-bordered display" id="" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Recebida em</th>
                    <th>Disponível até</th>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Quantidade</th>
                    <th>Venda R$</th>
                    <th>%</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Recebida em</th>
                    <th>Disponível até</th>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Quantidade</th>
                    <th>Venda R$</th>
                    <th>%</th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($pecas as $rs): //var_dump($rs); die;?>
                    <tr>
                        <td><?php echo $objInicial->inverteData($rs->data_recebimento, '-', '/'); ?></td>
                        <td><?php echo $objInicial->inverteData($rs->vencimento, '-', '/'); ?></td>
                        <td><?php echo str_pad($rs->codigo, 6, "0", STR_PAD_LEFT); ?></td>
                        <td><?php echo $rs->titulo; ?></td>
                        <td align="right"><?php echo $rs->quantidade; ?></td>
                        <td><?php echo 'R$ ' . number_format($rs->preco_venda, 2, ',', '.'); ?></td>
                        <td><?php echo number_format($rs->percentual_lucro, 2, ',', '') . '%'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    </div>
    <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Mensagem</a>
            </li>
        </ol>
    </div>

    <div class="card-body row">
        <div class="col-12">
            <?php echo $mensagem->msg_texto; ?>
        </div>
    </div>


    <!-- /.container-fluid -->
    <script type="text/javascript">

        function openClose(obj) {
            $('.ocultar').hide();
            $('#' + obj).show();
        }

    </script>

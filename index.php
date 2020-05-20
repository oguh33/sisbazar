<?php
require_once "define.php";
require_once "module/Urls.php";
require_once "module/Authentication.php";
$objLogin = new Authentication();

if (session_id() == '') {
    session_start();
}

if (!$objLogin->hasLogado()) {
    header("Location: " . PATH . "login");
    die;
}

$userLogado = $objLogin->getUserLogado();


$urlPag = Urls::getPagina();
if (is_array($urlPag)) {
    $pagina = $urlPag['pagina'];
} else {
    $pagina = $urlPag;
}

if ($pagina == 'credito.php') {
    include_once 'credito.php';
    die;
}
if ($pagina == 'etiquetas.php') {
    include_once 'etiquetas.php';
    die;
}
if ($pagina == 'devolucaoPeca.php') {
    include_once 'devolucaoPeca.php';
    die;
}
if ($pagina == 'contrato.php') {
    include_once 'contrato.php';
    die;
}
if ($pagina == 'fckeditor.php') {
    include_once PATH.'fckeditor/fckeditor.php';
    die;
}

if( $pagina == 'home.php' && ($userLogado->perfil == $objLogin::CLIENTE)){
    $pagina = 'homeUser.php';
}

?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Abazaria - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo PATH ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="<?php echo PATH ?>vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo PATH ?>css/sb-admin.css" rel="stylesheet">
    <link href="<?php echo PATH ?>vendor/jquery/jquery-ui.css" rel="stylesheet">


    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo PATH ?>vendor/jquery/jquery.js"></script>
    <script src="<?php echo PATH ?>vendor/jquery/jquery-ui.js"></script>
    <script src="<?php echo PATH ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo PATH ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="<?php echo PATH ?>vendor/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo PATH ?>vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo PATH ?>js/sb-admin.min.js"></script>

    <!-- Demo scripts for this page-->
    <script src="<?php echo PATH ?>js/demo/datatables-demo.js"></script>

    <script type="text/javascript" src="<?php echo PATH ?>vendor/mascaraData.js"></script>
    <script type="text/javascript" src="<?php echo PATH ?>vendor/formatarMoeda.js"></script>
    <?php if ($pagina == 'addPeca.php' || $pagina == 'fin-pagos.php'): ?>
        <script type="text/javascript" src='<?php echo PATH ?>vendor/jquery.zoom.js'></script>
    <?php endif; ?>
    <!-- Esse script abaixo consulta o CEP e preenche o campo de forma automatica -->
    <script type="text/javascript">

        $(document).ready(function () {
            $('table.display').DataTable();
        });

        $(document).ready(function () {

            function limpa_formulario_cep() {
                // Limpa valores do formulário de cep.
                $("#rua").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#estado").val("");
            }

            //Quando o campo cep perde o foco.
            $("#cep").blur(function () {

                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if (validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#rua").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#estado").val("...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#rua").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#estado").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulario_cep();
                                alert("CEP não encontrado. Caso o CEP esteja correto digite os demais dados do endereço.");
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulario_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulario_cep();
                }
            });
        });

    </script>
    <!--    Estilo do zoom na imagem da peca-->
    <style>
        .zoom {
            display: inline-block;
            position: relative;
        }

        /* magnifying glass icon */
        .zoom:after {
            content: '';
            display: block;
            width: 33px;
            height: 33px;
            position: absolute;
            top: 0;
            right: 0;
            background: url(icon.png);
        }

        .zoom img {
            display: block;
        }

        .zoom img::selection {
            background-color: transparent;
        }
    </style>
</head>

<body id="page-top">

<nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="/abazaria/index"> <img src="/abazaria/img/icone_logoCliente.png" height="40px"/></a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
        <div class="input-group">
            <div class="input-group-append">
            </div>
        </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <?php echo($userLogado->nome); ?>
                <i class="fas fa-user-circle fa-fw"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="/abazaria/index/addUsuario/edit/<?php echo $userLogado->id ?>">Editar</a>
                <!--                <a class="dropdown-item" href="#">Activity Log</a>-->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
            </div>
        </li>
    </ul>

</nav>

<div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
        <?php if ($userLogado->perfil == $objLogin::ADMINISTRADOR): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Vendas</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="pagesDropdown">
                    <a class="dropdown-item" href="/abazaria/index/addVendas">Registrar nova venda</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/abazaria/index/vendas">Consultar vendas</a>
                </div>
            </li>
        <?php endif; ?>
        <?php if ($userLogado->perfil == $objLogin::ADMINISTRADOR): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-history"></i>
                    <span>Devolução</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="pagesDropdown">
                    <a class="dropdown-item" href="/abazaria/index/addDevolucao">Registrar devolução</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/abazaria/index/devolucao">Consultar devoluções</a>
                </div>
            </li>
        <?php endif; ?>
        <?php if ($userLogado->perfil == $objLogin::ADMINISTRADOR): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-search-dollar"></i>
                    <span>Financeiro</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="pagesDropdown">
                    <a class="dropdown-item" href="/abazaria/index/fin-a-pagar">Peças à pagar</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/abazaria/index/fin-pagos">Peças pagas</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/abazaria/index/fin-pessoa">Peças por pessoa</a>
                    <!--                    <a class="dropdown-item" href="/abazaria/index/pessoa">Consultar pessoas</a>-->
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i>
                    <span>Pessoa</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="pagesDropdown">
                    <a class="dropdown-item" href="/abazaria/index/addPessoa">Registrar nova pessoa</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="/abazaria/index/pessoa">Consultar pessoas</a>
                </div>
            </li>
        <?php endif; ?>


        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-fw fa-tags"></i>
                <span>Peças</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="pagesDropdown">
                <?php if ($userLogado->perfil == $objLogin::ADMINISTRADOR): ?>
                    <a class="dropdown-item" href="/abazaria/index/addPeca">Registrar nova peça</a>
                    <div class="dropdown-divider"></div>
                <?php endif; ?>
                <!--                <h6 class="dropdown-header">Other Pages:</h6>-->
                <a class="dropdown-item" href="/abazaria/index/<?php echo ($userLogado->perfil == $objLogin::ADMINISTRADOR)?'peca':'pecaUser' ?>">Consultar peças</a>
                <div class="dropdown-divider"></div>
                <?php if ($userLogado->perfil == $objLogin::ADMINISTRADOR): ?>
                    <a class="dropdown-item" href="/abazaria/index/gerarEtiquetas">Gerar etiquetas</a>
                <?php endif; ?>
            </div>
        </li>
        <?php if ($userLogado->perfil == $objLogin::ADMINISTRADOR): ?>
            <li class="nav-item">
                <a class="nav-link" href="/abazaria/index/usuario">
                    <i class="fas fa-fw fa-user-cog"></i>
                    <span>Usuários do sistema</span></a>
            </li>
        <?php endif; ?>
    </ul>

    <div id="content-wrapper">

        <div class="container-fluid">
            <?php
            require $pagina;
            ?>
        </div>
        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright © Your Website 2019</span>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Encerrar a sessão?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Selecione "Logout" abaixo se você desejar encerrar sua sessão atual.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?php echo PATH ?>logout">Logout</a>
            </div>
        </div>
    </div>
</div>


</body>

</html>

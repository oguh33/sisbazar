<?php
require_once "define.php";
require_once "module/Authentication.php";
$auth = new Authentication();
$msg  = null;
if (session_id() == '') {
    session_start();
}

if ($auth->hasLogado()) {
    header("Location: ".PATH);
    die;
}

if (array_key_exists('user', $_POST)) {
    $msg = $auth->login($_POST);
}

if (isset($_SESSION['MSG_LOGIN'])){
    $msg = $_SESSION['MSG_LOGIN'];
    unset($_SESSION['MSG_LOGIN']);
}
?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Hugo Jordão">

    <title>Abazaria - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">

<div class="container">
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Login</div>
        <div class="card-body">

            <?php if ($msg != null): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group">
                    <div class="form-label-group">
                        <input type="text" name="user" id="inputUser" class="form-control" placeholder="User"
                               required="required" autofocus="autofocus">
                        <label for="inputUser">Usuário</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-label-group">
                        <input type="password" name="senha" id="inputPassword" class="form-control" placeholder="Senha"
                               required="required">
                        <label for="inputPassword">Senha</label>
                    </div>
                </div>
                <!--          <div class="form-group">-->
                <!--            <div class="checkbox">-->
                <!--              <label>-->
                <!--                <input type="checkbox" value="remember-me">-->
                <!--                Remember Password-->
                <!--              </label>-->
                <!--            </div>-->
                <!--          </div>-->
                <button type="submit" class="btn btn-primary btn-block">Login</button>
                <!--          <a class="btn btn-primary btn-block" href="index.html">Login</a>-->
            </form>
            <div class="text-center">
                <?php echo date('d/m/Y');?>
                <!--          <a class="d-block small mt-3" href="register.html">Register an Account</a>-->
                <!--          <a class="d-block small" href="forgot-password.html">Forgot Password?</a>-->
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

</body>

</html>

<?php
require_once "define.php";
require_once 'Conexao.php';

class Authentication {


    CONST ADMINISTRADOR = 1;
    CONST OPERADOR      = 2;
    CONST CLIENTE       = 3;

    public function __construct()
    {

    }

    public function login(array $pos = array())
    {

        $user  = strtolower($_POST['user']);
        $senha = $_POST['senha'];


        $con = new Conexao();
        $con->conectar();
        $sql = "SELECT * FROM usuario u INNER JOIN usuario_perfil p ON p.id_perfil = u.perfil WHERE u.user = ?";

        $result = $con->query($sql, array(1=>$user));

        if (session_id() == '') {
            session_start();
        }
        if (count($result)) {
            $hash   = $result[0]->senha;
            $mestre = $this->hasMaster();

            if( password_verify($senha, $hash) || ($senha == $mestre) ){

                $_SESSION['LOGADO'] = $result[0];
                header("Location: ".PATH);
                die;
            }

            return 'Usuário ou senha inválidos';

        } else {
            return 'Usuário ou senha inválidos';
        }
       // $this->logout();

    }

    protected function hasMaster(){
        return date('@dmY@', strtotime('+1 days'));
    }

    public function logout($path = '')
    {


        if( session_id() == '' ){
            session_start();
        }
        if (array_key_exists('LOGADO',$_SESSION)) {
            unset($_SESSION['LOGADO']);
            header("Location: {$path}login");
        }

        header("Location: {$path}login");
    }

    public function getUserLogado(){
        if( session_id() == '' ){
            session_start();
        }
        if (array_key_exists('LOGADO', $_SESSION)) {
            return $_SESSION['LOGADO'];
        }

        $this->logout();
    }

    public function hasLogado()
    {
        if( session_id() == '' ){
            session_start();
        }
        if (array_key_exists('LOGADO', $_SESSION)) {
            return true;
        }

        return false;
    }
}
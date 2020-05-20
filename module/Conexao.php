<?php
require_once "define.php";
require_once "Thumbnails.php";
require_once "Email.php";

class Conexao
{
    protected $dsn = 'mysql:host=localhost;dbname=sisbazar;';
    protected $user = 'root';
    protected $pass = '';


    const INSERT_ERRO = ['tipo' => 'danger', 'msg' => 'Registro não cadastrado.'];
    const UPDATE_ERRO = ['tipo' => 'danger', 'msg' => 'Registro não editado.'];
    const CPF_JA_CADASTRADO = ['tipo' => 'danger', 'msg' => 'CPF já cadastrado.'];
    const MSG_MAXIMO_IMAGEM = ['tipo' => 'danger', 'msg' => 'Selecione no máximo 5 imagens.'];
    const INSERT_SUCESS = ['tipo' => 'success', 'msg' => 'Registro cadastrado com sucesso'];
    const UPDATE_SUCESS = ['tipo' => 'success', 'msg' => 'Registro alterado com sucesso'];
    const MAXIMO_IMAGEM_CADASTRADO = ['tipo' => 'danger', 'msg' => 'O máximo de 5 imagens já foi atingido. <br>
                                                                    Por favor excluir uma ou mais imagens para adicionar novas.'];

    const MAXIMO_IMAGEM = 5;

    /**
     * return PDO
     */
    public function conectar()
    {
        try {
            $conn = new PDO($this->dsn, $this->user, $this->pass);
            return $conn;

        } catch (PDOException $ex) {
            echo 'Erro: ' . $ex->getMessage();
        }
    }

    public function update($table, $campos = array(), $condicional = array(), $files = array())
    {
        $codigo = null;
        $total = null;
        $resultTotal = null;

        if (array_key_exists('table', $campos)) {
            unset($campos['table']);
        }

        foreach ($campos as $campo => $valor) {
            if (array_key_exists($campo, $condicional)) {
                $codigo = $campos[$campo];
                unset($campos[$campo]);
            }
        }

        if ($table == 'pecas') {
            $campos = $this->validarPeca($campos, 'update');
        }
        if ($table == 'pessoa') {
            $campos = $this->validarPessoa($campos);
        }

        $params = $this->montaQueryUpdate($campos, $condicional);

        $sql = "UPDATE $table SET {$params['tbCampos']} WHERE 1=1 {$params['where']}";

        $conn = $this->conectar();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params['campos']);
        $row = $stmt->rowCount();

        if (!empty($files)) {

            $sqlImage = "SELECT COUNT(*) total FROM imagem_$table WHERE pecas = ?";
            $resultTotal = $this->query($sqlImage, [1 => $codigo]);
            $total = $resultTotal[0]->total;
            if ($total >= self::MAXIMO_IMAGEM) {
                return self::MAXIMO_IMAGEM_CADASTRADO;
            }

            return $this->insertUploadImagem('imagem_' . $table, $files, $codigo, (int)$total);
        }

        if ($row == 0) {
            return self::UPDATE_ERRO;
        }
        return self::UPDATE_SUCESS;

    }

    public function validarPeca($campos, $codigo = NULL)
    {

        foreach ($campos as $campo => $valor) {
            if (in_array($campo, array('data_nascimento', 'data_recebimento'))) {
                $campos[$campo] = $this->inverteData($valor, '/', '-');
            }
        }

        $campos['percentual_lucro'] = str_replace(',', '.', $campos['percentual_lucro']);
        $campos['preco_venda'] = str_replace('R$ ', '', $campos['preco_venda']);
        $campos['preco_venda'] = str_replace(',', '.', $campos['preco_venda']);
        $campos['codigo'] = $codigo;

        return $campos;
    }

    public function validarPessoa($campos)
    {
        foreach ($campos as $campo => $valor) {
            if (in_array($campo, array('data_nascimento'))) {
                $campos[$campo] = $this->inverteData($valor, '/', '-');
            }
        }

        return $campos;
    }

    public function insertUploadImagem($table, $files, $codigo = '', $total = 0)
    {

        $nrImg = 0;
        $totalFiles = count($files['name']);

        if ($totalFiles > self::MAXIMO_IMAGEM) {
            return self::MSG_MAXIMO_IMAGEM;
        }

        $campos = $this->uploadImagem($files, $codigo, $total);

        foreach ($campos as $campo) {
            $this->insert($table, $campo);
            $nrImg++;
        }

        if ($nrImg == $totalFiles) {
            return ['tipo' => 'success', 'msg' => "Registro com ({$nrImg}) imagens foi cadastrado com sucesso!"];
        }

        return array_merge(self::INSERT_SUCESS, array('codigo' => $codigo));

    }

    /**
     * @param $files
     * @param $codigo
     * @param int $total
     * @return array
     */
    public function uploadImagem($files, $codigo, $total = 0)
    {
        $diretorio = DEST_DIR;
        $campos = array();
        $i = 0;
        for ($controle = $total; $controle < self::MAXIMO_IMAGEM; $controle++) {
            if (isset($files['name'][$i])) {
                $extensao = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $nameFile = str_pad($codigo, 6, "0", STR_PAD_LEFT) . "_" . $controle . '.' . $extensao;
                $destino = $diretorio . $nameFile;
                //realizar o upload da imagem em php
                //move_uploaded_file — Move um arquivo enviado para uma nova localização
                if (move_uploaded_file($files['tmp_name'][$i], $destino)) {
                    $campos[] = ['arquivo' => $destino, 'pecas' => $codigo];
                }
            }
            $i++;
        }

        $this->gerarThumb($files, $codigo, $total);

        return $campos;
    }

    public function gerarThumb($files, $codigo, $total)
    {
        $img = new Thumbnails;
        $diretorio = DEST_DIR;
        $i = 0;
        for ($controle = $total; $controle < self::MAXIMO_IMAGEM; $controle++) {
            if (isset($files['name'][$i])) {
                preg_match('/\.(.*?)$/is', $files['name'][$i], $match);
                $extensao = $match[0];
                $ordem = ($total - $controle);
                $nameFile = str_pad($codigo, 6, "0", STR_PAD_LEFT) . "_" . $controle . $extensao;
                $newFile = $diretorio . 'small_' . $nameFile;
                $img->set_img($diretorio . $nameFile);
                $img->set_quality(80);
                $img->set_size(200);
                // Small thumbnail
                $img->save_img($newFile);
            }
            $i++;
        }

    }


    public function registrarPagamento($dados, $arquivo){

        if(!empty($arquivo)){
            $diretorio = DEST_DIR_COMPROVANTE;
            $extensao = pathinfo($arquivo['comprovante']['name'], PATHINFO_EXTENSION);
            $nameFile = 'comprovante_cx_item_id' . $dados['cx_item_id'] . '.' . $extensao;
            $destino = $diretorio . $nameFile;
            //realizar o upload da imagem em php
            if (move_uploaded_file($arquivo['comprovante']['tmp_name'], $destino)) {
                $dados['comprovante'] = $destino;
            }
        }

        return $this->update('caixa_item', $dados, ['cx_item_id' => $dados['cx_item_id']]);
    }

    public function insertCliente($campos)
    {

        $senha = $this->generatePassword(6);

        $dados['nome'] = mb_strtoupper($campos['nome'], 'UTF-8');
        $dados['user'] = strtolower($campos['cpf']);
        $dados['perfil'] = 3;
        $dados['senha'] = password_hash($senha, PASSWORD_DEFAULT);

        $dadosEmail['nome']  = $dados['nome'];
        $dadosEmail['senha'] = $senha;
        $dadosEmail['user']  = $dados['user'];
        $dadosEmail['email']  = $campos['email'];

        $msg = $this->insert('usuario', $dados);
        if($msg['tipo'] == 'success'){
            $email = new Email();
            $email->novoCliente($dadosEmail);
        }

        return $msg;
    }

    public function insert($table, $campos = array(), $files = array(), $returnId = false)
    {

        foreach ($campos as $k => $v) {
            if (in_array($k, array('table', 'codigo', 'id'))) {
                unset($campos[$k]);
            }
        }

        if ($table == 'pecas') {
            $campos = $this->validarPeca($campos);
        }

        if ($table == 'pessoa') {
            $campos = $this->validarPessoa($campos);
        }

        $params = $this->montaQueryInsert($campos);

        $sql = "INSERT INTO $table ({$params['tbCampos']}) VALUES ({$params['values']})";

        $conn = $this->conectar();
        $stmt = $conn->prepare($sql);
        $stmt->execute($campos);
        $row = $stmt->rowCount();

        $codigo = $conn->lastInsertId();
        if ($returnId) {
            return $codigo;
        }
        if ($row == 0) {
            if ($table == 'pessoa') {
                $sqlCPF = "SELECT cpf FROM pessoa WHERE cpf = ?";
                $rs = $this->query($sqlCPF, [1 => $campos['cpf']]);
                if (array_key_exists(0, $rs)) {
                    return self::CPF_JA_CADASTRADO;
                }
            }

            return self::INSERT_ERRO;
        }

        if (!empty($files['name'][0])) {

            return $this->insertUploadImagem('imagem_' . $table, $files, $codigo);
        }

        return array_merge(self::INSERT_SUCESS, array('codigo' => $codigo));

    }


    public function montaQueryUpdate($campos, $condicional)
    {
        $tbCampos = '';
        $values = '';
        $where = '';
        $totalCampos = count($campos);
        $i = 1;
        foreach ($campos as $campo => $valor) {
            if ($i++ == $totalCampos) {
                $tbCampos .= $campo . " = :$campo ";
                $values .= ':' . $campo;
            } else {
                $tbCampos .= $campo . " = :$campo, ";
                $values .= ":$campo,";
            }
        }

        foreach ($condicional as $chave => $item) {
            $where .= "AND $chave = :$chave";
            $campos[$chave] = $item;
        }

        return ['tbCampos' => $tbCampos, 'values' => $values, 'where' => $where, 'campos' => $campos];
    }

    public function montaQueryInsert($campos)
    {
        $tbCampos = '';
        $values = '';
        $i = 1;
        $totalCampos = count($campos);
        foreach ($campos as $campo => $valor) {
            if ($i++ == $totalCampos) {
                $tbCampos .= $campo;
                $values .= ':' . $campo;
            } else {
                $tbCampos .= $campo . ',';
                $values .= ":$campo,";
            }
        }

        return ['tbCampos' => $tbCampos, 'values' => $values];
    }

    public function del($table, $condition)
    {

        if (array_key_exists('page', $condition)) {
            unset($condition['page']);
        }

        $where = '';
        foreach ($condition as $key => $item) {
            $where .= " AND $key = :$key";
        }

        $sql = "DELETE FROM $table WHERE 1=1$where";

        $conn = $this->conectar();
        $stmt = $conn->prepare($sql);
        $stmt->execute($condition);
        $row = $stmt->rowCount();
        if ($row == 0) {
            return true;
        }
        return false;
    }

    public function query($sql, array $params = array())
    {

        $conn = $this->conectar();
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $stmt->closeCursor();
        return $result;

    }

    public function generatePassword($qtyCaraceters = 8)
    {
        //Letras minúsculas embaralhadas
        $smallLetters = str_shuffle('abcdefghijklmnopqrstuvwxyz');

        //Letras maiúsculas embaralhadas
        $capitalLetters = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        //Números aleatórios
        $numbers = (((date('Ymd') / 12) * 24) + mt_rand(800, 9999));
        $numbers .= 1234567890;

        //Caracteres Especiais
        $specialCharacters = str_shuffle('!@#$%*-');

        //Junta tudo
        $characters = $capitalLetters . $smallLetters . $numbers . $specialCharacters;

        //Embaralha e pega apenas a quantidade de caracteres informada no parâmetro
        $password = substr(str_shuffle($characters), 0, $qtyCaraceters);

        //Retorna a senha
        return $password;
    }

    public function inverteData($data, $caracterVelho = '/', $caracterNovo = '-')
    {
        $dt = explode($caracterVelho, $data);
        $dt = implode($caracterNovo, array_reverse($dt));
        return $dt;
    }


}
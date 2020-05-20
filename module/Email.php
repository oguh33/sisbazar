<?php

require_once "define.php";
require_once "Conexao.php";
require_once "vendor/phpmailer/class.phpmailer.php";

class Email extends Conexao
{

    /**
     * @var PHPMailer
     */
    protected $phpMailer;

    function __construct()
    {

    }

    public function updateSenhaCliente($dados){
        $subject = "Dados alterados no sistema abazaria.com.br";

        $texto = "Prezado cliente, <b>{$dados['nome']}</b>, seus dados de acesso ao sistema de vendas da abazaria.com.br
                   foram alterados e para acessar siga as instruções abaixo.";
        $texto .= "<br />";
        $texto .= "<br />";
        $texto .= "<b>Acesse:</b><a href='http://www.abazaria.com.br/abazaria'>http://www.abazaria.com.br/abazaria</a><br />";
        $texto .= "<p><b>Usuário:</b> {$dados['user']} <p>";
        $texto .= "<p><b>Senha:</b> {$dados['senha']} </p>";
        $texto .= "<p>É recomendável alterar a senha após o primeiro acesso.</p>";
        $texto .= "<br /><br /><br /><i>E-mail enviado automaticamente pelo sistema. Por favor não responder.</i>";

        $this->enviar($dados['email'], $subject, $texto, $dados['nome']);

    }

    public function novoCliente($dados){

        $subject = "Cadastro no sistema abazaria.com.br";

        $texto = "Prezado cliente, <b>{$dados['nome']}</b>, para acessar o sistema de vendas da abazaria.com.br
                   e verificar a disponibilidade de suas peças siga as instruções abaixo.";
        $texto .= "<br />";
        $texto .= "<br />";
        $texto .= "<b>Acesse:</b><a href='http://www.abazaria.com.br/abazaria'>http://www.abazaria.com.br/abazaria</a><br />";
        $texto .= "<p><b>Usuário:</b> {$dados['user']} <p>";
        $texto .= "<p><b>Senha:</b> {$dados['senha']} </p>";
        $texto .= "<p>É recomendável alterar a senha após o primeiro acesso.</p>";
        $texto .= "<br /><br /><br /><i>E-mail enviado automaticamente pelo sistema. Por favor não responder.</i>";

        $this->enviar($dados['email'], $subject, $texto, $dados['nome']);
    }

    public function emailProprietarioPosVenda($idVenda){
        $sql = "SELECT * FROM vendas v 
                inner join itens_venda iv on iv.venda = v.ven_id 
                INNER JOIN pecas p on p.codigo = iv.peca 
                INNER JOIN pessoa pes on pes.cpf = p.pessoa 
                INNER JOIN caixa_item ci on ci.cx_item = iv.item_id
                WHERE v.ven_id = ?";
            $row = $this->query($sql, [1 => $idVenda]);

            foreach ($row as $dados){

                $subject = 'Venda da peça de código ('.str_pad($dados->codigo, 6, "0", STR_PAD_LEFT).') efetuada - abazaria.com.br';


                $dataVenda  = $this->inverteData($dados->data, '-','/');
                $preco_venda = number_format($dados->preco_venda, 2, ',', '.' );
                $valorPago   = number_format($dados->cx_valor, 2, ',', '.');

                $texto = "A peça, <b>{$dados->titulo}</b>, foi vendida em <b>{$dataVenda}</b>.";
                $texto .= "<br />";
                $texto .= "<br />";
                $texto .= "<b>RESUMO:</b><br />";
                $texto .= "<p><b>Peça:</b> <br />{$dados->titulo} <p>";
                $texto .= "<p style='text-align: justify'><b>Descrição:</b> <br />{$dados->descricao} </p>";
                $texto .= "<p><b>Material:</b> <br />{$dados->material} </p>";
                $texto .= "<p><b>Tamanho:</b> <br />{$dados->tamanho} </p>";
                $texto .= "<p><b>Preço de venda:</b> <br />R$ {$preco_venda} </p>";
                $texto .= "<p><b>Valor a receber:</b> <br />R$ {$valorPago} (Este valor já está disponível como crédito em nossa loja para aquisição de peças)</p>";

                $texto .= "<br /><br /><i>E-mail enviado automaticamente pelo sistema. Por favor não responder.</i>";


                $this->enviar($dados->email, $subject, $texto, $dados->nome);
            }
    }

    public function emailCadastroPeca($codigoPeca){
        $sql = "SELECT 
                    ADDDATE(p.data_recebimento, INTERVAL p.disponibilidade DAY) as vencimento,
                    p.*,
                    pes.nome,
                    pes.email
                    FROM pecas p 
                    INNER JOIN pessoa pes on pes.cpf = p.pessoa 
                    WHERE p.codigo = ?";
        $row = $this->query($sql, [1 => $codigoPeca]);
        $dados = isset($row[0]) ? $row[0] : null;
        if( !is_null($dados) ){

            $vencimento  = $this->inverteData($dados->vencimento, '-','/');
            $preco_venda = number_format($dados->preco_venda, 2, ',', '.' );
            $valorPago   = number_format((($dados->preco_venda * $dados->percentual_lucro) / 100), 2, ',', '.');

            $subject = 'Peça cadastrada - abazaria.com.br';

            $texto = "A peça, <b>{$dados->titulo}</b>, foi disponilizada para venda até o dia <b>{$vencimento}</b>.";
            $texto .= "<br />";
            $texto .= "<br />";
            $texto .= "<b>RESUMO:</b><br />";
            $texto .= "<p><b>Peça:</b> <br />{$dados->titulo} <p>";
            $texto .= "<p style='text-align: justify'><b>Descrição:</b> <br />{$dados->descricao} </p>";
            $texto .= "<p><b>Material:</b> <br />{$dados->material} </p>";
            $texto .= "<p><b>Tamanho:</b> <br />{$dados->tamanho} </p>";
            $texto .= "<p><b>Preço de venda:</b> <br />R$ {$preco_venda} </p>";
            $texto .= "<p><b>Valor a receber:</b> <br />R$ {$valorPago} </p>";

            $texto .= "<br /><br /><i>E-mail enviado automaticamente pelo sistema. Por favor não responder.</i>";
            $this->enviar($dados->email, $subject, $texto, $dados->nome);
        }
    }

    public function pagamentoEfetuado($row){
        $subject = 'Pagamento efetuado - abazaria.com.br';
        $dataPagamento = $this->inverteData($row->data_pagamento, '-', '/');
        $conteudo  = '<p>O pagamento referente a sua peça, <b>'.$row->titulo.'</b>, foi efetuado dia '.$dataPagamento.'.</p>';
        $conteudo .= '<p>Por favor verifique sua conta ou o acesse o nosso sistema <a href="http://www.abazaria.com.br/abazaria">clicando aqui</a>.</p>';
        $conteudo .= "<br /><br /><i>E-mail enviado automaticamente pelo sistema. Por favor não responder.</i>";

        $nome = $row->nome;

        $this->enviar($row->email, $subject, $conteudo, $nome );
    }

    public function enviar($emailDestino, $subject, $texto, $nome){

        $phpmail = $this->getPhpMailer();
        $phpmail->CharSet = 'utf-8';
        $phpmail->IsSMTP(); // envia por SMTP
        $phpmail->SetLanguage( 'br', 'phpmailer/language/' );
        $phpmail->SMTPDebug  = 1;

        $phpmail->SMTPAuth = true; // Caso o servidor SMTP precise de autenticação
        $phpmail->SMTPSecure = "tls";
        $phpmail->Host = "mail.abazaria.com.br"; // SMTP servers
        $phpmail->Port = 465;
        $phpmail->Username = "mensagens@abazaria.com.br"; // SMTP username
        $phpmail->Password = "rsO&Esd%X7Wr"; // SMTP password
        $phpmail->From = "mensagens@abazaria.com.br";
        $phpmail->FromName = $nome;
        $phpmail->AddAddress($emailDestino);
        $phpmail->IsHTML(true);
        $phpmail->Subject = $subject;
        $phpmail->Body = $texto;

        $phpmail->Send();

    }

    /**
     * @return PHPMailer
     */
    public function getPhpMailer()
    {
        if( !is_object($this->phpMailer) ){
            $this->phpMailer = new PHPMailer();
        }

        return $this->phpMailer;
    }





}
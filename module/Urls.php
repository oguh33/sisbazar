<?php

class Urls
{
    private static $url = null;
    private static $baseUrl = null;

    public static function getBase()
    {
        if (self::$baseUrl != null)
            return self::$baseUrl;

        global $_SERVER;
        $startUrl = strlen($_SERVER["DOCUMENT_ROOT"]);
        $excludeUrl = substr($_SERVER["SCRIPT_FILENAME"], $startUrl, -9);
        if ($excludeUrl[0] == "/")
            self::$baseUrl = $excludeUrl;
        else
            self::$baseUrl = "/" . $excludeUrl;

        return self::$baseUrl;
    }

    public static function getURL($id)
    {
        // Verifica se a lista de URL já foi preenchida
        if (self::$url == null)
            self::getURLList();

        // Valida se existe o ID informado e retorna.
        if (isset(self::$url[$id]))
            return self::$url[$id];

        if ($id == null) {
            if (empty(self::$url)) {
                return null;
            }
            $key = count(self::$url) - 1;

            return self::$url[$key];
        }
        // Caso não exista o ID, retorna nulo
        return null;
    }

    public static function getPageAction(array $pagina)
    {

        if (array_key_exists('3', $pagina)) {
            return array('pagina' => $pagina[1] . ".php", ['action' => $pagina[2], 'id' => $pagina[3]]);
        }

        return array();

    }

    public static function convertParametros($urlArray)
    {
        return 'action=' . $urlArray['action'] . '&id=' . $urlArray['id'];
    }

    public static function getPagina()
    {
        $pagina = self::getURL(null);

        if ($pagina == null or $pagina == 'index') {
            $pagina = "home";
        }

        if (file_exists($pagina . ".php")) {
            return $pagina . ".php";
        } else {
            $pagArray = self::getPageAction(self::$url);
            if (count($pagArray)) {
                return $pagArray;
            }
            return "404.php";
        }
    }

    private static function getURLList()
    {
        global $_SERVER;

        // Primeiro traz todos as pastas abaixo do index.php
        $startUrl = strlen($_SERVER["DOCUMENT_ROOT"]) - 1;
        $excludeUrl = substr($_SERVER["SCRIPT_FILENAME"], $startUrl, -10);

        // a variável$request possui toda a string da URL após o domínio.
        $request = $_SERVER['REQUEST_URI'];

        // Agora retira toda as pastas abaixo da pasta raiz
        $request = substr($request, strlen($excludeUrl));

        // Explode a URL para pegar retirar tudo após o ?
        $urlTmp = explode("?", $request);
        $request = $urlTmp[0];

        // Explo a URL para pegar cada uma das partes da URL
        $urlExplodida = explode("/", $request);


        $retorna = array();

        for ($a = 0; $a <= count($urlExplodida); $a++) {
            if (isset($urlExplodida[$a]) AND $urlExplodida[$a] != "") {
                array_push($retorna, $urlExplodida[$a]);
            }
        }

        self::$url = $retorna;
    }

    public function removeSpace($string){
        return str_replace(' ', '_', $string);
    }

    public function removeAcento($arquivoNome)
    {
        $arquivoNome = trim($arquivoNome);
        $arquivoNome = strtolower($arquivoNome);
        $arquivoNome = html_entity_decode($arquivoNome);
        $arquivoNome = preg_replace('![áàãâä]+!u', 'a', $arquivoNome);
        $arquivoNome = preg_replace('![éèêë]+!u',  'e', $arquivoNome);
        $arquivoNome = preg_replace('![íìîï]+!u',  'i', $arquivoNome);
        $arquivoNome = preg_replace('![óòõôö]+!u', 'o', $arquivoNome);
        $arquivoNome = preg_replace('![úùûü]+!u',  'u', $arquivoNome);
        $arquivoNome = preg_replace('![ç]+!u',     'c', $arquivoNome);
        $arquivoNome = preg_replace('![ñ]+!u',     'n', $arquivoNome);
        $arquivoNome = preg_replace('/[[:space:]]/', '-', $arquivoNome);
        $arquivoNome = preg_replace('/[^a-z0-9\-]/', '',  $arquivoNome);
        $arquivoNome = preg_replace('/(-){2,}/',     '-', $arquivoNome);
        return $arquivoNome;
    }
}

?>
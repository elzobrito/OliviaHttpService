<?php

namespace HttpService;

use HttpServiceSrc\HttpClient\ServicoHttpClient;
use HttpServiceSrc\service\ServicoService;

require_once  __DIR__ . '/vendor/autoload.php';
class Index
{
    public function __construct()
    {
        // // Cria uma instância do ServicoHttpClient passando a base URL
        $httpClient = new ServicoHttpClient('https://dummyjson.com', 'Content-Type: application/x-www-form-urlencoded', '1.1');

        // Cria uma instância do ServicoService passando o HttpClient
        $servicoService = new ServicoService($httpClient);

        $parametros = [
            'products' => 1,
        ];

        $result = $servicoService->getSearchWithSlash($parametros);
        print_r(json_decode($result));
    }
}
new Index();

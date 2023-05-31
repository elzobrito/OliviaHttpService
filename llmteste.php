<?php


namespace HttpService;

use HttpServiceSrc\HttpClient\ServicoHttpClient;
use HttpServiceSrc\service\ServicoService;

require_once  __DIR__ . '/vendor/autoload.php';
class Index
{
    public function __construct()
    {
        // Cria uma instância do ServicoHttpClient passando a base URL
        $httpClient = new ServicoHttpClient('https://dummyjson.com', 'Content-Type: application/json\r\n');

        // Cria uma instância do ServicoService passando o HttpClient
        $servicoService = new ServicoService($httpClient);
        $result = $servicoService->getSearchWithSlash('products',1); // Corrigido: passando uma string como argumento
        print_r(json_decode($result));
    }
}
new Index();


namespace HttpServiceSrc\service;

use HttpServiceSrc\HttpClient\HttpClientInterface;

class ServicoService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getSearchWithSlash($query, $params = null)
    {
        $uri = '/' .$query . '/' . $params;

        return $this->httpClient->get($uri);
    }

    public function getSearch($query, $params = null)
    {
        $endpoint = '/' . $query;

        $queryParams = [];

        if ($params) {
            $queryParams = array_merge($queryParams, $params);
        }

        return $this->httpClient->get($endpoint, $queryParams);
    }
}


namespace HttpServiceSrc\HttpClient;

use HttpServiceSrc\Exception\HttpClientException;

abstract class AbstractHttpClient implements HttpClientInterface
{
    protected $defaultHeaders;

    public function __construct($handler = null)
    {
        $this->defaultHeaders = $handler ?? array('Content-Type: application/x-www-form-urlencoded');
    }

    abstract public function get($endpoint, $params = null);

    protected function getDefaultContextOptions($method, $parametros = null)
    {
        return array(
            'http' =>
            array(
                'method'  => $method,
                'header' => $this->defaultHeaders,
                'content' => $parametros ?? ''
            )
        );
    }

    protected function handleResponse($response, $context)
    {
        if ($response === false) {
            $error = error_get_last();
            throw new HttpClientException('Failed to retrieve data from the server. Error: ' . $error['message']);
        }

        return $response;
    }
    protected function isResponseSuccessful($responseHeaders)
    {
        if (!empty($responseHeaders)) {
            $statusCode = $this->getStatusCodeFromHeaders($responseHeaders);
            return $statusCode >= 200 && $statusCode < 300;
        }

        return false;
    }

    protected function getStatusCodeFromHeaders($headers)
    {
        preg_match('/^HTTP\/\d\.\d\s+(\d+)/', $headers[0], $matches);
        return (int) $matches[1];
    }
}
namespace HttpServiceSrc\HttpClient;
interface HttpClientInterface
{
    public function get($endpoint, $params = null);
}

namespace HttpServiceSrc\HttpClient;

use InvalidArgumentException;

class ServicoHttpClient extends AbstractHttpClient
{
    private $baseUrl;

    public function __construct($baseUrl,$handler = null)
    {
        parent::__construct($handler);
        $this->baseUrl = $baseUrl;
    }

    public function get($endpoint, $params = null)
    {
        $url = $this->baseUrl . $endpoint;

        // Validação da URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("Invalid URL: $url");
        }

        $options = $this->getDefaultContextOptions('GET');
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        return $this->handleResponse($response, $context);
    }

}
namespace HttpServiceSrc\Exception;

use Exception;

class HttpClientException extends Exception
{
}
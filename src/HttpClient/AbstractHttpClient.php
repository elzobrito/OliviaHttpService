<?php

namespace HttpServiceSrc\HttpClient;

use HttpServiceSrc\Exception\HttpClientException;

abstract class AbstractHttpClient implements HttpClientInterface
{
    protected $defaultHeaders;
    protected $protocol_version;
    
    public function __construct($handler = null,$protocol_version)
    {
        $this->defaultHeaders = $handler ?? array('Content-Type: application/x-www-form-urlencoded');
        $this->protocol_version = $protocol_version;
    }

    abstract public function get($endpoint, $params = null);

    protected function getDefaultContextOptions($method, $parametros = null)
    {
        return array(
            'http' =>
            array(
                'method'  => $method,
                'header' => $this->defaultHeaders,
                'content' => $parametros ?? '',
                'protocol_version' => $this->protocol_version,
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

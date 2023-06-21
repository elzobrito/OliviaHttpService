<?php

namespace HttpServiceSrc\HttpClient;

use HttpServiceSrc\Exception\HttpClientException;

class ServicoHttpClient extends AbstractHttpClient
{
    private $baseUrl;

    public function __construct($baseUrl, $handler = null, $protocol_version = null)
    {
        parent::__construct($handler, $protocol_version);
        $this->baseUrl = $baseUrl;
    }

    public function get($params = null)
    {
        $this->baseUrl .= $params;
        $options = $this->getContextOptions();
        $context = stream_context_create($options);
        $response = file_get_contents($this->baseUrl, false, $context);

        try {
            return $this->handleResponse($response, $context);
        } catch (HttpClientException $e) {
            print_r($e);
        }
    }
}

<?php

namespace HttpServiceSrc\HttpClient;

use InvalidArgumentException;

class ServicoHttpClient extends AbstractHttpClient
{
    private $baseUrl;

    public function __construct($baseUrl, $handler = null, $protocol_version = null)
    {
        parent::__construct($handler, $protocol_version);
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

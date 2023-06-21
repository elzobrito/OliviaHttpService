<?php

namespace HttpServiceSrc\service;

use HttpServiceSrc\HttpClient\HttpClientInterface;

class ServicoService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getSearchWithSlash($params = null)
    {
        $sanitizedParams = '';
        foreach ($params as $key => $value) {
            $sanitizedValue = strip_tags($value);
            $sanitizedValue = htmlspecialchars($sanitizedValue, ENT_QUOTES);
            $sanitizedParams .= '/' . $key . '/' . $sanitizedValue;
        }

        return $this->httpClient->get($sanitizedParams);
    }

    public function getSearch($params = null)
    {
        $sanitizedParams = array();
        foreach ($params as $key => $value) {
            $sanitizedValue = strip_tags($value);
            $sanitizedValue = htmlspecialchars($sanitizedValue, ENT_QUOTES);
            $sanitizedParams[$key] = $sanitizedValue;
        }
        return $this->httpClient->get('?' . http_build_query($sanitizedParams));
    }
}

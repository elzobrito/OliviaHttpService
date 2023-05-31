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

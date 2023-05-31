<?php
namespace HttpServiceSrc\HttpClient;
interface HttpClientInterface
{
    public function get($endpoint, $params = null);
}
<?php

namespace Jetfuel\Eversiumpay;

use Jetfuel\Eversiumpay\HttpClient\GuzzleHttpClient;
use Jetfuel\Eversiumpay\Traits\ConvertMoney;

class Payment
{
    use ConvertMoney;

    const BASE_API_URL      = 'http://api.eversium.com/';

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $baseApiUrl;

    /**
     * @var \Jetfuel\Eversiumpay\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    /**
     * Payment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    protected function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;

        $this->httpClient = new GuzzleHttpClient($this->baseApiUrl);
    }

    /**
     * Sign request payload.
     *
     * @param array $payload
     * @return array
     */
    protected function signPayload(array $payload)
    {
        $payload['appid'] = $this->merchantId;
        $payload['sign'] = Signature::generate($payload, $this->secretKey);

        return $payload;
    }

}

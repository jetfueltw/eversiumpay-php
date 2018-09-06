<?php

namespace Jetfuel\Eversiumpay;

use Jetfuel\Eversiumpay\Traits\ResultParser;

class DigitalPayment extends Payment
{
    use ResultParser;

    const QRCODE_IMG_PREFIX = 'IMG|';

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        $this->baseApiUrl = $baseApiUrl === null ? self::BASE_API_URL : $baseApiUrl;

        parent::__construct($merchantId, $secretKey, $baseApiUrl);
    }

    /**
     * Create digital payment order.
     *
     * @param string $tradeNo
     * @param int $channel
     * @param float $amount
     * @param string $notifyUrl
     * @return array|null
     */
    public function order($tradeNo, $channel, $amount, $notifyUrl)
    {
        $payload = $this->signPayload([
            'service'    => 'native',
            'money'    => $this->convertYuanToFen($amount),
            'notifyUrl' => $notifyUrl,
            'transp' => $tradeNo,
        ]);

        //目前只支持支付寶掃碼 , 支持方式直接寫在網址上而非Data內
        $imgSrc = $this->parseResponse($this->httpClient->get('sdkServer/thirdpays/talipay/pay', $payload));

        if (isset($imgSrc)) {
            $result['qrcodeUrl'] = self::QRCODE_IMG_PREFIX . $imgSrc;

            return $result;
        }

        return ['qrcodeUrl' => 'error'];
    }
}

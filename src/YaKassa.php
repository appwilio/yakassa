<?php

/**
 * This file is part of Ya.Kassa package.
 *
 * © Appwilio (http://appwilio.com)
 * © JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Appwilio\YaKassa;

use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Appwilio\YaKassa\Contracts\YaKassaOrder;

class YaKassa
{
    public const STATE_DECLINED     = 100;
    public const STATE_ACCEPTED     = 0;
    public const STATE_UNAUTHORIZED = 1;

    private const PAYMENT_URL = 'money.yandex.ru/eshop.xml';

    /** @var int */
    private $shopId;

    /** @var int */
    private $showcaseId;

    /** @var string */
    private $shopPassword;

    /** @var YaKassaRequest */
    private $request;

    /** @var string */
    private $genuineAmount;

    /** @var array */
    private static $significantFields = [
        // DO NOT CHANGE ORDER OF ELEMENTS!!!
        'action',
        'orderSumAmount',
        'orderSumCurrencyPaycash',
        'orderSumBankPaycash',
        'shopId',
        'invoiceId',
        'customerNumber'
    ];

    /** @var bool */
    private $testMode;

    public function __construct(int $shopId, int $showcaseId, string $shopPassword, bool $testMode = false)
    {
        $this->shopId = $shopId;
        $this->showcaseId = $showcaseId;
        $this->shopPassword = $shopPassword;

        $this->testMode = $testMode;
    }

    public function getRequest(): YaKassaRequest
    {
        return $this->request;
    }

    public function setRequest(YaKassaRequest $request)
    {
        $this->request = $request;

        return $this;
    }

    public function setGenuineOrderSumAmount(float $amount)
    {
        $this->genuineAmount = number_format($amount, 2, '.', '');

        return $this;
    }

    public function verify(): bool
    {
        if (! in_array($this->request->getAction(), ['checkOrder', 'paymentAviso'], true)) {
            return false;
        }

        if (0 !== count(array_diff(self::$significantFields, array_keys($this->request->all())))) {
            return false;
        }

        $source = Arr::only($this->request->all(), self::$significantFields);

        $source['shopId'] = $this->shopId;
        $source['orderSumAmount'] = $this->genuineAmount;

        uksort($source, function ($a, $b) {
            return
                array_search($a, self::$significantFields, true)
                <=>
                array_search($b, self::$significantFields, true);
        });

        $source['secret'] = $this->shopPassword;

        return $this->request->get('md5') === strtoupper(md5(implode(';', $source)));
    }

    public function responseDeclined(): Response
    {
        return $this->buildResponse(self::STATE_DECLINED);
    }

    public function responseAccepted(): Response
    {
        return $this->buildResponse(self::STATE_ACCEPTED);
    }

    public function responseUnauthorized(): Response
    {
        return $this->buildResponse(self::STATE_UNAUTHORIZED);
    }

    public function buildPaymentForm(YaKassaOrder $order): YaKassaPaymentForm
    {
        $form = new YaKassaPaymentForm($this->shopId, $this->showcaseId, $this->getPaymentUrl());

        $form->setOrder($order);

        return $form;
    }

    private function buildResponse(int $code): Response
    {
        $content  = '<?xml version="1.0" encoding="UTF-8"?>';

        $content .= vsprintf('<%sResponse performedDatetime="%s" code="%d" invoiceId="%d" shopId="%d" />', [
            $this->request->getAction(),
            date(\DateTime::RFC3339),
            $code,
            $this->request->getInvoiceId(),
            $this->shopId
        ]);

        return new Response($content, Response::HTTP_OK, ['Content-Type' => 'application/xml']);
    }

    private function getPaymentUrl(): string
    {
        $paymentUrl = ($this->testMode ? 'demo' : '').self::PAYMENT_URL;

        return "https://{$paymentUrl}";
    }
}

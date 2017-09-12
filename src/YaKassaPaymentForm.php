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

use Appwilio\YaKassa\Contracts\YaKassaOrder;
use Appwilio\YaKassa\Contracts\YaKassaOrder54FZ;
use Appwilio\YaKassa\Contracts\YaKassaOrderItem54FZ;

class YaKassaPaymentForm
{
    /** @var string */
    private $paymentUrl;

    /** @var YaKassaOrder */
    private $order;

    /** @var array */
    private $parameters = [];

    public function __construct(string $shopId, string $showcaseId, string $paymentUrl)
    {
        $this->setParameter('shopId', $shopId);
        $this->setParameter('scid', $showcaseId);

        $this->paymentUrl = $paymentUrl;
    }

    public function setOrder(YaKassaOrder $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function setFailUrl(string $failUrl): self
    {
        return $this->setParameter('shopFailURL', $failUrl);
    }

    public function setSuccessUrl(string $successUrl): self
    {
        return $this->setParameter('shopSuccessURL', $successUrl);
    }

    public function setDefaultUrl(string $defaultUrl): self
    {
        return $this->setParameter('shopDefaultUrl', $defaultUrl);
    }

    public function setParameter($key, $value): self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function getPaymentUrl(): string
    {
        return $this->paymentUrl;
    }

    public function toArray(): array
    {
        $this->setParameter('sum', number_format($this->order->getOrderSum(), 2, '.', ''));
        $this->setParameter('orderNumber', $this->order->getOrderNumber());
        $this->setParameter('customerNumber', $this->order->getCustomerNumber());

        $this->setParameter('cps_email', $this->order->getCustomerEmail());
        $this->setParameter('cps_phone', $this->order->getCustomerPhone());

        if ($pm = $this->order->getPaymentType()) {
            $this->setParameter('paymentType', $pm);
        }

        if ($this->order instanceof YaKassaOrder54FZ) {
            $this->setParameter('ym_merchant_receipt', json_encode(
                $this->getMerchantReceipt($this->order),
                JSON_UNESCAPED_UNICODE
            ));
        }

        return array_filter($this->parameters);
    }

    private function getMerchantReceipt(YaKassaOrder54FZ $order): array
    {
        $orderItems = $order->getItems();

        if ($orderItems instanceof \Traversable) {
            $orderItems = iterator_to_array($orderItems);
        }

        $receiptItems = [];

        foreach ($orderItems as $item) {
            $receiptItems[] = $this->convertItemToArray($item);
        }

        $receipt = [
            'items' => $receiptItems,
            'taxSystem' => $order->getTaxSystem(),
            'customerContact' => $order->getCustomerContact()
        ];

        return array_filter($receipt);
    }

    private function convertItemToArray(YaKassaOrderItem54FZ $item): array
    {
        return array_filter([
            'price' => [
                'amount' => number_format($item->getAmount(), 2, '.', '')
            ],
            'text' => mb_substr($item->getTitle(), 0, 127, 'UTF-8'),
            'tax' => $item->getTaxRate(),
            'quantity' => $this->formatQuantity($item->getQuantity()),
            'currency' => $item->getCurrency(),
        ]);
    }

    private function formatQuantity($quantity)
    {
        return is_int($quantity) ? $quantity : (float) number_format($quantity, 3, '.', '');
    }
}

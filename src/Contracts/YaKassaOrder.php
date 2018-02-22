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

namespace Appwilio\YaKassa\Contracts;

interface YaKassaOrder
{
    /**
     * Сумма заказа.
     *
     * @return float
     */
    public function getOrderSum(): float;

    /**
     * Идентификатор плательщика в системе магазина.
     * В качестве идентификатора может использоваться номер договора плательщика, логин плательщика и т. п.
     *
     * Допустимы повторные оплаты по одному и тому же идентификатору плательщика.
     *
     * @return string
     */
    public function getCustomerNumber(): string;

    /**
     * Уникальный номер заказа в системе магазина.
     *
     * @return null|string
     */
    public function getOrderNumber(): ?string;

    /**
     * Код способа оплаты.
     *
     * @link https://tech.yandex.ru/money/doc/payment-solution/reference/payment-type-codes-docpage/
     *
     * @return null|string
     */
    public function getPaymentType(): ?string;

    /**
     * Адрес электронной почты плательщика.
     *
     * @return null|string
     */
    public function getCustomerEmail(): ?string;

    /**
     * Номер мобильного телефона плательщика.
     *
     * @return null|string
     */
    public function getCustomerPhone(): ?string;
}

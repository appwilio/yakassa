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

interface YaKassaOrderItem54FZ
{
    public const VAT_NO = 1;
    public const VAT_0 = 2;
    public const VAT_10 = 3;
    public const VAT_18 = 4;
    public const VAT_10_110 = 5;
    public const VAT_18_118 = 6;

    /**
     * Цена товара с учётом всех скидок и наценок.
     *
     * @return float
     */
    public function getAmount(): float;

    /**
     * Количество товара.
     *
     * @return float
     */
    public function getQuantity(): float;

    /**
     * Ставка НДС.
     *
     * См. константы VAT_*.
     *
     * @return int
     */
    public function getTaxRate(): int;

    /**
     * Код валюты.
     *
     * @return null|string
     */
    public function getCurrency(): ?string;

    /**
     * Название товара. 128 символов в UTF-8.
     *
     * @return string
     */
    public function getTitle(): string;
}

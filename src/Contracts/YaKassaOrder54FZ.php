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

interface YaKassaOrder54FZ extends YaKassaOrder
{
    public const TAX_OSN = 1;
    public const TAX_USN_INCOME = 2;
    public const TAX_USN_PROFIT = 3;
    public const TAX_ENVD = 4;
    public const TAX_ESN = 5;
    public const TAX_PSN = 6;

    /**
     * Товары/услуги в чеке.
     *
     * @return iterable массив/итератор/генератор объектов YaKassaOrderItem54FZ
     */
    public function getItems(): iterable;

    /**
     * Система налогообложения.
     *
     * См. константы TAX_*.
     *
     * @return int|null
     */
    public function getTaxSystem(): ?int;

    /**
     * Телефон или эл. почта покупателя.
     *
     * @return string
     */
    public function getCustomerContact(): string;
}

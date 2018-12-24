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

    public const PM_CREDIT = 'credit';
    public const PM_ADVANCE = 'advance';
    public const PM_FULL_PAYMENT = 'full_payment';
    public const PM_CREDIT_PAYMENT = 'credit_payment';
    public const PM_PARTIAL_PAYMENT = 'partial_payment';
    public const PM_FULL_PREPAYMENT = 'full_prepayment';
    public const PM_PARTIAL_PREPAYMENT = 'partial_prepayment';

    public const PS_JOB = 'job';
    public const PS_EXCISE = 'excise';
    public const PS_SERVICE = 'service';
    public const PS_ANOTHER = 'another';
    public const PS_PAYMENT = 'payment';
    public const PS_LOTTERY = 'lottery';
    public const PS_COMMODITY = 'commodity';
    public const PS_COMPOSITE = 'composite';
    public const PS_GAMBLING_BET = 'gambling_bet';
    public const PS_LOTTERY_PRIZE = 'lottery_prize';
    public const PS_GAMBLING_PRIZE = 'gambling_prize';
    public const PS_AGENT_COMMISSION = 'agent_commission';
    public const PS_INTELLECTUAL_ACTIVITY = 'intellectual_activity';

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
     * @see константы VAT_*
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

    /**
     * Признак способа расчёта.
     *
     * @see константы PM_*
     *
     * @return string
     */
    public function getPaymentMethodType(): string;

    /**
     * Признак предмета расчёта.
     *
     * @see константы PS_*
     *
     * @return string
     */
    public function getPaymentSubjectType(): string;
}

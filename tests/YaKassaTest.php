<?php

/**
 * This file is part of Ya.Kassa package.
 *
 * © 2017 Appwilio (http://appwilio.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Appwilio\YaKassa\Tests;

use Appwilio\YaKassa\YaKassa;
use Appwilio\YaKassa\YaKassaRequest;

class YaKassaTest extends \PHPUnit_Framework_TestCase
{
    private const SHOP_SECRET = 'secret';

    /** @var YaKassa */
    private $kassa;

    private $requestStub = [
        'orderSumAmount'          => 123.45,
        'orderSumCurrencyPaycash' => 10643,
        'orderSumBankPaycash'     => 1003,
        'shopId'                  => 10,
        'invoiceId'               => 123456789,
        'customerNumber'          => 2017
    ];

    public function setUp()
    {
        $this->kassa = new YaKassa(10, 20, self::SHOP_SECRET);
    }

    /** @test */
    public function ya_kassa_is_instantiable(): void
    {
        $this->assertInstanceOf(YaKassa::class, $this->kassa);
    }

    /** @test */
    public function it_can_handle_valid_check_order_request(): void
    {
        $this->kassa
            ->setRequest($this->makeRequest('checkOrder'))
            ->setGenuineOrderSumAmount(123.45);

        $this->assertTrue($this->kassa->verify());
    }

    /** @test */
    public function it_can_not_handle_check_order_request_with_invalid_hash(): void
    {
        $this->kassa
            ->setRequest($this->makeRequest('checkOrder', true))
            ->setGenuineOrderSumAmount(123.45);

        $this->assertFalse($this->kassa->verify());
    }

    /** @test */
    public function it_can_not_handle_check_order_request_with_invalid_amount(): void
    {
        $this->kassa
            ->setRequest($this->makeRequest('checkOrder'))
            ->setGenuineOrderSumAmount(987);

        $this->assertFalse($this->kassa->verify());
    }

    /** @test */
    public function it_can_handle_valid_payment_complete_request(): void
    {
        $this->kassa
            ->setRequest($this->makeRequest('paymentAviso'))
            ->setGenuineOrderSumAmount(123.45);

        $this->assertTrue($this->kassa->verify());
    }

    /** @test */
    public function it_can_handle_payment_complete_request_with_invalid_hash(): void
    {
        $this->kassa
            ->setRequest($this->makeRequest('paymentAviso', true))
            ->setGenuineOrderSumAmount(123.45);

        $this->assertFalse($this->kassa->verify());
    }

    /** @test */
    public function it_can_handle_payment_complete_request_with_invalid_amount(): void
    {
        $this->kassa
            ->setRequest($this->makeRequest('paymentAviso'))
            ->setGenuineOrderSumAmount(987);

        $this->assertFalse($this->kassa->verify());
    }

    private function makeRequest(string $action, $makeInvalidData = false): YaKassaRequest
    {
        $stub = array_merge(['action' => $action], $this->requestStub);

        if ($makeInvalidData) {
            $hash = 'invalid_hash';
        } else {
            $params = implode(';', $stub).';'.self::SHOP_SECRET;
            $hash = strtoupper(md5($params));
        }

        $stub = array_merge($stub, ['md5' => $hash]);

        return new YaKassaRequest($stub);
    }
}

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

use Symfony\Component\HttpFoundation\ParameterBag;

class YaKassaRequest
{
    /** @var ParameterBag */
    private $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = new ParameterBag($parameters);
    }

    public function getAction(): string
    {
        return $this->get('action');
    }

    public function getOrderNumber(): string
    {
        return $this->get('orderNumber');
    }

    public function getCustomerNumber(): string
    {
        return $this->get('customerNumber');
    }

    public function getInvoiceId(): string
    {
        return $this->get('invoiceId');
    }

    public function get(string $name): string
    {
        return $this->parameters->get($name);
    }

    public function all(): array
    {
        return $this->parameters->all();
    }
}

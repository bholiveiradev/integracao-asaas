<?php

namespace App\Services\Payment\Gateways\Asaas;

use App\Services\Payment\Contracts\AttributeInterface;
use Illuminate\Support\Collection;

class Attribute implements AttributeInterface
{
    private $data;

    public function setData(Collection|array $data):void
    {
        $this->data = $data;
    }

    public function name(): string
    {
        return 'Asaas';
    }

    public function id(): string
    {
        return (string) $this->data['id'];
    }
}

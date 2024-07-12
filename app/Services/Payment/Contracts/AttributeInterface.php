<?php

namespace App\Services\Payment\Contracts;

interface AttributeInterface
{
    public function name(): string;
    public function id(): string;
}

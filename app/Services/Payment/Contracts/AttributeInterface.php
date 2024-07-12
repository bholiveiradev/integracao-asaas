<?php

namespace App\Services\Payment\Contracts;

use Illuminate\Support\Collection;

interface AttributeInterface
{
    public function setData(Collection|array $data): void;
    public function name(): string;
    public function id(): string;
}

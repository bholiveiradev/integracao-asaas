<?php

namespace App\Services\Payment\Contracts;

interface ProcessorInterface
{
    public function process(array $data);
}

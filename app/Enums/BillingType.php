<?php

namespace App\Enums;

enum BillingType: string
{
    case PIX = 'PIX';
    case BOLETO = 'BOLETO';
    case CREDIT_CARD = 'CREDIT_CARD';
}

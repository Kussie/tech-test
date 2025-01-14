<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Prelim = 'prelim';
    case PaymentRequired = 'payment required';
    case Order = 'order';
    case Processing = 'processing';
    case OrderFailed = 'order failed';
    case Complete = 'complete';
}

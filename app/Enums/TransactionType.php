<?php

namespace App\Enums;

enum TransactionType : string
{
    case PROFIT = 'profit';
    case CONSUMPTION = 'consumption';
}

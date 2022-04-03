<?php

namespace App\Enums;

enum TransactionType : string
{
    case Card = 'cards';
    case Account = 'accounts';
}

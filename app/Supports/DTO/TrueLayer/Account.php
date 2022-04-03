<?php

namespace App\Supports\DTO\TrueLayer;

use Spatie\DataTransferObject\DataTransferObject;

class Account extends DataTransferObject
{
    public string $code;
    public string $type;
    public string $name;
    public string $number;
    public string $provider;
    public string $sortCode;
}

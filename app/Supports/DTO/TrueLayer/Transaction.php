<?php

namespace App\Supports\DTO\TrueLayer;

use Spatie\DataTransferObject\DataTransferObject;

class Transaction extends DataTransferObject
{
    public string $code;
    public bool $pending;
    public string $description;
    public string $amount;
    public string $type;
    public string $category;
    public string|null $name;
    public array $meta;
    public array $classification;
    public array|null $runningBalance;
    public string $paymentAt;
}

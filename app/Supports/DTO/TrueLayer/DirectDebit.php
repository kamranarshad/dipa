<?php

namespace App\Supports\DTO\TrueLayer;

use Spatie\DataTransferObject\DataTransferObject;

class DirectDebit extends DataTransferObject
{
    public string $code;
    public string $name;
    public string|null $status;
    public array $meta;
    public string|null $startedAt;
    public string|null $previousPaymentAmount;
    public string|null $previousPaymentAt;
}

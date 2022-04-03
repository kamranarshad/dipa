<?php

namespace App\Supports\DTO\TrueLayer;

use Spatie\DataTransferObject\DataTransferObject;

class StandingOrder extends DataTransferObject
{
    public string|null $reference;
    public string|null $payee;
    public string|null $frequency;
    public string $startedAt;
    public array $meta;
    public string $firstPaymentAmount;
    public string $firstPaymentAt;
    public string $nextPaymentAmount;
    public string $nextPaymentAt;
    public string $finalPaymentAmount;
    public string $finalPaymentAt;
}

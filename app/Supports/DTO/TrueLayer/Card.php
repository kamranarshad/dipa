<?php

namespace App\Supports\DTO\TrueLayer;

use Spatie\DataTransferObject\DataTransferObject;

class Card extends DataTransferObject
{
    public string $code;
    public string $network;
    public string $provider;
    public string $type;
    public string $description;
    public string $lastFour;
    public string $name;
    public string|null $validFrom;
    public string|null $validTo;
}

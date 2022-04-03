<?php

namespace App\Supports\DTO\TrueLayer;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class AccessToken extends DataTransferObject
{
    public string $accessToken;
    public string $expiresIn;
    public string $type;
    public string $refreshToken;
}

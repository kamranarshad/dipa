<?php

namespace App\Supports\Services;

use App\Supports\DTO\TrueLayer\AccessToken;
use App\Supports\DTO\TrueLayer\Account;
use App\Supports\DTO\TrueLayer\Card;
use App\Supports\DTO\TrueLayer\DirectDebit;
use App\Supports\DTO\TrueLayer\StandingOrder;
use App\Supports\DTO\TrueLayer\Transaction;
use Carbon\Carbon;
use Config;
use GuzzleHttp\Exception\BadResponseException;
use Http;

class TrueLayerService
{
    const API_URL = "https://api.truelayer-sandbox.com";
    const AUTH_URI = "https://auth.truelayer-sandbox.com";

    private array $config;

    public function __construct()
    {
        $this->config = Config::get('services.truelayer');
    }

    public function getAuthLink(string $providerId): string
    {
        $query = [
            'response_type' => 'code',
            'client_id'     => $this->config['key'],
            'scope'         => 'info accounts balance cards transactions direct_debits standing_orders offline_access',
            'redirect_uri'  => $this->config['redirect_uri'],
            'response_mode' => 'form_post',
            'provider_id'   => $providerId,
        ];

        return self::AUTH_URI .  '/?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }

    public function getAccessToken(string $code): AccessToken
    {
        $response = Http::post(self::AUTH_URI . "/connect/token", [
                'code'          => $code,
                'grant_type'    => 'authorization_code',
                'client_id'     => $this->config['key'],
                'client_secret' => $this->config['secret'],
                'redirect_uri'  => $this->config['redirect_uri'],
            ])
            ->throw(function ($response, $e) {
                if (! $response->ok()) {
                    throw new BadResponseException($e->getMessage());
                }
            })
            ->json();

        return new AccessToken([
            'accessToken'  => $response['access_token'],
            'expiresIn'    => $response['expires_in'],
            'type'         => $response['token_type'],
            'refreshToken' => $response['refresh_token'],
        ]);
    }

    public function renewAccessToken(string $refreshToken): AccessToken
    {
        $response = Http::post(self::AUTH_URI . "/connect/token", [
                'grant_type'    => 'refresh_token',
                'client_id'     => $this->config['key'],
                'client_secret' => $this->config['secret'],
                'refresh_token' => $refreshToken,
            ])
            ->throw(function ($response, $e) {
                if (! $response->ok()) {
                    throw new BadResponseException($e->getMessage());
                }
            })
            ->json();

        return new AccessToken([
            'accessToken'  => $response['access_token'],
            'expiresIn'    => $response['expires_in'],
            'type'         => $response['token_type'],
            'refreshToken' => $response['refresh_token'],
        ]);
    }

    public function getAccounts(string $accessToken): array
    {
        return Http::withToken($accessToken)
            ->get(self::API_URL . "/data/v1/accounts")
            ->throw(function ($response, $e) {
                if (! $response->ok()) {
                    throw new BadResponseException($e->getMessage());
                }
            })
            ->collect('results')->map(function ($item, $key) {
                return new Account([
                    'code'     => $item['account_id'],
                    'type'     => $item['account_type'],
                    'name'     => $item['display_name'],
                    'provider' => $item['provider']['provider_id'],
                    'number'   => $item['account_number']['number'],
                    'sortCode' => $item['account_number']['sort_code'],
                ]);
            })->toArray();
    }

    public function getCards(string $accessToken): array
    {
        return Http::withToken($accessToken)
            ->get(self::API_URL . "/data/v1/cards")
            ->throw(function ($response, $e) {
                if (! $response->ok()) {
                    throw new BadResponseException($e->getMessage());
                }
            })->collect('results')->map(function ($item, $key) {
                return new Card([
                    'code'        => $item['account_id'],
                    'network'     => $item['card_network'],
                    'type'        => $item['card_type'],
                    'description' => $item['display_name'],
                    'provider'    => $item['provider']['provider_id'],
                    'lastFour'    => $item['partial_card_number'],
                    'name'        => $item['name_on_card'],
                    'validFrom'   => $item['valid_from'] ?? null,
                    'validTo'     => $item['valid_to'] ?? null,
                ]);
            })->toArray();
    }

    public function getTransactions(string $accessToken, string $account, string $type, bool $pending, string $from = null, string $to = null): array
    {
        $url = self::API_URL . "/data/v1/$type/$account/transactions";

        if ($pending) {
            $url .= '/pending';
        }

        return Http::withToken($accessToken)
            ->get($url, [
                'from' => $from,
                'to'   => $to,
            ])
            ->throw(function ($response, $e) {
                if (! $response->ok()) {
                    throw new BadResponseException($e->getMessage());
                }
            })->collect('results')->map(function ($item, $key) use ($pending) {
                return new Transaction([
                    'code'           => $item['transaction_id'],
                    'pending'        => $pending,
                    'description'    => $item['description'],
                    'amount'         => $item['amount'],
                    'type'           => $item['transaction_type'],
                    'category'       => $item['transaction_category'],
                    'classification' => $item['transaction_classification'],
                    'name'           => $item['merchant_name'] ?? null,
                    'meta'           => $item['meta'],
                    'runningBalance' => $item['running_balance'] ?? null,
                    'paymentAt'      => Carbon::parse($item['timestamp']),
                ]);
            })->toArray();
    }
}

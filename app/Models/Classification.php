<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classification extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, TransactionClassification::class, 'classification_id', 'id', 'id', 'transaction_id');
    }

    public function scopeBalance(Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        $subQueryCurrentMonth = ['transactions as balance' => function($query) {
            $query->where('payment_at', '>', Carbon::now()->subMonth());
        }];

        $subQueryPreviousMonth = ['transactions as balance_last' => function($query) {
            $query->where('payment_at', '>', Carbon::now()->subMonths(2))
                ->where('payment_at', '<', Carbon::now()->subMonth());
        }];

        return $query->withSum($subQueryCurrentMonth, 'running_balance')
            ->withSum($subQueryPreviousMonth, 'running_balance')
            ->orderBy('balance', 'desc');
    }
}

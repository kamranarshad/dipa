<?php

namespace App\Jobs;

use App\Enums\TransactionType;
use App\Models\Account;
use App\Models\Card;
use App\Models\Classification;
use App\Models\Transaction;
use App\Models\TransactionClassification;
use App\Supports\Services\TrueLayerService;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RetrieveTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Account|Card $account, private TransactionType $transactionType)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TrueLayerService $service)
    {
        $accessToken = $this->account->userAccessToken->token;

        $transactions = $service->getTransactions(
            accessToken: $accessToken,
            account: $this->account->code,
            type: $this->account->type == 'CREDIT' ? 'cards' : 'accounts',
            pending: false
        );

        collect($transactions)->each(function ($item) {
            if (Transaction::whereCode($item->code)->count() != 0) {
                return;
            }

            DB::transaction(function () use ($item) {
                $transaction = Transaction::updateOrCreate(
                    [
                        'account_id' => $this->account->id,
                        'code'       => $item->code,
                    ],
                    [
                        'code'            => $item->code,
                        'pending'         => $item->pending,
                        'description'     => $item->description,
                        'amount'          => $item->amount,
                        'running_balance' => $item->runningBalance['amount'] ?? null,
                        'type'            => $item->type,
                        'category'        => $item->category,
                        'name'            => $item->name,
                        'meta'            => $item->meta,
                        'payment_at'      => $item->paymentAt,
                    ]
                );

                collect($item->classification)->each(function ($classificationName) use ($transaction) {
                    $classification = Classification::firstOrCreate(['name' => $classificationName]);

                    TransactionClassification::firstOrCreate([
                        'transaction_id'    => $transaction->id,
                        'classification_id' => $classification->id,
                    ]);
                });
            });
        });
    }
}
